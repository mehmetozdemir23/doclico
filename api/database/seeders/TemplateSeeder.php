<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            // ── Facturation ──────────────────────────────────────────────

            [
                'type' => 'facture',
                'name' => 'Facture',
                'category' => 'Facturation',
                'icon' => 'Receipt',
                'popular' => true,
                'fields' => [
                    ['name' => 'date_facture',   'label' => 'Date',              'type' => 'date',       'required' => true],
                    ['name' => 'lignes',         'label' => 'Prestations',       'type' => 'line_items', 'required' => true],
                    ['name' => 'nature_transaction', 'label' => 'Nature de la transaction', 'type' => 'select', 'required' => true, 'options' => [
                        ['value' => 'Prestation de services',    'label' => 'Prestation de services'],
                        ['value' => 'Vente de biens',            'label' => 'Vente de biens'],
                        ['value' => 'Mixte (biens et services)', 'label' => 'Mixte (biens et services)'],
                    ]],
                    ['name' => 'tva',            'label' => 'TVA (%)',           'type' => 'text',       'required' => false, 'placeholder' => '20'],
                ],
            ],

            [
                'type' => 'devis',
                'name' => 'Devis',
                'category' => 'Facturation',
                'icon' => 'FileText',
                'popular' => true,
                'fields' => [
                    ['name' => 'date_devis',     'label' => 'Date',              'type' => 'date',       'required' => true],
                    ['name' => 'validite',       'label' => 'Validité',          'type' => 'text',       'required' => false, 'placeholder' => '30 jours'],
                    ['name' => 'lignes',         'label' => 'Prestations',       'type' => 'line_items', 'required' => true],
                    ['name' => 'tva',            'label' => 'TVA (%)',           'type' => 'text',       'required' => false, 'placeholder' => '20'],
                ],
            ],

            [
                'type' => 'avoir',
                'name' => 'Avoir',
                'category' => 'Facturation',
                'icon' => 'RotateCcw',
                'popular' => false,
                'fields' => [
                    ['name' => 'date_avoir',        'label' => 'Date',                   'type' => 'date',     'required' => true],
                    ['name' => 'facture_reference', 'label' => 'Facture de référence',   'type' => 'text',     'required' => false, 'placeholder' => 'FAC-2026-001'],
                    ['name' => 'motif',             'label' => 'Motif du crédit',        'type' => 'textarea', 'required' => true,  'placeholder' => 'Annulation partielle de la prestation suite à un accord client.'],
                    ['name' => 'montant_ht',        'label' => 'Montant crédité HT (€)', 'type' => 'text',     'required' => true,  'placeholder' => '500'],
                    ['name' => 'tva',               'label' => 'TVA (%)',                'type' => 'text',     'required' => false, 'placeholder' => '20'],
                ],
            ],

            // ── Contrats ─────────────────────────────────────────────────

            [
                'type' => 'prestation',
                'name' => 'Contrat de prestation',
                'category' => 'Contrats',
                'icon' => 'FilePen',
                'popular' => true,
                'fields' => [
                    ['name' => 'objet',              'label' => 'Objet de la prestation',  'type' => 'text',     'required' => true,  'placeholder' => "Développement d'une application mobile"],
                    ['name' => 'description',        'label' => 'Description détaillée',   'type' => 'textarea', 'required' => true,  'placeholder' => 'Détail des missions et livrables...'],
                    ['name' => 'montant',            'label' => 'Montant HT (€)',          'type' => 'text',     'required' => true,  'placeholder' => '5000'],
                    ['name' => 'modalites_paiement', 'label' => 'Modalités de paiement',   'type' => 'textarea', 'required' => false, 'placeholder' => '50% à la signature, 50% à la livraison'],
                    ['name' => 'date_debut',         'label' => 'Date de début',           'type' => 'date',     'required' => true],
                    ['name' => 'date_fin',           'label' => 'Date de fin',             'type' => 'date',     'required' => false],
                ],
            ],

            // ── Courrier ─────────────────────────────────────────────────

            [
                'type' => 'reclamation',
                'name' => 'Lettre de relance',
                'category' => 'Courrier',
                'icon' => 'Mail',
                'popular' => false,
                'fields' => [
                    ['name' => 'objet',                'label' => 'Objet',                   'type' => 'text',     'required' => true,  'placeholder' => 'Relance facture FAC-2026-001 du 15 janvier 2026'],
                    ['name' => 'contenu',              'label' => 'Corps du courrier',        'type' => 'textarea', 'required' => true,  'placeholder' => "Sauf erreur de ma part, la facture n° FAC-2026-001 d'un montant de 2 000 € HT demeure impayée à ce jour..."],
                ],
            ],

            // ── Administratif ────────────────────────────────────────────

            [
                'type' => 'note_frais',
                'name' => 'Note de frais',
                'category' => 'Administratif',
                'icon' => 'Wallet',
                'popular' => false,
                'fields' => [
                    ['name' => 'periode',      'label' => 'Période',               'type' => 'text',          'required' => false, 'placeholder' => 'Mars 2026'],
                    ['name' => 'lignes',       'label' => 'Frais',                 'type' => 'expense_items', 'required' => true],
                    ['name' => 'commentaire',  'label' => 'Commentaire',           'type' => 'textarea',      'required' => false, 'placeholder' => 'Déplacement dans le cadre du projet X...'],
                ],
            ],

        ];

        foreach ($templates as $template) {
            TemplateModel::create($template);
        }
    }
}
