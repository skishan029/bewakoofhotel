@extends('frontend.layout.layout')
@section('title', $title)
@section('content')

@include('frontend.layout.breadcrumb')

@if ($frequentlyAskedQuestions->isNotEmpty())
<div class="faq-area mt-10">
    <div class="container-fluid">
        <div class="row g-lg-5 gy-5">
            <div class="col-lg-5">
                <div class="faq-left-img">
                    <img class="img-fluid" src="{{ Storage::url($websetting->frequent_image_1) }}" alt="faq-big-img" />
                    <div class="sm-img">
                        <img class="img-fluid" src="{{ Storage::url($websetting->frequent_image_2) }}" alt="faq-sm-img" />
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="section-title">
                    <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec" />General Question<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec" /></span>
                    <h2>Some Frequently Question</h2>
                    <div class="accordion" id="accordionExample">
                        @foreach ($frequentlyAskedQuestions as $frequentlyAskedQuestion)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_{{ $frequentlyAskedQuestion->id }}">
                                    <button class="accordion-button {{ ($loop->first) ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $frequentlyAskedQuestion->id }}" aria-expanded="{{ ($loop->first) ? 'true' : 'false' }}" aria-controls="collapse_{{ $frequentlyAskedQuestion->id }}">
                                       {{ $frequentlyAskedQuestion->question }}
                                    </button>
                                </h2>
                                <div id="collapse_{{ $frequentlyAskedQuestion->id }}" class="accordion-collapse collapse {{ ($loop->first) ? 'show' : '' }}" aria-labelledby="heading_{{ $frequentlyAskedQuestion->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">{{ $frequentlyAskedQuestion->answer }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif  

@endsection