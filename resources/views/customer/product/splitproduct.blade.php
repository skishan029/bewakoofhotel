<div class="col-md-3 col-sm-3">
    <div class="food-items2-wrap prod-details-content">
        @if ($status == 'Closed')
            <div class="product-overlay">
                <div class="closed-badge">Closed</div>
            </div>
        @endif
        <form action="{{ route('customer.cart.store') }}" method="POST" class="add-to-cart-form">
            @csrf
            <div class="food-img" style="height: 200px;">
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
                <div class="pric-tag">
                    <span>₹ {{ number_format(floatval($product->full_price) + floatval($product->shipping_charge), 2) }}</span>
                </div>
            </div>
            <div class="food-content" style="padding-top: 15px;">
                <h3>
                    <a>
                        {{ $product->product_name_online ?? $product->product_name_english }}
                        @if ($product->is_online_label_show)
                            - Full
                        @endif
                    </a>
                </h3>
            </div>

            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="plate_type" value="1">

            <div class="prod-quantity d-flex align-items-center" style="border-radius: 0 0 10px 10px; border: 1px solid #eee; justify-content: space-between;">
                <div class="quantity align-items-center">
                    <div class="quantity-nav nice-number d-flex align-items-center">
                        <input type="number" name="qty" value="1" min="1" class="qty-input" readonly>
                    </div>
                </div>
                <button type="submit" class="primary-btn3">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
            
        </form>
    </div>
</div>

@if (!empty($product->half_price))
    <div class="col-md-3 col-sm-3">
        <div class="food-items2-wrap prod-details-content">
            @if ($status == 'Closed')
                <div class="product-overlay">
                    <div class="closed-badge">Closed</div>
                </div>
            @endif
            <form action="{{ route('customer.cart.store') }}" method="POST" class="add-to-cart-form">
                @csrf
                <div class="food-img" style="height: 200px;">
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
                    <div class="pric-tag">
                        <span>₹ {{ number_format(floatval($product->half_price) + floatval($product->shipping_charge), 2) }}</span>
                    </div>
                </div>
                <div class="food-content" style="padding-top: 15px;">
                    <h3>
                        <a>
                            {{ $product->product_name_online ?? $product->product_name_english }}
                            @if ($product->is_online_label_show)
                                - Half
                            @endif
                        </a>
                    </h3>
                </div>

                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="plate_type" value="2">

                <div class="prod-quantity d-flex align-items-center" style="border-radius: 0 0 10px 10px; border: 1px solid #eee; justify-content: space-between;">
                    <div class="quantity align-items-center">
                        <div class="quantity-nav nice-number d-flex align-items-center">
                            <input type="number" name="qty" value="1" min="1" class="qty-input" readonly>
                        </div>
                    </div>
                    <button type="submit" class="primary-btn3">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif