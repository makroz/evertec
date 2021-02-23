<?php

namespace App\Http\Services;

class Carrito
{
    static public function get(){
        $carrito=['id'=>null,'quantity'=>0];
        if (session()->has('carrito')){
            $carrito=session('carrito');
        }
        return $carrito;
    }

    static public function add($id,$qty=1){
        $carrito=self::get();
        $carrito['id']=$id;
        $carrito['quantity']=$carrito['quantity']+$qty;
        session(['carrito'=>$carrito]);
        return $carrito;
    }
    
    static public function clear(){
        session()->forget('carrito');
        return self::get();
    }
}