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
        </h3>
        <p class="product-desc"
            style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $product->product_desc }}
        </p>

        <form action="{{ route('customer.cart.store') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="qty" value="1">
            @php
                $isFullChecked = false;
                $isHalfChecked = false;
                if (empty($product->full_price) && !empty($product->half_price)) {
                    $isHalfChecked = true;
                } elseif (empty($product->half_price) && !empty($product->full_price)) {
                    $isFullChecked = true;
                } else {
                    $isFullChecked = true;
                }

            @endphp
            <div class="plate-options"
                style="background: rgba(15, 23, 42, 0.5); padding: 0.5rem; border-radius: 8px; margin-bottom: 1rem;">
                <div class="option-row"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="plate_type" value="2" @checked($isHalfChecked)
                            {{ empty($product->half_price) ? 'disabled' : '' }}>
                        <span>Half</span>
                    </label>
                    <span style="font-weight: 600;">
                        {{ empty($product->half_price) ? 'N/A' : '₹ ' . number_format(floatval($product->half_price) + floatval($product->shipping_charge), 2) }}</span>
                </div>
                <div class="option-row" style="display: flex; justify-content: space-between; align-items: center;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="plate_type" value="1" @checked($isFullChecked)
                            {{ empty($product->full_price) ? 'disabled' : '' }}>
                        <span>Full</span>
                    </label>
                    <span style="font-weight: 600;">
                        {{ empty($product->full_price) ? 'N/A' : '₹ ' . number_format(floatval($product->full_price) + floatval($product->shipping_charge), 2) }}</span>
                </div>
            </div>

            <button type="submit" class="action-btn"
                style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
            </button>
        </form>
    </div>
</div>
