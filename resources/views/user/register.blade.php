@extends('layouts.main')
@section('container')
<div class="container mt-5">
  <div class="row">
    @if($errors->any())
    @foreach($errors->all() as $err)
    <p class="alert alert-danger">{{ $err }}</p>
    @endforeach
    @endif
    @if(session()->has('status'))
    <div class="alert alert-success">
      {{ session()->get('status') }}
    </div>
    @endif
    <div class="col-md-6 offset-md-3 mt-4 mb-5" style="height: 100%">
      <div class="mb-3 mt-5">
        <h3>@yield('title', $title)</h3>
      </div>
      <form action="{{ route('register.action') }}" method="POST" class="shadow p-4">
        @csrf
        <div class="mb-3">
          <label for="username">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Email"
            value="{{ old('email') }}">
        </div>
        <div class="mb-3">
          <label>Username <span class="text-danger">*</span></label>
          <input class="form-control" type="username" name="username" value="{{ old('username') }}"
            placeholder="Username" />
        </div>
        <div class="mb-3">
          <label>Password <span class="text-danger">*</span></label>
          <input class="form-control" type="password" name="password" placeholder="Password" />
        </div>
        <div class="mb-3">
          <label>Password Confirmation<span class="text-danger">*</span></label>
          <input class="form-control" type="password" name="password_confirm" placeholder="Password Confirmation" />
        </div>

        <div class="mb-3">
          <button class="btn btn-primary">Register</button>
        </div>

        <hr>

        <p class="text-center mb-0">If you already have an account <a href="{{ route('login.action') }}">Login Now</a>
        </p>

      </form>
    </div>
  </div>
</div>
@endsection