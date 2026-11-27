<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_authentication(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/add_doctor_view')->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_admin_pages_or_actions(): void
    {
        $user = User::factory()->create(['usertype' => '0']);

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/show_doctors')->assertForbidden();
        $this->actingAs($user)->post('/upload_doctor')->assertForbidden();
    }

    public function test_admin_users_can_access_admin_pages(): void
    {
        $admin = User::factory()->create(['usertype' => '1']);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($admin)->get('/show_doctors')->assertOk();
    }

    public function test_admin_email_alone_does_not_grant_admin_access(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@hospital.com',
            'usertype' => '0',
        ]);

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_users_can_only_cancel_their_own_appointments(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $appointment = new Appointment;
        $appointment->user_id = $owner->id;
        $appointment->save();

        $this->actingAs($otherUser)
            ->post(route('cancel-appointment', $appointment->id))
            ->assertNotFound();

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id]);

        $this->actingAs($owner)
            ->post(route('cancel-appointment', $appointment->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]);
    }
}
