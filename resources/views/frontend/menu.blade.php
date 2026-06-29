@extends('frontend.layout.layout')
@section('title', $title)
@section('content')

@include('frontend.layout.breadcrumb')

@if ($allProducts->isNotEmpty())
    <div class="menu-list-area1 mb-20 mt-40"">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center"> <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Menu List<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Our Menu List</h2> </div>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($allProducts->splitIn(2) as $chunk)
                    <div class="col-lg-6">
                        <div class="menu-wrapper1"> 
                            <img class="menu-top-left" src="{{ asset('assests/frontend') }}/images/icon/menu-top-left.svg" alt="menu-top-left"> 
                            <img class="menu-top-right" src="{{ asset('assests/frontend') }}/images/icon/menu-top-right.svg" alt="menu-top-right"> 
                            <img class="menu-btm-right" src="{{ asset('assests/frontend') }}/images/icon/menu-btm-right.svg" alt="menu-btm-right"> 
                            <img class="menu-btm-left" src="{{ asset('assests/frontend') }}/images/icon/menu-btm-left.svg" alt="menu-btm-left">
                            <div class="section-title text-center pt-40"> <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome to {{ env('APP_NAME') }}<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                                <h2>Indian Menu</h2> </div>
                            <div class="menu-list">
                                <ul>
                                    <li>
                                        <div class="menu-content">
                                            <div class="menu-title">
                                                <h4 style="width: 60%;">Item</h4> 
                                                <span class="price">Half</span>
                                                <span class="price">Full</span>
                                            </div>
                                            @if (!empty($product->product_desc))
                                                <p>{{ $product->product_desc }}</p>
                                            @endif
                                            
                                        </div>
                                    </li>
                                    @foreach ($chunk as $product)
                                    <li>
                                        <div class="menu-content">
                                            <div class="menu-title">
                                                <h4 style="width: 60%;">{{ $product->product_name_english }}</h4> 
                                                <span class="price">
                                                    @if (!empty($product->half_price))
                                                        &#8377;{{ number_format($product->half_price, '2', '.', '') }}
                                                    @endif
                                                </span>
                                                <span class="price">&#8377;{{ number_format($product->full_price, '2', '.', '') }}</span>
                                                
                                            </div>
                                            @if (!empty($product->product_desc))
                                                <p>{{ $product->product_desc }}</p>
                                            @endif
                                            
                                        </div>
                                    </li>
                                    @endforeach
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endif

@endsection