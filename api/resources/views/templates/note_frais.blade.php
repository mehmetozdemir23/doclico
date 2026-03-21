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

        /* ── Signature ── */
        .signature-block { margin-top: 2cm; display: table; width: 100%; }
        .sig-left, .sig-right { display: table-cell; width: 50%; vertical-align: top; font-size: 9pt; color: #374151; }
        .sig-right { text-align: right; }
        .sig-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.12cm; }
    </style>
</head>
<body>
    @php
        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
        $lignes = $data['lignes'] ?? [];
        $total = 0;
        foreach ($lignes as $l) {
            $total += floatval($l['montant'] ?? 0);
        }
    @endphp

    <div class="header">
        <div class="header-left">
            @if(!empty($data['logo']))<img style="max-height:1.4cm;max-width:4.5cm;display:block;margin-bottom:0.15cm" src="{{ $data['logo'] }}" alt="">@endif
            <div class="company-name">{{ $data['nom'] ?? '' }}</div>
            <div class="header-meta">
                {!! nl2br(e($data['adresse'] ?? '')) !!}
                @if(!empty($data['siret']))<br>SIRET : {{ $data['siret'] }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">Note de frais</div>
            <div class="doc-meta">
                {{ $dateAujourdhui }}
                @if(!empty($data['periode']))<br>Période : {{ $data['periode'] }}@endif
            </div>
        </div>
    </div>

    @if(!empty($data['client_nom']))
    <div style="margin-bottom:0.9cm">
        <div style="font-size:7.5pt;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:0.12cm">Pour le compte de</div>
        <div style="font-weight:bold;font-size:10pt;color:#111827;margin-bottom:0.04cm">{{ $data['client_nom'] }}</div>
        <div style="font-size:9pt;color:#374151">{!! nl2br(e($data['client_adresse'] ?? '')) !!}</div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width:80%">Description</th>
                <th class="right" style="width:20%">Montant (€)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $ligne)
                <tr>
                    <td>{{ $ligne['description'] ?? '' }}</td>
                    <td class="right">{{ number_format(floatval($ligne['montant'] ?? 0), 2, ',', ' ') }} €</td>
                </tr>
            @empty
                <tr><td colspan="2" style="text-align:center;color:#9ca3af;padding:0.4cm">Aucune ligne</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr class="row-total">
            <td>Total</td>
            <td>{{ number_format($total, 2, ',', ' ') }} €</td>
        </tr>
    </table>

    <div class="signature-block">
        <div class="sig-left">
            @if(!empty($data['commentaire']))
                <div class="sig-label">Commentaire</div>
                {!! nl2br(e($data['commentaire'])) !!}
            @endif
        </div>
        <div class="sig-right">
            <div class="sig-label">Signature</div>
            {{ $data['nom'] ?? '' }}<br>
            {{ $dateAujourdhui }}
        </div>
    </div>
</body>
</html>
