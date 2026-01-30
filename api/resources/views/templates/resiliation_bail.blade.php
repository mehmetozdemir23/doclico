<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 2cm; }
        .header { margin-bottom: 1cm; }
        .recipient { margin-bottom: 1cm; text-align: right; }
        .object { margin-bottom: 0.8cm; font-weight: bold; }
        .content { text-align: justify; margin-bottom: 0.5cm; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateDepart = \Carbon\Carbon::parse($data['date_depart'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="header">
        {{ $data['locataire_nom'] ?? '' }}<br>
        {{ $data['adresse'] ?? '' }}
    </div>

    <div class="recipient">
        {{ $data['bailleur_nom'] ?? '' }}
    </div>

    <div class="object">
        Objet : Résiliation du bail
    </div>

    <div class="content">
        Madame, Monsieur,
    </div>

    <div class="content">
        Par la présente, je vous informe de ma décision de résilier le bail du logement situé au {{ $data['adresse'] ?? '' }}.
    </div>

    <div class="content">
        Conformément aux dispositions légales en vigueur, je vous prie de bien vouloir considérer cette lettre comme mon préavis de départ.
    </div>

    <div class="content">
        <strong>Date de départ souhaitée :</strong> {{ $dateDepart }}
    </div>

    <div class="content">
        Je reste à votre disposition pour convenir d'un rendez-vous afin de réaliser l'état des lieux de sortie et procéder à la remise des clés.
    </div>

    <div class="content">
        Je vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            {{ $data['locataire_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
