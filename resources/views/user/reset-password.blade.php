@extends('layouts.main')
@section('container')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6 offset-md-3 mt-4 mb-5" style="height: 100%">
      
      <div class="mb-3 mt-5">
        <h3>@yield('title', $title)</h3>
      </div>
      <form action="{{ route('password.update') }}" method="POST" class="shadow p-4">
        @csrf
        <input type="hidden" name="token" value="{{ request()->token }}">
        <input type="hidden" name="email" value="{{ request()->email }}">
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input class="form-control" type="password" name="password" />
      </div>
      <div class="mb-3">
          <label for="password_confirmation" class="form-label">Password Confirmation</label>
          <input class="form-control" type="password" name="password_confirmation" />
      </div>
      
      <input type="submit" value="Update password" class="btn btn-primary">
      </form>
    </div>
  </div>
</div>
@endsection