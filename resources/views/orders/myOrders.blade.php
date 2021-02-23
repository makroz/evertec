@extends('layouts.app')

@section('content')
    <main class="sm:container sm:mx-auto sm:mt-10">
        <div class="w-full sm:px-6">

            @if (session('status'))
                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4"
                    role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('request_status') == 'REJECTED')
                <div class="text-lg text-grey-700  px-3 py-4 mb-4">
                    Su pedido fue Cancelado, desea Intentarlo Otra Vez?
                    <a class="underline text-blue-500 text-sm" href="{{ route('carritoList') }}">
                        Click Aqui para voolver a intentarlo
                    </a>

                </div>
            @endif

            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

                <header class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                    MIS PEDIDOS
                </header>
                <div class="w-full p-6">
                    <p class="text-gray-700">

                        @if (count($orders) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                                @foreach ($orders as $order)
                                    <div>
                                        <section
                                            class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">
                                            <header class="font-semibold bg-blue-400 text-gray-700 p-4 sm:rounded-t-md">
                                                PEDIDO ID: {{ $order->id }}
                                            </header>

                                            <div
                                                class="p-4 cursor-pointer bg-white rounded w-full shadow-lg select-none overflow-hidden mx-auto">
                                                <p class="text-grey-500 group-hover:text-white mb-2">
                                                    Fecha: {{ $order->created_at }}
                                                </p>
                                                <p class="font-semibold text-lg mb-1 text-gray-900 group-hover:text-white">
                                                    {{ $order->product->name }}
                                                </p>
                                                <p class="text-green-500 group-hover:text-white mb-2">
                                                    Cantidad: {{ $order->quantity }}
                                                    <br>
                                                    Total Pedido: USD {{ $order->amount }}
                                                </p>

                                                @if ($order->status == 'PAYED')
                                                    <p class="text-grey-900 group-hover:text-white mb-2">
                                                        Status: PAGADO
                                                    </p>
                                                    <br>
                                                @endif

                                                @if ($order->status == 'REJECTED')
                                                    <p class="text-gey-200 group-hover:text-white mb-2 line-through ">
                                                        Status: ANULADA
                                                    </p>
                                                    <br>
                                                @endif

                                                @if ($order->status == 'CREATED')
                                                    <p class="text-red-700 group-hover:text-white mb-2">
                                                        Status: PENDIENTE
                                                    </p>
                                                    <a class="underline text-blue-500 text-sm"
                                                        href="{{ $order->process_url }}">
                                                        Click Aqui para ir a Terminar el PAGO
                                                    </a>
                                                @endif

                                            </div>
                                        </section>
                                    </div>
                                @endforeach

                            </div>
                        @else
                            <div>
                                No Tiene ningun Pedido
                            </div>
                        @endif

                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection
