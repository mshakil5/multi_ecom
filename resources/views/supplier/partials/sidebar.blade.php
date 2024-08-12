<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
           
        <li class="nav-item">
            <a href="{{ route('supplier.dashboard') }}" class="nav-link {{ (request()->is('supplier/dashboard')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('supplier.profile') }}" class="nav-link {{ (request()->is('supplier/profile')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Profile</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('productPurchaseHistory.supplier') }}" class="nav-link {{ (request()->is('supplier/sold-history')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-history"></i>
                <p>Sold History</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('stock.supplier') }}" class="nav-link {{ (request()->is('supplier/stocks')) ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>Stock</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('order.supplier') }}" class="nav-link {{ request()->routeIs('order.supplier', 'supplier.orders.details') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart nav-icon"></i>
                <p>Orders</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('supplier.transaction') }}" class="nav-link {{ request()->routeIs('supplier.transaction') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                <p>Transactions</p>
            </a>
        </li>
        
    </ul>
  </nav>