<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
            padding: 1.5cm 2cm;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 1cm;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 0.6cm;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 0.3cm;
            font-size: 11pt;
        }

        .content {
            text-align: justify;
            margin-bottom: 0.4cm;
        }

        .signature-block {
            margin-top: 1.5cm;
            display: table;
            width: 100%;
        }

        .signature-left, .signature-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .signature-right {
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        
        $bienTypeTexte = match($data['bien_type'] ?? '') {
            'appartement' => 'appartement',
            'maison' => 'maison',
            'studio' => 'studio',
            'local_commercial' => 'local commercial',
            default => 'bien'
        };

        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="title">
        Bail de location
    </div>

    <div class="section">
        <div class="section-title">Entre les soussignés :</div>
        <div class="content">
            Le bailleur : {{ $data['bailleur_nom'] ?? '' }}, demeurant {!! nl2br(e($data['bailleur_adresse'] ?? '')) !!}
        </div>
        <div class="content">
            Et le locataire : {{ $data['locataire_nom'] ?? '' }}, demeurant {!! nl2br(e($data['locataire_adresse'] ?? '')) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Article 1 - Désignation du local loué :</div>
        <div class="content">
            Le bailleur donne à bail au locataire qui accepte, un {{ $bienTypeTexte }}@if(!empty($data['bien_superficie'])) d'une superficie de {{ $data['bien_superficie'] }}@endif situé {!! nl2br(e($data['bien_adresse'] ?? '')) !!}.
        </div>
    </div>

    <div class="section">
        <div class="section-title">Article 2 - Durée du bail :</div>
        <div class="content">
            Le présent bail est consenti et accepté pour une durée@if(!empty($data['duree'])) de {{ $data['duree'] }}@else de trois ans@endif à compter du {{ $dateDebut }}.
        </div>
    </div>

    <div class="section">
        <div class="section-title">Article 3 - Loyer et charges :</div>
        <div class="content">
            Le présent bail est consenti moyennant un loyer mensuel de {{ $data['loyer_montant'] ?? '' }} euros
            @if(!empty($data['charges_montant']))
                , charges comprises d'un montant de {{ $data['charges_montant'] }} euros
            @endif
            , payable mensuellement d'avance.
        </div>
        @if(!empty($data['depot_garantie']))
            <div class="content">
                Le locataire verse à la signature du bail un dépôt de garantie d'un montant de {{ $data['depot_garantie'] }} euros.
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Article 4 - Obligations :</div>
        <div class="content">
            Le locataire s'engage à user du logement en bon père de famille, à payer le loyer aux échéances convenues et à entretenir les lieux loués.
        </div>
    </div>

    <div class="signature-block">
        <div class="signature-left">
            Fait à ___________________<br>
            Le {{ $dateAujourdhui }}<br><br>
            Le bailleur<br>
            {{ $data['bailleur_nom'] ?? '' }}
        </div>
        <div class="signature-right">
            <br><br><br>
            Le locataire<br>
            {{ $data['locataire_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
