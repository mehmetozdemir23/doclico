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
        
        $dateEtat = \Carbon\Carbon::parse($data['date_etat'])->locale('fr')->isoFormat('D MMMM YYYY');
        $titreEtat = $data['type_etat'] === 'entree' ? 'État des lieux d\'entrée' : 'État des lieux de sortie';
    @endphp

    <div class="title">
        {{ $titreEtat }}
    </div>

    <div class="section">
        <div class="section-title">Désignation du local :</div>
        <div class="content">
            Adresse : {!! nl2br(e($data['bien_adresse'] ?? '')) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Présents :</div>
        <div class="content">
            Le bailleur : {{ $data['bailleur_nom'] ?? '' }}
        </div>
        <div class="content">
            Le locataire : {{ $data['locataire_nom'] ?? '' }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Date de l'état des lieux :</div>
        <div class="content">
            {{ $dateEtat }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">État général du logement :</div>
        <div class="content">
            {!! nl2br(e($data['etat_general'] ?? '')) !!}
        </div>
    </div>

    @if(!empty($data['observations']))
        <div class="section">
            <div class="section-title">Observations particulières :</div>
            <div class="content">
                {!! nl2br(e($data['observations'] ?? '')) !!}
            </div>
        </div>
    @endif

    <div class="content">
        Le présent état des lieux a été établi contradictoirement entre les parties, qui le reconnaissent sincère et véritable.
    </div>

    <div class="signature-block">
        <div class="signature-left">
            Fait à ___________________<br>
            Le {{ $dateEtat }}<br><br>
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
