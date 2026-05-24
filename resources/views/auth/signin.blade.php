@extends('super_admin.layouts.app')

@section('title', 'Sign In')
@section('body_class', 'super-admin-signin-page')

@push('styles')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --signin-bg: #0b3f73;
      --signin-bg-deep: #082d53;
      --signin-surface: rgba(8, 34, 62, 0.34);
      --signin-surface-strong: rgba(255, 255, 255, 0.12);
      --signin-border: rgba(255, 255, 255, 0.2);
      --signin-text: #f8fbff;
      --signin-text-soft: rgba(248, 251, 255, 0.74);
      --signin-input-bg: rgba(255, 255, 255, 0.9);
      --signin-input-text: #17324d;
      --signin-shadow: 0 32px 80px rgba(0, 0, 0, 0.28);
      --signin-accent: #ffffff;
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      min-height: 100%;
    }

    body.super-admin-signin-page {
      margin: 0;
      font-family: 'Manrope', sans-serif;
      background:
        linear-gradient(135deg, rgba(7, 32, 58, 0.58), rgba(10, 57, 103, 0.88)),
        radial-gradient(circle at 18% 30%, rgba(255, 255, 255, 0.12), transparent 26%),
        radial-gradient(circle at 78% 18%, rgba(255, 255, 255, 0.1), transparent 24%),
        linear-gradient(180deg, #114f8c 0%, #0c406f 50%, #082f57 100%);
      color: var(--signin-text);
      overflow-x: hidden;
    }

    .signin-page {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px 20px 72px;
      isolation: isolate;
    }

    .signin-page::before,
    .signin-page::after {
      content: "";
      position: absolute;
      inset: 0;
      pointer-events: none;
      z-index: -2;
    }

    .signin-page::before {
      background:
        linear-gradient(rgba(7, 39, 72, 0.72), rgba(7, 39, 72, 0.72)),
        linear-gradient(115deg, transparent 0 26%, rgba(255, 255, 255, 0.06) 26% 28%, transparent 28% 100%),
        linear-gradient(90deg, transparent 0 58%, rgba(255, 255, 255, 0.045) 58% 60%, transparent 60% 100%);
      opacity: 0.9;
    }

    .signin-page::after {
      background:
        radial-gradient(circle at 12% 42%, rgba(255, 255, 255, 0.18), transparent 20%),
        radial-gradient(circle at 85% 78%, rgba(255, 255, 255, 0.08), transparent 18%);
      z-index: -1;
    }

    .signin-shell {
      width: min(1120px, 100%);
      position: relative;
    }

    .signin-card {
      display: grid;
      grid-template-columns: minmax(0, 1.1fr) 1px minmax(320px, 0.9fr);
      align-items: center;
      min-height: min(700px, calc(100vh - 120px));
      padding: clamp(28px, 4vw, 56px);
      border: 1px solid var(--signin-border);
      border-radius: 32px;
      background: linear-gradient(145deg, rgba(5, 35, 66, 0.26), rgba(255, 255, 255, 0.04));
      box-shadow: var(--signin-shadow);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }

    .brand-panel {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      min-height: 100%;
    }

    .brand-content {
      max-width: 420px;
      text-align: center;
    }

    .logo-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 18px;
      margin-bottom: 28px;
    }

    .logo-badge {
      width: clamp(78px, 9vw, 96px);
      height: clamp(78px, 9vw, 96px);
      border-radius: 24px;
      display: grid;
      place-items: center;
      /* background: radial-gradient(circle at top, rgba(255, 255, 255, 0.36), rgba(255, 255, 255, 0.1)); */
      /* border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(8px); */
      /* -webkit-backdrop-filter: blur(8px); */
    }

    .logo-badge img {
      width: 100%;
      height: auto;
      display: block;
    }

    .brand-title {
      margin: 0;
      font-size: clamp(1.9rem, 3vw, 2.85rem);
      line-height: 1.08;
      font-weight: 800;
      letter-spacing: -0.04em;
    }

    .brand-subtitle {
      margin: 16px 0 0;
      font-size: clamp(1rem, 1.5vw, 1.2rem);
      line-height: 1.5;
      color: var(--signin-text-soft);
      font-weight: 500;
    }

    .brand-meta {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-top: 24px;
      padding: 10px 16px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.16);
      background: rgba(255, 255, 255, 0.08);
      color: rgba(255, 255, 255, 0.82);
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .brand-divider {
      width: 1px;
      height: min(360px, 56vh);
      justify-self: center;
      background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.72), transparent);
      border-radius: 999px;
    }

    .signin-panel {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .signin-panel-inner {
      width: min(420px, 100%);
    }

    .signin-kicker {
      display: inline-block;
      margin-bottom: 16px;
      padding: 8px 14px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.16);
      background: rgba(255, 255, 255, 0.08);
      color: rgba(255, 255, 255, 0.78);
      font-size: 0.76rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
    }

    .signin-copy h2 {
      margin: 0;
      font-size: clamp(2rem, 2.8vw, 2.75rem);
      font-weight: 800;
      letter-spacing: -0.04em;
    }

    .signin-subcopy {
      margin: 14px 0 0;
      font-size: 1rem;
      line-height: 1.7;
      color: var(--signin-text-soft);
      max-width: 34ch;
    }

    .signin-feedback-stack {
      display: grid;
      gap: 10px;
      margin-top: 22px;
    }

    .feedback {
      margin: 0;
      padding: 12px 14px;
      border-radius: 14px;
      font-size: 0.95rem;
      line-height: 1.5;
      border: 1px solid transparent;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
    }

    .feedback-success {
      background: rgba(30, 181, 113, 0.16);
      border-color: rgba(72, 214, 146, 0.34);
      color: #d8ffeb;
    }

    .feedback-error {
      background: rgba(244, 91, 105, 0.16);
      border-color: rgba(255, 141, 153, 0.34);
      color: #ffe2e6;
    }

    .signin-form {
      margin-top: 28px;
    }

    .input-label {
      display: block;
      margin-bottom: 10px;
      font-size: 0.95rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.9);
    }

    .input-wrap {
      position: relative;
    }

    .email-input {
      width: 100%;
      min-height: 60px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 16px;
      padding: 0 18px;
      background: var(--signin-input-bg);
      color: var(--signin-input-text);
      font-size: 1rem;
      font-weight: 600;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
      transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .email-input::placeholder {
      color: #7e93a8;
      font-weight: 500;
    }

    .email-input:focus {
      outline: none;
      border-color: rgba(255, 255, 255, 0.44);
      box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.14);
      transform: translateY(-1px);
    }

    .otp-button {
      width: 100%;
      min-height: 60px;
      margin-top: 18px;
      border: 0;
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      background: linear-gradient(135deg, #ffffff 0%, #e7f0fb 100%);
      color: #0b3f73;
      font-size: 1rem;
      font-weight: 800;
      letter-spacing: 0.01em;
      box-shadow: 0 18px 36px rgba(4, 23, 43, 0.22);
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .otp-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 22px 40px rgba(4, 23, 43, 0.28);
      filter: brightness(1.02);
    }

    .otp-button:focus-visible {
      outline: 3px solid rgba(255, 255, 255, 0.32);
      outline-offset: 3px;
    }

    .otp-button span[aria-hidden="true"] {
      font-size: 1.2rem;
      line-height: 1;
    }

    .signin-footer {
      position: absolute;
      left: 50%;
      bottom: 24px;
      transform: translateX(-50%);
      text-align: center;
      color: rgba(255, 255, 255, 0.78);
      font-size: 0.82rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      white-space: nowrap;
    }

    @media (max-width: 991.98px) {
      .signin-page {
        padding: 24px 16px 76px;
      }

      .signin-card {
        grid-template-columns: 1fr;
        gap: 28px;
        min-height: auto;
        padding: 28px 20px;
      }

      .brand-panel,
      .signin-panel {
        padding: 8px;
      }

      .brand-divider {
        width: 100%;
        height: 1px;
        background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.72), transparent);
      }

      .brand-content,
      .signin-panel-inner {
        max-width: 100%;
      }

      .signin-copy h2,
      .signin-subcopy,
      .input-label {
        text-align: left;
      }
    }

    @media (max-width: 575.98px) {
      .signin-card {
        border-radius: 24px;
      }

      .logo-row {
        gap: 12px;
      }

      .logo-badge {
        width: 74px;
        height: 74px;
        border-radius: 20px;
      }

      .brand-title {
        font-size: 1.7rem;
      }

      .signin-copy h2 {
        font-size: 1.8rem;
      }

      .signin-subcopy {
        font-size: 0.95rem;
      }

      .email-input,
      .otp-button {
        min-height: 56px;
        border-radius: 14px;
      }

      .signin-footer {
        bottom: 18px;
        width: calc(100% - 32px);
        white-space: normal;
      }
    }
  </style>
