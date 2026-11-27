<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <meta name="copyright" content="MACode ID, https://macodeid.com/">

  <title>One Health - Medical Center HTML5 Template</title>

  <link rel="stylesheet" href="../assets/css/maicons.css">

  <link rel="stylesheet" href="../assets/css/bootstrap.css">

  <link rel="stylesheet" href="../assets/vendor/owl-carousel/css/owl.carousel.css">

  <link rel="stylesheet" href="../assets/vendor/animate/animate.css">

  <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>

  <!-- Back to top button -->
  <div class="back-to-top"></div>

  <header>
    <div class="topbar">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 text-sm">
            <div class="site-info">
              <a href="#"><span class="mai-call text-primary"></span> +00 123 4455 6666</a>
              <span class="divider">|</span>
              <a href="#"><span class="mai-mail text-primary"></span> mail@example.com</a>
            </div>
          </div>
          <div class="col-sm-4 text-right text-sm">
            <div class="social-mini-button">
              <a href="#"><span class="mai-logo-facebook-f"></span></a>
              <a href="#"><span class="mai-logo-twitter"></span></a>
              <a href="#"><span class="mai-logo-dribbble"></span></a>
              <a href="#"><span class="mai-logo-instagram"></span></a>
            </div>
          </div>
        </div> <!-- .row -->
      </div> <!-- .container -->
    </div> <!-- .topbar -->

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="#"><span class="text-primary">One</span>-Health</a>

        <form action="#">
          <div class="input-group input-navbar">
            <div class="input-group-prepend">
              <span class="input-group-text" id="icon-addon1"><span class="mai-search"></span></span>
            </div>
            <input type="text" class="form-control" placeholder="Enter keyword.." aria-label="Username" aria-describedby="icon-addon1">
          </div>
        </form>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupport" aria-controls="navbarSupport" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupport">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.html">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="doctors.html">Doctors</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../public/html/blog.html">M.H.P</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact</a>
            </li>

            @if(Route::has('login')) 
              @auth
              <li class="nav-item">
                <a class="nav-link" style = "background-color: #007bff; color: white; color:white" href="{{url('my-appointment')}}">My Appointment</a>
              </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                  <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger ml-lg-3" style="border:none; cursor:pointer;">Logout</button>
                  </form>
                </li>
              @else
                <li class="nav-item">
                  <a class="btn btn-primary ml-lg-3" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                  <a class="btn btn-primary ml-lg-3" href="{{ route('register') }}">Register</a>
                </li>
              @endauth
            @endif



          </ul>
        </div> <!-- .navbar-collapse -->
      </div> <!-- .container -->
    </nav>
  </header>


          @if(session()->has('message'))
         <div class = "alert alert-success">
          <button type = "button" class = "close" data-dismiss="alert">
            X
          </button>
           {{ session()->get('message') }}
          
         </div>
         @endif

  <section class="page-hero home-hero">
    <div class="container home-hero-inner">
      <div class="home-hero-copy wow fadeInLeft">
        <span class="home-hero-eyebrow"><span></span> ONE HEALTH · YOUR CARE, CONNECTED</span>
        <h1>Better care,<br><span>closer to home.</span></h1>
        <p>Find the right doctor and take the next step in your health journey with care that puts you first.</p>
        <div class="home-hero-actions">
          <a href="#appointment" class="home-hero-button">Book an appointment <span aria-hidden="true">→</span></a>
          <a href="#find-doctor" class="home-hero-link">Find nearby doctors</a>
        </div>
        <div class="home-hero-note"><span aria-hidden="true">✓</span> A simpler way to manage your care</div>
      </div>

      <div class="home-hero-art wow fadeInRight" aria-hidden="true">
        <div class="home-hero-glow"></div>
        <div class="home-orbit home-orbit-back"></div>
        <div class="home-orbit home-orbit-front"></div>
        <div class="home-plus home-plus-one">+</div>
        <div class="home-plus home-plus-two">+</div>
        <div class="home-care-card">
          <div class="home-care-card-top">
            <span class="home-card-mark">+</span>
            <span>ONE HEALTH</span>
            <span class="home-card-menu">•••</span>
          </div>
          <div class="home-care-illustration">
            <div class="home-care-ring"></div>
            <div class="home-care-cross"><span></span><i></i></div>
            <div class="home-care-orb"></div>
          </div>
          <div class="home-card-caption">
            <strong>Your health, in focus</strong>
            <span>Care starts with a conversation</span>
          </div>
          <div class="home-card-progress"><span></span></div>
        </div>
        <div class="home-floating-card">
          <span class="home-floating-icon">+</span>
          <span><strong>Here when you need us</strong><small>Explore care with One Health</small></span>
        </div>
        <div class="home-hero-platform"></div>
      </div>
    </div>
    <a class="home-hero-scroll" href="#services"><span></span> Explore care</a>
  </section>

  <section class="doctor-finder" id="find-doctor">
    <div class="container">
      <div class="doctor-finder-card">
        <div class="doctor-finder-copy">
          <span class="doctor-finder-eyebrow">PERSONALIZED TO YOUR AREA</span>
          <h2>Find care near you</h2>
          <p>We’ll look for available doctors with verified credentials within 25 km of your location.</p>
        </div>
        <form id="doctor-recommendation-form" method="POST" action="{{ route('doctor-recommendations') }}">
          @csrf
          <input type="hidden" name="latitude" id="recommendation-latitude">
          <input type="hidden" name="longitude" id="recommendation-longitude">
          <label for="recommendation-speciality">Specialty <span>(optional)</span></label>
          <select name="speciality" id="recommendation-speciality" class="form-control">
            <option value="">Any specialty</option>
            @foreach($doctors->pluck('speciality')->filter()->unique()->sort() as $speciality)
              <option value="{{ $speciality }}">{{ ucfirst($speciality) }}</option>
            @endforeach
          </select>
          <button type="submit" id="doctor-recommendation-submit" class="doctor-finder-button">
            Use my location <span aria-hidden="true">→</span>
          </button>
          <p id="doctor-location-status" class="doctor-location-status" role="status" aria-live="polite"></p>
          <p class="doctor-location-privacy">Your precise location is used for this search only and is not saved. Gemini receives shortlisted doctors' names, approximate distances, and review summaries—not your coordinates, selected specialty, account, or health details.</p>
        </form>
      </div>
    </div>
  </section>

  <div class="bg-light" id="services">
    <div class="page-section py-3 mt-md-n5 custom-index">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-4 py-3 py-md-0">
            <div class="card-service wow fadeInUp">
              <div class="circle-shape bg-secondary text-white">
                <span class="mai-chatbubbles-outline"></span>
              </div>
              <p><span>Chat</span> with a doctors</p>
            </div>
          </div>
          <div class="col-md-4 py-3 py-md-0">
            <div class="card-service wow fadeInUp">
              <div class="circle-shape bg-primary text-white">
                <span class="mai-shield-checkmark"></span>
              </div>
              <p><span>One</span>-Health Protection</p>
            </div>
          </div>
          <div class="col-md-4 py-3 py-md-0">
            <div class="card-service wow fadeInUp">
              <div class="circle-shape bg-accent text-white">
                <span class="mai-basket"></span>
              </div>
              <p><span>One</span>-Health Pharmacy</p>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- .page-section -->

    <div class="page-section pb-0">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 py-3 wow fadeInUp">
            <h1>Welcome to Your Health <br> Center</h1>
            <p class="text-grey mb-4">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Accusantium aperiam earum ipsa eius, inventore nemo labore eaque porro consequatur ex aspernatur. Explicabo, excepturi accusantium! Placeat voluptates esse ut optio facilis!</p>
            <a href="about.html" class="btn btn-primary">Learn More</a>
          </div>
          <div class="col-lg-6 wow fadeInRight" data-wow-delay="400ms">
            <div class="img-place custom-img-1">
              <img src="../assets/img/bg-doctor.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div> <!-- .bg-light -->
  </div> <!-- .bg-light -->

    @include('doctor');
    @include('latest');
 <!-- .page-section -->
    @include('appoinment');

 <!-- .page-section -->

  <div class="page-section banner-home bg-image" style="background-image: url(../assets/img/banner-pattern.svg);">
    <div class="container py-5 py-lg-0">
      <div class="row align-items-center">
        <div class="col-lg-4 wow zoomIn">
          <div class="img-banner d-none d-lg-block">
            <img src="../assets/img/mobile_app.png" alt="">
          </div>
        </div>
        <div class="col-lg-8 wow fadeInRight">
          <h1 class="font-weight-normal mb-3">Get easy access of all features using One Health Application</h1>
          <a href="#"><img src="../assets/img/google_play.svg" alt=""></a>
          <a href="#" class="ml-2"><img src="../assets/img/app_store.svg" alt=""></a>
        </div>
      </div>
    </div>
  </div> <!-- .banner-home -->

  <footer class="page-footer">
    <div class="container">
      <div class="row px-md-3">
        <div class="col-sm-6 col-lg-3 py-3">
          <h5>Company</h5>
          <ul class="footer-menu">
            <li><a href="#">About Us</a></li>
            <li><a href="#">Career</a></li>
            <li><a href="#">Editorial Team</a></li>
            <li><a href="#">Protection</a></li>
          </ul>
        </div>
        <div class="col-sm-6 col-lg-3 py-3">
          <h5>More</h5>
          <ul class="footer-menu">
            <li><a href="#">Terms & Condition</a></li>
            <li><a href="#">Privacy</a></li>
            <li><a href="#">Advertise</a></li>
            <li><a href="#">Join as Doctors</a></li>
          </ul>
        </div>
        <div class="col-sm-6 col-lg-3 py-3">
          <h5>Our partner</h5>
          <ul class="footer-menu">
            <li><a href="#">One-Fitness</a></li>
            <li><a href="#">One-Drugs</a></li>
            <li><a href="#">One-Live</a></li>
          </ul>
        </div>
        <div class="col-sm-6 col-lg-3 py-3">
          <h5>Contact</h5>
          <p class="footer-link mt-2">351 Willow Street Franklin, MA 02038</p>
          <a href="#" class="footer-link">701-573-7582</a>
          <a href="#" class="footer-link">healthcare@temporary.net</a>

          <h5 class="mt-3">Social Media</h5>
          <div class="footer-sosmed mt-3">
            <a href="#" target="_blank"><span class="mai-logo-facebook-f"></span></a>
            <a href="#" target="_blank"><span class="mai-logo-twitter"></span></a>
            <a href="#" target="_blank"><span class="mai-logo-google-plus-g"></span></a>
            <a href="#" target="_blank"><span class="mai-logo-instagram"></span></a>
            <a href="#" target="_blank"><span class="mai-logo-linkedin"></span></a>
          </div>
        </div>
      </div>

      <hr>

      <p id="copyright">Copyright &copy; 2020 <a href="https://macodeid.com/" target="_blank">MACode ID</a>. All right reserved</p>
    </div>
  </footer>

<script src="../assets/js/jquery-3.5.1.min.js"></script>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

<script src="../assets/vendor/owl-carousel/js/owl.carousel.min.js"></script>

<script src="../assets/vendor/wow/wow.min.js"></script>

<script src="../assets/js/theme.js"></script>

<script>
  (function () {
    const form = document.getElementById('doctor-recommendation-form');
    if (!form) return;

    const latitude = document.getElementById('recommendation-latitude');
    const longitude = document.getElementById('recommendation-longitude');
    const button = document.getElementById('doctor-recommendation-submit');
    const status = document.getElementById('doctor-location-status');

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!navigator.geolocation) {
        status.textContent = 'This browser does not support location access. Try a current browser on localhost or HTTPS.';
        return;
      }

      button.disabled = true;
      status.textContent = 'Requesting your location…';

      navigator.geolocation.getCurrentPosition(function (position) {
        latitude.value = position.coords.latitude;
        longitude.value = position.coords.longitude;
        form.submit();
      }, function () {
        button.disabled = false;
        status.textContent = 'Location was not available. Allow location access in your browser and try again.';
      }, {
        enableHighAccuracy: false,
        maximumAge: 300000,
        timeout: 10000
      });
    });
  })();
</script>
  
</body>
</html>