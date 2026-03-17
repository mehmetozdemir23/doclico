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
        .client-block { margin-bottom: 1cm; }
        .client-tag { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.12cm; }
        .client-name { font-weight: bold; font-size: 10pt; color: #111827; margin-bottom: 0.04cm; }
        .client-address { font-size: 9pt; color: #374151; }

        /* ── Objet ── */
        .objet { font-size: 9.5pt; color: #111827; margin-bottom: 0.8cm; }
        .objet strong { font-weight: bold; }

        /* ── Corps ── */
        .corps { font-size: 9.5pt; color: #374151; line-height: 1.75; white-space: pre-wrap; }

        /* ── Signature ── */
        .signature-block { margin-top: 1.8cm; font-size: 9pt; color: #374151; }
        .sig-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.3cm; }

        /* ── Footer ── */
        .footer { margin-top: 1.5cm; font-size: 7.5pt; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 0.4cm; line-height: 1.7; }
    </style>
</head>
<body>
    @php
        $today = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="header">
        <div class="header-left">
            @if(!empty($data['logo']))<img style="max-height:1.4cm;max-width:4.5cm;display:block;margin-bottom:0.15cm" src="{{ $data['logo'] }}" alt="">@endif
            <div class="company-name">{{ $data['emetteur_nom'] ?? '' }}</div>
            <div class="header-meta">
                {!! nl2br(e($data['emetteur_adresse'] ?? '')) !!}
                @if(!empty($data['emetteur_siret']))<br>SIRET : {{ $data['emetteur_siret'] }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">Lettre de<br>relance</div>
            <div class="doc-meta">{{ $today }}</div>
        </div>
    </div>

    <div class="client-block">
        <div class="client-tag">Destinataire</div>
        <div class="client-name">{{ $data['client_nom'] ?? '' }}</div>
        <div class="client-address">{!! nl2br(e($data['client_adresse'] ?? '')) !!}</div>
    </div>

    <div class="objet"><strong>Objet :</strong> {{ $data['objet'] ?? '' }}</div>

    <div class="corps">{{ $data['contenu'] ?? '' }}</div>

    <div class="signature-block">
        <div class="sig-label">Cordialement</div>
        {{ $data['emetteur_nom'] ?? '' }}
    </div>

    <div class="footer">
        @if(!empty($data['mentions_legales'])){{ $data['mentions_legales'] }}@endif
    </div>
</body>
</html>
