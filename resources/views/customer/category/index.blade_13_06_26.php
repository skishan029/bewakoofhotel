@extends('customer.layout.app')

@section('title', 'Categories')

@section('content')
    <div class="row category-grid">
        @forelse($categories as $category)
            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                <div class="category-card">
                    <div class="category-image">
                        @if ($category->cat_thumbnail)
                            <img src="{{ Storage::url($category->cat_thumbnail) }}" alt="{{ $category->cat_title }}">
                        @else
                            <div
                                style="width: 100%; height: 100%; background: #334155; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-utensils" style="font-size: 3rem; color: #475569;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="category-info">
                        <h3>{{ $category->cat_title }}</h3>
                        @if ($category->cat_desc)
                            {{-- <p>{{ Str::limit($category->cat_desc, 50) }}</p> --}}
                        @endif
                        <a href="{{ route('customer.product.index', ['category' => $category->id]) }}" class="view-btn">View
                            Products</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-data">
                <i class="fa-solid fa-plate-wheat"
                    style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p>No categories found.</p>
            </div>
        @endforelse
    </div>

    @push('css')
        <style>
            .category-grid {
                /* display: grid; */
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                /* gap: 20px; */
                margin-top: 20px;
            }

            .category-card {
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease;
                background: var(--glass);
            }

            .category-card:hover {
                transform: translateY(-5px);
            }

            .category-image {
                height: 150px;
                overflow: hidden;
            }

            .category-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .category-info {
                padding: 15px;
                text-align: center;
            }

            .category-info h3 {
                margin: 0 0 10px;
                font-size: 1.1rem;
                color: #fff;
            }

            .category-info p {
                font-size: 0.9rem;
                color: #fff;
                margin-bottom: 15px;
            }

            .view-btn {
                display: inline-block;
                padding: 8px 15px;
                background-color: var(--primary-color);
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-size: 0.9rem;
                transition: background-color 0.3s;
            }

            .view-btn:hover {
                background-color: var(--primary-dark);
            }

            .no-data {
                grid-column: 1 / -1;
                text-align: center;
                padding: 40px;
                background: var(--glass);
                border-radius: 10px;
                color: #fff;
            }
        </style>
    @endpush
@endsection
