@extends('customer.layout.app')

@section('title', 'My Profile')

@section('content')
    <div class="row" style="max-width: 800px; margin: 0 auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0;">Update Profile</h2>
            <a href="{{ route('customer.profile.changepassword') }}" class="action-btn" style="padding: 0.5rem 1.2rem; font-size: 0.9rem; text-decoration: none;">
                <i class="fa-solid fa-key me-1"></i> Change Password
            </a>
        </div>
        <div class="card mt-2">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Full Name <strong style="color: red;">*</strong></label>
                    <input type="text" name="name" id="name" class="form-control" required
                        value="{{ Auth::guard('customer')->user()->name }}">

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ Auth::guard('customer')->user()->email }}">

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mobile" class="form-label">Phone Number <strong style="color: red;">*</strong></label>
                    <input type="text" name="mobile" id="mobile" class="form-control" required
                        value="{{ Auth::guard('customer')->user()->username }}">

                    @error('mobile')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Delivery Address <strong style="color: red;">*</strong></label>
                    <textarea name="address" id="address" class="form-control" rows="4"
                        placeholder="Enter your full delivery address">{{ Auth::guard('customer')->user()->address ?? '' }}</textarea>

                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="landmark" class="form-label">Land Mark <strong style="color: red;">*</strong></label>
                    <textarea name="landmark" id="landmark" class="form-control" rows="4" placeholder="Enter your landmark">{{ Auth::guard('customer')->user()->landmark ?? '' }}</textarea>

                    @error('landmark')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                @php
                    $region_id = Auth::guard('customer')->user()->region_id;
                    $subregion_id = Auth::guard('customer')->user()->subregion_id;
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="region_id" class="form-label">Region <strong style="color: red;">*</strong></label>
                            <select name="region_id" id="region_id" class="form-select"
                                onchange="getSubRegions('region_id','subregion_id')" required>
                                <option value="">--Select Region</option>
                                @foreach ($parentRegions as $item)
                                    <option value="{{ $item->id }}" @selected($region_id == $item->id)>{{ $item->name }}</option>
                                @endforeach
                            </select>

                            @error('region_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subregion_id" class="form-label">Sub Region <strong
                                    style="color: red;">*</strong></label>
                            <select name="subregion_id" id="subregion_id" class="form-select" required>
                                <option value="">--Select Region</option>
                                @if ($subRegions->isNotEmpty())
                                    @foreach ($subRegions as $item)
                                        <option value="{{ $item->id }}" @selected($subregion_id == $item->id)>{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                            @error('subregion_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="action-btn" style="width: 100%; text-align: center;">Update Profile</button>
            </form>
        </div>
    </div>

    @push('script')
        @include('customer.layout.ajax.region-ajax')
    @endpush
@endsection
