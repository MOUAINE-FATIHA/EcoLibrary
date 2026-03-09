<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="EcoLibrary API",
 *     description="API de gestion d'une bibliothèque — catégories, livres, disponibilité et état de la collection.",
 *     @OA\Contact(email="admin@ecolibrary.com"),
 *     @OA\License(name="MIT")
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur local de développement"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Authentification via Laravel Sanctum. Format : Bearer {votre_token}"
 * )
 */
abstract class Controller
{
}