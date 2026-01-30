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
        
        $dateNaissance = !empty($data['declarant_date_naissance'])
            ? \Carbon\Carbon::parse($data['declarant_date_naissance'])->locale('fr')->isoFormat('D MMMM YYYY')
            : null;
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="title">
        Attestation sur l'honneur
    </div>

    <div class="content">
        Je soussigné(e) {{ $data['declarant_prenom'] ?? '' }} {{ $data['declarant_nom'] ?? '' }},@if($dateNaissance) né(e) le {{ $dateNaissance }},@endif demeurant {{ $data['declarant_adresse'] ?? '' }},@if(!empty($data['declarant_telephone'])) joignable au {{ $data['declarant_telephone'] }},@endif
    </div>

    <div class="content">
        <strong>Atteste sur l'honneur</strong> concernant : {{ $data['objet'] ?? '' }}
    </div>

    <div class="content">
        {!! nl2br(e($data['contenu'] ?? '')) !!}
    </div>

    <div class="content">
        Je suis conscient(e) que toute fausse déclaration m'expose à des sanctions pénales.
    </div>

    <div class="content">
        Fait pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        Fait à ___________________, le {{ $dateAujourdhui }}
        <div class="signature-line">
            Signature<br>
            {{ $data['declarant_prenom'] ?? '' }} {{ $data['declarant_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
