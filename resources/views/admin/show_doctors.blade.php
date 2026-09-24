
<!DOCTYPE html>
<html lang="en">
  @include('admin.css')
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


        <!-- Doctors data showing -->
         <div class = "container-fluid page-body-wrapper">
            <div align = "center" style = "padding:100px;">
                <table>
                    <tr style = "background-color:black;">
                        <th style = "padding:10px;">Doctor Name</th>
                        <th style = "padding:10px;">Phone</th>
                        <th style = "padding:10px;">Speciality</th>
                        <th style = "padding:10px;">Room No</th>
                        <th style = "padding:10px;">Image</th>
                        <th style = "padding:10px;">Delete</th>
                        <th style = "padding:10px;">Update</th>
                    </tr>
                    @foreach($doctors as $doctor)
                    <tr align = "center" style = "background-color:gray; color:white;">
                        <td style = "padding:10px;">{{$doctor->name}}</td>
                        <td style = "padding:10px;">{{$doctor->phone}}</td>
                        <td style = "padding:10px;">{{$doctor->speciality}}</td>
                        <td style = "padding:10px;">{{$doctor->room_number}}</td>
                        <td><img height="100" width="100" src="doctorimage/{{$doctor->doctor_image}}"></td>
                        <td>
                            <a onclick = "return confirm('Are you sure you want to delete this doctor?')" class = "btn btn-danger" href="{{url('delete_doctor', $doctor->id)}}">Delete</a>
                        </td>
                        <td>
                            <a class = "btn btn-primary" href="{{url('update_doctor', $doctor->id)}}">Update</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

      
    <!-- container-scroller -->
    <!-- plugins:js -->
    @include('admin.script')
  </body>
</html>