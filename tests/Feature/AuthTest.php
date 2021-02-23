<?php

namespace Tests\Feature;

use App\Http\Controllers\OrdersController;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Test de datos de Authentication
     *
     * @return void
     */
    public function testIsAuthCorrectlyFill()
    {
        $Auth = (new OrdersController())->p2pAuth();
        $array = (array)$Auth;
        $prefix = chr(0).'*'.chr(0);        
        $config=$array[$prefix.'config'];

        $this->assertEquals(config('dnetix.login'), $config['login'], 'Login Ok');
        $this->assertEquals(config('dnetix.trankey'), $config['tranKey'], 'TranKey Ok');
        $this->assertEquals(config('dnetix.url'), $config['url'], 'Url Ok');
    }
}
