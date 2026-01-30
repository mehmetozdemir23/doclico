<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 2cm; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 1.5cm; text-transform: uppercase; }
        .content { text-align: justify; margin-bottom: 0.5cm; }
        .amount-box { background: #f5f5f5; padding: 0.5cm; margin: 0.8cm 0; }
        .amount-line { display: flex; justify-content: space-between; margin-bottom: 0.2cm; }
        .total { font-weight: bold; border-top: 1px solid #000; padding-top: 0.3cm; margin-top: 0.3cm; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
        $loyer = floatval($data['loyer'] ?? 0);
        $charges = floatval($data['charges'] ?? 0);
        $total = $loyer + $charges;
    @endphp

    <div class="title">Quittance de loyer</div>

    <div class="content">
        <strong>Bailleur :</strong> {{ $data['bailleur_nom'] ?? '' }}
    </div>

    <div class="content">
        <strong>Locataire :</strong> {{ $data['locataire_nom'] ?? '' }}
    </div>

    <div class="content">
        <strong>Bien loué :</strong> {{ $data['adresse'] ?? '' }}
    </div>

    <div class="content">
        <strong>Période :</strong> {{ $data['periode'] ?? '' }}
    </div>

    <div class="content" style="margin-top: 0.8cm;">
        Je soussigné(e) {{ $data['bailleur_nom'] ?? '' }}, propriétaire du logement désigné ci-dessus, déclare avoir reçu de {{ $data['locataire_nom'] ?? '' }} la somme détaillée ci-après, en paiement du loyer et des charges pour la période indiquée.
    </div>

    <div class="amount-box">
        <div class="amount-line">
            <span>Loyer</span>
            <span>{{ number_format($loyer, 2, ',', ' ') }} €</span>
        </div>
        @if($charges > 0)
        <div class="amount-line">
            <span>Charges</span>
            <span>{{ number_format($charges, 2, ',', ' ') }} €</span>
        </div>
        @endif
        <div class="amount-line total">
            <span>Total</span>
            <span>{{ number_format($total, 2, ',', ' ') }} €</span>
        </div>
    </div>

    <div class="content">
        Cette quittance annule tous les reçus qui auraient pu être établis précédemment pour la même période.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature du bailleur<br>
            {{ $data['bailleur_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
