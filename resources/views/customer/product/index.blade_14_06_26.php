@extends('customer.layout.app')

@section('title', 'Our Menu')

@section('content')

    @push('css')
        <style>
            .product-card {
                position: relative;
                overflow: hidden;
                /* Ensure overlay stays inside */
            }

            .product-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(4px);
                z-index: 10;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                border-radius: inherit;
                /* Match card border radius */
            }

            .closed-badge {
                padding: 8px 16px;
                background: rgba(220, 38, 38, 0.9);
                border-radius: 4px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            }

            .qty-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                background: #1e293b;
                padding: 6px 10px;
                border-radius: 50px;
                width: fit-content;
                margin-bottom: 12px;
            }

            .qty-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: none;
                background: #334155;
                color: #fff;
                font-size: 14px;
                cursor: pointer;
                transition: 0.2s ease;
            }

            .qty-btn:hover {
                background: #3b82f6;
            }

            .qty-input {
                width: 50px;
                text-align: center;
                border: none;
                background: transparent;
                color: #fff;
                font-weight: 600;
                font-size: 14px;
            }

            .qty-input:focus {
                outline: none;
            }



            .custom-search-group {
                display: flex;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                overflow: hidden;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.1);
                box-shadow: 0 0 20px rgba(111, 66, 193, 0.15);
                transition: 0.3s ease-in-out;
            }

            /* On focus glow */
            .custom-search-group:focus-within {
                border-color: #7b5cff;
                box-shadow: 0 0 25px rgba(123, 92, 255, 0.6);
            }

            /* Input */
            .custom-search-input {
                background: transparent;
                border: none;
                color: #fff;
                padding: 14px 18px;
                font-size: 15px;
            }

            .custom-search-input::placeholder {
                color: rgba(255,255,255,0.5);
            }

            .custom-search-input:focus {
                outline: none;
                box-shadow: none;
            }

            /* Button */
            .custom-search-btn {
                background: linear-gradient(45deg, #7b5cff, #5f27cd);
                border: none;
                padding: 0 25px;
                color: #fff;
                font-weight: 500;
                transition: 0.3s;
            }

            .custom-search-btn:hover {
                opacity: 0.9;
            }
        </style>
    @endpush

    <div class="product-filters" style="margin-bottom: 2rem;">
        <!-- Future: Category filters can go here -->
    </div>

    @php
        $status = \App\Helper\Helper::getRestaurantStatus();
        $status = 'Opend';
    @endphp

    <form action="" method="get">
        <input type="hidden" name="category" value="{{ request('category')  }}">
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="custom-search-group">
                    <input type="text" 
                        value="{{ request('search') }}"
                        name="search"
                        class="form-control custom-search-input" 
                        placeholder="Search food," aria-label="Search">
                    <button class="custom-search-btn" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        @forelse($products as $product)
            @if ($product->is_split)
                @include('customer.product.splitproduct', ['product' => $product, 'status' => $status])
            @else
                @include('customer.product.nonsplitproduct', ['product' => $product, 'status' => $status])
            @endif
        @empty
            <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
                <i class="fa-solid fa-plate-wheat"
                    style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h3>No products found right now.</h3>
                <p>Please check back later!</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 2rem;">
        {{ $products->links() }}
    </div>

    @push('script')
        <script>
            function increaseQty(btn) {
                let input = btn.parentNode.querySelector('input[name="qty"]');
                input.value = parseInt(input.value) + 1;
            }

            function decreaseQty(btn) {
                let input = btn.parentNode.querySelector('input[name="qty"]');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            }
        </script>
    @endpush

@endsection
