<style>
    .metismenu a:hover {
        color: #f6921e;
    }
    .metismenu a:active {
        color: #f6921e;
    }
</style>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="menu-uni" role="tabpanel">
        <nav class="sidebar-nav">
            <ul class="metismenu">
                <li class="border-top">
                    <a class="{{ (request()->is('admin/customers*') || request()->is('admin/cleaners*') || request()->is('admin/contractors*')) ? 'active' : '' }}" href="">
                        <span class="ml-3">User</span><i class="ml-3 fas fa-angle-down"></i>
                    </a>
                    <ul class="sub-menu">
                        <li class="border-top border-bottom"><a href="{{route('admin.customers')}}">Customers</a></li>
                        <li class=""><a href="{{route('admin.cleaners')}}">Cleaner</a></li>
                        <li class="border-top"><a href="{{route('admin.contractors')}}">Supervisor</a></li>
                    </ul>
                </li>
                <li class="border-top">
                    <a href="">
                        <span class="ml-3">Store</span><i class="ml-3 fas fa-angle-down"></i>
                    </a>
                    <ul class="sub-menu">
                        <li class=" border-top"><a href="store-detail.html">Store Detail</a></li>
                        <li class="border-top"><a href="inventory-request.html">Inventory Request </a></li>
                        <li class="border-top"><a href="inventory-report.html">Inventory Report </a></li>
                    </ul>
                </li>
                <li class=" border-top">
                    <a class="{{ (request()->is('admin/subscriptions*')) ? 'active' : '' }}" href="{{url('admin/subscriptions')}}"><span class="ml-3">Subscription</span></a>
                </li>
                <li class=" border-bottom border-top">
                    <a class="{{ (request()->is('admin/payments*')) ? 'active' : '' }}" href="{{route('admin.payments')}}"><span class="ml-3">Payments</span></a>
                </li>
                <li class="border-bottom">
                    <a href="{{route('admin.work-orders')}}"><span class="ml-3">Work Order</span></a>
                </li>
                <li class="border-bottom">
                    <a class="{{ (request()->is('admin/categories*')) ? 'active' : '' }}" href="{{route('admin.categories')}}"><span class="ml-3 ">Category</span></a>
                </li>
                <li class="border-bottom">
                    <a class="{{ (request()->is('admin/contact-queries*')) ? 'active' : '' }}" href="{{route('admin.contact-queries')}}"><span class="ml-3 ">Queries</span></a>
                </li>
                <li class="border-bottom">
                    <a class="{{ (request()->is('admin/terms*')) ? 'active' : '' }}" href="{{route('admin.terms')}}"><span class="ml-3 ">Terms & Service </span></a>
                </li>
                <li class="border-bottom">
                    <a class="{{ (request()->is('admin/privacy*')) ? 'active' : '' }}" href="{{route('admin.privacy')}}"><span class="ml-3">Privacy & Policy</span></a>
                </li>
            </ul>
        </nav>
    </div>
</div>
