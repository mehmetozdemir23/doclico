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

        .header {
            margin-bottom: 1cm;
            display: table;
            width: 100%;
        }

        .header-left, .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .header-right {
            text-align: right;
        }

        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 1cm;
            text-transform: uppercase;
        }

        .devis-info {
            margin-bottom: 1cm;
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
            margin-bottom: 0.4cm;
        }

        .total-box {
            margin-top: 1cm;
            text-align: right;
            font-size: 12pt;
        }

        .total-line {
            margin-bottom: 0.3cm;
        }

        .total-ttc {
            font-weight: bold;
            font-size: 14pt;
        }
    </style>
</head>
<body>
    @php
        
        $dateDevis = \Carbon\Carbon::parse($data['date_devis'])->locale('fr')->isoFormat('D MMMM YYYY');

        
        $montantHT = floatval($data['montant_ht'] ?? 0);
        $tauxTVA = floatval($data['tva'] ?? 0);
        $montantTVA = $montantHT * ($tauxTVA / 100);
        $montantTTC = $montantHT + $montantTVA;
    @endphp

    <div class="header">
        <div class="header-left">
            <strong>{{ $data['emetteur_nom'] ?? '' }}</strong><br>
            {!! nl2br(e($data['emetteur_adresse'] ?? '')) !!}<br>
            @if(!empty($data['emetteur_siret']))
                SIRET : {{ $data['emetteur_siret'] }}
            @endif
        </div>
        <div class="header-right">
            <strong>Client :</strong><br>
            {{ $data['client_nom'] ?? '' }}<br>
            {!! nl2br(e($data['client_adresse'] ?? '')) !!}
        </div>
    </div>

    <div class="title">
        Devis
    </div>

    <div class="devis-info">
        <strong>Devis N° : </strong>{{ $data['numero_devis'] ?? '' }}<br>
        <strong>Date : </strong>{{ $dateDevis }}<br>
        @if(!empty($data['validite']))
            <strong>Validité : </strong>{{ $data['validite'] }}
        @endif
    </div>

    <div class="section">
        <div class="section-title">Désignation :</div>
        <div class="content">
            {!! nl2br(e($data['description'] ?? '')) !!}
        </div>
    </div>

    <div class="total-box">
        <div class="total-line">
            <strong>Montant HT : </strong>{{ $data['montant_ht'] ?? '' }} €
        </div>
        @if(!empty($data['tva']))
            <div class="total-line">
                <strong>TVA ({{ $data['tva'] }}%) : </strong>{{ number_format($montantTVA, 2, ',', ' ') }} €
            </div>
            <div class="total-line total-ttc">
                <strong>Montant TTC : </strong>{{ number_format($montantTTC, 2, ',', ' ') }} €
            </div>
        @else
            <div class="total-line total-ttc">
                <strong>Montant Total : </strong>{{ $data['montant_ht'] ?? '' }} €
            </div>
        @endif
    </div>

    <div class="content" style="margin-top: 2cm; font-size: 9pt;">
        Ce devis est valable
        @if(!empty($data['validite']))
            {{ $data['validite'] }}
        @else
            30 jours
        @endif
        à compter de sa date d'émission.<br>
        Signature du devis requise pour acceptation et démarrage des travaux.
    </div>

    <div style="margin-top: 2cm; text-align: right;">
        <strong>Bon pour accord</strong><br>
        Date et signature du client :
    </div>
</body>
</html>
