@extends('frontend.layout.layout')
@section('title', $title)

@section('content')

@include('frontend.layout.breadcrumb')


    <div class="container">
        <div class="reservation-2 checkout-section mb-20 mt-40">
            <div class="row d-flex align-items-center justify-content-center mb-15">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        @if (session('success'))
                            <span>
                                {{ session('success') }}
                            </span>
                        @endif
                        @if (session('error'))
                            <span class="alert alert-error">
                                {{ session('error') }}
                            </span>
                        @endif
                        <h2>Update Profile</h2>
                    </div>
                </div>
            </div>
            <div class="form-wrap row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" id="profile_update" autocomplete="off">
                    @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <input type="text" id="name" name="name" value="{{ Auth::guard('customer')->user()->name }}" placeholder="Full Name *" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <input type="email" id="email" name="email" value="{{ Auth::guard('customer')->user()->email }}" placeholder="Email *" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <input type="tel" id="mobile" name="mobile" value="{{ Auth::guard('customer')->user()->username }}" required maxlength="10" minlength="10" pattern="[6-9]{1}[0-9]{9}" inputmode="numeric" placeholder="Enter 10 digit mobile number *">
                                    @error('mobile')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <textarea name="address" id="address" rows="4" placeholder="Enter your full delivery address *" required>{{ Auth::guard('customer')->user()->address ?? '' }}</textarea>
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <textarea name="landmark" id="landmark" class="form-control" rows="4" placeholder="Enter your landmark *" required>{{ Auth::guard('customer')->user()->landmark ?? '' }}</textarea>
                                    @error('landmark')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            @php
                                $region_id = Auth::guard('customer')->user()->region_id;
                                $subregion_id = Auth::guard('customer')->user()->subregion_id;
                            @endphp
                            <div class="col-lg-6 col-md-6 mb-25">
                                <div class="form-inner">
                                    <select  name="region_id" id="region_id" onchange="getSubRegions('region_id','subregion_id')">
                                        <option value="">Select Region</option>
                                        @foreach ($parentRegions as $item)
                                            <option value="{{ $item->id }}" {{ $region_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="nice-select" tabindex="0" style="background: rgba(9, 22, 29, .8) !important;"><span class="current">{{ optional($parentRegions->firstWhere('id',$region_id))->name ?? 'Select Region *' }}</span>
                                        <ul class="list">
                                            @foreach ($parentRegions as $item)
                                                <li data-value="{{ $item->id }}" class="option {{ $region_id == $item->id ? 'selected' : '' }}">{{ $item->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('region_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <select name="subregion_id" id="subregion_id">
                                        @if ($subRegions->isNotEmpty())
                                            @foreach ($subRegions as $item)
                                                <option value="{{ $item->id }}" {{ $subregion_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="nice-select" tabindex="0" style="background: rgba(9, 22, 29, .8) !important;"><span class="current">{{ optional($subRegions->firstWhere('id',$subregion_id))->name ?? 'Select Sub Region *' }}</span>
                                        <ul class="list">
                                            @if ($subRegions->isNotEmpty())
                                                @foreach ($subRegions as $item)
                                                    <li data-value="{{ $item->id }}" class="option {{ $subregion_id == $item->id ? 'selected' : '' }}">{{ $item->name }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    @error('sub_region_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-inner">
                                    <button type="submit" name="submit">Update Profile</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="text-center login-link-area">
                                    <p class="mb-0">
                                        <a href="{{ route('customer.profile.changepassword') }}" class="login-link">
                                            Change Password
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        @include('customer.layout.ajax.region-ajax')
    @endpush

    @push('script')
        <script>
            $(document).ready(function () {

                $('#profile_update').on('submit', function(e) {

                    var region_id = $('#region_id').next('.nice-select').find('.option.selected').data('value');
                    var subregion_id = $('#subregion_id').next('.nice-select').find('.option.selected').data('value');
                
                    if (!region_id) {
                        e.preventDefault();
                        toastr.error('Please Select Region');
                        return false;
                    }

                    if (!subregion_id) {
                        e.preventDefault();
                        toastr.error('Please Select Sub Region');
                        return false;
                    }
                    // Optional: update actual select values before submit
                    $('#region_id').val(region_id);
                    $('#subregion_id').val(subregion_id);
                });

            });
        </script>
    @endpush
@endsection
