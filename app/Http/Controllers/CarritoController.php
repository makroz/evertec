<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Services\Carrito;
use Illuminate\Support\Facades\Redirect;

class CarritoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carrito=Carrito::get();

        $products=Products::find($carrito['id']);
        if ($products){
            $products->quantity=$carrito['quantity'];
            $products->amount=$carrito['quantity']*$products->price;
        }
        return view('carrito.listar',['product'=>$products]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('id')){
            return Redirect::route('products')->with(['status' => 'No se especifico Producto']);
        }
       
        Carrito::add($request->id);
        return Redirect::route('carritoList');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Carrito::clear();
        return Redirect::route('products');
    }
}
