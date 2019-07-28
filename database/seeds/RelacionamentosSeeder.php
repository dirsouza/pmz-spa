<?php

use Illuminate\Database\Seeder;

class RelacionamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = factory(\App\Models\User::class, 50)->create();
        $perfis = factory(\App\Models\Perfil::class, 5)->create();
        $aparelhos = factory(\App\Models\Aparelho::class, 100)->create();

        foreach ($usuarios as $usuario) {
            $usuario->perfis()->attach($perfis->random()->id);
            $usuario->aparelhos()->attach($aparelhos->random()->id);
        }
    }
}
