
<!DOCTYPE html>
<html lang="en">
  <head>
    <style type = "text/css">
      label{
        display: inline-block;
        width: 200px;
      }
    </style>
  @include('admin.css')
</head>
  <body>
    <div class="container-scroller">
      <div class="row p-0 m-0 proBanner" id="proBanner">
        <div class="col-md-12 p-0 m-0">
          <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
            <div class="ps-lg-1">
              <!-- <div class="d-flex align-items-center justify-content-between">
                <p class="mb-0 font-weight-medium me-3 buy-now-text">Free 24/7 customer support, updates, and more with this template!</p>
                <a href="https://www.bootstrapdash.com/product/corona-free/?utm_source=organic&utm_medium=banner&utm_campaign=buynow_demo" target="_blank" class="btn me-2 buy-now-btn border-0">Get Pro</a>
              </div> -->
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <a href="https://www.bootstrapdash.com/product/corona-free/"><i class="mdi mdi-home me-3 text-white"></i></a>
              <button id="bannerClose" class="btn border-0 p-0">
                <i class="mdi mdi-close text-white me-0"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->


        @include('admin.navbar')


        <!-- partial -->
         <div class = "container-fluid page-body-wrapper">


         
          <div class = "container" align = "center" style = "padding-top:100px;">
            @if($errors->any())
              <div class="alert alert-danger" role="alert">
                @foreach($errors->all() as $error)
                  <div>{{ $error }}</div>
                @endforeach
              </div>
            @endif
            @if(session()->has('message'))
         <div class = "alert alert-success">
          <button type = "button" class = "close" data-dismiss="alert">
            X
          </button>
           {{ session()->get('message') }}
          
         </div>
         @endif
            <form action="{{ url('upload_doctor') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div style = "padding:20px;">
                <label>Doctor Name</label>
                <input type="text" name="doctor_name" style="color:black" placeholder="Enter doctor name" value="{{ old('doctor_name') }}" required>

              </div>

              <div style = "padding:20px;">
                <label>Phone</label>
                <input type="tel" name="phone" style="color:black" placeholder="Enter doctor phone number" value="{{ old('phone') }}" required>

              </div>
              <div style = "padding:20px;">
                <label>Speciality</label>
                <select name = "speciality" style = "color:black; width:200px;">
                  <option value="">--select--</option>
                  <option value="skin" @selected(old('speciality') === 'skin')>Skin</option>
                  <option value="heart" @selected(old('speciality') === 'heart')>Heart</option>
                  <option value="brain" @selected(old('speciality') === 'brain')>Brain</option>
                  <option value="bone" @selected(old('speciality') === 'bone')>Bone</option>
                </select>

              </div>
              <div style = "padding:20px;">
                <label>Room Number</label>
                <input type="number" name="room_number" style="color:black" placeholder="Enter room number" value="{{ old('room_number') }}" required>

              </div>

              <div style = "padding:20px;">
                <label>Clinic Address</label>
                <input type="text" name="clinic_address" style="color:black" placeholder="Enter clinic address" value="{{ old('clinic_address') }}" required>
              </div>

              <div style = "padding:20px;">
                <label>Latitude</label>
                <input type="number" name="latitude" step="any" min="-90" max="90" style="color:black" placeholder="e.g. 23.8103" value="{{ old('latitude') }}" required>
              </div>

              <div style = "padding:20px;">
                <label>Longitude</label>
                <input type="number" name="longitude" step="any" min="-180" max="180" style="color:black" placeholder="e.g. 90.4125" value="{{ old('longitude') }}" required>
                <small>Enter the clinic's decimal coordinates from its map pin.</small>
              </div>

              <div style = "padding:20px;">
                <label>Credentials verified</label>
                <input type="hidden" name="credentials_verified" value="0">
                <input type="checkbox" name="credentials_verified" value="1">
              </div>

              <div style = "padding:20px;">
                <label>Available for appointments</label>
                <input type="hidden" name="available" value="0">
                <input type="checkbox" name="available" value="1" checked>
              </div>

              <div style = "padding:20px;">
                <label>Doctor image</label>
                <input type="file" name = "doctor_image" style = "color:white"required>

              </div>
              <div style = "padding:20px;">
                <input type="submit" class = "btn btn-success" value = "Submit"required>
              </div>
            </form>
          </div>
        </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    @include('admin.script')
  </body>
</html>
 