<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Orders;
use App\Http\Services\Carrito;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrdersTest extends TestCase
{

    use RefreshDatabase;
    private $aux;

    public function testComprobarDatosFormulario()
    {
        $response = $this->post(route('ordersSend'));
        $this->assertNotEquals(null,session('errors'),'Errors en Datos');
    }

    public function testComprobarNoEnviaCarritoVacio()
    {
        $response = $this->post(route('ordersSend'), [
            'customer_email'  => 'test@test.com',
            'customer_name'   => 'test',
            'customer_mobile' => '111',
        ]);
        $this->assertEquals('Carrito Vacio',session('status'),'Carrito esta vacio');
    }

    public function testEnviarOrdenyVerificar()
    {
        Artisan::call('db:seed');
        Carrito::add(1,1);
        $response = $this->post(route('ordersSend'), [
            'customer_email'  => 'test@test.com',
            'customer_name'   => 'test',
            'customer_mobile' => '111',
        ]);

        $order=Orders::all()->first();
        
        $this->assertEquals('test@test.com',$order->customer_email,'Email Ok');
        $this->assertEquals('CREATED',$order->status,'Status Ok');

        $response = $this->get(route('responseGet',$order->reference));
        $this->assertEquals('La petición se encuentra activa',session('status'),'Status Informacion Ok');
    }   
}