<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Products;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    /**
     * Display product details.
     */
    public function showDetail($id)
    {
        $products = Product::findOrFail($id);
        $category = Category::all();
        return view('pages.detail', compact('products', 'category'));
    }
    public function showCart()
    {
        $cartItems = Cart::where('id_user', Auth::user()->id)->with('product')->get();
        $category = Category::all();
        return view('pages.cart', compact('cartItems', 'category'));
    }


    /**
     * Add product to cart.
     */
    public function cartAdd(Request $request, $id)
    {
        $cartItem = Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $id)
            ->first();

        if ($cartItem) {
            $cartItem->qty += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'id_user' => Auth::user()->id,
                'id_product' => $id,
                'qty' => 1
            ]);
        }

        return redirect()->route('cart')->with('success', 'Product added to cart successfully!');
    }

    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $id)
            ->first();

        if ($cartItem) {
            $cartItem->qty = $request->quantity;
            $cartItem->save();
        }

        return redirect()->route('cart')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove product from cart.
     */
    public function cartDelete(Request $request, $id)
    {
        Cart::where('id_user', Auth::user()->id)
            ->where('id_product', $id)
            ->delete();

        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }

    public function checkout(CheckoutRequest $request)
    {
        $data = $request->all();
        // dd($data);

        $carts = Cart::with(['products'])->where('id', Auth::user()->id)->get();

        // Add To Transactions Data
        $data['id_user'] = Auth::user()->id;
        $data['id_address'] = $request->id_address;
        $data['total_price'] = $carts->sum('carts.total_price');
        $data['notes'] = $request->notes;

        // Buat transaksi
        $transaction = Transaction::create($data);

        // Tambahkan produk ke dalam transaksi
        foreach ($carts as $cart) {
            TransactionItem::create([
                'id_user' => $cart->id_user,
                'id_transaction' => $transaction->id,
                'id_product' => $cart->id_product,
            ]);
        }

        // Hapus cart setelah checkout
        $cart->products()->detach();

        dd($request);

        // Redirect ke halaman langkah-langkah pembayaran
        return redirect()->route('payment.steps');
    }

    public function paymentsteps()
    {
        return view();
    }
}
