@extends('admin.common.layout')
@section('title', 'Dashboard')
@section('module_title', 'Dashboard')

@section('content')
@push('includestyle')
    {{-- @include('admin.common.include.css.datatable') --}}
@endpush
<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        @if (Auth::guard('admin')->user()->user_type == '1')           

            
            <div class="row">
                <div class="col-md-11">
                    <form action="" method="post" id="search_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date"> Date <strong class="text-danger">*</strong></label>
                                    <input id="start_date" class="form-control rounded-0 form-control-sm" type="date" name="start_date" value="{{ (!empty($start_date)) ? $start_date : '' ; }}" required>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">Close Date <strong class="text-danger">*</strong></label>
                                    <input id="end_date" class="form-control rounded-0 form-control-sm" type="date" name="end_date" required>
                                </div>
                            </div> --}}
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="{{ Helper::adminPrimaryButtonClass() }} btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            @php
                                $r = 0;
                            @endphp
                            @if (!blank($productOrderItem))
                                @foreach ( $productOrderItem as $value ) 
                                    @php
                                        $r = $r + 1;
                                        $fullqty = \App\Models\ProductOrderItem::where([
                                            ['product_id','=',$value->id],
                                            ['plate_type','=','1'],
                                            ['status','=','2'],
                                        ])->whereDate('created_at', '=', $start_date)->sum('quantity');
                                        $halfqty = \App\Models\ProductOrderItem::where([
                                            ['product_id','=',$value->id],
                                            ['plate_type','=','2'],
                                            ['status','=','2'],
                                        ])->whereDate('created_at', '=', $start_date)->sum('quantity');
                                        $fulltotal = \App\Models\ProductOrderItem::where([
                                            ['product_id','=',$value->id],
                                            ['plate_type','=','1'],
                                            ['status','=','2'],
                                        ])->whereDate('created_at', '=', $start_date)->sum('total');
                                        $halftotal = \App\Models\ProductOrderItem::where([
                                            ['product_id','=',$value->id],
                                            ['plate_type','=','2'],
                                            ['status','=','2'],
                                        ])->whereDate('created_at', '=', $start_date)->sum('total');
                                    @endphp 
                                     @if (!empty($value->full_price))
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h6>{{ $value->product_name_english }} {{ ($value->full_lbl_show == '2') ? '' : '- Full' ; }} </h6>
                                                    <h6>Total Plate : {{ (!empty( $fullqty)) ?  $fullqty: '0' ; }}</h6>
                                                    <h6>Total Amount : {{ number_format($fulltotal, '2', '.', ''); }}</h6>
                                                </div>
                                                <div class="icon">
                                                    <i ><img class="direct-chat-img" src="{{ Storage::url($value->featured_photo) }}" alt="product image"></i>
                                                </div>                                   
                                            </div>
                                        </div> 
                                    @endif
                                    @if (!empty($value->half_price))
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="small-box bg-success">
                                                <div class="inner">
                                                    <h6>{{ $value->product_name_english }} - Half</h6>
                                                    <h6>Total Plate : {{ (!empty( $halfqty)) ?  $halfqty : '0' ; }}</h6>
                                                    <h6>Total Amount : {{ number_format($halftotal, '2', '.', ''); }}</h6>
                                                </div>
                                                <div class="icon">
                                                    <i><img class="direct-chat-img" src="{{ Storage::url($value->half_photo) }}" alt="product image"></i>
                                                </div>                                   
                                            </div>
                                        </div> 
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        
                    </form>
                </div>
            </div>

            {{-- <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            @foreach ($table as $key=> $var)
                                @if ($key == 'action')
                                    <th width="5%" class="text-center">Action</th>
                                @else
                                    <th>{{ $var }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    
                </table>
            </div> --}}
        @endif
        
    </div>
</div>

{{-- @php $table_keys = $table->keys(); @endphp --}}

@push('includescript')
    {{-- @include('admin.common.include.js.datatable') --}}
@endpush

@push('script')
    <script>
       
    </script>
@endpush
@endsection

  
