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
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            padding: 2cm;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 1.5cm;
            text-transform: uppercase;
        }

        .content {
            text-align: justify;
            margin-bottom: 0.6cm;
        }

        .signature-block {
            margin-top: 2cm;
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

        .signature-line {
            margin-top: 1cm;
            font-style: italic;
        }
    </style>
</head>
<body>
    @php
        
        $dateNaissanceHeberge = !empty($data['heberge_date_naissance'])
            ? \Carbon\Carbon::parse($data['heberge_date_naissance'])->locale('fr')->isoFormat('D MMMM YYYY')
            : null;
        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
        $titreHebergement = $data['titre_gratuit'] === 'oui' ? 'gratuit' : 'onéreux';
    @endphp

    <div class="title">
        Attestation d'hébergement
    </div>

    <div class="content">
        Je soussigné(e) {{ $data['hebergeur_prenom'] ?? '' }} {{ $data['hebergeur_nom'] ?? '' }}, demeurant au {{ $data['hebergeur_adresse'] ?? '' }},@if(!empty($data['hebergeur_telephone'])) joignable au {{ $data['hebergeur_telephone'] }},@endif atteste sur l'honneur héberger à titre {{ $titreHebergement }} {{ $data['heberge_prenom'] ?? '' }} {{ $data['heberge_nom'] ?? '' }}@if($dateNaissanceHeberge) né(e) le {{ $dateNaissanceHeberge }}@endif à mon domicile depuis le {{ $dateDebut }}.
    </div>

    <div class="content">
        Cette attestation est établie pour servir et valoir ce que de droit.
    </div>

    <div class="content">
        Fait pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        <div class="signature-left">
            Fait à ___________________
        </div>
        <div class="signature-right">
            Le {{ $dateAujourdhui }}
        </div>
    </div>

    <div class="signature-block">
        <div class="signature-left"></div>
        <div class="signature-right">
            <div class="signature-line">
                Signature de l'hébergeur<br>
                {{ $data['hebergeur_prenom'] ?? '' }} {{ $data['hebergeur_nom'] ?? '' }}
            </div>
        </div>
    </div>
</body>
</html>
