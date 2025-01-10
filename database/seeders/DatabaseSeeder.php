<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('cursos')->insert([
            ['curso' => 'Informática para Internet', 'created_at' => now(), 'updated_at' => now()],
            ['curso' => 'Eletrotécnica', 'created_at' => now(), 'updated_at' => now()],
            ['curso' => 'Téxtil', 'created_at' => now(), 'updated_at' => now()],
            ['curso' => 'Vestuário', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('responsaveis')->insert([
            ['nome' => 'João Henrique', 'telefone' => '84998051012', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Pedro Morais', 'telefone' => '84994061012', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Jonas Alencar', 'telefone' => '84995071219', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'André Luiz', 'telefone' => '84993142128', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('alunos')->insert([
            ['matricula' => '20211101110030', 'nome' => 'Moisés Henrique', 'email' => 'moises.h@escolar.ifrn.edu.br', 'telefone' => '84998051012', 'data_nascimento' => '2005-09-09', 'responsavel_id' => '1', 'curso_id' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['matricula' => '20211101110040', 'nome' => 'Pedro Henrique', 'email' => 'pedro.h@escolar.ifrn.edu.br', 'telefone' => '84994061012', 'data_nascimento' => '2009-05-28', 'responsavel_id' => '2', 'curso_id' => '2', 'created_at' => now(), 'updated_at' => now()],
            ['matricula' => '20211101110050', 'nome' => 'João Pedro', 'email' => 'joao.p@escolar.ifrn.edu.br', 'telefone' => '84995071219', 'data_nascimento' => '2006-02-07', 'responsavel_id' => '3', 'curso_id' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['matricula' => '20211101110060', 'nome' => 'Gislaine Nascimento', 'email' => 'gislaine.n@escolar.ifrn.edu.br', 'telefone' => '84993142128', 'data_nascimento' => '2008-03-05', 'responsavel_id' => '4', 'curso_id' => '4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table(table: 'porteiros')->insert([
            ['name' => 'Manoel da Silva', 'email' => 'manoeldasilva@gmail.com', 'cpf' => '32145698722', 'matricula' => '20250101', 'role' => 'porteiro', 'turno' => 'matutino', 'password' => '$2y$10$ZuoFwQLmH6E4NXPylUs8eeE30b.KM9gUs2o0xl3MBtQHr/xCIVDXG', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
