@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Product')

@section('content')

    @push('style')
        @include('admin.common.include.css.select2')
    @endpush

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="{{ Helper::adminCardClass() }}">
                        <div class="card-header">
                            <h5 class="m-0">{{ $title }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="product_form" data-parsley-validate
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_name">Product Name(Hindi)<strong
                                                    class="text-danger">*</strong></label>
                                            <input id="product_name" class="form-control rounded-0" type="text"
                                                name="product_name" required
                                                value="{{ empty($product) ? '' : $product->product_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_name_english">Product Name(English) <strong
                                                    class="text-danger">*</strong></label>
                                            <input id="product_name_english" class="form-control rounded-0" type="text"
                                                name="product_name_english" required
                                                value="{{ empty($product) ? '' : $product->product_name_english }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_name_online">Product Name(Online) <strong
                                                    class="text-danger">*</strong></label>
                                            <input id="product_name_online" class="form-control rounded-0" type="text"
                                                name="product_name_online" required
                                                value="{{ empty($product) ? '' : $product->product_name_online }}">
                                        </div>
                                    </div>

                                    @php
                                        $is_online_label_show = empty($product) ? '1' : $product->is_online_label_show;
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="is_online_label_show">Online Full-Half Label Show</label>
                                            <select id="is_online_label_show" class="form-control rounded-0"
                                                name="is_online_label_show">
                                                <option value="1" @selected($is_online_label_show == '1')>Yes</option>
                                                <option value="0" @selected($is_online_label_show == '0')>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="full_price">Full Price </label>
                                            <input type="text" name="full_price" id="full_price"
                                                class="form-control rounded-0" onclick="firstDeci()"
                                                onkeypress="return isNumberKey(this, event);" data-parsley-min="1"
                                                value="{{ empty($product) ? '' : $product->full_price }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="half_price">Half price</label>
                                            <input type="text" name="half_price" id="half_price"
                                                class="form-control rounded-0" onclick="firstDeci()"
                                                onkeypress="return isNumberKey(this, event);" data-parsley-min="1"
                                                value="{{ empty($product) ? '' : $product->half_price }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="shipping_charge">Shipping Charge</label>
                                            <input type="text" name="shipping_charge" id="shipping_charge"
                                                class="form-control rounded-0" onclick="firstDeci()"
                                                onkeypress="return isNumberKey(this, event);" data-parsley-min="1"
                                                value="{{ empty($product) ? '' : $product->shipping_charge }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="sku_code">SKU Code </label>
                                            <input type="text" name="sku_code" id="sku_code"
                                                class="form-control rounded-0"
                                                value="{{ empty($product) ? '' : $product->sku_code }}">
                                        </div>
                                    </div>

                                    @php
                                        $full_lbl_show = empty($product) ? '1' : $product->full_lbl_show;
                                    @endphp
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="full_lbl_show">Full Label Show</label>
                                            <select id="full_lbl_show" class="form-control rounded-0" name="full_lbl_show">
                                                <option value="1" @selected($full_lbl_show == '1')>Show</option>
                                                <option value="2" @selected($full_lbl_show == '2')>None</option>
                                            </select>
                                        </div>
                                    </div>

                                    @php
                                        $is_split = empty($product) ? '0' : $product->is_split;
                                    @endphp
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="is_split">Online Split</label>
                                            <select id="is_split" class="form-control rounded-0" name="is_split">
                                                <option value="1" @selected($is_split == '1')>Yes</option>
                                                <option value="0" @selected($is_split == '0')>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="ordering">Ordering <strong class="text-danger">*</strong></label>
                                            <input type="number" name="ordering" id="ordering"
                                                class="form-control rounded-0"
                                                value="{{ empty($product) ? '' : $product->ordering }}"
                                                onkeypress="return isNumber(event)" required>
                                        </div>
                                    </div>

                                    @php
                                        $is_active = empty($product) ? '1' : $product->is_active;
                                    @endphp
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="is_active">Active Status <strong class="text-danger">*</strong>
                                            </label>
                                            <select id="is_active" class="form-control rounded-0" name="is_active"
                                                required>
                                                <option value="1" @selected($is_active == '1')>Active</option>
                                                <option value="0" @selected($is_active == '0')>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="featured_photo">Full Photo <small>(only jpeg,png,jpeg,webp and max
                                                    size 2MB)</small></label>
                                            <div class="input-group mb-3">
                                                <input id="featured_photo" class="form-control rounded-0" type="file"
                                                    name="featured_photo" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="featured_photo_div">
                                                    @if (!empty($product))
                                                        @if (!empty($product->featured_photo))
                                                            <button type="button" class="btn btn-primary"
                                                                value="{{ Storage::url($product->featured_photo) }}"
                                                                onclick="viewFeaturedPhoto(this)">View</button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="half_photo">Half Photo <small>(only jpeg,png,jpeg,webp and max size
                                                    2MB)</small></label>
                                            <div class="input-group mb-3">
                                                <input id="half_photo" class="form-control rounded-0" type="file"
                                                    name="half_photo" accept=".png,.jpeg,.jpg,.webp">
                                                <div class="input-group-append" id="half_photo_div">
                                                    @if (!empty($product))
                                                        @if (!empty($product->half_photo))
                                                            <button type="button" class="btn btn-primary"
                                                                value="{{ Storage::url($product->half_photo) }}"
                                                                onclick="viewFeaturedPhoto(this)">View</button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $category_ids = empty($product)
                                            ? []
                                            : $product->categories->pluck('category_id')->toArray();
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id">Category <strong
                                                    class="text-danger">*</strong></label>
                                            <select id="category_id" class="form-control rounded-0" name="category_id[]"
                                                required data-parsley-errors-container="#category_error" multiple>
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @selected(in_array($category->id, $category_ids))>
                                                        {{ $category->cat_title }}</option>
                                                @endforeach
                                            </select>
                                            <div id="category_error"></div>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="product_desc">Product Description</label>
                                            <textarea id="product_desc" class="form-control rounded-0" name="product_desc" rows="1">{{ empty($product) ? '' : $product->product_desc }}</textarea>
                                        </div>
                                    </div>

                                </div>

                                @props(['row' => '2'])
                                <x-submitbutton :row="$row" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.common.include.defultmodel')

    @push('includescript')
        @include('admin.common.include.js.select2')
    @endpush

    @push('script')
        <script>
            $(function() {

                $('#category_id').select2({
                    placeholder: "Select Category",
                    allowClear: true,
                });

                $('#product_form').submit(function(e) {
                    e.preventDefault();
                    if ($('#product_form').parsley().isValid()) {

                        const formData = new FormData(this);
                        const formID = 'product_form';

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

                                    @if (empty($product))
                                        setSuccessButton(response, formID, 'Submit');
                                        $("#brand_id").val('').trigger('change');
                                        $("#category_id").val('').trigger('change');
                                        $("#sub_category_id").val('').trigger('change');
                                    @else
                                        setUpdateSuccessButton(response, formID, 'Submit');
                                        featured_photo = response.featured_photo;
                                        half_photo = response.half_photo;
                                        if (featured_photo != null) {
                                            htmlString =
                                                `<button type="button" class="btn btn-primary" value="${featured_photo}" onclick="viewFeaturedPhoto(this)">View</button>`;
                                            $('#featured_photo_div').html(htmlString);
                                        }
                                        if (half_photo != null) {
                                            htmlString =
                                                `<button type="button" class="btn btn-primary" value="${half_photo}" onclick="viewFeaturedPhoto(this)">View</button>`;
                                            $('#half_photo_div').html(htmlString);
                                        }

                                        $('#featured_photo').val('');
                                    @endif

                                } else if (response.type == 'error') {
                                    resetButton(formID, 'Submit');
                                    setErrorMessage(response);
                                }
                            }
                        });
                    }
                });
            });

            function viewFeaturedPhoto(evt) {
                path = evt.value;
                if (path !== '') {

                    $('#defaultmodal-size').removeClass();
                    $('#defaultmodal-size').addClass('modal-dialog');
                    $('#defaultmodal').modal('show');
                    htmlString =
                        `<img src="${path}" class="img-thumbnail" alt="no-image" style="height: 300px; width: 465px;" >`;
                    $('#defaultmodal-body').html(htmlString);
                }
            }
        </script>
    @endpush
@endsection
