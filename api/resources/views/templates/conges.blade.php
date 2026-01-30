@extends('layouts.letter')

@section('content')
@php
    
    $typeCongesTexte = match($data['type_conges'] ?? '') {
        'payes' => 'congés payés',
        'sans_solde' => 'congé sans solde',
        'maladie' => 'congé maladie',
        'maternite' => 'congé maternité',
        'paternite' => 'congé paternité',
        'parental' => 'congé parental',
        default => 'congés'
    };

    $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');
    $dateFin = \Carbon\Carbon::parse($data['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY');
    $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
@endphp

<div class="header">
    <div class="sender">
        {{ $data['expediteur_nom'] ?? '' }}<br>
        {!! nl2br(e($data['expediteur_adresse'] ?? '')) !!}<br>
        @if(!empty($data['expediteur_email']))
            {{ $data['expediteur_email'] }}<br>
        @endif
        @if(!empty($data['expediteur_telephone']))
            {{ $data['expediteur_telephone'] }}<br>
        @endif
    </div>

    <div class="recipient">
        {{ $data['destinataire_societe'] ?? '' }}<br>
        @if(!empty($data['destinataire_service']))
            {{ $data['destinataire_service'] }}<br>
        @endif
        {!! nl2br(e($data['destinataire_adresse'] ?? '')) !!}
    </div>
</div>

<div class="date-place">
    {{ $dateAujourdhui }}
</div>

<div class="subject">
    Objet : Demande de {{ $typeCongesTexte }}
</div>

<div class="greeting">
    Madame, Monsieur,
</div>

<div class="content">
    Par la présente, je souhaite vous faire part de ma demande de {{ $typeCongesTexte }} du {{ $dateDebut }} au {{ $dateFin }}.
</div>

@if(!empty($data['motif']))
    <div class="content">
        {{ $data['motif'] }}
    </div>
@endif

<div class="content">
    Je vous remercie de bien vouloir examiner ma demande et reste à votre disposition pour tout renseignement complémentaire.
</div>

<div class="content">
    Je vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.
</div>

<div class="signature">
    {{ $data['expediteur_nom'] ?? '' }}
</div>
@endsection
