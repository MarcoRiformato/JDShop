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
        $html = '<!DOCTYPE html><html><head><title>JDOutlet - Richiesta Informazioni</title></head><body>';
        $html .= '<h1>Nuova Richiesta Informazioni</h1>';
        $html .= '<h2>Dati Cliente</h2>';
        $html .= '<p><strong>Nome:</strong> ' . htmlspecialchars($this->name) . '</p>';
        $html .= '<p><strong>Email:</strong> ' . htmlspecialchars($this->email) . '</p>';
        if ($this->phone) {
            $html .= '<p><strong>Telefono:</strong> ' . htmlspecialchars($this->phone) . '</p>';
        }
        $html .= '<h2>Prodotto di Interesse</h2>';
        $html .= '<p><strong>Prodotto:</strong> ' . htmlspecialchars($this->productTitle) . '</p>';
        $html .= '<p><strong>Prezzo:</strong> €' . number_format($this->productPrice, 2, ',', '.') . '</p>';
        $html .= '<h2>Messaggio</h2>';
        $html .= '<p>' . nl2br(htmlspecialchars($this->message)) . '</p>';
        $html .= '<hr><p><em>Questa email è stata inviata dal form di contatto del sito web.</em></p>';
        $html .= '<p><em>Data: ' . date('d/m/Y H:i') . '</em></p>';
        $html .= '</body></html>';
        
        return $this->subject('Richiesta Informazioni - ' . $this->productTitle)
                    ->html($html);
    }
}
