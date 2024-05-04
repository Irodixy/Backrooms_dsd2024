<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'location';  // Especificando o nome da tabela

    protected $fillable = [
        'latitude', 
        'longitude', 
        'country', 
        'state', 
        'city', 
        'street', 
        'number', 
        'floor', 
        'zipcode'
    ];

    // Relações
    public function stores()
    {
        return $this->hasMany(Store::class, 'IDLocation', 'id');
    }
}
