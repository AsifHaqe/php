
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


        <!-- partial -->
         <div class = "container-fluid page-body-wrapper">
            <div align = "center" style = "padding:100px;">
                <table>
                    <tr style = "background-color:black;">
                        <th style = "padding:10px;">Customer Name</th>
                        <th style = "padding:10px;">Email</th>
                        <th style = "padding:10px;">Phone</th>
                        <th style = "padding:10px;">Doctor Name</th>
                        <th style = "padding:10px;">Date</th>
                        <th style = "padding:10px;">Message</th>
                        <th style = "padding:10px;">Status</th>
                        <th style = "padding:10px;">Approved</th>
                        <th style = "padding:10px;">Completed</th>
                        <th style = "padding:10px;">Cancel</th>
                    </tr>
                    @foreach($data as $appoint)
                    <tr align = "center" style = "background-color:gray; color:white;">
                        <td style = "padding:10px;">{{$appoint->name}}</td>
                        <td style = "padding:10px;">{{$appoint->email}}</td>
                        <td style = "padding:10px;">{{$appoint->phone}}</td>
                        <td style = "padding:10px;">{{$appoint->doctor}}</td>
                        <td style = "padding:10px;">{{$appoint->date}}</td>
                        <td style = "padding:10px;">{{$appoint->message}}</td>
                        <td style = "padding:10px;">{{$appoint->status}}</td>
                        <td>
                            @if(strtolower($appoint->status) === 'in progress')
                                <form method="POST" action="{{ route('approved', $appoint->id) }}">
                                    @csrf
                                    <button class="btn btn-success" type="submit">Approve</button>
                                </form>
                            @endif
                        </td>
                        <td>
                            @if(strtolower($appoint->status) === 'approved')
                                <form method="POST" action="{{ route('completed', $appoint->id) }}">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">Mark completed</button>
                                </form>
                            @endif
                        </td>
                        <td>
                            @if(!in_array(strtolower($appoint->status), ['canceled', 'completed']))
                                <form method="POST" action="{{ route('canceled', $appoint->id) }}">
                                    @csrf
                                    <button class="btn btn-danger" type="submit">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>

         </div>
      
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>
      
    <!-- container-scroller -->
    <!-- plugins:js -->
    @include('admin.script')
  </body>
</html>