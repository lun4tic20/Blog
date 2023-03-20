<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable=[
        'tag',
    ];

    function pios(){
        return $this->belongsToMany(Pio::class, 'post_has_tags', 'tag_id', 'pio_id');
    }
}
