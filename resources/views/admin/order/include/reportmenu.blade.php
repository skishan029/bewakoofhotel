<div class="card-tools">
    <a href="{{ route('admin.report.index') }}" class="btn btn-sm {{ request()->routeIs('admin.report.index') ? 'btn-primary' : 'btn-default' }}">Order</a>

    <a href="{{ route('admin.report.product') }}" class="btn btn-sm {{ request()->routeIs('admin.report.product') ? 'btn-primary' : 'btn-default' }}">Product</a>
</div>