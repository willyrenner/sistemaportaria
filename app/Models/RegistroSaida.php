<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroSaida extends Model
{
    use HasFactory;
    
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
    
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
    
}
