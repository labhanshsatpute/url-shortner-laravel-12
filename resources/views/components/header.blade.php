<header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="{{route('view.dashboard')}}" class="fw-bold text-decoration-none text-primary mr-4">
            URL Shortner
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          @can(\App\Enums\Permissions\CompanyPermission::COMPANY_VIEW->value)
          <li><a href="{{ route('view.all.companies') }}" class="nav-link px-2 link-secondary">Companies</a></li>
          @endcan
          @can([\App\Enums\Permissions\UserPermission::VIEW_ALL_COMPANY_USERS->value, \App\Enums\Permissions\UserPermission::VIEW_SELF_COMPANY_USERS->value])
          <li><a href="{{ route('view.all.users') }}" class="nav-link px-2 link-dark">Users</a></li>
          @endcan
          <li><a href="#" class="nav-link px-2 link-dark">Inventory</a></li>
          <li><a href="#" class="nav-link px-2 link-dark">Customers</a></li>
          <li><a href="#" class="nav-link px-2 link-dark">Products</a></li>
        </ul>
        <div class="dropdown text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
            <li>
              <form action="{{ route('handle.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Sign out</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>