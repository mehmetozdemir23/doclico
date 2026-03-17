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

        /* ── Section ── */
        .section { margin-bottom: 0.8cm; }
        .section-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.15cm; }
        .section-value { font-size: 9.5pt; color: #374151; }

        /* ── Divider ── */
        .divider { border: none; border-top: 1px solid #f3f4f6; margin: 0.6cm 0; }

        /* ── Montant ── */
        .montant-block { display: table; width: 100%; margin-top: 0.8cm; }
        .montant-inner { display: table-cell; text-align: right; }
        .montant-row { font-size: 9pt; color: #6b7280; margin-bottom: 0.1cm; }
        .montant-total { font-size: 13pt; font-weight: bold; color: #111827; border-top: 2px solid #111827; padding-top: 0.2cm; margin-top: 0.15cm; }

        /* ── Signature ── */
        .signature-block { margin-top: 1.8cm; display: table; width: 100%; }
        .sig-left, .sig-right { display: table-cell; width: 50%; vertical-align: top; font-size: 9pt; color: #374151; }
        .sig-right { text-align: right; }
        .sig-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.15cm; }

        /* ── Footer ── */
        .footer { margin-top: 1.5cm; font-size: 7.5pt; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 0.4cm; line-height: 1.7; }
    </style>
</head>
<body>
    @php
        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
        $dateFin   = !empty($data['date_fin']) ? \Carbon\Carbon::parse($data['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY') : null;
        $montantHT = floatval($data['montant'] ?? 0);
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
            <div class="doc-title">Contrat de<br>prestation</div>
            <div class="doc-meta">
                {{ $dateDebut }}
                @if($dateFin)<br>au {{ $dateFin }}@endif
            </div>
        </div>
    </div>

    <div class="client-block">
        <div class="client-tag">Entre les parties</div>
        <div class="client-name">{{ $data['client_nom'] ?? '' }}</div>
        <div class="client-address">{!! nl2br(e($data['client_adresse'] ?? '')) !!}</div>
    </div>

    <hr class="divider">

    <div class="section">
        <div class="section-label">Objet de la prestation</div>
        <div class="section-value"><strong>{{ $data['objet'] ?? '' }}</strong></div>
    </div>

    <div class="section">
        <div class="section-label">Description</div>
        <div class="section-value">{!! nl2br(e($data['description'] ?? '')) !!}</div>
    </div>

    <div class="section">
        <div class="section-label">Période d'exécution</div>
        <div class="section-value">
            Du {{ $dateDebut }}{{ $dateFin ? ' au ' . $dateFin : '' }}
        </div>
    </div>

    @if(!empty($data['modalites_paiement']))
    <div class="section">
        <div class="section-label">Modalités de paiement</div>
        <div class="section-value">{!! nl2br(e($data['modalites_paiement'])) !!}</div>
    </div>
    @endif

    <div class="montant-block">
        <div class="montant-inner">
            <div class="montant-row">Montant HT</div>
            <div class="montant-total">{{ number_format($montantHT, 2, ',', ' ') }} €</div>
            <div style="font-size:7.5pt;color:#9ca3af;margin-top:0.1cm">TVA non applicable, art. 293 B du CGI</div>
        </div>
    </div>

    <div class="signature-block">
        <div class="sig-left">
            <div class="sig-label">Le prestataire</div>
            {{ $data['emetteur_nom'] ?? '' }}<br>
            <br><br><br>
            Signature :
        </div>
        <div class="sig-right">
            <div class="sig-label">Le client</div>
            {{ $data['client_nom'] ?? '' }}<br>
            <br><br><br>
            Signature :
        </div>
    </div>

    <div class="footer">
        @if(!empty($data['mentions_legales'])){{ $data['mentions_legales'] }}@endif
    </div>
</body>
</html>
