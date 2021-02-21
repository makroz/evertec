<?php

namespace App\Http\Controllers;

use session;
use App\Models\Products;
use Illuminate\Http\Request;
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
        $carrito=['id'=>0,'quantity'=>0];
        if (session()->has('carrito')){
            $carrito=session('carrito');
        }
        $products=Products::find($carrito['id']);
        if ($products){
            $products->quantity=$carrito['quantity'];
            $products->amount=$carrito['quantity']*$products->price;
        }
        
        return view('carrito.listar',['product'=>$products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Estamos suponiendo que solo se enviara un Unico producto en 
        // esta prueba, sino habria que discriminar aqui para 
        // identificar cada producto en el carrito
        if (!$request->has('id')){
            
            return route('products');
        }
        $carrito=['id'=>0,'quantity'=>0];
        if (session()->has('carrito')){
            $carrito=session('carrito');
            print_r($carrito);
        }
        $carrito['id']=$request->id;
        $carrito['quantity']++;
        session(['carrito'=>$carrito]);
        return Redirect::route('carritoListar');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        session()->forget('carrito');
        return redirect()->route('products');
    }
}
