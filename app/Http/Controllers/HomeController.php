<?php

namespace App\Http\Controllers;

use App\Models\Orders;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //DB::connection()->enableQueryLog();
        //$status=session('request_status');
        $customer = session('customer');
        $orders   = Orders::with('product:id,name,price')->orderBy('id', 'desc')->get();
        //print_r(DB::getQueryLog());
        return view('home', ['orders' => $orders]);
    }
}
