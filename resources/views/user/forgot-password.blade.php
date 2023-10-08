@extends('layouts.main')
@section('container')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6 offset-md-3 mt-4 mb-5" style="height: 100%">
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
      <div class="mb-3 mt-5">
        <h3>@yield('title', $title)</h3>
      </div>
      <form action="{{ route('password.email') }}" method="POST" class="shadow p-4">
        @csrf

        <p>Please enter your email address to reset request</p>

        <div class="mb-3">
          <input type="email" class="form-control" name="email" id="email" placeholder="Email"
            value="{{ old('email') }}">
        </div>
        

        <input type="submit" value="Request password reset" class="btn btn-primary">

        <hr>

      </form>
    </div>
  </div>
</div>
@endsection