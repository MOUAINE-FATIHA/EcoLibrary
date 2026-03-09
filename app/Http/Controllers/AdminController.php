<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Category;
use App\Models\User;
use App\Http\Resources\LivreResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Administration", description="Statistiques et rapports (admin uniquement)")
 */
class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/stats",
     *     tags={"Administration"},
     *     summary="Statistiques globales de la bibliothèque",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_livres", type="integer", example=150),
     *             @OA\Property(property="total_categories", type="integer", example=10),
     *             @OA\Property(property="total_exemplaires", type="integer", example=450),
     *             @OA\Property(property="exemplaires_dispo", type="integer", example=380),
     *             @OA\Property(property="exemplaires_degrades", type="integer", example=25),
     *             @OA\Property(property="taux_disponibilite", type="string", example="84.44%"),
     *             @OA\Property(property="livres_populaires", type="array", @OA\Items(ref="#/components/schemas/Livre")),
     *             @OA\Property(property="stats_par_categorie", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=403, description="Accès refusé")
     * )
     */
    public function stats()
    {
        $totalExemplaires  = Livre::sum('total_exemplaires');
        $totalDispo = Livre::sum('exemplaires_dispo');
        $totalDegrades = Livre::sum('exemplaires_degrades');
        $tauxDispo = $totalExemplaires > 0
            ? round(($totalDispo / $totalExemplaires) * 100, 2) . '%'
            : '0%';

        $statsParCategorie = Category::withCount('livres')
            ->withSum('livres', 'total_exemplaires')
            ->withSum('livres', 'exemplaires_dispo')
            ->withSum('livres', 'exemplaires_degrades')
            ->orderBy('nom')
            ->get()
            ->map(fn($c) => [
                'categorie'=> $c->nom,
                'nb_livres' => $c->livres_count,
                'total_exemplaires'=> $c->livres_sum_total_exemplaires ?? 0,
                'exemplaires_dispo'=> $c->livres_sum_exemplaires_dispo ?? 0,
                'exemplaires_degrades'=> $c->livres_sum_exemplaires_degrades ?? 0,
            ]);

        return response()->json([
            'total_livres'=> Livre::count(),
            'total_categories'=> Category::count(),
            'total_exemplaires' => $totalExemplaires,
            'exemplaires_dispo'=> $totalDispo,
            'exemplaires_degrades'=> $totalDegrades,
            'taux_disponibilite'=> $tauxDispo,
            'livres_populaires'=> LivreResource::collection(
                Livre::with('categorie')->populaire(5)->get()
            ),
            'stats_par_categorie'=> $statsParCategorie,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/livres/degrades",
     *     tags={"Administration"},
     *     summary="Rapport des livres dégradés",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Livres avec exemplaires dégradés, triés du plus dégradé au moins",
     *     ),
     *     @OA\Response(response=403, description="Accès refusé")
     * )
     */
    public function degrades()
    {
        $livres = Livre::with('categorie')
            ->degrades()
            ->paginate(20);

        return LivreResource::collection($livres);
    }
}