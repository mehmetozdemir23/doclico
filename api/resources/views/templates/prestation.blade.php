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
        
        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin = !empty($data['date_fin'])
            ? \Carbon\Carbon::parse($data['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY')
            : null;
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp
    <div class="title">
        Contrat de prestation de services
    </div>

    <div class="section">
        <div class="section-title">Entre les soussignés :</div>
        <div class="content">
            Le prestataire : {{ $data['prestataire_nom'] ?? '' }}, {!! nl2br(e($data['prestataire_adresse'] ?? '')) !!}
            @if(!empty($data['prestataire_siret']))
                - SIRET : {{ $data['prestataire_siret'] }}
            @endif
        </div>
        <div class="content">
            Et le client : {{ $data['client_nom'] ?? '' }}, {!! nl2br(e($data['client_adresse'] ?? '')) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Objet de la prestation :</div>
        <div class="content">
            {{ $data['objet'] ?? '' }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Description :</div>
        <div class="content">
            {!! nl2br(e($data['description'] ?? '')) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Conditions financières :</div>
        <div class="content">
            Montant de la prestation : {{ $data['montant'] ?? '' }} euros HT
        </div>
        @if(!empty($data['modalites_paiement']))
            <div class="content">
                Modalités de paiement : {!! nl2br(e($data['modalites_paiement'] ?? '')) !!}
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Durée :</div>
        <div class="content">
            La prestation débutera le {{ $dateDebut }}@if($dateFin) et devra être achevée au plus tard le {{ $dateFin }}@endif.
        </div>
    </div>

    <div class="content">
        Le prestataire s'engage à fournir ses services avec tout le soin et la diligence requis.
    </div>

    <div class="signature-block">
        <div class="signature-left">
            Fait à ___________________<br>
            Le {{ $dateAujourdhui }}<br><br>
            Le prestataire<br>
            {{ $data['prestataire_nom'] ?? '' }}
        </div>
        <div class="signature-right">
            <br><br><br>
            Le client<br>
            {{ $data['client_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
