<div>
    <header>
        <div>
            <h1>Shop</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                {{ count($products) }} {{ str('product')->plural(count($products)) }} available
            </p>
        </div>
        <nav style="display: flex; gap: 1rem;">
            <a href="{{ route('order.create') }}">Checkout</a>
            <a href="{{ url('/') }}">← Home</a>
        </nav>
    </header>

    @if ($categories !== [])
        <section style="margin-bottom: 1.5rem;">
            <p style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; margin-bottom: 0.75rem;">
                Categories
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <button
                    type="button"
                    wire:click="clearCategoryFilter"
                    style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: 1px solid {{ $categoryId === null ? '#d97706' : '#d1d5db' }}; background: {{ $categoryId === null ? '#d97706' : '#fff' }}; color: {{ $categoryId === null ? '#fff' : '#374151' }};"
                >
                    All
                </button>
                @foreach ($categories as $category)
                    <button
                        type="button"
                        wire:click="$set('categoryId', {{ $category['id'] }})"
                        style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: 1px solid {{ $categoryId === $category['id'] ? '#d97706' : '#d1d5db' }}; background: {{ $categoryId === $category['id'] ? '#d97706' : '#fff' }}; color: {{ $categoryId === $category['id'] ? '#fff' : '#374151' }};"
                    >
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </div>
        </section>
    @endif

    @if ($products === [])
        <p style="color: #6b7280;">
            @if ($categoryId !== null)
                No products in this category right now.
                <button type="button" wire:click="clearCategoryFilter" style="color: #d97706; background: none; border: none; cursor: pointer; font: inherit; text-decoration: underline;">
                    View all products
                </button>
            @else
                No products available right now.
            @endif
        </p>
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
