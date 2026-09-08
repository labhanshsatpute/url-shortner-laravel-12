@extends('layouts.app')

@section('body')
<main>
    @include('components.header')
    <div class="container">
        @include('components.alert')
    </div>
    <div class="container">
        @yield('dashboard-section')
    </div>
</main>
@endsection