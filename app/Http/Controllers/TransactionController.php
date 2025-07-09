<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->orderBy('nama')->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($request->input('items') as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok untuk produk "' . $product->nama . '" tidak mencukupi.');
                }

                $totalAmount += $product->harga * $item['quantity'];
                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->harga,
                ];
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
            ]);

            foreach ($orderItemsData as $data) {
                $data['transaction_id'] = $transaction->id;
                OrderItem::create($data);

                $product = Product::find($data['product_id']);
                $product->decrement('stock', $data['quantity']);

                StockHistory::create([
                    'product_id' => $product->id,
                    'change' => -$data['quantity'],
                    'description' => 'Penjualan - Transaksi #' . $transaction->id,
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Membuat transaksi baru #' . $transaction->id,
            ]);

            DB::commit();
            return redirect()->route('transactions.create')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'items.product');
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load('items.product');
        $products = Product::orderBy('nama')->get();

        // Ekstrak data item untuk digunakan di frontend (Alpine/JS)
        $transactionItemsJson = $transaction->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->nama,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'stock' => $item->product->stock + $item->quantity,
            ];
        })->values()->toJson();

        return view('transactions.edit', compact('transaction', 'products', 'transactionItemsJson'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $newTotalAmount = 0;
            $originalItems = $transaction->items->keyBy('id');
            $updatedItemIds = [];

            foreach ($request->input('items') as $itemData) {
                $product = Product::lockForUpdate()->find($itemData['product_id']);
                $newQty = $itemData['quantity'];
                $price = $product->harga;
                $newTotalAmount += $price * $newQty;

                if (isset($itemData['id']) && $originalItems->has($itemData['id'])) {
                    $orderItem = $originalItems[$itemData['id']];
                    $oldQty = $orderItem->quantity;
                    $orderItem->update(['quantity' => $newQty, 'price' => $price]);
                    $updatedItemIds[] = $orderItem->id;

                    $product->increment('stock', $oldQty - $newQty);
                    StockHistory::create([
                        'product_id' => $product->id,
                        'change' => $oldQty - $newQty,
                        'description' => 'Perubahan item - Transaksi #' . $transaction->id,
                    ]);
                } else {
                    OrderItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $newQty,
                        'price' => $price,
                    ]);

                    $product->decrement('stock', $newQty);
                    StockHistory::create([
                        'product_id' => $product->id,
                        'change' => -$newQty,
                        'description' => 'Penambahan item - Transaksi #' . $transaction->id,
                    ]);
                }
            }

            // Hapus item yang tidak diinputkan lagi
            $itemsToDelete = $originalItems->except($updatedItemIds);
            foreach ($itemsToDelete as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
                StockHistory::create([
                    'product_id' => $product->id,
                    'change' => $item->quantity,
                    'description' => 'Penghapusan item - Transaksi #' . $transaction->id,
                ]);
                $item->delete();
            }

            $transaction->update(['total_amount' => $newTotalAmount]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Mengupdate transaksi #' . $transaction->id,
            ]);

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            foreach ($transaction->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    StockHistory::create([
                        'product_id' => $product->id,
                        'change' => $item->quantity,
                        'description' => 'Penghapusan transaksi #' . $transaction->id,
                    ]);
                }
            }

            $transaction->delete();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Menghapus transaksi #' . $transaction->id,
            ]);

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
