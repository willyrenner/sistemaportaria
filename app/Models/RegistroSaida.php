<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroSaida extends Model
{
    use HasFactory;

    protected $table = 'registros_saidas';

    protected $fillable = [
        'aluno_id',
        'funcionario_id',
        'porteiro_id',
        'tipo',
        'motivo',
        'permissao',
        'saida',
        'solicitacao',
        'observacao_responsavel',
    ];

    protected $casts = [
        'saida' => 'datetime',
        'solicitacao' => 'datetime',
    ];

    // Relacionamento com o modelo Aluno
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    // Relacionamento com o modelo Funcionario
    public function funcionario()
    {
        return $this->belongsTo(User::class, 'funcionario_id');
    }



}
