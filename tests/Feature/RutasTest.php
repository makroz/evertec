<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RutasTest extends TestCase
{
    /**
     * Probando que existan las rutas para productos
     *
     * @return void
     */
    public function testExistenRutasProductos()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Probando que existan las rutas para manejo del carrito
     *
     * @return void
     */
    public function testExistenRutasCarrito()
    {
        $response = $this->get(route('carritoList'));
        $response->assertStatus(200);

        $response = $this->post(route('carritoList'));
        $response->assertStatus(302);

        $response = $this->delete('/carrito');
        $response->assertStatus(302);
    }

    /**
     * Probando que existan las rutas para Manejo de Ordenes
     *
     * @return void
     */
    public function testExistenRutasOrdenes()
    {
        $response = $this->post('/orders');
        $response->assertStatus(302);

        $response = $this->get('/myOrders');
        $response->assertStatus(200);

    }

    /**
     * Probando que existan las rutas para Responder al P2P
     *
     * @return void
     */
    public function testExistenRutasRespondenP2P()
    {
        $response = $this->get('/response/445544');
        $response->assertStatus(302);
    }

    /**
     * Probando que existan las rutas para Admin
     *
     * @return void
     */
    public function testExistenRutasAdmin()
    {
        $response = $this->get('/home');
        $response->assertStatus(302);
    }
}
