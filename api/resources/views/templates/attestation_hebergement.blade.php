<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 2cm; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 1.5cm; text-transform: uppercase; }
        .content { text-align: justify; margin-bottom: 0.5cm; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="title">Attestation d'hébergement</div>

    <div class="content">
        Je soussigné(e) {{ $data['hebergeur_nom'] ?? '' }}, demeurant au {{ $data['adresse'] ?? '' }}, atteste sur l'honneur héberger à mon domicile :
    </div>

    <div class="content" style="margin-left: 1cm; font-weight: bold;">
        {{ $data['heberge_nom'] ?? '' }}
    </div>

    <div class="content">
        Cette personne réside à mon domicile depuis le {{ $dateDebut }}.
    </div>

    <div class="content" style="margin-top: 0.8cm;">
        Je m'engage à signaler tout changement de situation à qui de droit.
    </div>

    <div class="content">
        Fait pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature de l'hébergeur<br>
            {{ $data['hebergeur_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
