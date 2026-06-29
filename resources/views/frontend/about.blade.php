@extends('frontend.layout.layout')
@section('title', $title)

@section('content')


@include('frontend.layout.breadcrumb')


<style>
.home3-testimonial .swiper-wrapper {
    align-items: stretch;
}

.home3-testimonial .swiper-slide {
    height: auto;
    display: flex;
    overflow: visible;
    padding-bottom: 2px;
}

.home3-testimonial .testimonial-wrap {
    width: 100%;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-sizing: border-box;
}
</style>


<div class="about-introduction-area mt-10 mb-20">
    <div class="container-lg container-fluid">
        <div class="row "> </div>
    </div>
    <div class="container-fluid">
        <div class="row gy-5 margin-left">
            <div class="col-lg-12">
                <div class="intro-right-img1"> 
                        <img class="img-fluid" src="{{ Storage::url($websetting->left_image) }}" alt="h1-intro-left-img">
                    <div class="intro-sm-img magnetic-wrap"> 
                        <img class="img-fluid magnetic-item" src="{{ Storage::url($websetting->right_image) }}" alt="h1-intro-right-img"> 
                    </div>
                </div>
                <div class="about-left">
                    <div class="section-title mb-20"> <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Introduction of {{ env('APP_NAME') }}<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>We Are Experienced Hotel</h2> 
                    </div>
                    <!--<div class="description">
						<p>{{ $websetting->about_content }}</p>
					</div>-->
					<div class="our-mission">
                        <div class="description">
                            {!! $websetting->about_content !!}
                        </div>
                    </div>
                    <div class="intro-right">
                        <div class="features-author">
                            <div class="author-area">
                                <div class="author-content">
                                    <p>{{ $websetting->owner_about }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($testimonials->isNotEmpty())
	<div class="home3-testimonial mb-20">
        <div class="container">
            <div class="row justify-content-center mb-10">
                <div class="col-lg-8">
                    <div class="section-title3 text-center">
                        <span> <img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/h3-sub-title-vec.svg" alt />Testimonials<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/h3-sub-title-vec.svg" alt /></span>
                        <h2>Customer Feedback</h2>
                    </div>
                </div>
            </div>
            <div class="row mb-10">
                <div class="swiper home3-testimonial-slider">
                    <div class="swiper-wrapper">
                        @foreach ($testimonials as $testimonial)
                            <div class="swiper-slide">
                            <div class="testimonial-wrap">
                                <div class="testimonial-content">
                                    <!-- <div class="quoat-icon">
                                        <img src="{{ asset('assests/frontend') }}/images/icon/Comma.svg" alt="Comma" />
                                    </div> -->
                                    @php
										$fullText = $testimonial->description;
										$shortText = \Illuminate\Support\Str::limit($fullText, 100, '...');
									@endphp
                                    <!-- <p>{{ $testimonial->description }}</p> -->
                                     <p>
										<span id="short-{{ $testimonial->id }}">{{ $shortText }}</span>
										@if(strlen($fullText) > 100)
											<span id="full-{{ $testimonial->id }}" style="display:none;">
												{{ $fullText }}
											</span>
											<a href="javascript:void(0)" id="toggle-{{ $testimonial->id }}"conclick="toggleTestimonial({{ $testimonial->id }})">Read More</a>
										@endif
									</p>
                                </div>
                                <div class="testi-author-area">
                                    <div class="author-img">
                                        @if (!empty($testimonial->thumbnail))
											<img src="{{ Storage::url($testimonial->thumbnail) }}" alt="{{ $testimonial->title }}" width="74px" height="74px;"> 
										@else
											<img src="{{ Helper::adminProfile() }}" alt="{{ $testimonial->title }}" width="74px" height="74px;"> 
										@endif
                                    </div>
                                    <div class="name-review">
                                        <h5>{{ $testimonial->title }}</h5>
                                        @if (intval($testimonial->rating) > 0)
                                            <ul>
                                                @for ($i=1; $i<= intval($testimonial->rating); $i++)
                                                    <li><img src="{{ asset('assests/frontend') }}/images/icon/Star2.svg" alt="Star2" /></li>
                                            	@endfor
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider-btn">
                        <div class="prev-btn-4">
                            <i class="bi bi-arrow-left-short"></i>
                        </div>
                        <div class="next-btn-4">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($gallerys->isNotEmpty())
    <div class="food-gallery-area mb-20">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center"> <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Gallery<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>{{ env('APP_NAME') }}’s Gallery</h2> </div>
                </div>
            </div>
            <div class="row">
                <div class="swiper gallery-slider1">
                    <div class="swiper-wrapper">

                        @foreach ($gallerys as $gallery)
                            <div class="swiper-slide">
                                <a href="{{ Storage::url($gallery->thumbnail) }}" data-fancybox="gallery" class="gallery2-img">
                                    <div class="gallery-wrap"> 
                                        <img class="img-fluid" src="{{ Storage::url($gallery->thumbnail) }}" alt>
                                        <div class="overlay d-flex align-items-center justify-content-center">
                                            <div class="items-content text-center"> 
                                                <h3>{{ $gallery->tag }}</h3> 
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@push('script')
    <script>

        function toggleTestimonial(id) {
            const shortText = document.getElementById('short-' + id);
            const fullText = document.getElementById('full-' + id);
            const toggleBtn = document.getElementById('toggle-' + id);

            if (fullText.style.display === 'none') {
                shortText.style.display = 'none';
                fullText.style.display = 'inline';
                toggleBtn.innerText = 'Read Less';
            } else {
                shortText.style.display = 'inline';
                fullText.style.display = 'none';
                toggleBtn.innerText = 'Read More';
            }
        }
    </script>
@endpush

@endsection