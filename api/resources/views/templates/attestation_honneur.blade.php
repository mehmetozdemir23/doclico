<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 2cm; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 1.5cm; text-transform: uppercase; }
        .content { text-align: justify; margin-bottom: 0.5cm; }
        .declaration { background: #f5f5f5; padding: 0.5cm; margin: 0.8cm 0; font-style: italic; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="title">Attestation sur l'honneur</div>

    <div class="content">
        Je soussigné(e) {{ $data['nom'] ?? '' }}, demeurant au {{ $data['adresse'] ?? '' }}, atteste sur l'honneur :
    </div>

    <div class="declaration">
        {{ $data['objet'] ?? '' }}
    </div>

    <div class="content">
        Je suis informé(e) que toute fausse déclaration m'expose à des sanctions pénales prévues par l'article 441-7 du Code pénal.
    </div>

    <div class="content">
        Fait pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature<br>
            {{ $data['nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
