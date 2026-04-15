<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Google Authenticator</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{margin:0;font-family:'Poppins',sans-serif;background:linear-gradient(180deg,#eef4fb 0%,#dde9f6 100%);color:#17324b}
        .page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px 16px}
        .card{width:min(760px,100%);background:#fff;border-radius:28px;padding:32px;box-shadow:0 28px 64px rgba(15,23,42,.12)}
        .card h1{margin:0 0 10px;font-size:1.8rem}
        .card p{margin:0 0 14px;line-height:1.7;color:#5f7285}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;margin-top:24px}
        .qr-shell,.secret-shell{padding:20px;border:1px solid #dbe5ef;border-radius:20px;background:#f8fbff}
        .qr-shell img{max-width:100%;height:auto;display:block;margin:0 auto}
        .secret-code{display:block;margin-top:10px;padding:12px 14px;border-radius:14px;background:#fff;border:1px dashed #b7c8d9;font-weight:600;word-break:break-all}
        .steps{padding-left:18px;color:#5f7285}
        .steps li{margin-bottom:10px}
        .feedback{margin:18px 0 0;padding:12px 14px;border-radius:14px;font-size:.95rem}
        .feedback-success{background:#e8f7ef;color:#146c43}
        .feedback-error{background:#fdecec;color:#b42318}
        .form-row{margin-top:24px}
        .form-row label{display:block;margin-bottom:8px;font-size:.9rem;font-weight:600}
        .form-row input{width:100%;height:50px;padding:0 16px;border:1px solid #d6e0eb;border-radius:14px;font:inherit}
        .actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:20px}
        .primary-btn,.secondary-btn{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 18px;border-radius:14px;border:0;text-decoration:none;font:inherit;font-weight:600;cursor:pointer}
        .primary-btn{background:linear-gradient(135deg,#0a3f72,#0c5ea0);color:#fff}
        .secondary-btn{background:#eef4fb;color:#17324b}
        @media (max-width:720px){.card{padding:24px}.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="page">
        <section class="card" aria-label="Google Authenticator setup">
            <h1>Set Up Google Authenticator</h1>
            <p>Scan the QR code below with Google Authenticator or any TOTP-compatible app. After scanning, enter the current 6-digit code from your app to finish setup.</p>

            @if (session('status'))
                <p class="feedback feedback-success">{{ session('status') }}</p>
            @endif

            @if (session('error'))
                <p class="feedback feedback-error">{{ session('error') }}</p>
            @endif

            @if ($errors->has('code'))
                <p class="feedback feedback-error">{{ $errors->first('code') }}</p>
            @endif

            <div class="grid">
                <div class="qr-shell">
                    {!! $QR_Image !!}
                </div>

                <div class="secret-shell">
                    <p><strong>Manual setup key</strong></p>
                    <span class="secret-code">{{ $secret }}</span>

                    <ol class="steps">
                        <li>Open Google Authenticator on your phone.</li>
                        <li>Tap the add button and scan the QR code.</li>
                        <li>If scanning fails, enter the setup key manually.</li>
                        <li>Type the current 6-digit code below to confirm setup.</li>
                    </ol>
                </div>
            </div>

            <form method="POST" action="{{ route('auth.2fa.enable') }}">
                @csrf

                <div class="form-row">
                    <label for="code">Authenticator Code</label>
                    <input
                        id="code"
                        type="text"
                        name="code"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        value="{{ old('code') }}"
                        required
                    >
                </div>

                <div class="actions">
                    <button type="submit" class="primary-btn">Enable Google Authenticator</button>
                    <a href="{{ route('dashboard') }}" class="secondary-btn">Back to Dashboard</a>
                </div>
            </form>

            @if ($isEnabled)
                <form method="POST" action="{{ route('auth.2fa.disable') }}" style="margin-top:14px;">
                    @csrf
                    <button type="submit" class="secondary-btn">Disable Google Authenticator</button>
                </form>
            @endif
        </section>
    </div>
</body>
</html>
