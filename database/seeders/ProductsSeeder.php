<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('products')->insert([
        //     'name' => 'Producto de prueba Servicio',
        //     'descrip' => 'Prueba de Descripcion',
        //     'price' => 100,
        // ]);
        Products::factory()->create();
    }
}
