<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Modifier les produits') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Modifier les produits') }}
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if (session('success') === 'Product successfully updated')
                            <div class="bg-green-600 text-center max-w-sm mx-auto py-4 lg:px-4"  x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-gray-600 darke:text-gray-400">
                                <div class="p-2 items-center text-white leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                                <span class="flex rounded-full  uppercase px-2 py-1 text-xs font-bold mr-3"> {{__('OK')}} </span>
                                <span class="font-semibold mr-2 text-left flex-auto">{{ __('Produit modifié avec succès.') }}</span>
                                </div>
                            </div>
                        @endif
                        @if (session('success') === 'Product successfully deleted')
                            <div class="bg-green-600 text-center max-w-sm mx-auto py-4 lg:px-4"  x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-gray-600 darke:text-gray-400">
                                <div class="p-2 items-center text-white leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                                <span class="flex rounded-full  uppercase px-2 py-1 text-xs font-bold mr-3"> {{__('OK')}} </span>
                                <span class="font-semibold mr-2 text-left flex-auto">{{ __('Produit supprimé avec succès !') }}</span>
                                </div>
                            </div>
                        @endif
                        @foreach ($products as $product)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-12">
                                <form action="{{route('product.edit')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="p-4">
                                        <div class=" flex flex-col xl:flex-row gap-4">
                                            <input name="id" hidden value="{{ $product->id }}" >
                                            <div>
                                                <x-input-label for="name" :value="__('Nom')"/>
                                                <x-text-input id="name" class="block mt-1" type="text" name="name" placeholder="Ex: Seringue 12cc" required value=" {{ $product->name }} " />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="price" :value="__('Prix')" class="" />
                                                <x-text-input id="price" class="block mt-1" type="number" name="price" placeholder="Ex: 120000" min="0" required value=" {{ $product->price }} " />
                                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="discount_price" :value="__('Prix cassé (facultatif)')" class="" />
                                                <input id="discount_price" class="block mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="number" name="discount_price" placeholder="Ex: 100000"  min="0"
                                                    @if ( $product->discount_price != -1)
                                                    value="{{ $product->discount_price }}"
                                                    @endif  />
                                                <x-input-error :messages="$errors->get('discount_price')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="category" :value="__('Categorie')" class=""/>
                                                <select name="category" id="category" class="rounded-md border-gray-300">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @if ($category->id == $product->category_id)
                                                            selected
                                                        @endif > {{ $category->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <x-input-label for="description" :value="__('Description')" class=""/>
                                                <textarea id="description" class="block mt-1 rounded-md border-gray-300" name="description">{{ $product->description }} </textarea>
                                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                            </div>
                                        </div>

                                        <div class="flex flex-col lg:flex-row">
                                            <div>
                                                <x-input-label for="productImage1" :value="__('Image 1')" class="mt-4" />
                                                <input type="file"accept="image/png, image/gif, image/jpeg,  image/jpg, image/webp" class="form-control" name="productImage1" @error('productImage1') is-invalid @enderror id="selectImage1{{$product->id}}">
                                                <x-input-error :messages="$errors->get('productImage1')" class="mt-2" />
                                                @if (json_decode($product->images)[0]!="")
                                                    <img id="oldpreview1{{$product->id}}" src="{{asset(json_decode($product->images)[0])}}" alt="votre image" class="mt-3" width="150px" style=""/>
                                                @endif
                                                <img id="preview1{{$product->id}}" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>
                                            </div>

                                            <div>
                                                <x-input-label for="productImage2" :value="__('Image 2')" class="mt-4" />
                                                <input type="file"accept="image/png, image/gif, image/jpeg,  image/jpg, image/webp" class="form-control" name="productImage2" @error('productImage2') is-invalid @enderror id="selectImage2{{$product->id}}">
                                                <x-input-error :messages="$errors->get('productImage2')" class="mt-2" />
                                                @if (json_decode($product->images)[1]!="")
                                                    <img id="oldpreview2{{$product->id}}" src="{{asset('storage/'.json_decode($product->images)[1])}}" alt="votre image" class="mt-3" width="150px" style=""/>
                                                @endif
                                                <img id="preview2{{$product->id}}" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>
                                            </div>

                                            <div>
                                                <x-input-label for="productImage3" :value="__('Image 3')" class="mt-4" />
                                                <input type="file"accept="image/png, image/gif, image/jpeg,  image/jpg, image/webp" class="form-control" name="productImage3" @error('productImage3') is-invalid @enderror id="selectImage3{{$product->id}}">
                                                <x-input-error :messages="$errors->get('productImage3')" class="mt-2" />
                                                @if (json_decode($product->images)[2]!="")
                                                    <img id="oldpreview3{{$product->id}}" src="{{asset('storage/'.json_decode($product->images)[2])}}" alt="votre image" class="mt-3" width="150px" style=""/>
                                                @endif
                                                <img id="preview3{{$product->id}}" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>
                                            </div>
                                        </div>

                                        <div>
                                            <x-primary-button class="py-2"  style="margin-left: 66px" > {{ __('Modifier') }} </x-primary-button>
                                            <x-danger-button class="" type="button" style="margin-left: 10px" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete{{$product->id}}' }));" > <span class="fa fa-trash text-primary"></span>D </x-danger-button>
                                            <x-modal name="delete{{$product->id}}" maxWidth="md">
                                                <!-- Modal content -->
                                                <div class="p-4">
                                                    <h2 class="text-lg font-semibold">Voulez vous confirmer cette action ?</h2>
                                                    <x-danger-button class=" mx-auto"> <a href="{{route('product.delete', $product->id)}}">Oui</a> </x-danger-button>
                                                </div>
                                            </x-modal>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </main>
        </div>
    </body>
    <script>
        @foreach ($products as $product)

            selectImage1{{$product->id}}.onchange = evt => {
                preview = document.getElementById('preview1{{$product->id}}');
                oldpreview = document.getElementById('oldpreview1{{$product->id}}');
                oldpreview.style.display = 'none';
                preview.style.display = 'block';
                const [file] = selectImage1{{$product->id}}.files
                if (file) {
                    preview.src = URL.createObjectURL(file)
                }
            }

            selectImage2{{$product->id}}.onchange = evt => {
                preview = document.getElementById('preview2{{$product->id}}');
                oldpreview = document.getElementById('oldpreview2{{$product->id}}');
                oldpreview.style.display = 'none';
                preview.style.display = 'block';
                const [file] = selectImage2{{$product->id}}.files
                if (file) {
                    preview.src = URL.createObjectURL(file)
                }
            }

            selectImage3{{$product->id}}.onchange = evt => {
                preview = document.getElementById('preview3{{$product->id}}');
                oldpreview = document.getElementById('oldpreview3{{$product->id}}');
                oldpreview.style.display = 'none';
                preview.style.display = 'block';
                const [file] = selectImage3{{$product->id}}.files
                if (file) {
                    preview.src = URL.createObjectURL(file)
                }
            }

        @endforeach
    </script>
</html>
