@extends('layouts.letter')

@section('content')
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
        {{ $data['entreprise_nom'] ?? '' }}<br>
        {!! nl2br(e($data['entreprise_adresse'] ?? '')) !!}
    </div>
</div>

<div class="date-place">
    {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY') }}
</div>

<div class="subject">
    Objet : Candidature au poste de {{ $data['poste'] ?? '' }}
</div>

<div class="greeting">
    Madame, Monsieur,
</div>

<div class="content">
    {!! nl2br(e($data['contenu'] ?? '')) !!}
</div>

<div class="content">
    Je reste à votre disposition pour un entretien à votre convenance afin de vous exposer plus en détail mes motivations et mes compétences.
</div>

<div class="content">
    Dans l'attente d'une réponse de votre part, je vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.
</div>

<div class="signature">
    {{ $data['expediteur_nom'] ?? '' }}
</div>
@endsection
