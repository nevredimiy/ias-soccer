<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // from: new Address('soccer@ias-soccer.com', 'Максим Мамедов'),
            // replyTo: [
            //     new Address('artemlitivnov@gmail.com', 'Artem Litvinov'),
            // ],
            
            subject: 'Новий турнір на сайті Soccer',

        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
