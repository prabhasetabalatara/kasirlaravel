<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Transaksi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifikasi -->
            @if (session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if (session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            <div x-data="transactionForm()">
                <form :action="formAction" method="POST" @submit.prevent="submitForm">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Kolom Kiri: Input Produk -->
                        <div class="lg:col-span-2">
                            <x-card title="Pilih Produk">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Input Nama Barang -->
                                    <div>
                                        <x-input-label for="product_id" value="Nama Barang" />
                                        <select x-model="selectedProductId" id="product_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->harga }}" data-stock="{{ $product->stock }}" data-name="{{ $product->nama }}">
                                                    {{ $product->nama }} (Stok: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Input Jumlah -->
                                    <div>
                                        <x-input-label for="quantity" value="Jumlah" />
                                        <x-text-input x-model.number="quantity" id="quantity" type="number" class="block mt-1 w-full" min="1" />
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <x-primary-button type="button" @click="addToCart" x-bind:disabled="!selectedProductId || quantity <= 0">
                                        Tambah ke Keranjang
                                    </x-primary-button>
                                </div>
                            </x-card>
                        </div>

                        <!-- Kolom Kanan: Keranjang Belanja -->
                        <div class="lg:col-span-1">
                            <x-card title="Keranjang Belanja">
                                <!-- Daftar Item -->
                                <div class="space-y-4">
                                    <template x-if="cart.length === 0">
                                        <p class="text-gray-500 text-center">Keranjang masih kosong.</p>
                                    </template>

                                    <template x-for="(item, index) in cart" :key="index">
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <div>
                                                <p class="font-semibold" x-text="item.name"></p>
                                                <p class="text-sm text-gray-600" x-text="`Rp ${formatRupiah(item.price)} x ${item.quantity}`"></p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <p class="font-semibold" x-text="`Rp ${formatRupiah(item.price * item.quantity)}`"></p>
                                                <button @click="removeFromCart(index)" type="button" class="text-red-500 hover:text-red-700">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Total -->
                                <div class="mt-6 border-t pt-4">
                                    <div class="flex justify-between items-center">
                                        <p class="text-lg font-semibold">Total</p>
                                        <p class="text-lg font-bold" x-text="`Rp ${formatRupiah(totalAmount)}`"></p>
                                    </div>
                                </div>

                                <!-- Tombol Simpan Transaksi -->
                                <div class="mt-6">
                                    <x-primary-button type="submit" class="w-full justify-center" x-bind:disabled="cart.length === 0">
                                        Simpan Transaksi
                                    </x-primary-button>
                                </div>
                            </x-card>
                        </div>
                    </div>

                    <!-- Hidden inputs for form submission -->
                    <template x-for="(item, index) in cart">
                        <div>
                            <input type="hidden" :name="`items[${index}][product_id]`" :value="item.product_id">
                            <input type="hidden" :name="`items[${index}][quantity]`" :value="item.quantity">
                        </div>
                    </template>
                </form>
            </div>
        </div>
    </div>

    <script>
        function transactionForm() {
            return {
                // Data
                cart: [],
                selectedProductId: '',
                quantity: 1,
                formAction: '{{ route("transactions.store") }}',

                // Computed Properties
                get totalAmount() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                // Methods
                addToCart() {
                    const selectElement = document.getElementById('product_id');
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    if (!selectedOption.value) return;

                    const product = {
                        product_id: parseInt(selectedOption.value),
                        name: selectedOption.dataset.name,
                        price: parseInt(selectedOption.dataset.price),
                        stock: parseInt(selectedOption.dataset.stock),
                    };

                    const existingItem = this.cart.find(item => item.product_id === product.product_id);
                    const newQuantity = existingItem ? existingItem.quantity + this.quantity : this.quantity;

                    if (newQuantity > product.stock) {
                        alert(`Stok tidak mencukupi! Sisa stok untuk ${product.name} adalah ${product.stock}.`);
                        return;
                    }

                    if (existingItem) {
                        existingItem.quantity = newQuantity;
                    } else {
                        this.cart.push({ ...product, quantity: this.quantity });
                    }

                    // Reset form
                    this.selectedProductId = '';
                    this.quantity = 1;
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                submitForm(event) {
                    if (this.cart.length === 0) {
                        alert('Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
                        return;
                    }
                    event.target.submit();
                }
            }
        }
    </script>
</x-app-layout>
