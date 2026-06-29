@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Setting')

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="post" id="setting_form" data-parsley-validate>
                        <div class="{{ Helper::adminCardClass() }}">
                            <div class="card-header">
                                <h5 class="m-0">{{ $title }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="notification_email">Notification Email <strong
                                                    class="text-danger">*</strong></label>
                                            <input id="notification_email" class="form-control rounded-0" type="email"
                                                name="notification_email"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->notification_email }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input id="email" class="form-control rounded-0" type="text"
                                                name="email"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->email }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="contact_one">Contact 1</label>
                                            <input id="contact_one" class="form-control rounded-0" type="text"
                                                name="contact_one"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->contact_one }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="contact_two">Contact 2</label>
                                            <input id="contact_two" class="form-control rounded-0" type="text"
                                                name="contact_two"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->contact_two }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="banner_heading">Banner Heading</label>
                                            <input id="banner_heading" class="form-control rounded-0" type="text"
                                                name="banner_heading"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->banner_heading }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input id="address" class="form-control rounded-0" type="text"
                                                name="address"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->address }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="facebook">Facebook</label>
                                            <input id="facebook" class="form-control rounded-0" type="text"
                                                name="facebook"
                                                value="{{ blank($panelsettings) ? '#' : $panelsettings->facebook }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="youtube">YouTube</label>
                                            <input id="youtube" class="form-control rounded-0" type="text"
                                                name="youtube"
                                                value="{{ blank($panelsettings) ? '#' : $panelsettings->youtube }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="instagram">Instagram</label>
                                            <input id="instagram" class="form-control rounded-0" type="text"
                                                name="instagram"
                                                value="{{ blank($panelsettings) ? '#' : $panelsettings->instagram }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="twitter">Twitter</label>
                                            <input id="twitter" class="form-control rounded-0" type="text"
                                                name="twitter"
                                                value="{{ blank($panelsettings) ? '#' : $panelsettings->twitter }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="established_year">Established Year</label>
                                            <input id="established_year" class="form-control rounded-0" type="text"
                                                name="established_year"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->established_year }}">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="restaurant_open_time">Open Time <strong
                                                    class="text-danger">*</strong></label>
                                            <input id="restaurant_open_time" class="form-control rounded-0"
                                                type="time" name="restaurant_open_time"
                                                value="{{ blank($panelsettings) ? '09:00' : $panelsettings->restaurant_open_time }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="restaurant_close_time">Close Time <strong
                                                    class="text-danger">*</strong></label>
                                            <input id="restaurant_close_time" class="form-control rounded-0"
                                                type="time" name="restaurant_close_time"
                                                value="{{ blank($panelsettings) ? '22:00' : $panelsettings->restaurant_close_time }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="is_restaurant_open">Restaurant Status <strong
                                                    class="text-danger">*</strong></label>
                                            <select name="is_restaurant_open" id="is_restaurant_open"
                                                class="form-control rounded-0" required>
                                                <option value="1"
                                                    {{ !blank($panelsettings) && $panelsettings->is_restaurant_open == 1 ? 'selected' : '' }}>
                                                    Open</option>
                                                <option value="0"
                                                    {{ !blank($panelsettings) && $panelsettings->is_restaurant_open == 0 ? 'selected' : '' }}>
                                                    Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="about_content">About Us</label>
                                            <textarea id="about_content" class="form-control rounded-0" name="about_content" rows="4">{{ blank($panelsettings) ? '' : $panelsettings->about_content }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="owner_name">Owner Name</label>
                                            <input id="owner_name" class="form-control rounded-0" type="text"
                                                name="owner_name"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->owner_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="owner_about">Owner About</label>
                                            <input id="owner_about" class="form-control rounded-0" type="text"
                                                name="owner_about"
                                                value="{{ blank($panelsettings) ? '' : $panelsettings->owner_about }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qrcode">Company Logo <small>(W*H 185px*40px and max size
                                                    2MB)</small></label>
                                            <div class="input-group">
                                                <input id="company_logo" class="form-control rounded-0" type="file"
                                                    name="company_logo" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="company_logo_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->company_logo))
                                                            <a href="{{ Storage::url($panelsettings->company_logo) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qrcode">Favicon Logo <small>(W*H 20px*20px and max size
                                                    2MB)</small></label>
                                            <div class="input-group">
                                                <input id="favicon_logo" class="form-control rounded-0" type="file"
                                                    name="favicon_logo" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="favicon_logo_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->favicon_logo))
                                                            <a href="{{ Storage::url($panelsettings->favicon_logo) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qrcode">Left Image <small>(W*H 450px*440px and max size
                                                    2MB)</small></label>
                                            <div class="input-group">
                                                <input id="left_image" class="form-control rounded-0" type="file"
                                                    name="left_image" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="left_image_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->left_image))
                                                            <a href="{{ Storage::url($panelsettings->left_image) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qrcode">Right Image <small>(W*H 335px*325px and max size
                                                    2MB)</small></label>
                                            <div class="input-group">
                                                <input id="right_image" class="form-control rounded-0" type="file"
                                                    name="right_image" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="right_image_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->right_image))
                                                            <a href="{{ Storage::url($panelsettings->right_image) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_image">User Image1 <small>(W*H 64px*64px and max size 2MB)</small></label>
                                        <div class="input-group">
                                            <input id="user_image" class="form-control rounded-0" type="file" name="user_image" accept=".png,.jpeg,.jpg,.webp">
                                            <div class="input-group-append" id="user_image_div">
                                                @if (!empty($panelsettings))
                                                    @if (!empty($panelsettings->user_image))
                                                        <a href="{{ Storage::url($panelsettings->user_image) }}" target="_blank" class="btn btn-primary">View</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                    {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_image">User Image 2 <small>(W*H 64px*64px and max size 2MB)</small></label>
                                        <div class="input-group">
                                            <input id="user_image2" class="form-control rounded-0" type="file" name="user_image2" accept=".png,.jpeg,.jpg,.webp">
                                            <div class="input-group-append" id="user_image_div">
                                                @if (!empty($panelsettings))
                                                    @if (!empty($panelsettings->user_image2))
                                                        <a href="{{ Storage::url($panelsettings->user_image2) }}" target="_blank" class="btn btn-primary">View</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="frequent_image_1">Frequently Image one <small>(W*H 490px*542px and
                                                    max size 2MB)</small></label>
                                            <div class="input-group">
                                                <input id="frequent_image_1" class="form-control rounded-0"
                                                    type="file" name="frequent_image_1"
                                                    accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="frequent_image_1_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->frequent_image_1))
                                                            <a href="{{ Storage::url($panelsettings->frequent_image_1) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="frequent_image_2">Frequently Image two <small>(W*H 512px*281px and
                                                    max size 2MB)</small></label>
                                            <div class="input-group">
                                                <input id="frequent_image_2" class="form-control rounded-0"
                                                    type="file" name="frequent_image_2"
                                                    accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="frequent_image_2_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->frequent_image_2))
                                                            <a href="{{ Storage::url($panelsettings->frequent_image_2) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="banner_image">Banner Image <small>(W*H 1920px*696px and max size
                                                    2MB)</small></label>
                                            <div class="input-group">
                                                <input id="banner_image" class="form-control rounded-0" type="file"
                                                    name="banner_image" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="banner_image">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->banner_image))
                                                            <a href="{{ Storage::url($panelsettings->banner_image) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pop_back_image">Popular Back Image <small>(W*H 1920px*917px and max
                                                    size 2MB)</small></label>
                                            <div class="input-group">
                                                <input id="pop_back_image" class="form-control rounded-0" type="file"
                                                    name="pop_back_image" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="pop_back_image_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->pop_back_image))
                                                            <a href="{{ Storage::url($panelsettings->pop_back_image) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="footer_back_image">Footer Back Image <small>(W*H 1920px*558px and
                                                    max size 2MB)</small></label>
                                            <div class="input-group">
                                                <input id="footer_back_image" class="form-control rounded-0"
                                                    type="file" name="footer_back_image"
                                                    accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="footer_back_image_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->footer_back_image))
                                                            <a href="{{ Storage::url($panelsettings->footer_back_image) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="testimonial_back_image">Testimonial Background Image <small>(W*H
                                                    1920px*558px and max size 2MB)</small></label>
                                            <div class="input-group">
                                                <input id="testimonial_back_image" class="form-control rounded-0"
                                                    type="file" name="testimonial_back_image"
                                                    accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="testimonial_back_image_div">
                                                    @if (!empty($panelsettings))
                                                        @if (!empty($panelsettings->testimonial_back_image))
                                                            <a href="{{ Storage::url($panelsettings->testimonial_back_image) }}"
                                                                target="_blank" class="btn btn-primary">View</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
                                @props(['row' => '2'])
                                <x-submitbutton :row="$row" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('assests/ckeditor/ckeditor.js') }}"></script>

        <script>
            $(function() {
                CKEDITOR.replace('about_content');
                $('#setting_form').submit(function(e) {
                    e.preventDefault();
                    if ($('#setting_form').parsley().isValid()) {

                        about_content = CKEDITOR.instances['about_content'].getData();
                        formData = new FormData($('#setting_form')[0]);
                        formData.append('about_content', about_content);
                        const formID = 'setting_form';
                        $.ajax({
                            type: "POST",
                            url: "{{ $submitURL }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            cache: false,
                            beforeSend: function() {
                                setProcessingButton(formID);
                            },
                            success: function(response) {
                                if (response.type == 'success') {
                                    alert(response.message);
                                    window.location.reload();
                                } else if (response.type == 'error') {
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
