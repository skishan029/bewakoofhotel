@extends('admin.common.layout')
@section('title', $title)
@section('module_title', 'Customer')

@section('content')

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter Search
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <!-- Keyword Search -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Keyword</label>
                                            <input type="text" name="keyword"
                                                class="form-control"
                                                placeholder="Search by order no, name, mobile..."
                                                value="{{ request('keyword') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Verified Status</label>
                                            <select name="is_verified" class="form-control">
                                                <option value="">All</option>
                                                <option value="verified" @selected(request('is_verified') == 'verified')>Verified</option>
                                                <option value="unverified" @selected(request('is_verified') == 'unverified')>Unverified</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="is_active" class="form-control">
                                                <option value="">All</option>
                                                <option value="active" @selected(request('is_active') == 'active')>Active</option>
                                                <option value="inactive" @selected(request('is_active') == 'inactive')>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.customer.index') }}"
                                            class="btn btn-secondary btn-block mt-2">
                                                Reset
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>C. Date</th>
                                            <th>Details</th>
                                            <th>Is Verified</th>
                                            <th>Status</th>
                                            <th>Region (Sub Region)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customers as $customer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <b>Name: </b> {{ $customer->name }} <br>
                                                    <b>Ph: </b> {{ $customer->username }} <br>
                                                    <b>Email: </b> {{ $customer->email }} <br>
                                                </td>
                                                <td>
                                                    @if ($customer->is_verified == 1)
                                                        <span class="badge badge-success">Verified</span>
                                                    @else
                                                        <span class="badge badge-danger">Not Verified</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($customer->is_active == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($customer->region_id))
                                                        {{ $customer->region?->name ?? '' }} ({{ $customer->sub_region?->name }})
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No Customer Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
