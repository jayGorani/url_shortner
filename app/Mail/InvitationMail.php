<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

use App\Models\Invitation;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable {

    use Queueable,SerializesModels;

    public function __construct(public Invitation $invitation,public string $plainToken)
    {

    }

    public function build()
    {
        return $this->subject('You are invited to '.($this->invitation->company_name ?: $this->invitation->company?->name))->view('emails.invitation')->with( [
            'acceptUrl' => route( 'invitations.show', $this->plainToken )
        ]);
    }


}