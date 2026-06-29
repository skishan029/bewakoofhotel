<div class="col-sm-12 col-md-6 col-lg-4 mb-3">
    <div class="product-card card">
        @if ($status == 'Closed')
            <div class="product-overlay">
                <div class="closed-badge">Closed</div>
            </div>
        @endif
        <div class="product-image" style="height: 200px; overflow: hidden; border-radius: 12px; margin-bottom: 1rem;">
            @if ($product->featured_photo)
                <img src="{{ Storage::url($product->featured_photo) }}"
                    alt="{{ $product->product_name_online ?? $product->product_name_english }}"
                    style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div
                    style="width: 100%; height: 100%; background: #334155; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-utensils" style="font-size: 3rem; color: #475569;"></i>
                </div>
            @endif
        </div>

        <h3 class="product-title">
            {{ $product->product_name_online ?? $product->product_name_english }}
            @if ($product->is_online_label_show)
                - Full
            @endif
        </h3>
        <p class="product-desc"
            style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $product->product_desc }}
        </p>
        <h5 style="font-weight:600; margin-bottom:10px;">
            ₹ {{ number_format(floatval($product->full_price) + floatval($product->shipping_charge), 2) }}
        </h5>

        <form action="{{ route('customer.cart.store') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="plate_type" value="1">

            <div class="qty-wrapper">
                <button type="button" class="qty-btn" onclick="decreaseQty(this)">
                    <i class="fa-solid fa-minus"></i>
                </button>

                <input type="number" name="qty" value="1" min="1" class="qty-input" readonly>

                <button type="button" class="qty-btn" onclick="increaseQty(this)">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            <button type="submit" class="action-btn"
                style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
            </button>
        </form>
    </div>
</div>

@if (!empty($product->half_price))
    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
        <div class="product-card card">
            @if ($status == 'Closed')
                <div class="product-overlay">
                    <div class="closed-badge">Closed</div>
                </div>
            @endif
            <div class="product-image"
                style="height: 200px; overflow: hidden; border-radius: 12px; margin-bottom: 1rem;">
                @if ($product->featured_photo)
                    <img src="{{ Storage::url($product->featured_photo) }}"
                        alt="{{ $product->product_name_online ?? $product->product_name_english }}"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div
                        style="width: 100%; height: 100%; background: #334155; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-utensils" style="font-size: 3rem; color: #475569;"></i>
                    </div>
                @endif
            </div>

            <h3 class="product-title">
                {{ $product->product_name_online ?? $product->product_name_english }}
                @if ($product->is_online_label_show)
                    - Half
                @endif
            </h3>
            <p class="product-desc"
                style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $product->product_desc }}
            </p>
            <h5 style="font-weight:600; margin-bottom:10px;">
                ₹ {{ number_format(floatval($product->half_price) + floatval($product->shipping_charge), 2) }}
            </h5>

            <form action="{{ route('customer.cart.store') }}" method="POST" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="plate_type" value="2">

                <div class="qty-wrapper">
                    <button type="button" class="qty-btn" onclick="decreaseQty(this)">
                        <i class="fa-solid fa-minus"></i>
                    </button>

                    <input type="number" name="qty" value="1" min="1" class="qty-input" readonly>

                    <button type="button" class="qty-btn" onclick="increaseQty(this)">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <button type="submit" class="action-btn"
                    style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </button>
            </form>
        </div>
    </div>
@endif