@endpush

@section('content')
  <div class="signin-page">
    <main class="signin-shell">
      <section class="signin-card" aria-label="DICT sign in">
        <section class="brand-panel" aria-label="Department information">
          <div class="brand-content">
            <div class="logo-row">
              <div class="logo-badge">
                <img src="{{ asset('images/dict_logo.png') }}" alt="DICT Logo">
              </div>
              <div class="logo-badge">
                <img src="{{ asset('images/bagong_pilipinas.png') }}" alt="Bagong Pilipinas Logo">
              </div>
            </div>

            <h1 class="brand-title">
              Department of Information and
              Communications Technology
            </h1>
            <p class="brand-subtitle">Region 01</p>
            <div class="brand-meta">Digital Access Portal</div>
          </div>
        </section>

        <div class="brand-divider" aria-hidden="true"></div>

        <section class="signin-panel" aria-label="Sign in form">
          <div class="signin-panel-inner">
            <div class="signin-copy">
              <h2>Sign In</h2>
              <p class="signin-subcopy">Enter the email address authorized by the super admin. Your Google Authenticator QR code and manual setup key are sent from the Authenticator Access page, and login continues directly to the authenticator code screen.</p>

              <div class="signin-feedback-stack">
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
            </div>

            <form class="signin-form" method="POST" action="" data-send-otp-form>
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

              <button type="submit" class="otp-button" data-send-otp-button>
                <span class="otp-button-text">Continue</span>
                <span aria-hidden="true">&rsaquo;</span>
              </button>
            </form>
          </div>
        </section>
      </section>
    </main>

    <footer class="signin-footer">&copy; DICT R01 {{ date('Y') }}. All Rights Reserved</footer>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.querySelector('[data-send-otp-form]');
      const button = document.querySelector('[data-send-otp-button]');

      if (!form || !button) {
        return;
      }

      form.addEventListener('submit', function () {
        if (button.disabled) {
          return;
        }

        button.disabled = true;
        button.setAttribute('aria-disabled', 'true');
        button.querySelector('.otp-button-text').textContent = 'Checking account...';
      });
    });
  </script>
@endsection
