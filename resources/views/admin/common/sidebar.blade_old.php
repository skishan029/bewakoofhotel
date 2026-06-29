<!-- Sidebar user panel (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="{{ Helper::adminProfile() }}" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
        <a href="{{ route('admin.dashboard') }}" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
    </div>
</div>
  <!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column nav-compact" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        @php
            $accessMenu = \App\Models\AccessMenu::where([
                ['user_id','=', Auth::guard('admin')->id()],
            ])->get()->pluck('menu_id')->toArray();
        @endphp

        @if (in_array('2', $accessMenu))
        <li class="nav-item">
            <a href="{{ route('admin.attendance.attendance') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>Attendance</p>
            </a>
        </li>
        @endif

        @if (in_array('5', $accessMenu))
            <li class="nav-item {{ request()->routeIs('admin.frontend.*') ? 'menu-open' : '' }}">
			<a href="#" class="nav-link {{ request()->routeIs('admin.frontend.*') ? 'active' : '' }}"> <i class="fas fa-toolbox nav-icon"></i>
				<p>Frontend Setting<i class="fas fa-angle-right right"></i></p>
			</a>
			<ul class="nav nav-treeview">
			    
				@if (in_array('6', $accessMenu))
                    <li class="nav-item">
                        <a href="{{ route('admin.frontend.slider.upload') }}" class="nav-link {{ request()->routeIs('admin.frontend.slider.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                            <p>Slider</p>
                        </a>
                    </li>
                @endif
                
                @if (in_array('7', $accessMenu))
                    <li class="nav-item">
                        <a href="{{ route('admin.frontend.fooditem.upload') }}" class="nav-link {{ request()->routeIs('admin.frontend.fooditem.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                            <p>Food Item</p>
                        </a>
                    </li>
                @endif
                
                @if (in_array('8', $accessMenu))
                    <li class="nav-item">
                        <a href="{{ route('admin.frontend.gallery.upload') }}" class="nav-link {{ request()->routeIs('admin.frontend.gallery.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                            <p>Gallery</p>
                        </a>
                    </li>
                @endif
                
                @if (in_array('9', $accessMenu))
                    <li class="nav-item">
                        <a href="{{ route('admin.frontend.frequentlyaskedquestion.create') }}" class="nav-link {{ request()->routeIs('admin.frontend.frequentlyaskedquestion.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                            <p>FAQ</p>
                        </a>
                    </li>
                @endif
                
                @if (in_array('10', $accessMenu))
                    <li class="nav-item">
                        <a href="{{ route('admin.frontend.testimonial.create') }}" class="nav-link {{ request()->routeIs('admin.frontend.testimonial.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                            <p>Testimonial</p>
                        </a>
                    </li>
                @endif
                
                @if (in_array('11', $accessMenu))
                    <li class="nav-item">
                        <a href="{{ route('admin.frontend.setting') }}" class="nav-link {{ request()->routeIs('admin.frontend.setting') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                            <p>Panel Setting</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.frontend.video.upload') }}" class="nav-link {{ request()->routeIs('admin.frontend.video.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                        <p>Video</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.frontend.offer.create') }}" class="nav-link {{ request()->routeIs('admin.frontend.offer.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                        <p>Offer Set</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.frontend.contactus.create') }}" class="nav-link {{ request()->routeIs('admin.frontend.contactus.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-long-arrow-alt-right"></i>
                        <p>Contact Us</p>
                    </a>
                </li>
				
			</ul>
            
		</li>
		@endif

        

        @if (in_array('4', $accessMenu))
            <li class="nav-item {{ request()->routeIs('admin.profile.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"> <i class="fas fa-user-edit nav-icon"></i>
                    <p>Profile <i class="fas fa-angle-left right"></i> </p>
                </a>
                <ul class="nav nav-treeview">
                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}"> <i class="fas fa-long-arrow-alt-right nav-icon "></i>
                            <p>Edit Profile</p>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.profile.changepassword') }}" class="nav-link {{ request()->routeIs('admin.profile.changepassword') ? 'active' : '' }}"> <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile.empchangepassword') }}" class="nav-link {{ request()->routeIs('admin.profile.empchangepassword') ? 'active' : '' }}"> <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                            <p>Employee Change Password</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        

        @if (in_array('1', $accessMenu))
            <li class="nav-item">
                <a href="{{ route('admin.productmaster.product.index') }}" class="nav-link {{ request()->routeIs('admin.productmaster.product.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-hamburger"></i>
                    <p>Products</p>
                </a>
            </li>
        @endif
        

        @if (in_array('2', $accessMenu))
            <li class="nav-item">
                <a href="{{ route('admin.order.create') }}" class="nav-link {{ request()->routeIs('admin.order.create') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-plus"></i>
                    <p>Create Invoice</p>
                </a>
            </li>
        @endif
        

        @if (in_array('2', $accessMenu))
            @php
                $InvoicesActive = '';
                if (request()->routeIs('admin.order.index') || request()->routeIs('admin.order.details') || request()->routeIs('admin.order.edit')) {
                    $InvoicesActive = 'active';
                }
                $penInvoicesActive = '';
                if (request()->routeIs('admin.order.pendingindex') || request()->routeIs('admin.order.details') || request()->routeIs('admin.order.edit')) {
                    $penInvoicesActive = 'active';
                }
            @endphp
            <li class="nav-item">
                <a href="{{ route('admin.order.index') }}" class="nav-link {{ $InvoicesActive }}">
                    <i class="nav-icon fas fa-file-invoice"></i>
                    <p>Invoices</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.order.pendingindex') }}" class="nav-link {{ $penInvoicesActive }}">
                    <i class="nav-icon fas fa-file-invoice"></i>
                    <p>Pending Invoices</p>
                </a>
            </li>
        @endif

        {{-- @if (in_array('1', $accessMenu) || in_array('2', $accessMenu)) --}} 
        @if (in_array('1', $accessMenu))
            <li class="nav-item {{ request()->routeIs('admin.report.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.report.*') ? 'active' : '' }}"> <i class="fas fa-user-edit nav-icon"></i>
                    <p>Report <i class="fas fa-angle-left right"></i> </p>
                </a>
                <ul class="nav nav-treeview">
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.report.index') }}" class="nav-link {{ (request()->routeIs('admin.report.product') || request()->routeIs('admin.report.index')) ? 'active' : '' }}"> <i class="fas fa-long-arrow-alt-right nav-icon "></i>
                            <p>Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.report.expense') }}" class="nav-link {{ request()->routeIs('admin.report.expense') ? 'active' : '' }}"> <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                            <p>Expense</p>
                        </a>
                    </li>
                    {{-- @if (in_array('1', $accessMenu)) --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.report.attendance') }}" class="nav-link {{ request()->routeIs('admin.report.attendance') ? 'active' : '' }}"> <i class="fas fa-long-arrow-alt-right nav-icon"></i>
                            <p>Attendance</p>
                        </a>
                    </li>
                    {{-- @endif --}}
                </ul>
            </li>
        @endif
        

        
        {{-- <li class="nav-item">
            <a href="{{ route('admin.report.index') }}" class="nav-link {{ request()->routeIs('admin.report.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>Report</p>
            </a>
        </li> --}}
        @if (in_array('2', $accessMenu))
        <li class="nav-item">
            <a href="{{ route('admin.expence.expenceadd') }}" class="nav-link {{ request()->routeIs('admin.expence.expenceadd') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>Expence</p>
            </a>
        </li>
        @endif
        @if (in_array('3', $accessMenu))
        <li class="nav-item">
            <a href="{{ route('admin.employee.index') }}" class="nav-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>Employee</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.databasebackup') }}" class="nav-link">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>Database Download</p>
            </a>
        </li>
        @endif
    </ul>
</nav>
  <!-- /.sidebar-menu -->