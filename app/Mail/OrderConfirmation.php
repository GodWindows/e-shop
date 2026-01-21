<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public string $transactionId;
    public string $customerName;
    public int $amount;
    public string $orderDateTime;
    public array $orderItems;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $transactionId,
        string $customerName,
        int $amount,
        string $orderDateTime,
        array $orderItems
    ) {
        $this->transactionId = $transactionId;
        $this->customerName = $customerName;
        $this->amount = $amount;
        $this->orderDateTime = $orderDateTime;
        $this->orderItems = $orderItems;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Confirmation de votre commande - ' . $this->transactionId,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_confirmation',
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
