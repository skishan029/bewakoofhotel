<div class="breadcrumb-section" style="background-image: url(' {{ Storage::url($websetting->banner_image) }}')">
    <div class="breadcrumb-left-vec"> <img src="{{ asset('assests/frontend') }}/images/icon/breadcumb-left-vec.svg" alt="breadcumb-left-vec"> </div>
    <div class="breadcrumb-right-vec"> <img src="{{ asset('assests/frontend') }}/images/icon/breadcumb-right-vec.svg" alt="breadcumb-right-vec"> </div>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-12">
                <h2 class="breadcrumb-title">{{ $title }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb d-flex">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>