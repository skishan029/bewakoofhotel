@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Billing Or Invoice')

@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4 col-sm-12">
                <div class="card shadow">
                    <div class="card-body text-center">
                        {{-- <i class="fas fa-check-circle fa-4x text-success"></i> <br> --}}
                        <img src="{{ asset('assests/logo/order_success.png') }}" alt="" style="width: 70px"> <br><br>
                        Order No : <strong>{{ $productOrder->order_no }}</strong> <br>
                        Total : <strong>&#8377;{{ number_format($productOrder->grand_total, '2', '.', '') }}</strong> <br>
                        Order Date : <strong>{{ date('d-M-Y g:i A', strtotime($productOrder->created_at)) }}</strong> <br><br>

                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.order.create') }}" class="btn btn-primary btn-block">Order Again</a>
                    </div>
                    <div class="col-6">
                        <button type="button" value="{{ route('admin.order.print', ['order_key'=> $productOrder->order_key]) }}" class="btn btn-primary btn-block" onclick="printInvoice(this.value)" id="printButton">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            //$("#printButton").trigger('click');
            url = '{{ route('admin.order.print', ['order_key'=> $productOrder->order_key]) }}';
            console.log(url);
            window.open(url,"Invoice Print","_blank","toolbar=yes,scrolbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
        });
        function printInvoice(url) {
            window.open(url,"Invoice Print","_blank","toolbar=yes,scrolbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
            
        }
    </script>
@endpush
@endsection