<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Services\Carrito;

class CarritoUnitTest extends TestCase
{
    public function test_verificaId()
    {
        $response = $this->post(route('carritoAdd'));
        $this->assertEquals(session('status'),'No se especifico Producto');
    }

    public function test_getCarrito()
    {
        $carrito = Carrito::get();
        $this->assertEquals(0,$carrito['quantity'],'Carrito em 0');
    }

    public function test_addCarrito()
    {
        $carrito = Carrito::add(1,2);
        $this->assertEquals(2,$carrito['quantity'],'Carrito en 2');
    }

}
