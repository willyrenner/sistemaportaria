<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastrarVisitante extends Model
{
    use HasFactory;

    protected $table = 'visitantes';

    protected $fillable = [
        'nome',
        'cpf',
        'tipo',
        'motivo',
        'saida',
        'funcionario_id',
        'porteiro_id',
    ];


    protected $casts = [
        'saida' => 'datetime',
    ];

    // Relacionamento com o modelo Funcionario
    public function funcionario()
    {
        return $this->belongsTo(User::class, 'funcionario_id');
    }

}
