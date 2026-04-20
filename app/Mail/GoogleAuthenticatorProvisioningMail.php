<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GoogleAuthenticatorProvisioningMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $recipientEmail,
        public readonly string $manualSetupKey,
        public readonly ?string $qrImage,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'DICT Google Authenticator Access',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.google-authenticator-provisioning',
            text: 'emails.google-authenticator-provisioning-text',
            with: [
                'recipientEmail' => $this->recipientEmail,
                'manualSetupKey' => $this->manualSetupKey,
                'qrImage' => $this->qrImage,
            ],
        );
    }
}
