@extends('super_admin.layouts.app')

@section('title', 'DICT Sign In')
@section('body_class', 'super-admin-signin-page')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/sign.css') }}?v={{ filemtime(public_path('css/sign.css')) }}" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
  <div class="signin-page">
    <div class="overlay"></div>

    <main class="signin-layout">
      <section class="brand-panel" aria-label="Department information">
        <div class="logo-row">
          <div class="logo-glow">
            <img src="{{ asset('images/dict_logo.png') }}" alt="DICT Logo">
          </div>
          <div class="logo-glow">
            <img src="{{ asset('images/bagong_pilipinas.png') }}" alt="Bagong Pilipinas Logo">
          </div>
        </div>

        <h1 class="brand-title">
          Department of Information and<br>
          Communications Technology<br>
        </h1>
      </section>

      <div class="brand-divider" aria-hidden="true"></div>

      <section class="signin-panel" aria-label="Sign in form">
        <div class="signin-shell">
        <div class="signin-copy">
          <p class="signin-kicker">Secure Access Portal</p>
          <h2>Sign In</h2>
          <p class="signin-subcopy">Enter your active email address to receive a one-time verification code.</p>

          @if (session('status'))
            <p class="feedback feedback-success">{{ session('status') }}</p>
          @endif

          @if (session('error'))
            <p class="feedback feedback-error">{{ session('error') }}</p>
          @endif

          @if ($errors->has('email'))
            <p class="feedback feedback-error">{{ $errors->first('email') }}</p>
          @endif
        </div>

        <form class="signin-form" method="POST" action="">
          @csrf
          <label class="input-label" for="email">Email Address</label>
          <div class="input-wrap">
            <input
              id="email"
              type="email"
              name="email"
              class="email-input"
              placeholder="name@dict.gov.ph"
              value="{{ old('email') }}"
              required
              autofocus
            >
            
          </div>

          <button type="submit" class="otp-button">
            <span class="otp-button-text">Send OTP</span>
            <span aria-hidden="true">&rsaquo;</span>
          </button>
        </form>
        </div>
      </section>
    </main>

    <footer class="signin-footer">&copy; DICT PO1 2026. All Rights Reserved</footer>
  </div>
@endsection
