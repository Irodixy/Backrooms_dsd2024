<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'feedback';  // Especificando o nome da tabela
    
    protected $fillable = [
        'IDUser', 
        'IDStore', 
        'IDItem', 
        'comment', 
        'rating', 
        'date'
    ];

    // Defina relações com User, Store, e Item se necessário
    public function user() {
        return $this->belongsTo(User::class, 'IDUser', 'id');
    }

    public function store() {
        return $this->belongsTo(Store::class, 'IDStore', 'id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'IDItem', 'id');
    }
}
