<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 2cm; }
        .header { margin-bottom: 1cm; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 1.5cm; text-transform: uppercase; }
        .content { text-align: justify; margin-bottom: 0.5cm; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin = \Carbon\Carbon::parse($data['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="header">
        {{ $data['entreprise'] ?? '' }}
    </div>

    <div class="title">Certificat de travail</div>

    <div class="content">
        Je soussigné(e), représentant de l'entreprise {{ $data['entreprise'] ?? '' }}, certifie que :
    </div>

    <div class="content" style="margin-left: 1cm; font-weight: bold;">
        {{ $data['employe_nom'] ?? '' }}
    </div>

    <div class="content">
        a été employé(e) au sein de notre entreprise en qualité de <strong>{{ $data['poste'] ?? '' }}</strong>.
    </div>

    <div class="content">
        <strong>Période d'emploi :</strong> du {{ $dateDebut }} au {{ $dateFin }}.
    </div>

    <div class="content" style="margin-top: 0.8cm;">
        {{ $data['employe_nom'] ?? '' }} quitte notre entreprise libre de tout engagement.
    </div>

    <div class="content">
        Ce certificat est délivré conformément aux dispositions de l'article L. 1234-19 du Code du travail.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature et cachet de l'entreprise
        </div>
    </div>
</body>
</html>
