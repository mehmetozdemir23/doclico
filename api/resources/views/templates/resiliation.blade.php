@extends('layouts.letter')

@section('content')
@php
    
    $motifTexte = null;
    if (!empty($data['motif'])) {
        $motifTexte = match($data['motif']) {
            'demenagement' => 'un déménagement',
            'insatisfaction' => 'une insatisfaction du service',
            'prix' => 'un prix trop élevé',
            'concurrent' => 'une offre concurrente plus avantageuse',
            default => $data['motif_detail'] ?? 'des raisons personnelles'
        };
    }

    $dateResiliation = !empty($data['date_resiliation'])
        ? \Carbon\Carbon::parse($data['date_resiliation'])->locale('fr')->isoFormat('D MMMM YYYY')
        : null;

    $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
@endphp

<div class="header">
    <div class="sender">
        {{ $data['expediteur_nom'] ?? '' }}<br>
        {!! nl2br(e($data['expediteur_adresse'] ?? '')) !!}<br>
        @if (!empty($data['expediteur_email']))
            {{ $data['expediteur_email'] }}<br>
        @endif
        @if (!empty($data['expediteur_telephone']))
            {{ $data['expediteur_telephone'] }}<br>
        @endif
    </div>

    <div class="recipient">
        {{ $data['destinataire_societe'] ?? '' }}<br>
        @if (!empty($data['destinataire_service']))
            {{ $data['destinataire_service'] }}<br>
        @endif
        {!! nl2br(e($data['destinataire_adresse'] ?? '')) !!}
    </div>
</div>

<div class="date-place">
    {{ $dateResiliation ?? $dateAujourdhui }}
</div>

<div class="subject">
    Objet : Demande de résiliation
    @if (!empty($data['contrat_numero']))
        - Contrat n° {{ $data['contrat_numero'] }}
    @endif
</div>

<div class="greeting">
    Madame, Monsieur,
</div>

<div class="content">
    Par la présente, je vous informe de ma décision de résilier mon contrat
    @if (!empty($data['contrat_numero']))
        portant le numéro {{ $data['contrat_numero'] }}
    @endif
    souscrit auprès de votre société.
</div>

@if ($motifTexte)
    <div class="content">
        Cette résiliation fait suite à {{ $motifTexte }}.
    </div>
@endif

@if (!empty($data['motif_detail']) && $data['motif'] !== 'autre')
    <div class="content">
        {{ $data['motif_detail'] }}
    </div>
@endif

<div class="content">
    Je vous prie de bien vouloir prendre en compte cette demande et de me confirmer la résiliation effective de mon contrat{{ $dateResiliation ? " à compter du $dateResiliation" : '' }}.
</div>

<div class="content">
    Je vous remercie par avance de votre compréhension et vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.
</div>

<div class="signature">
    {{ $data['expediteur_nom'] ?? '' }}
</div>
@endsection
