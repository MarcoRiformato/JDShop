<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;
    public $message;
    public $productTitle;
    public $productPrice;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'] ?? '';
        $this->message = $data['message'];
        $this->productTitle = $data['product_title'];
        $this->productPrice = $data['product_price'];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Richiesta Informazioni - ' . $this->productTitle)
                    ->view('emails.contact-inquiry')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'message' => $this->message,
                        'productTitle' => $this->productTitle,
                        'productPrice' => $this->productPrice,
                    ]);
    }
}
