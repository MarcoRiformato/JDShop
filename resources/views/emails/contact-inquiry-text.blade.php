NUOVA RICHIESTA INFORMAZIONI
=============================

DATI CLIENTE:
Nome: {{ $name }}
Email: {{ $email }}
@if($phone)Telefono: {{ $phone }}
@endif

PRODOTTO DI INTERESSE:
Prodotto: {{ $productTitle }}
Prezzo: €{{ number_format($productPrice, 2, ',', '.') }}

MESSAGGIO:
{{ $message }}

---
Questa email è stata inviata dal form di contatto del sito web.
Data: {{ date('d/m/Y H:i') }}
