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
        $dateNaissance = \Carbon\Carbon::parse($data['enfant_date_naissance'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="title">Autorisation parentale</div>

    <div class="content">
        Je soussigné(e) {{ $data['parent_nom'] ?? '' }}, agissant en qualité de représentant légal de l'enfant :
    </div>

    <div class="content" style="margin-left: 1cm; font-weight: bold;">
        {{ $data['enfant_nom'] ?? '' }}, né(e) le {{ $dateNaissance }}
    </div>

    <div class="content">
        autorise mon enfant à participer à l'activité suivante :
    </div>

    <div class="content" style="margin-left: 1cm; font-weight: bold;">
        {{ $data['activite'] ?? '' }}
    </div>

    <div class="content" style="margin-top: 0.8cm;">
        Je déclare avoir pris connaissance des conditions de déroulement de cette activité et décharge les organisateurs de toute responsabilité en cas d'accident.
    </div>

    <div class="content">
        Fait pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature du parent<br>
            {{ $data['parent_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
