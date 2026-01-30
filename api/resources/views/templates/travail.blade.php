<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 15px;
            text-align: justify;
        }
        .signature-block {
            margin-top: 50px;
        }
        .signature-line {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    @php
        
        $typeContratTexte = match($data['type_contrat'] ?? '') {
            'cdi' => 'Contrat à Durée Indéterminée (CDI)',
            'cdd' => 'Contrat à Durée Déterminée (CDD)',
            'stage' => 'Convention de stage',
            'alternance' => "Contrat d'alternance",
            'interim' => "Contrat d'intérim",
            default => $data['type_contrat'] ?? ''
        };

        $dateDebut = \Carbon\Carbon::parse($data['date_debut'])->locale('fr')->isoFormat('D MMMM YYYY');

        $dateFin = !empty($data['date_fin'])
            ? \Carbon\Carbon::parse($data['date_fin'])->locale('fr')->isoFormat('D MMMM YYYY')
            : null;

        $dateAujourdhui = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp

    <div class="header">
        <div style="font-weight: bold; font-size: 16px;">
            {{ $data['entreprise_nom'] ?? '' }}
        </div>
        <div>{{ $data['entreprise_adresse'] ?? '' }}</div>
        @if (!empty($data['entreprise_siret']))
            <div>SIRET : {{ $data['entreprise_siret'] }}</div>
        @endif
    </div>

    <div class="title">
        ATTESTATION D'EMPLOI
    </div>

    <div class="content">
        Je soussigné(e) {{ $data['responsable_nom'] ?? '' }}, {{ $data['responsable_fonction'] ?? '' }} de la société {{ $data['entreprise_nom'] ?? '' }}, atteste que {{ $data['employe_prenom'] ?? '' }} {{ $data['employe_nom'] ?? '' }} a été employé(e) au sein de notre entreprise.
    </div>

    <div class="content">
        Poste occupé : {{ $data['employe_poste'] ?? '' }}
    </div>

    <div class="content">
        Type de contrat : {{ $typeContratTexte }}
    </div>

    <div class="content">
        Période d'emploi : du {{ $dateDebut }}{{ $dateFin ? " au $dateFin" : ' à ce jour' }}.
    </div>

    <div class="content">
        Cette attestation est délivrée pour servir et valoir ce que de droit.
    </div>

    <div class="signature-block">
        Fait à ___________________, le {{ $dateAujourdhui }}
        <div class="signature-line">
            {{ $data['responsable_nom'] ?? '' }}<br>
            {{ $data['responsable_fonction'] ?? '' }}
        </div>
    </div>
</body>
</html>
