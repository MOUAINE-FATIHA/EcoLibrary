<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Catégories", description="Gestion des catégories de livres")
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Catégories"},
     *     summary="Lister toutes les catégories",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Categorie"))
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::withCount('livres')->orderBy('nom')->get();
        return CategoryResource::collection($categories);
    }



    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Catégories"},
     *     summary="Créer une catégorie (admin)",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Categorie")
     *     ),
     *     @OA\Response(response=201, description="Catégorie créée"),
     *     @OA\Response(response=403, description="Accès refusé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Catégories"},
     *     summary="Afficher une catégorie avec ses livres",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de la catégorie"),
     *     @OA\Response(response=404, description="Catégorie introuvable")
     * )
     */
    public function show(Category $category)
    {
        $category->load('livres');
        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Catégories"},
     *     summary="Modifier une catégorie (admin)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Categorie")),
     *     @OA\Response(response=200, description="Catégorie mise à jour"),
     *     @OA\Response(response=403, description="Accès refusé"),
     *     @OA\Response(response=404, description="Catégorie introuvable")
     * )
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Catégories"},
     *     summary="Supprimer une catégorie (admin)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Catégorie supprimée"),
     *     @OA\Response(response=403, description="Accès refusé"),
     *     @OA\Response(response=404, description="Catégorie introuvable")
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message'=> 'Catégorie étais supprimée']);
    }
}