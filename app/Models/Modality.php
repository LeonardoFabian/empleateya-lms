<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modality extends Model
{
    // Guarded: do not allow massive income
    protected $guarded = ['id'];

    use HasFactory;

    const E_LEARNING = 'Aprendizaje Online';
    const B_LEARNING = 'Aprendizaje Semipresencial';
    const M_LEARNING = 'Aprendizaje Móvil';
    const PRESENCIAL = 'Presencial';

    /**
     * Relation 1:N
     */
    public function course(){
        return $this->hasMany('App\Models\Course');
    }
}
