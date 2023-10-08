@extends('layouts.main')
@section('container')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6 offset-md-3 mt-4 mb-5" style="height: 100%">
      @if(session('success'))
      <p class="alert alert-success">{{ session('success') }}</p>
      @endif
      @if($errors->any())
      @foreach($errors->all() as $err)
      <div class="alert alert-danger" role="alert">{{ $err }}</div>
      @endforeach
      @endif
      <div class="mb-3 mt-5">
        <h3>@yield('title', $title)</h3>
      </div>
      <form action="{{ route('login.action') }}" method="POST" class="shadow p-4">
        @csrf
        <div class="mb-3">
          <label for="username">Username</label>
          <input type="username" class="form-control" name="username" id="username" placeholder="Username"
            value="{{ old('username') }}">
        </div>

        <div class="mb-3">
          <label for="Password">Password</label>
          <input type="password" class="form-control" name="password" id="Password" placeholder="Password">
        </div>

        <div class="form-group mt-4 mb-4">
          <div class="captcha">
            <span>{!! captcha_img() !!}</span>
            <button type="button" class="btn btn-danger" class="reload" id="reload">
              &#x21bb;
            </button>
          </div>
        </div>
        <div class="form-group mb-4">
          <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
        </div>

        <a href="{{ route('password.request') }}" class="float-end text-decoration-none">Reset Password</a>

        <div class="mb-3">
          <button class="btn btn-primary">Login</button>
        </div>

        <hr>

        <p class="text-center mb-0">If you have not account <a href={{ route('register.action') }}>Register Now</a></p>

      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });
</script>
@endpush