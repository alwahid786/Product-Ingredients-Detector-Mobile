<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_img',
        'ingredients',
        'device_id',
        'is_harmful',
    ];

    // Accessor For Get Decrypt Message
    public function getIngredientsAttribute($value)
    {
        return explode(',', $value);
    }
    // Mutator For Set Message as in encryption form
    public function setIngredientsAttribute($value)
    {
        $this->attributes['ingredients'] = implode(',', $value);
    }
}
