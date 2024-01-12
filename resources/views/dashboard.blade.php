<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success') === 'Category created successfully')
                <div class="bg-green-600 text-center max-w-sm mx-auto py-4 lg:px-4"  x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 5000)"
                class="text-sm text-gray-600 darke:text-gray-400">
                    <div class="p-2 items-center text-white leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                    <span class="flex rounded-full  uppercase px-2 py-1 text-xs font-bold mr-3"> {{__('OK')}} </span>
                    <span class="font-semibold mr-2 text-left flex-auto">{{ __('Nouvelle catégorie ajoutée.') }}</span>
                    </div>
                </div>
            @endif
            @if (session('success') === 'Product created successfully')
                <div class="bg-green-600 text-center max-w-sm mx-auto py-4 lg:px-4"  x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 5000)"
                class="text-sm text-gray-600 darke:text-gray-400">
                    <div class="p-2 items-center text-white leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                    <span class="flex rounded-full  uppercase px-2 py-1 text-xs font-bold mr-3"> {{__('OK')}} </span>
                    <span class="font-semibold mr-2 text-left flex-auto">{{ __('Nouveau produit ajouté !') }}</span>
                    </div>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-12">
                <div class=" text-center">
                    <div class=" text-2xl">Catégorie</div>
                </div>
                <form action="{{route('category.create')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="p-4">
                        <x-input-label for="catname" :value="__('Nom')" />
                        <x-text-input id="catname" class="block mt-1" type="text" name="catname" placeholder="Ex: Immobilier" required />
                        <x-input-error :messages="$errors->get('catname')" class="mt-2" />

                        <x-input-label for="categoryImage" :value="__('Image')" class="mt-4" />
                        <input type="file"accept="image/png, image/gif, image/jpeg, image/jpg" class="form-control" name="categoryImage" @error('categoryImage') is-invalid @enderror id="selectImage">
                        <x-input-error :messages="$errors->get('categoryImage')" class="mt-2" />
                        <img id="preview" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>

                        <br><x-primary-button class="mt-3"> {{ __('Ajouter une catégorie') }} </x-primary-button>

                    </div>
                </form>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-12">
                <div class=" text-center">
                    <div class=" text-2xl">Produit</div>
                </div>
                <form action="{{route('product.create')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="p-4">
                        <x-input-label for="name" :value="__('Nom')"/>
                        <x-text-input id="name" class="block mt-1" type="text" name="name" placeholder="Ex: Seringue 12cc" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />

                        <x-input-label for="price" :value="__('Prix')" class="mt-4" />
                        <x-text-input id="price" class="block mt-1" type="number" name="price" placeholder="Ex: 120000" min="0" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />

                        <x-input-label for="discount_price" :value="__('Prix cassé (facultatif)')" class="mt-4" />
                        <x-text-input id="discount_price" class="block mt-1" type="number" name="discount_price" placeholder="Ex: 100000"  min="0" />
                        <x-input-error :messages="$errors->get('discount_price')" class="mt-2" />

                        <x-input-label for="category" :value="__('Categorie')" class="mt-4"/>
                        <select name="category" id="category" class="rounded-md border-gray-300">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"> {{ $category->name }} </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        <div class="flex flex-col lg:flex-row">
                            <div>
                                <x-input-label for="productImage1" :value="__('Image 1')" class="mt-4" />
                                <input type="file"accept="image/png, image/gif, image/jpeg, image/jpg" class="form-control" name="productImage1" @error('productImage1') is-invalid @enderror id="selectImage1">
                                <x-input-error :messages="$errors->get('productImage1')" class="mt-2" />
                                <img id="preview1" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>
                            </div>

                            <div>
                                <x-input-label for="productImage2" :value="__('Image 2')" class="mt-4" />
                                <input type="file"accept="image/png, image/gif, image/jpeg, image/jpg" class="form-control" name="productImage2" @error('productImage2') is-invalid @enderror id="selectImage2">
                                <x-input-error :messages="$errors->get('productImage2')" class="mt-2" />
                                <img id="preview2" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>
                            </div>

                            <div>
                                <x-input-label for="productImage3" :value="__('Image 3')" class="mt-4" />
                                <input type="file"accept="image/png, image/gif, image/jpeg, image/jpg" class="form-control" name="productImage3" @error('productImage3') is-invalid @enderror id="selectImage3">
                                <x-input-error :messages="$errors->get('productImage3')" class="mt-2" />
                                <img id="preview3" src="#" alt="votre image" class="mt-3" width="150px" style="display:none;"/>
                            </div>
                        </div>

                        <x-input-label for="description" :value="__('Description')" class="mt-4"/>
                        <textarea id="description" class="block mt-1 rounded-md border-gray-300" name="description"> Décrivez votre produit </textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />

                        <x-primary-button class="mt-3"> {{ __('Ajouter un produit') }} </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        selectImage.onchange = evt => {
            preview = document.getElementById('preview');
            preview.style.display = 'block';
            const [file] = selectImage.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }

        selectImage1.onchange = evt => {
            preview = document.getElementById('preview1');
            preview.style.display = 'block';
            const [file] = selectImage1.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }

        selectImage2.onchange = evt => {
            preview = document.getElementById('preview2');
            preview.style.display = 'block';
            const [file] = selectImage2.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }

        selectImage3.onchange = evt => {
            preview = document.getElementById('preview3');
            preview.style.display = 'block';
            const [file] = selectImage3.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
    </script>
</x-app-layout>
