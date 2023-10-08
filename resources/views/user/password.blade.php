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
      <p class="alert alert-danger">{{ $err }}</p>
      @endforeach
      @endif
      <div class="mb-3 mt-5">
        <h3>@yield('title', $title)</h3>
      </div>
      <form action="{{ route('password.action') }}" method="POST" class="shadow p-4">
        @csrf
        <div class="mb-3">
          <label>Password <span class="text-danger">*</span></label>
          <input class="form-control" type="password" name="old_password" />
      </div>
      <div class="mb-3">
          <label>New Password <span class="text-danger">*</span></label>
          <input class="form-control" type="password" name="new_password" />
      </div>
      <div class="mb-3">
          <label>New Password Confirmation<span class="text-danger">*</span></label>
          <input class="form-control" type="password" name="new_password_confirmation" />
      </div>
      <div class="mb-3">
          <button class="btn btn-primary">Change</button>
          <a class="btn btn-danger" href="{{ route('home') }}">Back</a>
      </div>

        <hr>

      </form>
    </div>
  </div>
</div>
@endsection