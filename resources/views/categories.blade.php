<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Modifier les catégories') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

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
                        {{ __('Modifier les catégories') }}
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if (session('success') === 'Category successfully updated')
                            <div class="bg-green-600 text-center max-w-sm mx-auto py-4 lg:px-4"  x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-gray-600 darke:text-gray-400">
                                <div class="p-2 items-center text-white leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                                <span class="flex rounded-full  uppercase px-2 py-1 text-xs font-bold mr-3"> {{__('OK')}} </span>
                                <span class="font-semibold mr-2 text-left flex-auto">{{ __('Catégorie modifiée avec succès.') }}</span>
                                </div>
                            </div>
                        @endif
                        @if (session('success') === 'Category successfully deleted')
                            <div class="bg-green-600 text-center max-w-sm mx-auto py-4 lg:px-4"  x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-gray-600 darke:text-gray-400">
                                <div class="p-2 items-center text-white leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                                <span class="flex rounded-full  uppercase px-2 py-1 text-xs font-bold mr-3"> {{__('OK')}} </span>
                                <span class="font-semibold mr-2 text-left flex-auto">{{ __('Catégorie supprimée !') }}</span>
                                </div>
                            </div>
                        @endif
                        @foreach ($categories as $category)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-12">
                                <form action="{{route('category.edit')}}" method="post" enctype="multipart/form-data" >
                                    @csrf
                                    <div class="p-4">
                                        <input type="text" value="{{ $category->id }}" name="id" hidden>
                                        <x-input-label for="catname" :value="__('Nom')" style="margin-left: 66px" />
                                        <div class="flex flex-row">
                                            <label for="selectImage{{ $category->id }}">
                                                <img id="preview{{ $category->id }}" src=" {{asset($category->image )}}" alt="votre image" class="mt-3" width="50px"/>
                                            </label>
                                            <input type="file" hidden accept="image/png, image/gif, image/jpeg,  image/jpg, image/webp" class="form-control" name="categoryImage" @error('categoryImage') is-invalid @enderror id="selectImage{{ $category->id }}">
                                            <x-text-input id="catname" class="block mt-1 ml-4" type="text" name="catname" placeholder="Ex: Immobilier" required value="{{ $category->name }}"/>
                                        </div>
                                        <x-input-error :messages="$errors->get('catname')" class="mt-2"  style="margin-left: 50px"  />

                                        <div>
                                            <x-primary-button class="mt-3"  style="margin-left: 66px" > {{ __('Modifier') }} </x-primary-button>
                                            <x-danger-button class="mt-3" type="button" style="margin-left: 10px" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete{{$category->id}}' }));" > <i class="fa fa-trash text-primary"></i> </x-danger-button>
                                            <x-modal name="delete{{$category->id}}" maxWidth="md">
                                                <!-- Modal content -->
                                                <div class="p-4">
                                                    <h2 class="text-lg font-semibold"> {{__('Voulez vous confirmer cette action ?')}}</h2>
                                                    <x-danger-button class=" mx-auto"> <a href="{{route('category.delete', $category->id)}}">{{ __('Oui') }}</a> </x-danger-button>
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
        @foreach ($categories as $category)
            selectImage{{ $category->id }}.onchange = evt => {
                preview = document.getElementById('preview{{ $category->id }}');
                const [file] = selectImage{{ $category->id }}.files
                if (file) {
                    preview.src = URL.createObjectURL(file)
                }
            }
        @endforeach
    </script>
</html>
