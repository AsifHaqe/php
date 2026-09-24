
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

            <div class = "container-fluid page-body-wrapper">

                <div class = "container" align = "center" style = "padding:100px">
                    @if(session()->has('message'))
                    <div class = "alert alert-success" style="max-width: 500px; margin-bottom: 20px;">
                        <button type = "button" class = "close" data-dismiss="alert">
                            X
                        </button>
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <form action="{{ url('edit_doctor', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style = "padding:20px;">
                            <label>Doctor Name</label>
                            <input type = "text" name = "name" style = "color: black;" value = "{{$doctor->name}}" required>
                        </div>
                        <div style = "padding:20px;">
                            <label>Phone</label>
                            <input type = "text" name = "phone" style = "color: black;" value = "{{$doctor->phone}}" required>
                        </div>
                        <div>
                            <label>Speciality</label>
                            <input type = "text" name = "speciality" value = "{{$doctor->speciality}}" required>
                        </div>
                        <div style = "padding:20px;">
                            <label>Room Number</label>
                            <input type = "text" name = "room_number" style = "color: black;" value = "{{$doctor->room_number}}" required>
                        </div>
                        <div style = "padding:20px;">
                            <label>Current Image</label>
                            <img height="100" width="100" src="{{ asset('doctorimage/' . $doctor->doctor_image) }}" alt="Doctor Image">
                            <input type="hidden" name="old_doctor_image" value="{{ $doctor->doctor_image }}">
                        </div>
                        <div style = "padding:20px;">
                            <label>Change Image</label>
                            <input type = "file" name = "doctor_image" style = "color: white;">
                        </div>
                        <div style = "padding:20px;">
                            <input type = "submit" class = "btn btn-success">
                        </div>
                    </form>
                </div>
            </div>



        <!-- partial -->
      
    <!-- container-scroller -->
    <!-- plugins:js -->
    @include('admin.script')
  </body>
</html>