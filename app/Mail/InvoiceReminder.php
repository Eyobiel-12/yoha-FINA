<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Invoice $invoice
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = Setting::getValue('company_name', 'Yohannes Hoveniersbedrijf B.V.');
        
        return new Envelope(
            subject: "Herinnering: Openstaande factuur {$this->invoice->invoice_number} - {$companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice.reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Attach the invoice PDF if it exists
        if ($this->invoice->pdf_path) {
            return [
                Attachment::fromPath(public_path($this->invoice->pdf_path))
                    ->as("factuur_{$this->invoice->invoice_number}.pdf")
                    ->withMime('application/pdf'),
            ];
        }
        
        return [];
    }
} 