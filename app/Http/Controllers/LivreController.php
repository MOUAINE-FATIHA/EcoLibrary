<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Category;
use App\Http\Requests\StoreLivreRequest;
use App\Http\Resources\LivreResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Livres", description="Gestion des livres de la bibliothèque")
 */
class LivreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/livres",
     *     tags={"Livres"},
     *     summary="Lister tous les livres",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="categorie_id", in="query", description="Filtrer par catégorie", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="disponible", in="query", description="Uniquement les disponibles", @OA\Schema(type="boolean")),
     *     @OA\Response(response=200, description="Liste des livres")
     * )
     */
    public function index(Request $request){
        $query = Livre::with('categorie');

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->boolean('disponible')) {
            $query->disponible();
        }

        $livres = $query->orderBy('titre')->paginate(15);
        return LivreResource::collection($livres);
    }

    /**
     * @OA\Post(
     *     path="/api/livres",
     *     tags={"Livres"},
     *     summary="Ajouter un livre (admin)",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Livre")),
     *     @OA\Response(response=201, description="Livre créé"),
     *     @OA\Response(response=403, description="Accès refusé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(StoreLivreRequest $request)
    {
        $livre = Livre::create($request->validated());
        $livre->load('categorie');
        return new LivreResource($livre);
    }

    /**
     * @OA\Get(
     *     path="/api/livres/{id}",
     *     tags={"Livres"},
     *     summary="Afficher un livre",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail du livre"),
     *     @OA\Response(response=404, description="Livre introuvable")
     * )
     */
    public function show(Livre $livre){
        //l'incrementation du cmp de consultations
        $livre->increment('nb_consultations');
        $livre->load('categorie');
        return new LivreResource($livre);
    }

    /**
     * @OA\Put(
     *     path="/api/livres/{id}",
     *     tags={"Livres"},
     *     summary="Modifier un livre (admin)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Livre")),
     *     @OA\Response(response=200, description="Livre mis à jour"),
     *     @OA\Response(response=403, description="Accès refusé"),
     *     @OA\Response(response=404, description="Livre introuvable")
     * )
     */
    public function update(StoreLivreRequest $request, Livre $livre)
    {
        $livre->update($request->validated());
        $livre->load('categorie');
        return new LivreResource($livre);
    }

    /**
     * @OA\Delete(
     *     path="/api/livres/{id}",
     *     tags={"Livres"},
     *     summary="Supprimer un livre (admin)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Livre supprimé"),
     *     @OA\Response(response=403, description="Accès refusé"),
     *     @OA\Response(response=404, description="Livre introuvable")
     * )
     */
    public function destroy(Livre $livre)
    {
        $livre->delete();
        return response()->json(['message' => 'Livre supprimé avec succès']);
    }

    /**
     * @OA\Get(
     *     path="/api/livres/recherche",
     *     tags={"Livres"},
     *     summary="Rechercher un livre par titre ou catégorie",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="q", in="query", required=true, description="Mot clé", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Résultats de recherche")
     * )
     */
    public function recherche(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2']);
        $terme = $request->q;

        $livres = Livre::with('categorie')
            ->where('titre', 'ilike', "%{$terme}%")
            ->orWhere('auteur', 'ilike', "%{$terme}%")
            ->orWhereHas('categorie', fn($q) => $q->where('nom', 'ilike', "%{$terme}%"))
            ->paginate(15);

        return LivreResource::collection($livres);
    }
    /**
     * @OA\Get(
     *     path="/api/livres/populaires",
     *     tags={"Livres"},
     *     summary="Livres les plus consultés",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="limite", in="query", description="Nombre de résultats", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Livres populaires")
     * )
     */
    public function populaires (Request $request){
        $limite  = $request->integer('limite', 10);
        $livres=Livre::with('categorie')->populaire($limite)->get();
        return LivreResource::collection($livres);
    }
    /**
     * @OA\Get(
     *     path="/api/livres/recents",
     *     tags={"Livres"},
     *     summary="Nouveaux arrivages",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="jours", in="query", description="Nombre de jours", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Livres récents")
     * )
     */
    public function recents(Request $request){
        $jours= $request->integer('jours',30);
        $livres= Livre::with('categorie')->recent($jours)->get();
        return LivreResource::collection($livres);
    }
}