<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Livre",
 *     required={"categorie_id","titre","auteur"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="categorie_id", type="integer", example=2),
 *     @OA\Property(property="titre", type="string", example="Le Petit Prince"),
 *     @OA\Property(property="auteur", type="string", example="Antoine de Saint-Exupéry"),
 *     @OA\Property(property="isbn", type="string", example="978-2-07-040850-4"),
 *     @OA\Property(property="description", type="string", example="Un conte poétique et philosophique"),
 *     @OA\Property(property="total_exemplaires", type="integer", example=5),
 *     @OA\Property(property="exemplaires_dispo", type="integer", example=3),
 *     @OA\Property(property="exemplaires_degrades", type="integer", example=1),
 *     @OA\Property(property="nb_consultations", type="integer", example=42),
 *     @OA\Property(property="date_publication", type="string", format="date", example="1943-04-06"),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 */
class Livre extends Model
{
    use HasFactory;
    protected $table = 'livres';
    protected $fillable = [
        'categorie_id',
        'titre',
        'auteur',
        'isbn',
        'description',
        'total_exemplaires',
        'exemplaires_dispo',
        'exemplaires_degrades',
        'nb_consultations',
        'date_publication',
    ];

    protected $casts = [
        'date_publication' => 'date',
        'total_exemplaires'=> 'integer',
        'exemplaires_dispo' => 'integer',
        'exemplaires_degrades'=> 'integer',
        'nb_consultations' => 'integer',
    ];
    public function categorie(){
        return $this->belongsTo(Category::class,'categorie_id');
    }


    /** Livres ayant au moins 1 exemplaire disponible */
    public function scopeDisponible($query){
        return $query->where('exemplaires_dispo','>', 0);
    }

    /** Livres les plus consultés */
    public function scopePopulaire($query, $limite = 10){
        return $query->orderByDesc('nb_consultations')->limit($limite);
    }

    /** Nouveaux arrivages (30 derniers jours par défaut) */
    public function scopeRecent($query, $jours = 30){
        return $query->where('created_at','>=', now()->subDays($jours))->orderByDesc('created_at');
    }

    /** Livres ayant des exemplaires dégradés */
    public function scopeDegrades($query){
        return $query->where('exemplaires_degrades','>', 0)->orderByDesc('exemplaires_degrades');
    }
}