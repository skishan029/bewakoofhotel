@extends('frontend.layout.layout')
@section('title', $title)
@section('content')

@include('frontend.layout.breadcrumb')

<div class="contact-page pt-40">
    <div class="container-fluid">
        <div class="row justify-content-center g-4">
            <div class="col-xxl-6 col-xl-4 col-lg-5 col-md-6 col-sm-8">
                <div class="contact-wrap">
                    <div class="contact-img"> @if(!empty($contactusImage))<img src="{{ Storage::url($contactusImage->front_image) }}" alt="contact-img-01"> @endif </div>
                    <div class="contact-content">
                        <h3>Contact Information</h3>
                        <ul>
                            <li>
                                <div class="icon"> <img src="{{ asset('assests/frontend') }}/images/icon/location.svg" alt="location"> </div>
                                <div class="content"> <a>{{ $websetting->address }}</a> </div>
                            </li>
                            <li>
                                <div class="icon"> <img src="{{ asset('assests/frontend') }}/images/icon/phone.svg" alt="phone"> </div>
                                <div class="content"> <a href="tel:+91{{ $websetting->contact_one }}">{{ $websetting->contact_one }}</a> </div>
                            </li>
                            <li>
                                <div class="icon"> 
                                    <img src="{{ asset('assests/frontend') }}/images/icon/envlope.svg" alt="envlope"> 
                                </div>
                                <div class="content"> 
                                    <a href="mailto:{{ $websetting->email }}">{{ $websetting->email }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="contact-form mb-20 mt-40" style="background-image: url('{{ Storage::url($contactusImage->background_image) }}')">
        <div class="row d-flex align-items-center justify-content-center mb-40">
            <div class="col-lg-8">
                <div class="section-title text-center"> <span><img class="left-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Contact Us<img class="right-vec" src="{{ asset('assests/frontend') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                    <h2>Get In Touch</h2> </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="" method="post" id="sendmessage_form" data-parsley-validate>
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6 mb-25">
                            <div class="form-inner">
                                <input type="text" placeholder="Name*" name="name" required> 
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-25">
                            <div class="form-inner">
                                <input type="email" placeholder="Email*" name="email" required> 
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <textarea placeholder="Message ..." name="message" required></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-inner">
                                <button type="submit" name="submit">Send Message</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--<div class="container">
    <div class="contact-map">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14599.578237069936!2d90.3654215!3d23.8223482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1668781325239!5m2!1sen!2sbd"
        style="border: 0;"
        allowfullscreen
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
    ></iframe>
</div>
</div>-->
@push('script')
    <script>
        $(function(){

            $('#sendmessage_form').submit(function (e) { 
                e.preventDefault();
                if ($('#sendmessage_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    const formID = 'sendmessage_form';

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
                                setSuccessButton(response, formID, 'Send Message');
                            }else if(response.type == 'error'){
                                resetButton(formID, 'Send Message');
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