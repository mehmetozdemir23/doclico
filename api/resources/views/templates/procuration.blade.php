<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; line-height: 1.6; color: #000; padding: 2cm; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 1.5cm; text-transform: uppercase; }
        .content { text-align: justify; margin-bottom: 0.5cm; }
        .person { margin-left: 1cm; margin-bottom: 0.5cm; }
        .signature-block { margin-top: 2cm; text-align: right; }
        .signature-line { margin-top: 1cm; font-style: italic; }
    </style>
</head>
<body>
    @php
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
        $dateValidite = !empty($data['date_validite'])
            ? \Carbon\Carbon::parse($data['date_validite'])->locale('fr')->isoFormat('D MMMM YYYY')
            : null;
    @endphp

    <div class="title">Procuration</div>

    <div class="content">
        Je soussigné(e) :
    </div>

    <div class="person">
        <strong>{{ $data['mandant_nom'] ?? '' }}</strong> (le mandant)
    </div>

    <div class="content">
        donne pouvoir à :
    </div>

    <div class="person">
        <strong>{{ $data['mandataire_nom'] ?? '' }}</strong> (le mandataire)
    </div>

    <div class="content">
        pour effectuer en mon nom et pour mon compte l'opération suivante :
    </div>

    <div class="content" style="font-weight: bold; margin-left: 1cm;">
        {{ $data['objet'] ?? '' }}
    </div>

    @if($dateValidite)
    <div class="content" style="margin-top: 0.8cm;">
        Cette procuration est valable jusqu'au {{ $dateValidite }}.
    </div>
    @endif

    <div class="content" style="margin-top: 0.8cm;">
        Fait pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        <div>Fait le {{ $dateAujourdhui }}</div>
        <div class="signature-line">
            Signature du mandant<br>
            {{ $data['mandant_nom'] ?? '' }}
        </div>
    </div>
</body>
</html>
