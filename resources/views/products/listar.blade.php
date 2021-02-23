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
                    BIENVENIDOS A LA TIENDA
                </header>

                <div class="w-full p-6">
                    <p class="text-gray-700">

                        @foreach ($products as $product)

                            <div class="group hover:bg-gray-900 p-4 cursor-pointer bg-white rounded max-w-xs
                                    w-full shadow-lg select-none overflow-hidden mx-auto">
                                <p class="font-semibold text-lg mb-1 text-gray-900 group-hover:text-white">
                                    {{ $product->id }} - {{ $product->name }}
                                </p>
                                <p class="text-gray-700 group-hover:text-white mb-2">
                                    {{ $product->descrip }}
                                </p>
                                <p class="text-red-700 group-hover:text-white mb-2">
                                    USD {{ $product->price }}
                                </p>
                                <hr>

                                <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST"
                                    action="{{ route('carritoAdd') }}">
                                    @csrf
                                    <input type="hidden" value="{{ $product->id }}" name="id">
                                    <button type="submit"
                                        class="m-4  center bg-blue-500 active:bg-blue-700 text-white font-semibold
                                            hover:text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline
                                            transition transform ease-in-out duration-300 hover:-translate-y-1 hover:scale-110">
                                        Adicionar al Carrito
                                    </button>
                                </form>

                            </div>
                            <br>
                        @endforeach

                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection
