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
        {{ $data['destinataire_societe'] ?? '' }}<br>
        @if(!empty($data['destinataire_service']))
            {{ $data['destinataire_service'] }}<br>
        @endif
        {!! nl2br(e($data['destinataire_adresse'] ?? '')) !!}
    </div>
</div>

<div class="date-place">
    {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY') }}
</div>

<div class="subject">
    Objet : {{ $data['objet'] ?? 'Réclamation' }}
</div>

<div class="greeting">
    Madame, Monsieur,
</div>

<div class="content">
    {!! nl2br(e($data['contenu'] ?? '')) !!}
</div>

<div class="content">
    Je vous remercie par avance de bien vouloir prendre en compte ma réclamation et vous prie de m'informer rapidement des suites que vous y donnerez.
</div>

<div class="content">
    Je vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.
</div>

<div class="signature">
    {{ $data['expediteur_nom'] ?? '' }}
</div>
@endsection
