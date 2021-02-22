<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Http\Request;
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
        
        if (!$request->validate([
            'customer_email'  => 'required|email',
            'customer_name'   => 'required',
            'customer_mobile' => 'required'
        ])){
            return Redirect::route('carritoListar')->with(['status' => 'Error de Campos']);
        }

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

        $amount    = $carrito['quantity'] * $products->price;
        $reference = md5(date('YmdHis') . 'evertec');

        $requeste = [
            'buyer'    => [
                'name'   => $request->customer_name,
                'email'   => $request->customer_email,
                'mobile'   => $request->customer_mobile
            ],
            'payment'    => [
                'reference'   => $reference,
                'description' => $products->name . " x " . $carrito['quantity'],
                'allowPartial'=> false,
                'amount'      => [
                    'currency' => 'USD',
                    'total'    => $amount,
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl'  => getenv('APP_URL') . "/response/{$reference}",
            'ipAddress'  => $request->ip(),
            'userAgent'  => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

        try {
            $response = $placetopay->request($requeste);
        } catch (\Exception $e) {
            return Redirect::route('carritoListar')->with(['status' => $e->getMessage()]);
        }

        if ($response->isSuccessful()) {
            $requestId               = $response->requestId;
            $processUrl              = $response->processUrl;
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
            $orders->reference       = $reference;
            $orders->save();
            session('customer',[
            'email'  => $request->customer_email,
            'name'   => $request->customer_name,
            'mobile' => $request->customer_mobile
            ]);
        
            return Redirect::to($response->processUrl());
        } else {
            return Redirect::route('carritoListar')->with(['status' => $response->status()->message()]);
        }
    }

    /**
     * Intercept responde.
     *
     */
    public function responseGet(Request $request, $reference)
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

        $order    = Orders::where('reference', $reference)->first();
        $response = $placetopay->query($order->request_id);
        //dd($response);
        if ($response->isSuccessful()) {
            if ($response->status()->isApproved()) {
                $order->status = 'PAYED';
                session()->forget('carrito');
            }
            if ($response->status()->isRejected()) {
                $order->status = 'REJECTED';
            }
            if (!empty($response->payment)){
                $order->request_currency = $response->payment[0]->amount()->to()->currency();
                $order->request_amount = $response->payment[0]->amount()->to()->total();
                $order->request_paymentMethodName = $response->payment[0]->paymentMethodName();
            }
            $order->request_status  = $response->status()->status();
            $order->request_message = $response->status()->message();
            $order->request_date = strftime("%F %X",strtotime($response->status()->date()));
            $order->save();
            return Redirect::route('misordenes')->with(['status' => $response->status()->message(),'request_status'=>$response->status()->status()]);
        } else {
            return Redirect::route('products')->with(['status' => $response->status()->message()]);
        }
    }
}
