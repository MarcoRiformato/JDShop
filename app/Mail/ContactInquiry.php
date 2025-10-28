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
        $template = file_get_contents(resource_path('views/emails/contact-inquiry-simple.html'));
        
        $template = str_replace('[NAME]', $this->name, $template);
        $template = str_replace('[EMAIL]', $this->email, $template);
        $template = str_replace('[PHONE]', $this->phone, $template);
        $template = str_replace('[PRODUCT_TITLE]', $this->productTitle, $template);
        $template = str_replace('[PRODUCT_PRICE]', number_format($this->productPrice, 2, ',', '.'), $template);
        $template = str_replace('[MESSAGE]', $this->message, $template);
        $template = str_replace('[DATE]', date('d/m/Y H:i'), $template);
        
        return $this->subject('Richiesta Informazioni - ' . $this->productTitle)
                    ->html($template);
    }
}
