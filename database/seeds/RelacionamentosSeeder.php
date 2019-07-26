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

        foreach ($perfis as $perfi) {
            $perfi->usuarios()->attach($usuarios->random()->id);
        }

        foreach ($aparelhos as $aparelho) {
            $aparelho->usuarios()->attach($usuarios->random()->id);
        }
    }
}
