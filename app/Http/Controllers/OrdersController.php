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
     * Devuelve el objeto de Autenticacion para PlaceTopay
     *
     * @return Objetc
     */
    private function p2pAuth(){
        return new PlacetoPay([
            'login'   => config('dnetix.login'),
            'tranKey' => config('dnetix.trankey'),
            'url'     => config('dnetix.url'),
            'rest'    => [
                'timeout'         => 45,
                'connect_timeout' => 30,
            ],
        ]);
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
            'customer_mobile' => 'required',
        ])) {
            return Redirect::route('carritoListar')->with(['status' => 'Error de Campos']);
        }

        if (session()->has('carrito')) {
            $carrito = session('carrito');
        } else {
            return Redirect::route('carritoListar');
        }

        $products = Products::find($carrito['id']);

        $placetopay = $this->p2pAuth();

        $amount    = $carrito['quantity'] * $products->price;
        $reference = md5(date('YmdHis') . 'evertec');

        $customer = [
            'name'   => $request->customer_name,
            'email'  => $request->customer_email,
            'mobile' => $request->customer_mobile,
        ];
        session(['customer' => $customer]);
        
        $requeste = [
            'buyer'      => $customer,
            'payment'    => [
                'reference'    => $reference,
                'description'  => $products->name . " x " . $carrito['quantity'],
                'allowPartial' => false,
                'recurring'    => false,
                'subscribe'    => false,
                'amount'       => [
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

            return Redirect::to($response->processUrl());
        } else {
            return Redirect::route('carritoListar')->with(['status' => $response->status()->message()]);
        }
    }

    /**
     * Intercept response
     *
     */
    public function responseGet(Request $request, $reference)
    {
        $placetopay = $this->p2pAuth();

        $order    = Orders::where('reference', $reference)->first();
        $response = $placetopay->query($order->request_id);
        if ($response->isSuccessful()) {
            if ($response->status()->isApproved()) {
                $order->status = 'PAYED';
                session()->forget('carrito');
            }
            if ($response->status()->isRejected()) {
                $order->status = 'REJECTED';
            }

            if ($response->status()->status()=='PENDING') {
                session()->forget('carrito');
            }

            if (!empty($response->payment)) {
                $order->request_currency          = $response->payment[0]->amount()->to()->currency();
                $order->request_amount            = $response->payment[0]->amount()->to()->total();
                $order->request_paymentMethodName = $response->payment[0]->paymentMethodName();
            }
            $order->request_status  = $response->status()->status();
            $order->request_message = $response->status()->message();
            $order->request_date    = strftime("%F %X", strtotime($response->status()->date()));
            $order->save();
            if ($request->home){
                return Redirect::route('home')->with(['status' => $response->status()->message()]);    
            }
            return Redirect::route('myOrders')->with(['status' => $response->status()->message(), 'request_status' => $response->status()->status()]);
        } else {
            if ($request->home){
                return Redirect::route('home')->with(['status' => $response->status()->message()]);    
            }
            return Redirect::route('products')->with(['status' => $response->status()->message()]);
        }
    }

    /**
     * Muestras las ordenes del Cliente
     *
     */
    public function myOrders(Request $request)
    {
        $customer = session('customer');
        if (!$customer){
            echo "entro";
            $customer['email']='guess';
        }
        $orders   = Orders::with('product:id,name')
            ->Where('customer_email', $customer['email'])
            ->orderBy('id', 'desc')
            ->get();
        return view('orders.myorders', ['orders' => $orders]);
    }
}
