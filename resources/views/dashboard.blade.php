<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Salam Pembuka -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="text-sm text-gray-600">Berikut adalah ringkasan aktivitas toko Anda hari ini.</p>
                </div>
            </div>

            <!-- Kartu Ringkasan Data -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Penjualan Hari Ini -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Penjualan Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </x-card>

                <!-- Transaksi Hari Ini -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                           <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $todayTransactions }}</p>
                        </div>
                    </div>
                </x-card>

                <!-- Jumlah Produk -->
                <x-card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Jumlah Produk</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Konten Utama (2 kolom) -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Riwayat Transaksi Terbaru -->
                <div class="lg:col-span-2">
                    <x-card title="Riwayat Transaksi Terbaru">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($recentTransactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada transaksi hari ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>

                <!-- Kolom Kanan: Stok Menipis -->
                <div class="lg:col-span-1">
                    <x-card title="Produk Stok Menipis">
                        <ul class="divide-y divide-gray-200">
                            @forelse ($lowStockProducts as $product)
                                <li class="py-3 flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-800">{{ $product->nama }}</span>
                                    <x-badge color="red">Sisa {{ $product->stock }}</x-badge>
                                </li>
                            @empty
                                <li class="py-3 text-center text-sm text-gray-500">
                                    Semua stok produk aman.
                                </li>
                            @endforelse
                        </ul>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
