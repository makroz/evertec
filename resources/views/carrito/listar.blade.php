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
                    CARRITO DE COMPRAS
                </header>

                <div class="w-full p-6">
                    <p class="text-gray-700">

                        @if ($product)

                            <div class="grid grid-cols-2 gap-4">
                                <div>

                                    <section
                                        class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">
                                        <header
                                            class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                                            DATOS DEL PEDIDO
                                        </header>
                                        <div class="p-4 cursor-pointer bg-white rounded w-full 
                                                    shadow-lg select-none overflow-hidden mx-auto">
                                            <p class="font-semibold text-lg mb-1 text-gray-900 group-hover:text-white">
                                                {{ $product->name }}
                                            </p>
                                            <p class="text-gray-700 group-hover:text-white mb-2">
                                                {{ $product->descrip }}
                                            </p>
                                            <p class="text-green-500 group-hover:text-white mb-2">
                                                Precio Unitario: USD {{ $product->price }}
                                                <br>
                                                Cantidad: {{ $product->quantity }}
                                            </p>
                                            <p class="text-red-700 group-hover:text-white mb-2">
                                                Tortal a Pagar: USD {{ $product->amount }}
                                            </p>
                                            <hr>

                                            <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8 text-center"
                                                method="POST" action="{{ route('carrito') }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" value="{{ $product->id }}" name="id">
                                                <button type="submit"
                                                    class="m-4  center bg-red-500 active:bg-blue-700 text-white font-semibold
                                                             hover:text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline 
                                                             transition transform ease-in-out duration-300 hover:-translate-y-1 hover:scale-110">
                                                    Vaciar el Carrito
                                                </button>
                                            </form>

                                        </div>
                                    </section>
                                </div>
                                <!-- ... -->
                                <div>
                                    <section
                                        class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">
                                        <header
                                            class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                                            DATOS DEL COMPRADOR
                                        </header>

                                        <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST"
                                            action="{{ route('ordersSend') }}">
                                            @csrf

                                            <div class="flex flex-wrap">
                                                <label for="customer_email"
                                                    class="block text-gray-700 text-sm font-bold mb-1 sm:mb-4">
                                                    E-Mail
                                                </label>

                                                <input id="customer_email" type="email"
                                                    class="form-input w-full @error('customer_email') border-red-500 @enderror"
                                                    name="customer_email"
                                                    value="{{ session('customer') ? session('customer')['email'] : old('customer_email') }}"
                                                    required autocomplete="customer_email" autofocus>

                                                @error('customer_email')
                                                    <p class="text-red-500 text-xs italic mt-4">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="flex flex-wrap">
                                                <label for="customer_name"
                                                    class="block text-gray-700 text-sm font-bold mb-1 sm:mb-4">
                                                    Nombre
                                                </label>

                                                <input id="customer_name" type="text"
                                                    class="form-input w-full @error('customer_name') border-red-500 @enderror"
                                                    name="customer_name"
                                                    value="{{ session('customer') ? session('customer')['name'] : old('customer_name') }}"
                                                    required autocomplete="customer_name" autofocus>

                                                @error('customer_name')
                                                    <p class="text-red-500 text-xs italic mt-4">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="flex flex-wrap">
                                                <label for="customer_mobile"
                                                    class="block text-gray-700 text-sm font-bold mb-1 sm:mb-4">
                                                    Mobile
                                                </label>

                                                <input id="customer_name" type="text"
                                                    class="form-input w-full @error('customer_mobile') border-red-500 @enderror"
                                                    name="customer_mobile"
                                                    value="{{ session('customer') ? session('customer')['mobile'] : old('customer_mobile') }}"
                                                    required autocomplete="customer_mobile" autofocus>

                                                @error('customer_mobile')
                                                    <p class="text-red-500 text-xs italic mt-4">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="flex flex-wrap">
                                                <button type="submit"
                                                    class="w-full select-none font-bold whitespace-no-wrap p-3 rounded-lg text-base
                                                                leading-normal no-underline text-gray-100 bg-blue-500 hover:bg-blue-700 sm:py-4">
                                                    Enviar Pedido
                                                </button>
                                            </div>
                                        </form>
                                        <br>
                                    </section>
                                </div>
                            </div>
                        @else
                            <div>
                                Carrito esta vacio
                                <image class="h-48 w-full object-scale-down" src="image/cartEmpty.png"
                                    style="width:50%; align:center" />
                            </div>
                        @endif

                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection
