<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'item';  // Especificando o nome da tabela

    protected $fillable = [
        'name', 
        'price', 
        'description', 
        'type',
        'quantity', 
        'imgName', 
        'IDStore', 
        'costumerVisits'
    ];

    // Relações
    public function store()
    {
        return $this->belongsTo(Store::class, 'IDStore', 'id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'IDItem', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'IDItem', 'id');
    }
}
