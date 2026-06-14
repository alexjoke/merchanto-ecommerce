<div>
    <header>
        <h1>Shop</h1>
        <nav style="display: flex; gap: 1rem;">
            <a href="{{ route('order.create') }}">Checkout</a>
            <a href="{{ url('/') }}">← Home</a>
        </nav>
    </header>

    @if ($products === [])
        <p style="color: #6b7280;">No products available right now.</p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(16rem, 1fr)); gap: 1.5rem;">
            @foreach ($products as $product)
                <article style="background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem;">
                    @if ($product['categoryName'])
                        <p style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">
                            {{ $product['categoryName'] }}
                        </p>
                    @endif
                    <h2 style="font-size: 1.125rem; font-weight: 600; margin-top: 0.25rem;">{{ $product['name'] }}</h2>
                    @if ($product['description'])
                        <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem;">{{ $product['description'] }}</p>
                    @endif
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem;">
                        <span style="font-size: 1.125rem; font-weight: 700; color: #d97706;">
                            ${{ number_format($product['priceCents'] / 100, 2) }}
                        </span>
                        <span style="font-size: 0.875rem; color: #6b7280;">
                            {{ $product['stock'] }} in stock
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
