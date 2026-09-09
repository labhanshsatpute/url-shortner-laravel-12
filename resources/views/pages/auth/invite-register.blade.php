@extends('layouts.app')

@section('head')
<style>
    html,
    body {
    height: 100%;
    }

    body {
    display: flex;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
    }

    .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
    }

    .form-signin .checkbox {
    font-weight: 400;
    }

    .form-signin .form-floating:focus-within {
    z-index: 2;
    }
</style>
@endsection

@section('body')
<main class="form-signin">
  <form action="{{ route('handle.register.from.invite', ['token' => $invite->token]) }}" method="POST">
    @csrf

    @include('components.alert')

    <h1 class="h3 mb-3 fw-normal">Register from invite</h1>
    <div class="mb-2">
      <label class="form-label">Name</label>
      <input type="text" name="name" value="{{ old('name', $invite->name) }}" class="form-control" placeholder="Your name">
      @error('name')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-2">
      <label class="form-label">Email address</label>
      <input type="email" name="email" readonly value="{{ old('email', $invite->email) }}" class="form-control" placeholder="name@example.com">
      @error('email')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-2">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Password">
      @error('password')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Confirm Password</label>
      <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
      @error('password_confirmation')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <button class="w-100 btn btn-primary" type="submit">Register</button>
  </form>
</main>
@endsection