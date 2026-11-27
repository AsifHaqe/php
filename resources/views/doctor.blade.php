  <div class="page-section" id="doctors">
    <div class="container">
      <h1 class="text-center mb-5 wow fadeInUp">Our Doctors</h1>

      <div class="owl-carousel wow fadeInUp" id="doctorSlideshow">

        @foreach($doctors as $doctor)
        <div class="item">
          <div class="card-doctor">
            <div class="header">
              <img src="doctorimage/{{$doctor->doctor_image}}" alt="">
              <div class="meta">
                <a href="#"><span class="mai-call"></span></a>
                <a href="#"><span class="mai-logo-whatsapp"></span></a>
              </div>
            </div>
            <div class="body">
              <p class="text-xl mb-0">{{$doctor->name}}</p>
              <span class="text-sm text-grey">{{$doctor->speciality}}</span>
              @if($doctor->credentials_verified)
                <span class="doctor-verified-label">Credentials verified</span>
              @endif
              <span class="doctor-rating-label">
                @if($doctor->reviews_count > 0)
                  ★ {{ number_format((float) $doctor->reviews_avg_rating, 1) }} · {{ $doctor->reviews_count }} verified {{ \Illuminate\Support\Str::plural('review', $doctor->reviews_count) }}
                @else
                  No verified reviews yet
                @endif
              </span>
            </div>
          </div>
        </div>
        @endforeach
 
          
      </div>
    </div>
  </div>