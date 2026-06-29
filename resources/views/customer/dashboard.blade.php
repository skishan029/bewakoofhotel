@extends('customer.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-intro">
        <h2>Welcome back, {{ Auth::guard('customer')->user()->name ?? 'Guest' }}!</h2>
        <p style="color: var(--text-muted);">Hungry? Let's get you something delicious.</p>
        @php
            $status = \App\Helper\Helper::getRestaurantStatus();
            $statusColor = $status == 'Open' ? 'green' : 'red';
        @endphp
        <div
            style="margin-top: 15px; padding: 10px; border-radius: 8px; background-color: {{ $status == 'Open' ? '#e6fffa' : '#fff5f5' }}; border: 1px solid {{ $statusColor }}; color: {{ $statusColor }}; font-weight: bold;">
            Restaurant is currently {{ $status }}.
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-list"></i>
            </div>
            <h3>Categories</h3>
            <p>Browse food by categories.</p>
            <a href="{{ route('customer.category') }}" class="action-btn">View Categories</a>
        </div>

        {{-- <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-burger"></i>
            </div>
            <h3>Browse Menu</h3>
            <p>Explore our delicious variety of half and full plates.</p>
            <a href="{{ route('customer.product.index') }}" class="action-btn">Order Now</a>
        </div> --}}

        <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h3>Your Cart</h3>
            <p>You have {{ session('cart') ? count(session('cart')) : 0 }} items waiting for checkout.</p>
            <a href="{{ route('customer.cart.index') }}" class="action-btn">View Cart</a>
        </div>

        <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h3>Order History</h3>
            <p>Track your current orders and view past delicious meals.</p>
            <a href="{{ route('customer.order.index') }}" class="action-btn">View Orders</a>
        </div>

        <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <h3>My Profile</h3>
            <p>Update your personal details and delivery address.</p>
            <a href="{{ route('customer.profile.update') }}" class="action-btn">Manage Profile</a>
        </div>
    </div>
@endsection
