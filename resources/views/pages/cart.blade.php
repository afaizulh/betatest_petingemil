@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Your Shopping Cart</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <p>Your cart is empty.</p>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td>{{ $item->product->name_product }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item->id_product) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="quantity" value="{{ $item->qty }}" min="1"
                                                class="form-control" style="width: 60px;">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </td>
                                <td>RP {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td>RP {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.delete', $item->id_product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <textarea id="notes" class="form-control @error('notes') is-invalid @enderror"
                        style="height: 100px" name="notes" value="{{ old('notes') }}" required
                        autocomplete="notes"></textarea>
                    <button type="submit" class="btn btn-danger">Checkout</button>
                </form>
            </div>
        @endif
    </div>
@endsection
