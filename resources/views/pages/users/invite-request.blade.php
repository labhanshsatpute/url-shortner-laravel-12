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
  <form action="{{ route('handle.invite.request', ['token' => $invite->token]) }}" method="POST">
    @csrf

    @include('components.alert')

    <h1 class="h3 mb-3 fw-normal">Accept or Reject invite</h1>
    <div class="d-flex mb-3">
        <div class="form-check me-4">
            <input class="form-check-input" value="{{ \App\Enums\InviteStatus::ACCEPT->value }}" type="radio" name="status" id="status_{{ \App\Enums\InviteStatus::ACCEPT->value }}" required>
            <label class="form-check-label" for="status_{{ \App\Enums\InviteStatus::ACCEPT->value }}">
                Accept
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" value="{{ \App\Enums\InviteStatus::REJECT->value }}" type="radio" name="status" id="status_{{ \App\Enums\InviteStatus::REJECT->value }}" required>
            <label class="form-check-label" for="status_{{ \App\Enums\InviteStatus::REJECT->value }}">
                Reject
            </label>
        </div>
    </div>
    
    <button class="w-100 btn btn-primary" type="submit">Submit</button>
  </form>
</main>
@endsection