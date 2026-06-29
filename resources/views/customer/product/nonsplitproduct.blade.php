<div class="col-md-3 col-sm-3">
    <div class="food-items2-wrap prod-details-content">
        <?php // dd($status); ?>
        @if ($status == 'Closed')
            <div class="product-overlay">
                <div class="closed-badge">Closed</div>
            </div>
        @endif
        <form action="{{ route('customer.cart.store') }}" method="POST" class="add-to-cart-form">
            @csrf
            <div class="food-img" style="height: 200px; overflow: hidden;">
                @if ($product->featured_photo)
                    <img class="img-fluid" src="{{ Storage::url($product->featured_photo) }}" alt="{{ $product->product_name_online ?? $product->product_name_english }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div
                        style="width: 100%; height: 100%; background: #334155; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-utensils" style="font-size: 3rem; color: #475569;"></i>
                    </div>
                @endif
                <div class="cart-icon">
                    <button type="submit" class="action-btn"><i class="bi bi-cart-plus"></i></button>
                </div>
            </div>
            <div class="food-content">
                <h3><a>{{ $product->product_name_online ?? $product->product_name_english }}</a></h3>
            </div>
        
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
            <div class="plate-options prod-quantity" style="padding: 0.5rem; border-radius: 0 0 10px 10px; margin-bottom: 1rem; border: 1px solid #eee; color: var(--title-color-dark);">
                <div class="option-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="plate_type" value="2" @checked($isHalfChecked) {{ empty($product->half_price) ? 'disabled' : '' }}>
                        <span>Half</span>
                    </label>
                    <span style="font-weight: 600;">
                        {{ empty($product->half_price) ? 'N/A' : '₹ ' . number_format(floatval($product->half_price) + floatval($product->shipping_charge), 2) }}
                    </span>
                </div>
                <div class="option-row" style="display: flex; justify-content: space-between; align-items: center;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="plate_type" value="1" @checked($isFullChecked){{ empty($product->full_price) ? 'disabled' : '' }}>
                        <span>Full</span>
                    </label>
                    <span style="font-weight: 600;">
                        {{ empty($product->full_price) ? 'N/A' : '₹ ' . number_format(floatval($product->full_price) + floatval($product->shipping_charge), 2) }}
                    </span>
                </div>
                <button type="submit" class="primary-btn3" style="display: flex;">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </form>
    </div>
</div>