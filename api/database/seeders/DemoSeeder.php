<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\DocumentModel;
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
        ]);

        $templates = TemplateModel::all()->keyBy('type');

        $documents = [
            [
                'template' => 'quittance_loyer',
                'name' => 'Quittance Janvier 2026',
                'data' => [
                    'bailleur_nom' => 'Jean Dupont',
                    'locataire_nom' => 'Marie Martin',
                    'adresse' => '45 rue des Lilas, 75011 Paris',
                    'periode' => 'Janvier 2026',
                    'loyer' => '950',
                    'charges' => '80',
                ],
            ],
            [
                'template' => 'quittance_loyer',
                'name' => 'Quittance Décembre 2025',
                'data' => [
                    'bailleur_nom' => 'Jean Dupont',
                    'locataire_nom' => 'Marie Martin',
                    'adresse' => '45 rue des Lilas, 75011 Paris',
                    'periode' => 'Décembre 2025',
                    'loyer' => '950',
                    'charges' => '80',
                ],
            ],
            [
                'template' => 'attestation_hebergement',
                'name' => 'Attestation hébergement - Paul',
                'data' => [
                    'hebergeur_nom' => 'Marie Martin',
                    'heberge_nom' => 'Paul Martin',
                    'adresse' => '45 rue des Lilas, 75011 Paris',
                    'date_debut' => '2025-09-01',
                ],
            ],
            [
                'template' => 'attestation_employeur',
                'name' => 'Attestation TechCorp',
                'data' => [
                    'entreprise' => 'TechCorp SAS',
                    'employe_nom' => 'Marie Martin',
                    'poste' => 'Développeuse Full Stack',
                    'date_embauche' => '2023-03-15',
                    'type_contrat' => 'cdi',
                    'salaire' => '4200',
                ],
            ],
            [
                'template' => 'attestation_honneur',
                'name' => 'Attestation domicile',
                'data' => [
                    'nom' => 'Marie Martin',
                    'adresse' => '45 rue des Lilas, 75011 Paris',
                    'objet' => 'résider à l\'adresse mentionnée ci-dessus depuis le 1er janvier 2024',
                ],
            ],
            [
                'template' => 'autorisation_parentale',
                'name' => 'Autorisation sortie scolaire',
                'data' => [
                    'parent_nom' => 'Marie Martin',
                    'enfant_nom' => 'Lucas Martin',
                    'enfant_date_naissance' => '2018-05-12',
                    'activite' => 'Sortie scolaire au Musée d\'Orsay le 15 février 2026',
                ],
            ],
        ];

        foreach ($documents as $index => $doc) {
            $template = $templates[$doc['template']] ?? null;
            if (! $template) {
                continue;
            }

            $document = DocumentModel::create([
                'id' => Str::uuid(),
                'template_id' => $template->id,
                'user_id' => $user->id,
                'name' => $doc['name'],
                'data' => $doc['data'],
            ]);

            if ($index === 0) {
                ShareModel::create([
                    'id' => Str::uuid(),
                    'document_id' => $document->id,
                    'token' => Str::random(32),
                    'expires_at' => now()->addDays(7),
                    'downloads_count' => 3,
                    'last_downloaded_at' => now()->subHours(2),
                ]);
            }
        }
    }
}
