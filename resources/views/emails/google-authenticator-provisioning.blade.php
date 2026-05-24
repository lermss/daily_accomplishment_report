<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Authenticator Access</title>
</head>
<body style="margin:0;padding:24px;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#17324b;">
    <div style="max-width:620px;margin:0 auto;background:#ffffff;border-radius:18px;padding:32px;box-shadow:0 16px 40px rgba(15,23,42,0.08);">
        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Your DICT login has been authorized by the super admin.</p>

        <p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#5f7285;">
            This account now uses Google Authenticator directly. Scan the QR code below in your authenticator app, or use the manual setup key if scanning is unavailable.
        </p>

        @if ($qrImage)
            <div style="margin:0 0 22px;padding:20px;border-radius:16px;background:#eef4fb;border:1px solid #d7e4f2;text-align:center;">
                {!! $qrImage !!}
            </div>
        @endif

        <div style="margin:0 0 18px;padding:18px 20px;border-radius:16px;background:#f8fbff;border:1px dashed #b7c8d9;">
            <div style="font-size:13px;font-weight:700;color:#5f7285;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">Manual Setup Key</div>
            <div style="font-size:24px;line-height:1.5;font-weight:700;color:#0c5ea0;word-break:break-all;">{{ $manualSetupKey }}</div>
        </div>

        <p style="margin:0 0 8px;font-size:14px;line-height:1.6;"><strong>Authorized email:</strong> {{ $recipientEmail }}</p>

        <ol style="margin:18px 0 0;padding-left:20px;font-size:14px;line-height:1.7;color:#17324b;">
            <li>Open Google Authenticator on your phone.</li>
            <li>Add a new account by scanning the QR code or entering the manual setup key.</li>
            <li>Go to the DICT sign-in page and enter your authorized email address.</li>
            <li>Enter the current 6-digit Google Authenticator code to log in.</li>
        </ol>
    </div>
</body>
</html>
