<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BudgetWelcome extends Mailable
{
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('naoresponder@dellanno.com.br', 'Dell Anno | Site'),
            bcc: [new Address('rafael@8poroito.com.br')],
            subject: 'Bem-vindo ao atendimento exclusivo Dell Anno!',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.budget');
    }
}
