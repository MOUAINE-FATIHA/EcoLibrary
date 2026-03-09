<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Categorie",
 *     required={"nom"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nom", type="string", example="Roman"),
 *     @OA\Property(property="description", type="string", example="Livres de fiction narrative"),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'description',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function livres()
    {
        return $this->hasMany(Livre::class, 'categorie_id');
    }
}