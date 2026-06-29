@extends('frontend.layout.layout')
@section('title', $title)

@section('content')
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

	@if ($sliders->isNotEmpty())
		<div class="banner-section1">
			<div class="swiper banner1-slider">
				<div class="swiper-wrapper">

					@foreach ($sliders as $slider)
						<div class="swiper-slide">
							<div class="banner-wrapper d-flex align-items-center justify-content-between">
								<div class="banner-left-img"> 
									<img src="{{ asset('assests/frontend') }}/images/icon/union-left.svg" alt="union-left">
									<div class="food-img"> 
										<img class="img-fluid" src="{{ Storage::url($slider->thumbnail) }}" alt="banner-img-1"> 
									</div>
								</div>
								<div class="banner-content"> 
									<span>
										<img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome To {{ env('APP_NAME') }}
										<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">
									</span>
									<h1>
										@if (empty($slider->tag))
											{{ $websetting->banner_heading }}
										@else
											{{ $slider->tag }}
										@endif
									</h1>
									<a class="primary-btn2" href="{{ route('customer.category') }}"><i class="bi bi-arrow-up-right-circle"></i>Order Now</a>
								</div>
								<div class="banner-right-img"> 
									<img src="{{ asset('assests/frontend') }}/images/icon/union-right.svg" alt="union-right">
									<div class="food-img"> 
										<img class="img-fluid" src="{{ Storage::url($slider->thumbnail_two) }}" alt="banner-img-2"> 
									</div>
								</div>
							</div>
						</div>
					@endforeach
					
				</div>
				<div class="swiper-btn d-flex justify-content-between align-items-center">
					<div class="prev-btn-1"><i class="bi bi-chevron-left"></i></div>
					<div class="next-btn-1"><i class="bi bi-chevron-right"></i></div>
				</div>
			</div>
		</div>
	@endif
	
	@if ($foodItems->isNotEmpty())
	<div class="h2-product-area pt-40 pb-60">
        <div class="swiper h2-product-slider">
            <div class="swiper-wrapper">

				@foreach ($foodItems as $foodItem)
					<div class="swiper-slide">
						<div class="product-wrap">
							<div class="product-img">
								<img class="img-fluid" src="{{ Storage::url($foodItem->thumbnail) }}" alt="h2-product-1" />
							</div>
							<div class="product-content">
								<h4><a href="#">{{ $foodItem->title }}</a></h4>
								<p>{{ $foodItem->description }}</p>
							</div>
						</div>
					</div>
				@endforeach

            </div>
        </div>
    </div>
	@endif


	<div class="home2-food-items mb-20">
		<div class="container">
			<div class="row d-flex align-items-center justify-content-center g-3">
				<div class="col-lg-8">
					<div class="section-title">
						<span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Menu List<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
						<h2>Find Your Food Item</h2>
					</div>
				</div>
				<div class="col-lg-4 text-lg-end mb-sm-25">
					<a class="primary-btn5 btn-md2" href="{{ route('customer.category') }}"><i class="bi bi-arrow-up-right-circle"></i>View More</a>
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
		</div>
	</div>
	
	<div class="home1-introduction-area mt-10 mb-20">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="section-title"> <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Introduction of {{ env('APP_NAME') }}<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
						<h2>We Are Experienced Hotel</h2> 
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row gy-5">
				<div class="col-lg-4">
					<div class="into-left-img magnetic-wrap"> <img class="img-fluid magnetic-item" src="{{ Storage::url($websetting->left_image) }}" alt="h1-intro-left-img"> </div>
					{{-- {{ asset('assests/frontend') }}/images/bg/h1-intro-left-img.png --}}
				</div>
				<div class="col-lg-8">
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
						<div class="intro-right-img magnetic-wrap"> <img class="img-fluid magnetic-item" src="{{ Storage::url($websetting->right_image) }}" alt="h1-intro-right-img"> </div>
						{{-- <div class="intro-right-img magnetic-wrap"> <img class="img-fluid magnetic-item" src="{{ asset('assests/frontend') }}/images/bg/h1-intro-right-img.png" alt="h1-intro-right-img"> </div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
	@if ($videos->isNotEmpty())
		<div class="3-columns-menu-area mt-40 mb-20 chef-video-area">
			<div class="container">
			    <div class="row d-flex justify-content-center mb-15">
        			<div class="col-lg-8">
        				<div class="section-title text-center"> 
        				    <span>
        				        <img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Our Videos
        				        <img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">
        				    </span>
        					<h2>Our Videos</h2> 
        				</div>
        			</div>
        		</div>
				<div class="row justify-content-center g-4">
					@foreach ($videos as $video)
						<div class="col-lg-4 col-md-6 col-sm-6">
							{{-- <div class="video-img"> --}}
								{{-- <img class="img-fluid" src="{{ Storage::url($video->thumbnail) }}" alt="chef-dt-video-bg"> --}}
								<!-- <div class="video-content"> -->
									<div class="video-wrapper">
										{{-- <a class="gallery2-img" data-fancybox="gallery" href="{{$video->link}}"><i class="bi bi-play-circle"></i></a> --}}
										<iframe src="{{$video->link}}" title="Video" frameborder="0" allowfullscreen></iframe>
									</div>
								<!-- </div> -->
							{{-- </div> --}}
						</div>
					@endforeach
				</div>
			</div>
		</div>
	@endif

	@if ($foodItems->isNotEmpty())
		<div class="populer-food-area mb-20" style="background-image: linear-gradient(rgba(9,22,29,0.8),rgba(9,22,29,0.8)),url(' {{ Storage::url($websetting->pop_back_image) }}')">
			<div class="container">
				<div class="row justify-content-center mb-20">
					<div class="col-lg-8">
						<div class="section-title3 text-center">
							<h2>Popular Food Item</h2>
						</div>
					</div>
				</div>
				<div class="row justify-content-center position-relative">
					<div class="swiper h3-popular-food-slider">
						<div class="swiper-wrapper">
							@foreach ($foodItems as $foodItem)
								<div class="swiper-slide">
									<div class="h3-popular-food-card">
										<div class="food-img">
											<img class="img-fluid" src="{{ Storage::url($foodItem->thumbnail_two) }}" alt />
										</div>
										<div class="food-content">
											<div class="food-cetagory">
												<a>{{ $foodItem->title }}</a>
											</div>
											<p>{{ $foodItem->description }}</p>
										</div>
									</div>
								</div>
							@endforeach
							
							
						</div>
					</div>
					<div class="slider-btn">
						<div class="prev-btn-3">
							<i class="bi bi-arrow-left-short"></i>
						</div>
						<div class="next-btn-3">
							<i class="bi bi-arrow-right-short"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

    
	@if ($allProducts->isNotEmpty())
		{{--<div class="menu-list-area1 mb-20">
			<div class="container">
				<div class="row d-flex justify-content-center mb-15">
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
		</div>--}}
	@endif
	@if (!blank($offerDiscount))
	<div class="new-items1 mb-20">
        <div class="container">
            <div class="row d-flex justify-content-center mb-15">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec" />Best Offer<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec" /></span>
                        <h2>Choose Your Best Offer</h2>
                    </div>
                </div>
            </div>
            <div class="row mb-20 g-4 justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="new-items-wrap1 d-flex align-items-center justify-content-center">
                        <div class="items-content text-center">
                            <span>Special offer</span>
                            <h3><a href="">{{$offerDiscount->offer_text}}</a></h3>
                            <div class="descount-area text-center">
                                <h3>Discount</h3>
                                <span>{{$offerDiscount->percent}}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10 order-lg-2 order-2">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ Storage::url($offerDiscount->offer_image) }}" alt="new-items1" />
                                        <div class="price">
                                            <span>Discount - {{$offerDiscount->percent}}%</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="">Special offer</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>				
                <div class="col-lg-4 col-md-6 col-sm-10 order-lg-3 order-3 checkout-section best-offer-area1">                    
                    {{-- <div class="form-wrap box--shadow new-items-wrap1 mb-30">
                        <h4 class="title-25">Give us Feedback</h4>
                        <form action="" method="post" id="testimonialenquiry_form" data-parsley-validate>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-inner">
                                        <label>Your Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="title" id="title" placeholder="Your first name" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-inner">
                                        <label>Your Rating</label>
                                        <select style="display: none;" name="rating" id="rating">
                                            @for ($i=5; $i>= 1; $i--)
                                                <option value="{{ $i }}">{{ $i }} Star</option>
                                            @endfor
                                        </select>
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">Select Rating</span>
                                            <ul class="list">
                                                @for ($i=5; $i>= 1; $i--)
                                                    <li data-value="{{ $i }}" class="option selected">{{ $i }} Star</li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-inner">
                                        <label>Your Photo <small>(W*H 74px*74px & max-size 2MB)</small></label>
                                        <input type="file" name="thumbnail" id="thumbnail" accept=".png,.jpeg,.jpg" />
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-inner">
                                        <label>Your Feedback <strong class="text-danger">*</strong></label>
                                        <textarea name="description" id="description" placeholder="Please Give us Feedback" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-inner two">
                                        <button class="primary-btn btn-lg" type="submit">Submit</button>                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>					 --}}
					{{-- <div class="col-lg-6 col-md-6">
                        <div class="best-offer-wrap clearfix">
                            <div class="best-offer-img">
                                <img class="img-fluid" src="{{ asset('assests/frontend') }}/images/bg/best-offer-img1.png" alt="best-offer-img1" />
                                <div class="price-tag">
                                    <span>$55</span>
                                </div>
                            </div>
                            <div class="best-offer-content">
                                <h3>Buy One Get One Free</h3>
                                <p>If you are going to use a passage of Lorem Ipsum need.</p>
                                <a class="primary-btn3 btn-sm">Limited Offer</a>
                                <ol class="features">
                                    <li>Prawn with Noodls.</li>
                                    <li>Special Drinks.</li>
                                </ol>
                            </div>
                        </div>
                    </div> --}}
					<div class="new-items-wrap3">
						<div class="items-img">
							<img class="img-fluid" src="{{ Storage::url($offerDiscount->back_image) }}" alt="reserve1" />
						</div>
						<div class="overlay d-flex align-items-center justify-content-center">
							<div class="items-content text-center">
								<a class="primary-btn btn-sm" href="{{route('contact')}}">Contact Now</a>
							</div>
						</div>
					</div>

                </div>
            </div>
			<div class="contact-form mb-20 mt-40" style="background-image: url('{{ Storage::url($websetting->testimonial_back_image) }}')">
				<div class="row d-flex align-items-center justify-content-center mb-15">
					<div class="col-lg-8">
						<div class="section-title text-center"> 
						    <h2>Give us Feedback</h2> 
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-lg-10">
						<form action="" method="post" id="testimonialenquiry_form" data-parsley-validate>
							<div class="row justify-content-center">
								<div class="col-lg-6 col-md-6 mb-25">
									<div class="form-inner">
										<label for="title">Your Name <strong class="text-danger">*</strong></label>
										<input type="text" placeholder="Name*" id="title" name="title" required> 
									</div>
								</div>
								<div class="col-lg-6 col-md-6 mb-25">
									<div class="form-inner">
										<label for="thumbnail">Your Photo <small>(W*H 74px*74px & max-size 2MB)</small>
										</label>
										
										<input type="file" name="thumbnail" id="thumbnail" accept=".png,.jpeg,.jpg">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 mb-25">
									<div class="form-inner">
										<label for="rating">Your Rating </label>
										<select id="rating" name="rating" id="rating" class="time-select">
											@for ($i=5; $i>= 1; $i--)
												<option value="{{ $i }}">{{ $i }} Star</option>
											@endfor
										</select>
									</div>
								</div>
								<div class="col-lg-6 col-md-6">
									<div class="form-inner">
										<label for="description">Your Feedback <strong class="text-danger">*</strong></label>
										<textarea placeholder="Description ..." id="description" name="description" required></textarea>
									</div>
								</div>
								<div class="col-lg-6 col-md-6">
									<div class="form-inner">
										<button type="submit" name="submit">Submit</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>


        </div>
    </div>


	{{-- <div class="best-offer-area1 mb-120">
		<div class="container">
			<div class="row d-flex justify-content-center mb-15">
				<div class="col-lg-8">
					<div class="section-title text-center">
						<span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec" />Best Offer<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec" /></span>
						<h2>Choose Your Best Offer</h2>
					</div>
				</div>
			</div>
			<div class="row g-4">
				<div class="col-lg-6 col-md-6">
					<div class="best-offer-wrap clearfix">
						<div class="best-offer-img">
							<img class="img-fluid" src="{{ asset('assests/frontend') }}/images/bg/best-offer-img1.png" alt="best-offer-img1" />
							<div class="price-tag">
								<span>$55</span>
							</div>
						</div>
						<div class="best-offer-content">
							<h3>Buy One Get One Free</h3>
							<p>If you are going to use a passage of Lorem Ipsum need.</p>
							<a class="primary-btn3 btn-sm">Limited Offer</a>
							<ol class="features">
								<li>Prawn with Noodls.</li>
								<li>Special Drinks.</li>
							</ol>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="best-offer-wrap clearfix">
						<div class="best-offer-img">
							<img class="img-fluid" src="{{ asset('assests/frontend') }}/images/bg/best-offer-img2.png" alt="best-offer-img1" />
							<div class="price-tag">
								<span>$55</span>
							</div>
						</div>
						<div class="best-offer-content">
							<h3>Buy One Get One Free</h3>
							<p>If you are going to use a passage of Lorem Ipsum need.</p>
							<a class="primary-btn3 btn-sm">Limited Offer</a>
							<ol class="features">
								<li>Fried Chicken.</li>
								<li>Watermelon Juice.</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> --}}
	@endif
	

	@if ($testimonials->isNotEmpty())
	<div class="home3-testimonial mb-20">
        <div class="container">
            <div class="row justify-content-center mb-15">
                <div class="col-lg-8">
                    <div class="section-title3 text-center">
                        <span> <img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/h3-sub-title-vec.svg" alt />Testimonials<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/h3-sub-title-vec.svg" alt /></span>
                        <h2>Customer Feedback</h2>
                    </div>
                </div>
            </div>
            <div class="row mb-15">
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
				<div class="row d-flex justify-content-center mb-15">
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
			$(function(){

				$('#testimonialenquiry_form').submit(function (e) { 
					e.preventDefault();
					if ($('#testimonialenquiry_form').parsley().isValid()) {
						const formData = new FormData(this);
						const formID = 'testimonialenquiry_form';

						$.ajax({
							type: "POST",
							url: "{{ $submitURL }}",
							data: formData,
							processData: false,
							contentType : false,
							cache: false,
							beforeSend: function () {  
								setProcessingButton(formID);
							},
							success: function (response) {
								if (response.type == 'success') {
									setSuccessButton(response, formID, 'Submit');
								}else if(response.type == 'error'){
									resetButton(formID, 'Submit');
									setErrorMessage(response);
								}
							}
						});
					}
				});
				
			});

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

	