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

            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

                <header class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                    Listado Completo de Ventas de la Tienda
                </header>

                <div class="w-full p-6">
                    <p class="text-gray-700">
                    <p class="text-gray-700">
                        @if (count($orders) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4">

                                @foreach ($orders as $order)
                                    <div>
                                        <section
                                            class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">
                                            <header class="font-semibold bg-blue-400 text-gray-700 p-4 sm:rounded-t-md">
                                                PEDIDO ID: {{ $order->id }}
                                            </header>

                                            <div
                                                class="p-4 cursor-pointer bg-white rounded w-full shadow-lg select-none overflow-hidden mx-auto">
                                                <p class="text-grey-500 group-hover:text-white mb-2 text-xs">
                                                    Fecha: {{ $order->created_at }}
                                                    <br>
                                                    Referencia: {{ $order->reference }}
                                                </p>
                                                <fieldset class="border-2 p-2 ">
                                                    <legend class=" px-1 font-semibold">Información Cliente</legend>
                                                    Nombre: {{ $order->customer_name }}
                                                    <br>
                                                    Email: {{ $order->customer_email }}
                                                    <br>
                                                    Telefono: {{ $order->customer_mobile }}
                                                </fieldset>

                                                <fieldset class="border-2 p-2 ">
                                                    <legend class="px-1 font-semibold">Informacion del Producto</legend>
                                                    {{ $order->product->name }}
                                                    <br>
                                                    Precio: {{ $order->product->price }}
                                                    <br>
                                                    Cantidad: {{ $order->quantity }}
                                                    <br>
                                                    Total: USD {{ $order->amount }}
                                                </fieldset>

                                                <fieldset class="border-2 p-2 ">
                                                    <legend class="px-1 font-semibold">Informacion del Pago</legend>
                                                    Request Id: {{ $order->request_id }} <br>
                                                    Fecha de Procesado: <br> {{ $order->request_date }} <br>
                                                    Total Recibido: {{ $order->request_currency }}
                                                    {{ $order->request_amount }} <br>
                                                    Metodo de Pago: {{ $order->request_paymentMethodName }} <br>
                                                    Status de Pago: {{ $order->request_status }} <br>
                                                    Mensaje del Pago: {{ $order->request_message }} <br>

                                                </fieldset>

                                                @if ($order->status == 'PAYED')
                                                    <p class="text-grey-900 group-hover:text-white mb-2">
                                                        Status: PAGADO
                                                    </p><br>
                                                @endif

                                                @if ($order->status == 'REJECTED')
                                                    <p class="text-gey-200 group-hover:text-white mb-2 line-through ">
                                                        Status: ANULADA
                                                    </p><br>
                                                @endif

                                                @if ($order->status == 'CREATED')
                                                    <p class="text-red-700 group-hover:text-white mb-2">
                                                        Status: PENDIENTE
                                                    </p>
                                                    <a class="underline text-blue-500 text-sm"
                                                        href="/response/{{ $order->reference }}?home=1">
                                                        Click Aqui para Verificar el PAGO Nuevamente
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
                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection
