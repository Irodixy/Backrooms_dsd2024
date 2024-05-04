<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'purchase';  // Especificando o nome da tabela
    
    protected $fillable = [
        'IDUser', 
        'IDItem', 
        'didLike', 
        'date'
    ];

    // Relação com User
    public function user() {
        return $this->belongsTo(User::class, 'IDUser', 'id');
    }

    // Relação com Item
    public function item() {
        return $this->belongsTo(Item::class, 'IDItem', 'id');
    }
}
