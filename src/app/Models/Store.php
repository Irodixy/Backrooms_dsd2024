<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'store';  // Especificando o nome da tabela

    protected $fillable = [
        'name', 
        'type', 
        'IDLocation', 
        'description'
    ];

    // Relações
    public function location()
    {
        return $this->belongsTo(Location::class, 'IDLocation', 'id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'IDStore', 'id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'IDStore', 'id');
    }

    public function huntedStores()
    {
        return $this->hasMany(HuntedStore::class, 'IDStore', 'id');
    }
}
