@extends('frontend.layout.layout')
@section('title', $title)
@section('content')

@include('frontend.layout.breadcrumb')


<div class="container">
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
                                <label for="thumbnail">Your photo <small>(W*H 74px*74px & max-size 2MB)</small>
                                </label>
                                {{-- <input type="email" placeholder="Email*" name="email" required>  --}}
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
                        <div class="col-lg-6 col-md-6 mb-25">
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
    </script>
@endpush
@endsection