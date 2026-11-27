<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nearby doctor matches | One Health</title>
  <link rel="stylesheet" href="{{ asset('assets/css/maicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body>
  <header class="doctor-results-header">
    <div class="container">
      <a class="doctor-results-brand" href="{{ route('home') }}"><span>+</span> One Health</a>
      <a class="doctor-results-home" href="{{ route('home') }}">Back to home</a>
    </div>
  </header>

  <main class="doctor-results-page">
    <div class="container">
      <a class="doctor-results-back" href="{{ route('home') }}#find-doctor">← New search</a>
      <div class="doctor-results-heading">
        <span class="doctor-finder-eyebrow">YOUR NEARBY MATCHES</span>
        <h1>Top nearby matches</h1>
        <p>Only available doctors with verified credentials within {{ $radiusKm }} km are included. Results are ordered by distance; ratings break ties.</p>
      </div>

      @if($ai['message'] !== '')
        <div class="doctor-ai-notice {{ $ai['available'] ? 'doctor-ai-notice-ready' : '' }}" role="status">
          <strong>{{ $ai['available'] ? 'AI profile notes' : 'AI notes unavailable' }}</strong>
          <span>{{ $ai['message'] }}</span>
        </div>
      @endif

      @if($doctors->isEmpty())
        <section class="doctor-empty-state">
          <div class="doctor-empty-icon" aria-hidden="true">⌖</div>
          <h2>No verified, available matches found</h2>
          <p>There are no doctors with verified profiles and clinic coordinates within {{ $radiusKm }} km{{ $speciality !== '' ? ' for this specialty' : '' }}. Try another specialty or ask the administrator to update doctor profiles.</p>
          <a class="doctor-finder-button" href="{{ route('home') }}#appointment">Book an appointment</a>
        </section>
      @else
        <div class="doctor-results-list">
          @foreach($doctors as $doctor)
            <article class="doctor-result-card">
              <div class="doctor-result-photo">
                @if($doctor->doctor_image)
                  <img src="{{ asset('doctorimage/' . $doctor->doctor_image) }}" alt="">
                @else
                  <span aria-hidden="true">+</span>
                @endif
              </div>
              <div class="doctor-result-info">
                <div class="doctor-result-badges">
                  <span class="doctor-result-badge">Credentials verified</span>
                  <span class="doctor-result-badge doctor-result-available">Available</span>
                </div>
                <h2>{{ $doctor->name }}</h2>
                <p class="doctor-result-speciality">{{ ucfirst($doctor->speciality) }}</p>
                <p class="doctor-result-address">{{ $doctor->clinic_address }}</p>
                @if(isset($ai['reasons'][$doctor->id]))
                  <p class="doctor-result-reason">{{ $ai['reasons'][$doctor->id] }}</p>
                @endif
              </div>
              <div class="doctor-result-meta">
                <strong class="doctor-result-distance">{{ number_format($doctor->distance_km, 1) }} <span>km</span></strong>
                @if($doctor->reviews_count > 0)
                  <span class="doctor-result-rating">★ {{ number_format((float) $doctor->reviews_avg_rating, 1) }} <small>({{ $doctor->reviews_count }} verified {{ \Illuminate\Support\Str::plural('review', $doctor->reviews_count) }})</small></span>
                @else
                  <span class="doctor-result-no-rating">No verified reviews yet</span>
                @endif
                <a class="doctor-result-book" href="{{ route('home', ['doctor_id' => $doctor->id]) }}#appointment">Book this doctor</a>
              </div>
            </article>
          @endforeach
        </div>
      @endif

      <p class="doctor-results-disclaimer">These matches are based on the directory information provided by the clinic, not a medical assessment. For emergencies, contact local emergency services.</p>
    </div>
  </main>
</body>
</html>
