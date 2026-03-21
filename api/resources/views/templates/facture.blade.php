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
        .doc-meta { font-size: 9pt; color: #374151; line-height: 1.75; }

        /* ── Client ── */
        .client-block { margin-bottom: 0.9cm; }
        .client-tag { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.12cm; }
        .client-name { font-weight: bold; font-size: 10pt; color: #111827; margin-bottom: 0.04cm; }
        .client-address { font-size: 9pt; color: #374151; }

        /* ── Objet ── */
        .doc-nature { margin-bottom: 0.8cm; font-size: 9pt; color: #374151; }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 0.5cm; font-size: 9pt; }
        thead tr { background: #111827; }
        th { color: #e5e7eb; font-weight: bold; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.04em; padding: 0.22cm 0.3cm; text-align: left; border: none; }
        th.right, td.right { text-align: right; white-space: nowrap; }
        td { padding: 0.22cm 0.3cm; border: none; border-bottom: 1px solid #f3f4f6; vertical-align: top; color: #374151; }
        tbody tr:last-child td { border-bottom: none; }

        /* ── Totals ── */
        table.totals { width: 44%; margin-left: auto; border-collapse: collapse; margin-top: 0.3cm; margin-bottom: 0; }
        table.totals td { padding: 0.12cm 0.3cm; border: none; font-size: 9pt; color: #6b7280; }
        table.totals td:last-child { text-align: right; }
        table.totals .row-total td { font-size: 11pt; font-weight: bold; color: #111827; border-top: 2px solid #111827; padding-top: 0.22cm; padding-bottom: 0.1cm; }

        /* ── Footer ── */
        .footer { margin-top: 1.5cm; font-size: 7.5pt; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 0.4cm; line-height: 1.7; }
    </style>
</head>
<body>
    @php
        $dateFacture = \Carbon\Carbon::parse($data['date_facture'])->locale('fr')->isoFormat('D MMMM YYYY');
        $lignes = $data['lignes'] ?? [];
        $totalHT = 0;
        foreach ($lignes as $l) {
            $totalHT += floatval($l['quantite'] ?? 0) * floatval($l['prix_unitaire'] ?? 0);
        }
        $tauxTVA    = floatval($data['tva'] ?? 0);
        $montantTVA = $totalHT * ($tauxTVA / 100);
        $montantTTC = $totalHT + $montantTVA;
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
            <div class="doc-title">Facture</div>
            <div class="doc-meta">
                N° {{ $data['numero_facture'] ?? '' }}<br>
                {{ $dateFacture }}
            </div>
        </div>
    </div>

    <div class="client-block">
        <div class="client-tag">Facturé à</div>
        <div class="client-name">{{ $data['client_nom'] ?? '' }}</div>
        <div class="client-address">{!! nl2br(e($data['client_adresse'] ?? '')) !!}</div>
    </div>

    @if(!empty($data['nature_transaction']))
    <div class="doc-nature"><strong>Objet :</strong> {{ $data['nature_transaction'] }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width:42%">Désignation</th>
                <th class="right" style="width:10%">Qté</th>
                <th class="right" style="width:24%">Prix unitaire HT</th>
                <th class="right" style="width:24%">Sous-total HT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $ligne)
                @php $sous_total = floatval($ligne['quantite'] ?? 0) * floatval($ligne['prix_unitaire'] ?? 0); @endphp
                <tr>
                    <td>{{ $ligne['description'] ?? '' }}</td>
                    <td class="right">{{ $ligne['quantite'] ?? '' }}</td>
                    <td class="right">{{ number_format(floatval($ligne['prix_unitaire'] ?? 0), 2, ',', ' ') }} €</td>
                    <td class="right">{{ number_format($sous_total, 2, ',', ' ') }} €</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:0.4cm">Aucune ligne</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sous-total HT</td>
            <td>{{ number_format($totalHT, 2, ',', ' ') }} €</td>
        </tr>
        @if($tauxTVA > 0)
        <tr>
            <td>TVA ({{ $tauxTVA }}%)</td>
            <td>{{ number_format($montantTVA, 2, ',', ' ') }} €</td>
        </tr>
        <tr class="row-total">
            <td>Total TTC</td>
            <td>{{ number_format($montantTTC, 2, ',', ' ') }} €</td>
        </tr>
        @else
        <tr class="row-total">
            <td>Total HT</td>
            <td>{{ number_format($totalHT, 2, ',', ' ') }} €</td>
        </tr>
        @endif
    </table>

    <div class="footer">
        @if($tauxTVA == 0)TVA non applicable, art. 293 B du CGI.<br>@endif
        Conditions de paiement : à réception de facture. Pas d'escompte pour paiement anticipé. En cas de retard, une pénalité égale à 3× le taux d'intérêt légal sera appliquée, ainsi qu'une indemnité forfaitaire de 40 €.
        @if(!empty($data['mentions_legales']))<br><br>{{ $data['mentions_legales'] }}@endif
    </div>
</body>
</html>
