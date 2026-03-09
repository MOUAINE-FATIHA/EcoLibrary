<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Livre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Utilisateurs ────────────────────────────────────────
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@ecolibrary.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Lecteur Test',
            'email'    => 'lecteur@ecolibrary.com',
            'password' => Hash::make('lecteur123'),
            'role'     => 'lecteur',
        ]);

        // ── Catégories ───────────────────────────────────────────
        $categories = [
            ['nom' => 'Roman',       'description' => 'Fiction narrative longue forme'],
            ['nom' => 'Science',     'description' => 'Ouvrages scientifiques et techniques'],
            ['nom' => 'Histoire',    'description' => 'Livres historiques et biographies'],
            ['nom' => 'Philosophie', 'description' => 'Philosophie et pensée critique'],
            ['nom' => 'Jeunesse',    'description' => 'Livres pour enfants et adolescents'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ── Livres ────────────────────────────────────────────────
        $livres = [
            [
                'categorie_id'         => 1,
                'titre'                => 'Le Petit Prince',
                'auteur'               => 'Antoine de Saint-Exupéry',
                'isbn'                 => '978-2-07-040850-4',
                'description'          => 'Un conte poétique et philosophique',
                'total_exemplaires'    => 5,
                'exemplaires_dispo'    => 3,
                'exemplaires_degrades' => 1,
                'nb_consultations'     => 124,
                'date_publication'     => '1943-04-06',
            ],
            [
                'categorie_id'         => 1,
                'titre'                => 'Les Misérables',
                'auteur'               => 'Victor Hugo',
                'isbn'                 => '978-2-07-040816-0',
                'description'          => 'Roman historique et social',
                'total_exemplaires'    => 4,
                'exemplaires_dispo'    => 4,
                'exemplaires_degrades' => 0,
                'nb_consultations'     => 98,
                'date_publication'     => '1862-01-01',
            ],
            [
                'categorie_id'         => 2,
                'titre'                => 'Une brève histoire du temps',
                'auteur'               => 'Stephen Hawking',
                'isbn'                 => '978-2-08-081225-2',
                'description'          => 'Introduction à la cosmologie moderne',
                'total_exemplaires'    => 3,
                'exemplaires_dispo'    => 2,
                'exemplaires_degrades' => 2,
                'nb_consultations'     => 75,
                'date_publication'     => '1988-04-01',
            ],
            [
                'categorie_id'         => 3,
                'titre'                => 'Sapiens',
                'auteur'               => 'Yuval Noah Harari',
                'isbn'                 => '978-2-226-25701-7',
                'description'          => 'Une brève histoire de l\'humanité',
                'total_exemplaires'    => 6,
                'exemplaires_dispo'    => 5,
                'exemplaires_degrades' => 0,
                'nb_consultations'     => 210,
                'date_publication'     => '2011-01-01',
            ],
            [
                'categorie_id'         => 4,
                'titre'                => 'Le Contrat social',
                'auteur'               => 'Jean-Jacques Rousseau',
                'isbn'                 => '978-2-07-036284-8',
                'description'          => 'Traité de philosophie politique',
                'total_exemplaires'    => 2,
                'exemplaires_dispo'    => 1,
                'exemplaires_degrades' => 1,
                'nb_consultations'     => 45,
                'date_publication'     => '1762-01-01',
            ],
            [
                'categorie_id'         => 5,
                'titre'                => 'Harry Potter à l\'école des sorciers',
                'auteur'               => 'J.K. Rowling',
                'isbn'                 => '978-2-07-054602-2',
                'description'          => 'Premier tome de la saga Harry Potter',
                'total_exemplaires'    => 8,
                'exemplaires_dispo'    => 6,
                'exemplaires_degrades' => 3,
                'nb_consultations'     => 315,
                'date_publication'     => '1997-06-26',
            ],
        ];

        foreach ($livres as $livre) {
            Livre::create($livre);
        }
    }
}