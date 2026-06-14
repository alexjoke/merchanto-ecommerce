<div>
    <header>
        <h1>Order {{ $order['reference'] }}</h1>
        <nav>
            <a href="{{ route('catalog.shop') }}">Shop</a>
            <a href="{{ route('order.create') }}">New order</a>
            <a href="{{ url('/') }}">Home</a>
        </nav>
    </header>

    @if ($order)
        <section class="card">
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">
                Placed on {{ $order['createdAt'] }}
            </p>
            <p style="margin-bottom: 0.25rem;">
                <strong>Status:</strong> {{ $order['statusLabel'] }}
            </p>
            <p style="margin-bottom: 0.25rem;">
                <strong>Customer:</strong> {{ $order['customerName'] }} ({{ $order['customerEmail'] }})
            </p>
            @if ($order['customerPhone'])
                <p style="margin-bottom: 0.25rem;">
                    <strong>Phone:</strong> {{ $order['customerPhone'] }}
                </p>
            @endif
            @if ($order['shippingAddress'])
                <p>
                    <strong>Shipping address:</strong> {{ $order['shippingAddress'] }}
                </p>
            @endif
        </section>

        <section class="card">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Items</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb; text-align: left;">
                        <th style="padding: 0.5rem 0;">Product</th>
                        <th style="padding: 0.5rem 0;">Qty</th>
                        <th style="padding: 0.5rem 0;">Unit price</th>
                        <th style="padding: 0.5rem 0;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order['items'] as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.5rem 0;">{{ $item['productName'] }}</td>
                            <td style="padding: 0.5rem 0;">{{ $item['quantity'] }}</td>
                            <td style="padding: 0.5rem 0;">${{ number_format($item['unitPriceCents'] / 100, 2) }}</td>
                            <td style="padding: 0.5rem 0;">${{ number_format($item['subtotalCents'] / 100, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding-top: 0.75rem; font-weight: 600; text-align: right;">Total</td>
                        <td style="padding-top: 0.75rem; font-weight: 700; color: #d97706;">
                            ${{ number_format($order['totalCents'] / 100, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <p style="font-size: 0.875rem; color: #6b7280;">
            Save your reference <strong>{{ $order['reference'] }}</strong> to view this order again later.
        </p>
    @endif
</div>
