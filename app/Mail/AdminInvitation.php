<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminInvitation extends Mailable
{
	use Queueable;
	use SerializesModels;

	/**
	 * Create a new message instance.
	 */
	public function __construct(public Invitation $invitation, public string $token) {}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			subject: __('[Garfaludica APS] You have been invited to join the admin team of GobCon Garfagnana 2024!'),
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			// view: 'mail.html.admin-invitation',
			markdown: 'mail.markdown.admin-invitation',
			with: [
				'invitationUrl' => route('admin.invitations.accept', [
					'invitation' => $this->invitation,
					'token' => $this->token,
					'email' => $this->invitation->email,
				]),
			],
		);
	}

	/**
	 * Get the attachments for the message.
	 *
	 * @return array<int, \Illuminate\Mail\Mailables\Attachment>
	 */
	public function attachments(): array
	{
		return [];
	}
}
