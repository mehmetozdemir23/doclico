<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            [
                'type' => 'quittance_loyer',
                'name' => 'Quittance de loyer',
                'category' => 'Logement',
                'icon' => 'Home',
                'popular' => true,
                'fields' => [
                    [
                        'name' => 'bailleur_nom',
                        'label' => 'Bailleur',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Jean Dupont',
                    ],
                    [
                        'name' => 'locataire_nom',
                        'label' => 'Locataire',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Martin',
                    ],
                    [
                        'name' => 'adresse',
                        'label' => 'Adresse du bien',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => '12 rue de la Paix, 75001 Paris',
                    ],
                    [
                        'name' => 'periode',
                        'label' => 'Période',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Janvier 2026',
                    ],
                    [
                        'name' => 'loyer',
                        'label' => 'Loyer (€)',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => '850',
                    ],
                    [
                        'name' => 'charges',
                        'label' => 'Charges (€)',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '50',
                    ],
                ],
            ],
            [
                'type' => 'attestation_hebergement',
                'name' => 'Attestation d\'hébergement',
                'category' => 'Logement',
                'icon' => 'Home',
                'popular' => true,
                'fields' => [
                    [
                        'name' => 'hebergeur_nom',
                        'label' => 'Hébergeur',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Jean Dupont',
                    ],
                    [
                        'name' => 'heberge_nom',
                        'label' => 'Personne hébergée',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Martin',
                    ],
                    [
                        'name' => 'adresse',
                        'label' => 'Adresse',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => '12 rue de la Paix, 75001 Paris',
                    ],
                    [
                        'name' => 'date_debut',
                        'label' => 'Hébergé depuis le',
                        'type' => 'date',
                        'required' => true,
                    ],
                ],
            ],
            [
                'type' => 'resiliation_bail',
                'name' => 'Résiliation de bail',
                'category' => 'Logement',
                'icon' => 'Home',
                'popular' => false,
                'fields' => [
                    [
                        'name' => 'locataire_nom',
                        'label' => 'Locataire',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Martin',
                    ],
                    [
                        'name' => 'adresse',
                        'label' => 'Adresse du logement',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => '12 rue de la Paix, 75001 Paris',
                    ],
                    [
                        'name' => 'bailleur_nom',
                        'label' => 'Bailleur',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Jean Dupont',
                    ],
                    [
                        'name' => 'date_depart',
                        'label' => 'Date de départ souhaitée',
                        'type' => 'date',
                        'required' => true,
                    ],
                ],
            ],

            [
                'type' => 'attestation_employeur',
                'name' => 'Attestation employeur',
                'category' => 'Emploi',
                'icon' => 'Briefcase',
                'popular' => true,
                'fields' => [
                    [
                        'name' => 'entreprise',
                        'label' => 'Entreprise',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Acme Corp',
                    ],
                    [
                        'name' => 'employe_nom',
                        'label' => 'Employé',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Martin',
                    ],
                    [
                        'name' => 'poste',
                        'label' => 'Poste',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Développeur',
                    ],
                    [
                        'name' => 'date_embauche',
                        'label' => 'En poste depuis',
                        'type' => 'date',
                        'required' => true,
                    ],
                    [
                        'name' => 'type_contrat',
                        'label' => 'Contrat',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'cdi', 'label' => 'CDI'],
                            ['value' => 'cdd', 'label' => 'CDD'],
                            ['value' => 'alternance', 'label' => 'Alternance'],
                        ],
                    ],
                    [
                        'name' => 'salaire',
                        'label' => 'Salaire brut mensuel (€)',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '3000',
                    ],
                ],
            ],
            [
                'type' => 'certificat_travail',
                'name' => 'Certificat de travail',
                'category' => 'Emploi',
                'icon' => 'Briefcase',
                'popular' => true,
                'fields' => [
                    [
                        'name' => 'entreprise',
                        'label' => 'Entreprise',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Acme Corp',
                    ],
                    [
                        'name' => 'employe_nom',
                        'label' => 'Employé',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Martin',
                    ],
                    [
                        'name' => 'poste',
                        'label' => 'Poste occupé',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Développeur',
                    ],
                    [
                        'name' => 'date_debut',
                        'label' => 'Du',
                        'type' => 'date',
                        'required' => true,
                    ],
                    [
                        'name' => 'date_fin',
                        'label' => 'Au',
                        'type' => 'date',
                        'required' => true,
                    ],
                ],
            ],

            [
                'type' => 'attestation_honneur',
                'name' => 'Attestation sur l\'honneur',
                'category' => 'Administratif',
                'icon' => 'FileText',
                'popular' => true,
                'fields' => [
                    [
                        'name' => 'nom',
                        'label' => 'Nom complet',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Jean Dupont',
                    ],
                    [
                        'name' => 'adresse',
                        'label' => 'Adresse',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => '12 rue de la Paix, 75001 Paris',
                    ],
                    [
                        'name' => 'objet',
                        'label' => 'J\'atteste sur l\'honneur',
                        'type' => 'textarea',
                        'required' => true,
                        'placeholder' => 'ne pas percevoir de revenus...',
                    ],
                ],
            ],
            [
                'type' => 'procuration',
                'name' => 'Procuration',
                'category' => 'Administratif',
                'icon' => 'FileText',
                'popular' => false,
                'fields' => [
                    [
                        'name' => 'mandant_nom',
                        'label' => 'Mandant (vous)',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Jean Dupont',
                    ],
                    [
                        'name' => 'mandataire_nom',
                        'label' => 'Mandataire',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Martin',
                    ],
                    [
                        'name' => 'objet',
                        'label' => 'Objet',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Retrait de colis',
                    ],
                    [
                        'name' => 'date_validite',
                        'label' => 'Valable jusqu\'au',
                        'type' => 'date',
                        'required' => false,
                    ],
                ],
            ],

            [
                'type' => 'autorisation_parentale',
                'name' => 'Autorisation parentale',
                'category' => 'Famille',
                'icon' => 'Users',
                'popular' => true,
                'fields' => [
                    [
                        'name' => 'parent_nom',
                        'label' => 'Parent',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Jean Dupont',
                    ],
                    [
                        'name' => 'enfant_nom',
                        'label' => 'Enfant',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Marie Dupont',
                    ],
                    [
                        'name' => 'enfant_date_naissance',
                        'label' => 'Né(e) le',
                        'type' => 'date',
                        'required' => true,
                    ],
                    [
                        'name' => 'activite',
                        'label' => 'Activité autorisée',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Sortie scolaire au musée',
                    ],
                ],
            ],
        ];

        foreach ($templates as $template) {
            TemplateModel::create($template);
        }
    }
}
