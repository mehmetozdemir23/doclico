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
        .info-box { background: #f5f5f5; padding: 0.5cm; margin: 0.8cm 0; }
        .info-line { margin-bottom: 0.2cm; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateEmbauche = \Carbon\Carbon::parse($data['date_embauche'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
        $contrats = ['cdi' => 'CDI', 'cdd' => 'CDD', 'alternance' => 'Alternance'];
        $typeContrat = $contrats[$data['type_contrat'] ?? ''] ?? $data['type_contrat'] ?? '';
    @endphp

    <div class="header">
        {{ $data['entreprise'] ?? '' }}
    </div>

    <div class="title">Attestation employeur</div>

    <div class="content">
        Je soussigné(e), représentant de l'entreprise {{ $data['entreprise'] ?? '' }}, atteste que :
    </div>

    <div class="info-box">
        <div class="info-line"><strong>Nom :</strong> {{ $data['employe_nom'] ?? '' }}</div>
        <div class="info-line"><strong>Poste :</strong> {{ $data['poste'] ?? '' }}</div>
        <div class="info-line"><strong>Type de contrat :</strong> {{ $typeContrat }}</div>
        <div class="info-line"><strong>En poste depuis :</strong> {{ $dateEmbauche }}</div>
        @if(!empty($data['salaire']))
        <div class="info-line"><strong>Salaire brut mensuel :</strong> {{ number_format(floatval($data['salaire']), 2, ',', ' ') }} €</div>
        @endif
    </div>

    <div class="content">
        Cette attestation est délivrée pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature et cachet de l'entreprise
        </div>
    </div>
</body>
</html>
