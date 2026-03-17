<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5pt; line-height: 1.6; color: #111827; padding: 1.8cm 2cm; }

        /* ── Header ── */
        .header { display: table; width: 100%; padding-bottom: 0.85cm; border-bottom: 1px solid #e5e7eb; margin-bottom: 0.85cm; }
        .header-left, .header-right { display: table-cell; vertical-align: top; }
        .header-left { width: 55%; }
        .header-right { width: 45%; text-align: right; }
        .company-name { font-size: 12pt; font-weight: bold; color: #111827; margin-bottom: 0.1cm; }
        .header-meta { font-size: 8.5pt; color: #6b7280; line-height: 1.55; }
        .doc-title { font-size: 22pt; font-weight: bold; color: #111827; margin-bottom: 0.2cm; line-height: 1; }
        .doc-subtitle { font-size: 9pt; color: #6b7280; margin-bottom: 0.15cm; }
        .doc-meta { font-size: 9pt; color: #374151; line-height: 1.75; }

        /* ── Client ── */
        .client-block { margin-bottom: 1cm; }
        .client-tag { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.12cm; }
        .client-name { font-weight: bold; font-size: 10pt; color: #111827; margin-bottom: 0.04cm; }
        .client-address { font-size: 9pt; color: #374151; }

        /* ── Motif ── */
        .motif-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.15cm; }
        .motif-content { font-size: 9.5pt; color: #374151; margin-bottom: 1cm; }

        /* ── Totals ── */
        table.totals { width: 44%; margin-left: auto; border-collapse: collapse; margin-top: 0; margin-bottom: 0; }
        table.totals td { padding: 0.14cm 0.3cm; border: none; font-size: 9pt; color: #6b7280; }
        table.totals td:last-child { text-align: right; }
        table.totals .row-total td { font-size: 12pt; font-weight: bold; color: #111827; border-top: 2px solid #111827; padding-top: 0.22cm; padding-bottom: 0.1cm; }

        /* ── Notice ── */
        .notice { margin-top: 1.5cm; font-size: 7.5pt; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 0.4cm; line-height: 1.7; }
    </style>
</head>
<body>
    @php
        $dateAvoir = \Carbon\Carbon::parse($data['date_avoir'])->locale('fr')->isoFormat('D MMMM YYYY');
        $montantHT  = floatval($data['montant_ht'] ?? 0);
        $tauxTVA    = floatval($data['tva'] ?? 0);
        $montantTVA = $montantHT * ($tauxTVA / 100);
        $montantTTC = $montantHT + $montantTVA;
    @endphp

    <div class="header">
        <div class="header-left">
            @if(!empty($data['logo']))<img style="max-height:1.4cm;max-width:4.5cm;display:block;margin-bottom:0.15cm" src="{{ $data['logo'] }}" alt="">@endif
            <div class="company-name">{{ $data['emetteur_nom'] ?? '' }}</div>
            <div class="header-meta">
                {!! nl2br(e($data['emetteur_adresse'] ?? '')) !!}
                @if(!empty($data['emetteur_siret']))<br>SIRET : {{ $data['emetteur_siret'] }}@endif
                @if(!empty($data['emetteur_tva']))<br>N° TVA : {{ $data['emetteur_tva'] }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">Avoir</div>
            @if(!empty($data['facture_reference']))
            <div class="doc-subtitle">Réf. facture n° {{ $data['facture_reference'] }}</div>
            @endif
            <div class="doc-meta">
                N° {{ $data['numero_avoir'] ?? '' }}<br>
                {{ $dateAvoir }}
            </div>
        </div>
    </div>

    <div class="client-block">
        <div class="client-tag">Établi pour</div>
        <div class="client-name">{{ $data['client_nom'] ?? '' }}</div>
        <div class="client-address">{!! nl2br(e($data['client_adresse'] ?? '')) !!}</div>
    </div>

    @if(!empty($data['motif']))
    <div style="margin-bottom:1cm">
        <div class="motif-label">Motif</div>
        <div class="motif-content">{!! nl2br(e($data['motif'])) !!}</div>
    </div>
    @endif

    <table class="totals">
        <tr>
            <td>Montant crédité HT</td>
            <td>{{ number_format($montantHT, 2, ',', ' ') }} €</td>
        </tr>
        @if($tauxTVA > 0)
        <tr>
            <td>TVA ({{ $tauxTVA }}%)</td>
            <td>{{ number_format($montantTVA, 2, ',', ' ') }} €</td>
        </tr>
        <tr class="row-total">
            <td>Total crédité TTC</td>
            <td>{{ number_format($montantTTC, 2, ',', ' ') }} €</td>
        </tr>
        @else
        <tr class="row-total">
            <td>Montant crédité</td>
            <td>{{ number_format($montantHT, 2, ',', ' ') }} €</td>
        </tr>
        @endif
    </table>

    <div class="notice">
        @if($tauxTVA == 0)TVA non applicable, art. 293 B du CGI.<br>@endif
        Ce document annule et remplace, en tout ou partie, la facture référencée. Le montant crédité sera déduit de votre prochaine facture ou remboursé selon accord.
        @if(!empty($data['mentions_legales']))<br><br>{{ $data['mentions_legales'] }}@endif
    </div>
</body>
</html>
