@extends('frontend.layout.layout')

@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')
    <div class="Shop-pages pt-40 mb-40">
        <div class="container">
            <div class="row g-lg-5 gy-5">
                <div class="col-lg-12">
                    <div class="widget-area">
                        <div class="single-widgets widget_search">
                            <form action="" method="get">
                                <input type="hidden" name="category" value="{{ request('category')  }}">
                                <div class="wp-block-search__inside-wrapper ">
                                    <input type="search" id="wp-block-search__input-1" class="wp-block-search__input" name="search" value="{{ request('search') }}" placeholder="Search Category..." aria-label="Search">
                                    <button type="submit" class="wp-block-search__button">
                                        <svg width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.10227 0.0713005C1.983 0.760967 -1.22002 5.91264 0.44166 10.7773C1.13596 12.8 2.60323 14.471 4.55652 15.4476C6.38483 16.3595 8.59269 16.5354 10.5737 15.9151C11.4023 15.6559 12.6011 15.0218 13.2121 14.5126L13.3509 14.3969L16.1281 17.1695C19.1413 20.1735 18.9932 20.0531 19.4237 19.9698C19.6505 19.9281 19.9282 19.6504 19.9699 19.4236C20.0532 18.9932 20.1735 19.1413 17.1695 16.128L14.397 13.3509L14.5127 13.212C14.7858 12.8834 15.2394 12.152 15.4755 11.6614C17.0029 8.48153 16.3271 4.74159 13.7814 2.28379C11.9994 0.561935 9.52304 -0.257332 7.10227 0.0713005ZM9.38418 1.59412C11.0135 1.9135 12.4669 2.82534 13.4666 4.15376C14.0591 4.94062 14.4572 5.82469 14.6793 6.83836C14.8136 7.44471 14.8228 8.75925 14.7025 9.34708C14.3507 11.055 13.4713 12.4622 12.1336 13.4666C11.3467 14.059 10.4627 14.4571 9.44898 14.6793C8.80097 14.8228 7.48644 14.8228 6.83843 14.6793C4.78332 14.2303 3.0985 12.9389 2.20054 11.1337C1.75156 10.2312 1.54328 9.43503 1.49699 8.4445C1.36276 5.62566 3.01055 3.05677 5.6535 1.96904C6.10248 1.7839 6.8014 1.59412 7.28741 1.52932C7.74102 1.46452 8.92595 1.50155 9.38418 1.59412Z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row g-4">
                        @forelse($categories as $category)
                        <div class="col-md-3 col-sm-3">
                            <div class="food-items2-wrap">
                                @if ($category->cat_thumbnail)
                                    <a href="{{ route('customer.product.index', ['category' => $category->id]) }}">
                                        <div class="food-img">
                                            <img class="img-fluid" src="{{ Storage::url($category->cat_thumbnail) }}" alt="h2-food-item-1">
                                            <!-- <div class="cart-icon">
                                                <a href="cart.html"><i class="bi bi-cart-plus"></i></a>
                                            </div> -->
                                        </div>
                                    </a>
                                @else
                                    <div style="width: 100%; height: 100%; background: #334155; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-utensils" style="font-size: 3rem; color: #475569;"></i>
                                    </div>
                                @endif
                                <div class="food-content" style="border-bottom: 1px solid #eee;">
                                    <h3><a href="{{ route('customer.product.index', ['category' => $category->id]) }}">{{ $category->cat_title }}</a></h3>
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
                    <!-- <div class="row pt-60">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <div class="paginations-area">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link" href="#"><i class="bi bi-arrow-left"></i></a></li>
                                        <li class="page-item"><a class="page-link" href="#">01</a></li>
                                        <li class="page-item"><a class="page-link" href="#">02</a></li>
                                        <li class="page-item"><a class="page-link" href="#">03</a></li>
                                        <li class="page-item"><a class="page-link" href="#"><i class="bi bi-arrow-right"></i></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection