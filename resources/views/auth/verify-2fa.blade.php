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
                    <p class="page-copy">Enter the current 6-digit code from Google Authenticator for your authorized account.</p>

                    @if (session('status'))
                        <p class="feedback feedback-success">{{ session('status') }}</p>
                    @endif

                    @if (session('error'))
                        <p class="feedback feedback-error">{{ session('error') }}</p>
                    @endif

                    @if ($errors->has('code'))
                        <p class="feedback feedback-error">{{ $errors->first('code') }}</p>
                    @endif

                    <form method="POST" action="{{ route('auth.2fa.verify') }}" class="verify-form" data-otp-form>
                        @csrf
                        <input type="hidden" name="code" value="{{ old('code') }}" data-otp-hidden>

                        @php
                            $codeDigits = array_pad(str_split((string) old('code', '')), 6, '');
                        @endphp

                        <div class="otp-group" role="group" aria-label="Enter 6 digit Google Authenticator code">
                            @for ($i = 0; $i < 6; $i++)
                                <input
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    maxlength="1"
                                    class="otp-input"
                                    value="{{ $codeDigits[$i] ?? '' }}"
                                    data-otp-input
                                    {{ $i === 0 ? 'autofocus' : '' }}
                                >
                            @endfor
                        </div>

                        <div class="form-meta">
                            <div class="meta-left">
                                <span class="timer">Refreshes every 30 seconds</span>
                                <span class="resend-link" aria-hidden="true">{{ $userEmail }}</span>
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

        <footer class="footer">&copy; DICT R01 {{ date('Y') }}. All Rights Reserved</footer>
    </div>

    <script src="{{ asset('js/verify-otp.js') }}" defer></script>
</body>
</html>
