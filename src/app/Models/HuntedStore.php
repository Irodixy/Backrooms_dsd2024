<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuntedStore extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'huntedstore';  // Especificando o nome da tabela

    protected $fillable = [
        'IDUser', 
        'IDStore', 
        'date_time'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'IDUser', 'id');
    }

    public function store() {
        return $this->belongsTo(Store::class, 'IDStore', 'id');
    }
}
