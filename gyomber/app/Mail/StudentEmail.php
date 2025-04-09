<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentEmail extends Mailable
{
    // sorbaállíthatóság email küldéshez
    use Queueable, SerializesModels;



    
    public $mailData;

    public function __construct($mailData)  // kapott paraméter majd inicializalasa
    {
        $this->mailData = $mailData;
    }


    public function envelope(): Envelope    // levél tárgya
    {
        return new Envelope(
            subject: 'Jövedelem igazolás',
        );
    }




    public function content(): Content
    {
        return new Content(
            view: 'emails.students',
            with: [
                'name' => $this->mailData['name'],  // átadjuk a felhasznalo nevét
            ]
        );
    }




    public function attachments(): array
    {
        $pdfName = $this->mailData['pdf_name'];
        $pdfPath = storage_path('app/public/kuldendoFajlok/' . $pdfName);

        return [
            Attachment::fromPath($pdfPath)
                ->withMime('application/pdf'),  // meghatarozzuk hogy a levelezoporgram tudja mit kuldunk és hogyan kezelje
        ];
    }
}

