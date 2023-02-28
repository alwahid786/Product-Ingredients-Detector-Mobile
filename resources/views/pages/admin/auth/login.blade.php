@extends('layouts.admin.admin-default')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-4 login-banner-right px-0">
      <div class="login-logo text-center">
        <!-- <img src="{{asset('public/assets/images/login-logo.svg')}}" alt="image"> -->
        <h1>Tulip</h1>
      </div>

    </div>
    <div class="col-sm-8">
      <div class="login-content-outer">
        <div class="login-content py-5">
          <div class="login-heading text-center">
            <h2>Tulip</h2>
            <!-- <img src="{{asset('public/assets/images/login-logo-right.svg')}}" alt="image"> -->
            <h1>Sign in</h1>
          </div>
          <div class="login-form">
            <form>

              <div class="form-group login-email-field">
                <label for="loginemail">Email</label>
                {{-- <img src="{{asset('assets/images/mail.svg')}}" alt="image"> --}}
                <input type="email" name="email" class="form-control" id="loginemail" aria-describedby="emailHelp" placeholder="Enter your email">

              </div>
              <div class="form-group login-email-field pt-3">
                <label for="loginpassword">Password</label>
                <i class="fa fa-eye-slash" aria-hidden="true" onclick="passView()"></i>
                <input type="password" class="form-control" id="loginpassword" name="password" placeholder="Enter your password">

              </div>


              <div class="d-flex justify-content-center login-button-outer ">
                <a href="{{url('/dashboard')}}" class="btn login-btn"> login

                </a>
                <!-- <button type="" class="btn  login-btn">Login</button> -->
              </div>
            </form>
          </div>
        </div>
      </div>


    </div>

  </div>
</div>

@endsection
@section('admininsertjavascript')
<script>
  function passView() {
    var passField = document.getElementById("loginpassword");
    if (passField.type === "password") {
      passField.type = "text";
    } else {
      passField.type = "password";
    }
  }
</script>
@endsection