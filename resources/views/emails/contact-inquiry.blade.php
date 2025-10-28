<!DOCTYPE html>
<html>
<head>
    <title>Richiesta Informazioni</title>
</head>
<body>
    <h1>Nuova Richiesta Informazioni</h1>
    
    <h2>Dati Cliente</h2>
    <p><strong>Nome:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    @if($phone)
    <p><strong>Telefono:</strong> {{ $phone }}</p>
    @endif
    
    <h2>Prodotto di Interesse</h2>
    <p><strong>Prodotto:</strong> {{ $productTitle }}</p>
    <p><strong>Prezzo:</strong> €{{ number_format($productPrice, 2, ',', '.') }}</p>
    
    <h2>Messaggio</h2>
    <p>{{ $message }}</p>
    
    <hr>
    <p><em>Questa email è stata inviata dal form di contatto del sito web.</em></p>
    <p><em>Data: {{ date('d/m/Y H:i') }}</em></p>
</body>
</html>