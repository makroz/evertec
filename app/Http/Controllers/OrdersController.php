<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\Facades\Redirect;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $carrito = ['id' => 0, 'quantity' => 0];
        if (session()->has('carrito')) {
            $carrito = session('carrito');
        } else {
            return Redirect::route('carritoListar');
        }
        $products = Products::find($carrito['id']);

        $placetopay = new PlacetoPay([
            'login'   => config('dnetix.login'),
            'tranKey' => config('dnetix.trankey'),
            'url'     => config('dnetix.url'),
            'rest'    => [
                'timeout'         => 45,
                'connect_timeout' => 30,
            ],
        ]);

        $amount=$carrito['quantity'] * $products->price;
        $reference = md5(date('YmdHis').'evertec');

        $requeste  = [
            'payment'    => [
                'reference'   => $reference,
                'description' => $products->name." x ".$carrito['quantity'],
                'amount'      => [
                    'currency' => 'USD',
                    'total'    => $amount,
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl'  => 'http://evertec.test/response/' . $reference,
            'ipAddress'  => $request->ip(),
            'userAgent'  => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

        try {
            $response = $placetopay->request($requeste);
        }catch (\Exception $e) {
            return Redirect::route('carritoListar')->with(['status'=>$e->getMessage()]);
        }

        if ($response->isSuccessful()) {
            $requestId=$response->requestId;
            $processUrl=$response->processUrl;
            $orders                  = new Orders();
            $orders->customer_name   = $request->customer_name;
            $orders->customer_email  = $request->customer_email;
            $orders->customer_mobile = $request->customer_mobile;
            $orders->quantity        = $carrito['quantity'];
            $orders->amount          = $amount;
            $orders->products_id     = $carrito['id'];
            $orders->status          = "CREATED";
            $orders->request_id      = $requestId;
            $orders->process_url     = $processUrl;
            $orders->reference     = $reference;
            $orders->save();
            session()->forget('carrito');
            return Redirect::to($response->processUrl());
        } else {
            return Redirect::route('carritoListar')->with(['status'=>$response->status()->message()]);
        }
    }

    /**
     * Intercept responde.
     *
     */
    public function responseGet(Request $request,$reference)
    {
        $placetopay = new PlacetoPay([
            'login'   => config('dnetix.login'),
            'tranKey' => config('dnetix.trankey'),
            'url'     => config('dnetix.url'),
            'rest'    => [
                'timeout'         => 45,
                'connect_timeout' => 30,
            ],
        ]);

        $order=Orders::where('reference',$reference)->first();
        $response = $placetopay->query($order->request_id);

        if ($response->isSuccessful()) {
            if ($response->status()->isApproved()) {
                $order->status='PAYED';
                $order->request_status='PAYED';
                $order->request_message=$response->status()->message();
                $order->save();
            }
        } else {
            print_r($response->status()->message() . "\n");
        }
    }
}
