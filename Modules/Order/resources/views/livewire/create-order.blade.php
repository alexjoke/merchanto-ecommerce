<div>
    <header>
        <h1>Checkout</h1>
        <nav>
            <a href="{{ route('catalog.shop') }}">Shop</a>
            <a href="{{ url('/') }}">Home</a>
        </nav>
    </header>

    <form wire:submit="submit">
        <section class="card">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Products</h2>

            @error('quantities')
                <p class="error" style="margin-bottom: 1rem;">{{ $message }}</p>
            @enderror

            @if ($products === [])
                <p style="color: #6b7280;">No products available to order.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach ($products as $product)
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                            <div>
                                <p style="font-weight: 600;">{{ $product['name'] }}</p>
                                <p style="font-size: 0.875rem; color: #6b7280;">
                                    ${{ number_format($product['priceCents'] / 100, 2) }}
                                    · {{ $product['stock'] }} in stock
                                </p>
                            </div>
                            <div style="width: 5rem;">
                                <label for="qty-{{ $product['id'] }}">Qty</label>
                                <input
                                    id="qty-{{ $product['id'] }}"
                                    type="number"
                                    min="0"
                                    max="{{ $product['stock'] }}"
                                    wire:model="quantities.{{ $product['id'] }}"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="card">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Customer details</h2>

            <div class="field">
                <label for="customerName">Name</label>
                <input id="customerName" type="text" wire:model="customerName">
                @error('customerName') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="customerEmail">Email</label>
                <input id="customerEmail" type="email" wire:model="customerEmail">
                @error('customerEmail') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="customerPhone">Phone (optional)</label>
                <input id="customerPhone" type="text" wire:model="customerPhone">
                @error('customerPhone') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="shippingAddress">Shipping address (optional)</label>
                <textarea id="shippingAddress" rows="3" wire:model="shippingAddress"></textarea>
                @error('shippingAddress') <p class="error">{{ $message }}</p> @enderror
            </div>
        </section>

        <button type="submit" class="btn" @disabled($products === [])>Place order</button>
    </form>
</div>
