<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Authenticator Verification</title>
    <link rel="stylesheet" href="{{ asset('css/verify-otp.css') }}?v={{ filemtime(public_path('css/verify-otp.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="page">
        <div class="overlay"></div>

        <div class="content">
            <section class="left-panel" aria-label="Department information">
                <div class="logos">
                    <img src="{{ asset('images/dict_logo.png') }}" alt="DICT Logo">
                    <img src="{{ asset('images/bagong_pilipinas.png') }}" alt="Bagong Pilipinas Logo">
                </div>

                <h1 class="office-title">
                    Department of Information and<br>
                    Communications Technology<br>
                </h1>
            </section>

            <div class="divider" aria-hidden="true"></div>

            <section class="right-panel" aria-label="Google Authenticator verification form">
                <div class="form-shell">
                    <h2 class="page-title">Google Authenticator</h2>
                    <p class="page-copy">Open your authenticator app and enter the current 6-digit code for <strong>{{ $userEmail }}</strong>.</p>

                    @if (session('status'))
                        <p class="feedback feedback-success">{{ session('status') }}</p>
                    @endif

                    @if (session('error'))
                        <p class="feedback feedback-error">{{ session('error') }}</p>
                    @endif

                    @if ($errors->has('code'))
                        <p class="feedback feedback-error">{{ $errors->first('code') }}</p>
                    @endif

                    <form method="POST" action="{{ route('auth.2fa.verify') }}" class="verify-form">
                        @csrf

                        <label class="input-label" for="code">Authentication Code</label>
                        <div class="input-wrap">
                            <input
                                id="code"
                                type="text"
                                name="code"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="6"
                                class="email-input"
                                placeholder="123456"
                                value="{{ old('code') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-meta">
                            <div class="meta-left">
                                <span class="timer">Refreshes every 30 seconds</span>
                            </div>

                            <button type="submit" class="verify-button">
                                Verify
                                <span aria-hidden="true">&rsaquo;</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <footer class="footer">&copy; DICT PO1 2026. All Rights Reserved</footer>
    </div>
</body>
</html>
