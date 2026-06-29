<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Receipt Sample</title>
	<link rel="stylesheet" href="style.css"> 
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap');

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Source Sans Pro', sans-serif;
		}

		.container {
			display: block;
			width: 100%;
			background: #fff;
			max-width: 350px;
			padding: 25px;
			/* margin: 50px auto 0; */
			/* box-shadow: 0 3px 10px rgb(0 0 0 / 0.2); */
		}

		.receipt_header {
			padding-bottom: 40px;
			border-bottom: 1px dashed #000;
			text-align: center;
		}

		.receipt_header h1 {
			font-size: 20px;
			margin-bottom: 5px;
			text-transform: uppercase;
		}

		.receipt_header h1 span {
			display: block;
			font-size: 25px;
		}

		.receipt_header h2 {
			font-size: 14px;
			color: #727070;
			font-weight: 300;
		}

		.receipt_header h2 span {
			display: block;
		}

		.receipt_body {
			margin-top: 25px;
		}

		table {
			width: 100%;
		}

		thead, tfoot {
			position: relative;
		}

		thead th:not(:last-child) {
			text-align: left;
		}

		thead th:last-child {
			text-align: right;
		}

		thead::after {
			content: '';
			width: 100%;
			border-bottom: 1px dashed #000;
			display: block;
			position: absolute;
		}

		tbody td:not(:last-child), tfoot td:not(:last-child) {
			text-align: left;
		}

		tbody td:last-child, tfoot td:last-child {
			text-align: right;
		}

		tbody tr:first-child td {
			padding-top: 15px;
		}

		tbody tr:last-child td {
			padding-bottom: 15px;
		}

		tfoot tr:first-child td {
			padding-top: 15px;
		}

		tfoot::before {
			content: '';
			width: 100%;
			border-top: 1px dashed #000;
			display: block;
			position: absolute;
		}

		tfoot tr:last-child , tfoot tr:last-child td:last-child {
			font-weight: bold;
			font-size: 20px;
		}

		.date_time_con {
			display: flex;
			justify-content: center;
			column-gap: 25px;
		}

		.items {
			margin-top: 25px;
		}

		h3 {
			border-top: 1px dashed #000;
			padding-top: 10px;
			margin-top: 25px;
			text-align: center;
			text-transform: uppercase;
		}
	</style>
</head>

{{-- onload="printPage()" --}}

<body onload="printPage()">
	<div class="container">
		<div class="receipt_header">
			<h1>Receipt of Sale <span>{{ Helper::companyFullName() }}</span></h1>
			@if (!empty($productOrder->name))
			<h2>Name : {{ $productOrder->name }}</h2>
			@endif
			<h2>Address : {{ Helper::companyFullAddress() }} <span>Tel: {{ Helper::companyContact() }}</span></h2>
		</div>
		<div class="receipt_body">
			<div class="date_time_con">
				<div class="date">Order No</div>
				<div class="time">{{ $productOrder->order_no }}</div>
			</div>
            {{-- <div class="date_time_con">
				<div class="date">Customer Name</div>
				<div class="time">{{ $productOrder->name }}</div>
			</div> --}}
            <div class="date_time_con">
				<div class="date">{{ date('d/m/Y', strtotime($productOrder->created_at)) }}</div>
				<div class="time">{{ date('g:i A', strtotime($productOrder->created_at)) }}</div>
			</div>
			<div class="items">
				<table>
					<thead>
						<th>QTY</th>
						<th style="text-align: center;">ITEM</th>
						<th>AMT</th>
					</thead>
					<tbody>
                        @foreach ($productOrder->order_items as $order_item)
							@php
								$product_name = $order_item->product_name." - ".Helper::getPlateType($order_item->plate_type);
								if ($order_item->full_lbl_show == '2') {
									$product_name = $order_item->product_name;
								}
							@endphp
                            <tr>
                                <td>{{ $order_item->quantity }}</td>
                                <td>{{ $product_name }}</td>
                                <td>{{ number_format($order_item->total, 2, '.', '') }}</td>
                            </tr>
                        @endforeach
						
					</tbody>
					<tfoot>
                        <tr>
							<td>Sub Total</td>
							<td></td>
							<td>{{ number_format($productOrder->sub_total, 2, '.', '') }}</td>
						</tr>
                        @if (!empty($productOrder->discount))
                        <tr>
							<td>Discount</td>
							<td></td>
							<td>-{{ number_format($productOrder->discount, 2, '.', '') }}</td>
						</tr>
                        @endif
						
						<tr>
							<td>Total</td>
							<td></td>
							<td>{{ number_format($productOrder->grand_total, 2, '.', '') }}</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<h3>Thank You!</h3> 
	</div>

	<script>
		function printPage() {
			//alert('ghgfhhh');
			window.print();
        	setTimeout(window.close, 0);
		}
	</script>
</body>
</html>