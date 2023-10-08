@extends('layouts.main')
@section('container')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6 offset-md-3 mt-4 mb-5" style="height: 100%">
      @auth
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
      <p>Welcome <b>{{ Auth::user()->username }}</b></p>
      <a class="btn btn-primary" href="{{ route('password') }}">Change Password</a>
      <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
      @endauth
      @guest
      <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
      <a class="btn btn-info" href="{{ route('register') }}">Register</a>
      @endguest
    </div>
  </div>
</div>

@endsection