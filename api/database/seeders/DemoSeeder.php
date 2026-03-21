<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\DocumentSequenceModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = UserModel::create([
            'id' => Str::uuid(),
            'first_name' => 'Marie',
            'last_name' => 'Martin',
            'email' => 'demo@doclico.fr',
            'password' => Hash::make('password'),
            'siret' => '82341756800014',
            'address' => "12 rue de la Paix\n75001 Paris",
            'phone' => '+33 6 12 34 56 78',
        ]);

        // ── Clients ───────────────────────────────────────────────────
        $clients = [
            [
                'nom' => 'Acme Corp',
                'adresse' => "1 avenue des Champs-Élysées\n75008 Paris",
                'email' => 'contact@acme-corp.fr',
                'telephone' => '+33 1 40 00 10 00',
                'siret' => '12345678901234',
            ],
            [
                'nom' => 'Studio Pixel',
                'adresse' => "34 rue Oberkampf\n75011 Paris",
                'email' => 'hello@studiopixel.fr',
                'telephone' => '+33 6 78 90 12 34',
                'siret' => null,
            ],
            [
                'nom' => 'Freelab SAS',
                'adresse' => "8 place Bellecour\n69002 Lyon",
                'email' => 'compta@freelab.io',
                'telephone' => null,
                'siret' => null,
            ],
            [
                'nom' => 'BioTech Solutions',
                'adresse' => "2 allée de la Forêt\n67000 Strasbourg",
                'email' => null,
                'telephone' => '+33 3 88 00 55 66',
                'siret' => null,
            ],
            [
                'nom' => 'Lumière Design',
                'adresse' => "15 rue du Faubourg Saint-Antoine\n75011 Paris",
                'email' => 'studio@lumiere-design.fr',
                'telephone' => '+33 6 22 33 44 55',
                'siret' => '98765432109876',
            ],
            [
                'nom' => 'TechVision Paris',
                'adresse' => "42 boulevard Haussmann\n75009 Paris",
                'email' => 'contact@techvision.fr',
                'telephone' => '+33 1 55 66 77 88',
                'siret' => null,
            ],
            [
                'nom' => 'Green Impact',
                'adresse' => "7 rue de la Transition\n44000 Nantes",
                'email' => 'hello@green-impact.org',
                'telephone' => '+33 2 40 11 22 33',
                'siret' => null,
            ],
            [
                'nom' => 'Nexus Consulting',
                'adresse' => "3 allée Jean Jaurès\n31000 Toulouse",
                'email' => 'info@nexus-consulting.fr',
                'telephone' => null,
                'siret' => '56473829101234',
            ],
            [
                'nom' => 'Atelier Créatif',
                'adresse' => "18 cours Julien\n13006 Marseille",
                'email' => 'bonjour@ateliercreativ.fr',
                'telephone' => '+33 4 91 00 11 22',
                'siret' => null,
            ],
            [
                'nom' => 'DataSync SARL',
                'adresse' => "22 avenue de la République\n06000 Nice",
                'email' => 'admin@datasync.io',
                'telephone' => '+33 4 93 44 55 66',
                'siret' => '10293847561023',
            ],
        ];

        $clientsByNom = [];
        foreach ($clients as $clientData) {
            $model = ClientModel::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'nom' => $clientData['nom'],
                'adresse' => $clientData['adresse'],
                'email' => $clientData['email'],
                'telephone' => $clientData['telephone'],
                'siret' => $clientData['siret'] ?? null,
            ]);
            $clientsByNom[$clientData['nom']] = $model;
        }

        // ── Documents ─────────────────────────────────────────────────
        $templates = TemplateModel::all()->keyBy('type');

        $fullName = 'Marie Martin';
        $userInjected = [
            'emetteur_nom' => $fullName,
            'emetteur_adresse' => $user->address,
            'emetteur_siret' => $user->siret,
            'prestataire_nom' => $fullName,
            'prestataire_adresse' => $user->address,
            'prestataire_siret' => $user->siret,
            'expediteur_nom' => $fullName,
            'expediteur_adresse' => $user->address,
            'expediteur_email' => $user->email,
            'expediteur_telephone' => $user->phone,
            'nom' => $fullName,
            'adresse' => $user->address,
            'siret' => $user->siret,
        ];

        $documents = [
            [
                'template' => 'facture',
                'client' => 'Acme Corp',
                'name' => 'Facture Acme Corp - Mars 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Acme Corp',
                    'client_adresse' => "1 avenue des Champs-Élysées\n75008 Paris",
                    'numero_facture' => 'FAC-2026-003',
                    'date_facture' => '2026-03-01',
                    'nature_transaction' => 'Prestation de services',
                    'lignes' => [
                        ['description' => 'Développement interface React', 'quantite' => 8, 'prix_unitaire' => '450'],
                        ['description' => 'Intégration API REST', 'quantite' => 3, 'prix_unitaire' => '450'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'facture',
                'client' => 'Studio Pixel',
                'name' => 'Facture Studio Pixel - Février 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Studio Pixel',
                    'client_adresse' => "34 rue Oberkampf\n75011 Paris",
                    'numero_facture' => 'FAC-2026-002',
                    'date_facture' => '2026-02-03',
                    'nature_transaction' => 'Prestation de services',
                    'lignes' => [
                        ['description' => 'Refonte site vitrine', 'quantite' => 12, 'prix_unitaire' => '400'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'devis',
                'client' => 'Freelab SAS',
                'name' => 'Devis Freelab SAS - Avril 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Freelab SAS',
                    'client_adresse' => "8 place Bellecour\n69002 Lyon",
                    'numero_devis' => 'DEV-2026-004',
                    'date_devis' => '2026-03-15',
                    'validite' => '30 jours',
                    'lignes' => [
                        ['description' => 'Audit technique', 'quantite' => 2, 'prix_unitaire' => '900'],
                        ['description' => 'Rapport et recommandations', 'quantite' => 1, 'prix_unitaire' => '600'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'prestation',
                'client' => 'Acme Corp',
                'name' => 'Contrat Acme Corp - Projet Alpha',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Acme Corp',
                    'client_adresse' => "1 avenue des Champs-Élysées\n75008 Paris",
                    'objet' => 'Développement d\'une application web de gestion',
                    'description' => 'Conception et développement d\'un outil interne de gestion des ressources humaines incluant : module de suivi des congés, tableau de bord RH, export PDF.',
                    'montant' => '12000',
                    'modalites_paiement' => '30 % à la signature, 40 % à la livraison intermédiaire, 30 % à la réception.',
                    'date_debut' => '2026-04-01',
                    'date_fin' => '2026-06-30',
                ]),
            ],
            [
                'template' => 'note_frais',
                'client' => 'Acme Corp',
                'name' => 'Note de frais - Février 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Acme Corp',
                    'client_adresse' => "1 avenue des Champs-Élysées\n75008 Paris",
                    'periode' => 'Février 2026',
                    'lignes' => [
                        ['description' => 'Train Paris – Lyon (aller-retour)', 'montant' => '124.50'],
                        ['description' => 'Hôtel 1 nuit Lyon', 'montant' => '89.00'],
                        ['description' => 'Repas client', 'montant' => '42.00'],
                    ],
                    'commentaire' => 'Déplacement dans le cadre du projet de refonte SI.',
                ]),
            ],
            [
                'template' => 'facture',
                'client' => 'TechVision Paris',
                'name' => 'Facture TechVision Paris - Janvier 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'TechVision Paris',
                    'client_adresse' => "42 boulevard Haussmann\n75009 Paris",
                    'numero_facture' => 'FAC-2026-004',
                    'date_facture' => '2026-01-15',
                    'nature_transaction' => 'Prestation de services',
                    'lignes' => [
                        ['description' => 'Consulting technique', 'quantite' => 5, 'prix_unitaire' => '600'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'facture',
                'client' => 'Green Impact',
                'name' => 'Facture Green Impact - Janvier 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Green Impact',
                    'client_adresse' => "7 rue de la Transition\n44000 Nantes",
                    'numero_facture' => 'FAC-2026-005',
                    'date_facture' => '2026-01-28',
                    'nature_transaction' => 'Mixte (biens et services)',
                    'lignes' => [
                        ['description' => 'Développement site web', 'quantite' => 10, 'prix_unitaire' => '380'],
                        ['description' => 'Hébergement annuel', 'quantite' => 1, 'prix_unitaire' => '240'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'facture',
                'client' => 'Nexus Consulting',
                'name' => 'Facture Nexus Consulting - Janvier 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Nexus Consulting',
                    'client_adresse' => "3 allée Jean Jaurès\n31000 Toulouse",
                    'numero_facture' => 'FAC-2026-001',
                    'date_facture' => '2026-01-10',
                    'nature_transaction' => 'Prestation de services',
                    'lignes' => [
                        ['description' => 'Formation équipe dev (2 jours)', 'quantite' => 2, 'prix_unitaire' => '1200'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'devis',
                'client' => 'Lumière Design',
                'name' => 'Devis Lumière Design - Identité visuelle',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Lumière Design',
                    'client_adresse' => "15 rue du Faubourg Saint-Antoine\n75011 Paris",
                    'numero_devis' => 'DEV-2026-005',
                    'date_devis' => '2026-02-20',
                    'validite' => '30 jours',
                    'lignes' => [
                        ['description' => 'Refonte identité visuelle complète', 'quantite' => 1, 'prix_unitaire' => '2800'],
                        ['description' => 'Charte graphique PDF', 'quantite' => 1, 'prix_unitaire' => '400'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'devis',
                'client' => 'DataSync SARL',
                'name' => 'Devis DataSync - Migration cloud',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'DataSync SARL',
                    'client_adresse' => "22 avenue de la République\n06000 Nice",
                    'numero_devis' => 'DEV-2026-006',
                    'date_devis' => '2026-03-05',
                    'validite' => '15 jours',
                    'lignes' => [
                        ['description' => 'Audit infrastructure existante', 'quantite' => 3, 'prix_unitaire' => '750'],
                        ['description' => 'Migration vers AWS', 'quantite' => 8, 'prix_unitaire' => '600'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'reclamation',
                'client' => 'BioTech Solutions',
                'name' => 'Relance BioTech Solutions - FAC-2026-001',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'BioTech Solutions',
                    'client_adresse' => "2 allée de la Forêt\n67000 Strasbourg",
                    'objet' => 'Relance facture FAC-2026-001 du 10 décembre 2025',
                    'contenu' => "Sauf erreur de ma part, la facture n° FAC-2026-001 d'un montant de 2 400 € HT demeure impayée à ce jour.\n\nJe vous remercie de bien vouloir régulariser cette situation dans les plus brefs délais.",
                ]),
            ],
            [
                'template' => 'prestation',
                'client' => 'Atelier Créatif',
                'name' => 'Contrat Atelier Créatif - Refonte web',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Atelier Créatif',
                    'client_adresse' => "18 cours Julien\n13006 Marseille",
                    'objet' => 'Refonte complète du site e-commerce',
                    'description' => 'Conception UX/UI, développement front-end et back-end, intégration catalogue produits, mise en ligne et formation équipe.',
                    'montant' => '8500',
                    'modalites_paiement' => '40 % à la signature, 60 % à la livraison.',
                    'date_debut' => '2026-05-01',
                    'date_fin' => '2026-07-31',
                ]),
            ],
            [
                'template' => 'note_frais',
                'client' => 'TechVision Paris',
                'name' => 'Note de frais - Mars 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'TechVision Paris',
                    'client_adresse' => "42 boulevard Haussmann\n75009 Paris",
                    'periode' => 'Mars 2026',
                    'lignes' => [
                        ['description' => 'Taxi aéroport CDG', 'montant' => '65.00'],
                        ['description' => 'Repas de travail', 'montant' => '38.50'],
                        ['description' => 'Matériel de bureau', 'montant' => '27.90'],
                    ],
                    'commentaire' => 'Frais engagés lors du déplacement client.',
                ]),
            ],
            [
                'template' => 'avoir',
                'client' => 'Studio Pixel',
                'name' => 'Avoir Studio Pixel - FAC-2026-002',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Studio Pixel',
                    'client_adresse' => "34 rue Oberkampf\n75011 Paris",
                    'numero_avoir' => 'AV-2026-001',
                    'date_avoir' => '2026-02-28',
                    'facture_reference' => 'FAC-2026-002',
                    'motif' => 'Réduction accordée suite à un délai de livraison non respecté.',
                    'montant_ht' => '480',
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'devis',
                'client' => 'Acme Corp',
                'name' => 'Devis Acme Corp - Maintenance annuelle',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Acme Corp',
                    'client_adresse' => "1 avenue des Champs-Élysées\n75008 Paris",
                    'numero_devis' => 'DEV-2026-007',
                    'date_devis' => '2026-03-10',
                    'validite' => '30 jours',
                    'lignes' => [
                        ['description' => 'Contrat de maintenance mensuel', 'quantite' => 12, 'prix_unitaire' => '350'],
                    ],
                    'tva' => '20',
                ]),
            ],
            [
                'template' => 'facture',
                'client' => 'Lumière Design',
                'name' => 'Facture Lumière Design - Février 2026',
                'data' => array_merge($userInjected, [
                    'client_nom' => 'Lumière Design',
                    'client_adresse' => "15 rue du Faubourg Saint-Antoine\n75011 Paris",
                    'numero_facture' => 'FAC-2026-006',
                    'date_facture' => '2026-02-14',
                    'nature_transaction' => 'Prestation de services',
                    'lignes' => [
                        ['description' => 'Maquettes UX (5 écrans)', 'quantite' => 5, 'prix_unitaire' => '320'],
                    ],
                    'tva' => '20',
                ]),
            ],
        ];

        foreach ($documents as $index => $doc) {
            $template = $templates[$doc['template']] ?? null;
            if (! $template) {
                continue;
            }

            $client = isset($doc['client']) ? ($clientsByNom[$doc['client']] ?? null) : null;

            $document = DocumentModel::create([
                'id' => Str::uuid(),
                'template_id' => $template->id,
                'user_id' => $user->id,
                'client_id' => $client?->id,
                'name' => $doc['name'],
                'data' => $doc['data'],
            ]);

            if ($index === 0) {
                ShareModel::create([
                    'id' => Str::uuid(),
                    'document_id' => $document->id,
                    'token' => Str::random(32),
                    'shared_at' => now()->subDays(2),
                    'expires_at' => now()->addDays(7),
                    'downloads_count' => 3,
                    'last_downloaded_at' => now()->subHours(2),
                    'views_count' => 5,
                    'first_viewed_at' => now()->subDay(),
                ]);
            }
        }

        // ── Séquences de numérotation ─────────────────────────────────
        $sequences = [
            ['type' => 'facture', 'last_number' => 6],
            ['type' => 'devis', 'last_number' => 7],
            ['type' => 'avoir', 'last_number' => 1],
        ];

        foreach ($sequences as $seq) {
            DocumentSequenceModel::create([
                'user_id' => $user->id,
                'type' => $seq['type'],
                'year' => 2026,
                'last_number' => $seq['last_number'],
            ]);
        }
    }
}
