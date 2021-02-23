<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Services\Carrito;

class CarritoTest extends TestCase
{
    public function testEnviarCarrito()
    {
        $response = $this->post(route('carritoAdd'),['id'=>1]);
        $carrito = Carrito::get();
        $this->assertEquals(1,$carrito['quantity']);
    }

    public function testLimpiarCarrito()
    {
        $carrito = Carrito::add(1,5);
        $this->assertEquals(5,$carrito['quantity'],'Se cargo 5 items al Carrito');

        $response = $this->delete(route('carritoAdd'));
        $carrito = Carrito::get(1,5);
        $this->assertEquals(0,$carrito['quantity'],'Carrito Vacio');
    }
}
