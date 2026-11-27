<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DoctorRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendations_only_include_verified_available_specialty_matches_within_radius(): void
    {
        Config::set('services.gemini.api_key', 'test-key');

        $nearby = $this->createDoctor('Nearby Skin Doctor', 23.801, 90.4);
        $outsideRadius = $this->createDoctor('Distant Skin Doctor', 23.8, 90.8);
        $otherSpecialty = $this->createDoctor('Nearby Heart Doctor', 23.8005, 90.4, 'Heart');
        $unverified = $this->createDoctor('Unverified Skin Doctor', 23.8001, 90.4, 'Skin', false);
        $unavailable = $this->createDoctor('Unavailable Skin Doctor', 23.8002, 90.4, 'Skin', true, false);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'recommendations' => [[
                                    'doctor_id' => $nearby->id,
                                    'reason' => 'This verified doctor is nearby and matches your selected specialty.',
                                ]],
                            ]),
                        ]],
                    ],
                ]],
            ]),
        ]);

        $response = $this->post(route('doctor-recommendations'), [
            'latitude' => 23.8,
            'longitude' => 90.4,
            'speciality' => 'Skin',
        ]);

        $response->assertOk()
            ->assertSee('Nearby Skin Doctor')
            ->assertSee('This verified doctor is nearby')
            ->assertDontSee('Distant Skin Doctor')
            ->assertDontSee('Nearby Heart Doctor')
            ->assertDontSee('Unverified Skin Doctor')
            ->assertDontSee('Unavailable Skin Doctor');

        Http::assertSent(function ($request) use ($nearby): bool {
            $prompt = $request->data()['contents'][0]['parts'][0]['text'];

            return $request->hasHeader('x-goog-api-key', 'test-key')
                && str_contains($prompt, '"id":'.$nearby->id)
                && ! str_contains($prompt, '23.8');
        });

        $this->assertNotSame($outsideRadius->id, $nearby->id);
        $this->assertNotSame($otherSpecialty->id, $nearby->id);
        $this->assertNotSame($unverified->id, $nearby->id);
        $this->assertNotSame($unavailable->id, $nearby->id);
    }

    public function test_results_are_still_shown_with_a_clear_notice_when_gemini_is_not_configured(): void
    {
        Config::set('services.gemini.api_key', null);
        Http::fake();

        $doctor = $this->createDoctor('Local Match', 23.8, 90.4);

        $this->post(route('doctor-recommendations'), [
            'latitude' => 23.8,
            'longitude' => 90.4,
        ])
            ->assertOk()
            ->assertSee('Local Match')
            ->assertSee('Gemini is not configured');

        Http::assertNothingSent();
    }

    public function test_gemini_cannot_add_doctors_outside_the_local_candidate_set(): void
    {
        Config::set('services.gemini.api_key', 'test-key');
        $doctor = $this->createDoctor('Only Local Doctor', 23.8, 90.4);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'recommendations' => [[
                                    'doctor_id' => $doctor->id + 1000,
                                    'reason' => 'Invented result',
                                ]],
                            ]),
                        ]],
                    ],
                ]],
            ]),
        ]);

        $this->post(route('doctor-recommendations'), [
            'latitude' => 23.8,
            'longitude' => 90.4,
        ])
            ->assertOk()
            ->assertSee('Only Local Doctor')
            ->assertSee('Gemini explanations are unavailable')
            ->assertDontSee('Invented result');
    }

    public function test_only_the_patient_can_review_their_completed_appointment_once(): void
    {
        $doctor = $this->createDoctor('Reviewable Doctor', 23.8, 90.4);
        $patient = User::factory()->create();
        $otherUser = User::factory()->create();
        $appointment = $this->createAppointment($doctor, $patient, 'approved');

        $this->actingAs($otherUser)
            ->post(route('appointments.review', $appointment->id), ['rating' => 5])
            ->assertNotFound();

        $this->actingAs($patient)
            ->post(route('appointments.review', $appointment->id), ['rating' => 5])
            ->assertNotFound();

        $appointment->status = 'completed';
        $appointment->save();

        $this->actingAs($patient)
            ->post(route('appointments.review', $appointment->id), [
                'rating' => 5,
                'review' => 'Clear and helpful appointment.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('doctor_reviews', [
            'doctor_id' => $doctor->id,
            'user_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'rating' => 5,
        ]);

        $this->actingAs($patient)
            ->post(route('appointments.review', $appointment->id), ['rating' => 4])
            ->assertRedirect()
            ->assertSessionHasErrors('review');

        $this->assertDatabaseCount('doctor_reviews', 1);
    }

    public function test_admin_can_complete_only_an_approved_appointment(): void
    {
        $doctor = $this->createDoctor('Appointment Doctor', 23.8, 90.4);
        $admin = User::factory()->create(['usertype' => '1']);
        $appointment = $this->createAppointment($doctor, User::factory()->create(), 'approved');

        $this->actingAs($admin)
            ->post(route('completed', $appointment->id))
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed',
        ]);

        $inProgress = $this->createAppointment($doctor, User::factory()->create(), 'In progress');

        $this->actingAs($admin)
            ->post(route('completed', $inProgress->id))
            ->assertNotFound();
    }

    private function createDoctor(
        string $name,
        float $latitude,
        float $longitude,
        string $speciality = 'Skin',
        bool $verified = true,
        bool $available = true,
    ): Doctor {
        $doctor = new Doctor;
        $doctor->name = $name;
        $doctor->phone = '0123456789';
        $doctor->speciality = $speciality;
        $doctor->room_number = '1';
        $doctor->clinic_address = 'Clinic address';
        $doctor->latitude = $latitude;
        $doctor->longitude = $longitude;
        $doctor->credentials_verified = $verified;
        $doctor->available = $available;
        $doctor->save();

        return $doctor;
    }

    private function createAppointment(Doctor $doctor, User $patient, string $status): Appointment
    {
        $appointment = new Appointment;
        $appointment->name = $patient->name;
        $appointment->email = $patient->email;
        $appointment->phone = '0123456789';
        $appointment->doctor_id = $doctor->id;
        $appointment->doctor = $doctor->name;
        $appointment->date = now()->toDateString();
        $appointment->status = $status;
        $appointment->user_id = $patient->id;
        $appointment->save();

        return $appointment;
    }
}
