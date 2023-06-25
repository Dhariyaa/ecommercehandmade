@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">My Settings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Settings (Admin) </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Renewal of Password </h3>
              </div>
              <!-- /.card-header -->
                            <!-- Error Message -->
                  @if (Session::has('error_message'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 20px;">
                    {{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                 <!-- Error Message -->
                 @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 20px;">
                    {{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

              <!-- form start -->
              <form role="form" method="post" action="{{ url('/admin/update-present-password') }}"
              name="updatePasswordForm" id="updatePasswordForm">@csrf
                <div class="card-body">

                <div class="form-group">
                    <label for="exampleInputEmail1">Admin Type </label>
                    <input class="form-control" value="{{ $adminDetails->type }}" readonly="">
                  </div>

                  <?php /*div class="form-group">
                    <label for="exampleInputEmail1">Admin Name </label>
                    <input type="text" class="form-control" value="{{ $adminDetails->name }}" placeholder="Enter admin or sub-admin name"
                    id="admin_name" name="admin_name">
                  </div>*/ ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Admin Email address </label>
                    <input class="form-control" value="{{ $adminDetails->email }}" readonly="">
                  </div>


                  <div class="form-group">
                    <label for="exampleInputPassword1">Present Password</label>
                    <input type="password" class="form-control" name="present_password" id="present_password"
                    placeholder="Enter your Present Password" required="">
                    <span id="checkPresentPassword"></span>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1">New Password</label>
                    <input type="password" class="form-control" name="new_password" id="new_password" 
                    placeholder="Enter your New Password" required="">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1">Set Password</label>
                    <input type="password" class="form-control" name="set_password" id="set_password" 
                    placeholder="Enter your set Password" required="">
                  </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Finalize</button>
                </div>
              </form>
            </div>

            <!-- /.card -->
          </div>
          <!--/.col (left) -->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  @endsection
