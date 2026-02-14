<!-- Header -->
<div class="header">
    <div class="main-header">

        <div class="header-left">
            <a href="{{ route('backoffice.dashboard') }}" class="logo">
                <img src="{{ URL::asset('admin_assets/img/logo.svg') }}" alt="Logo">
            </a>
            <a href="{{ route('backoffice.dashboard') }}" class="dark-logo">
                <img src="{{ URL::asset('admin_assets/img/logo-white.svg') }}" alt="Logo">
            </a>
        </div>

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <div class="header-user">
            <div class="nav user-menu nav-list">

                <div class="me-auto d-flex align-items-center" id="header-search">
                    <a id="toggle_btn" href="javascript:void(0);">
                        <i class="ti ti-menu-deep"></i>
                    </a>

                    <div class="add-dropdown">
                        <a href="javascript:void(0);" class="btn btn-dark d-inline-flex align-items-center">
                            <i class="ti ti-plus me-1"></i>New Reservation
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center header-icons">

                    <!-- Flag -->
                    <div class="nav-item dropdown has-arrow flag-nav nav-item-box">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);"
                            role="button">
                            <img src="{{ URL::asset('admin_assets/img/flags/gb.svg') }}" alt="Language"
                                class="img-fluid">
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ URL::asset('admin_assets/img/flags/gb.svg') }}" alt=""
                                        height="16">English
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ URL::asset('admin_assets/img/flags/sa.svg') }}" alt=""
                                        height="16">Arabic
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ URL::asset('admin_assets/img/flags/de.svg') }}" alt=""
                                        height="16">German
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /Flag -->

                    <div class="theme-item">
                        <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle btn btn-menubar">
                            <i class="ti ti-moon"></i>
                        </a>
                        <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle btn btn-menubar">
                            <i class="ti ti-sun-high"></i>
                        </a>
                    </div>

                    <div class="notification_item">
                        <a href="javascript:void(0);" class="btn btn-menubar position-relative" id="notification_popup"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-bell"></i>
                            <span class="badge bg-violet rounded-pill"></span>
                        </a>

                        <!-- keep notifications dropdown as-is -->
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                            {{-- ... your existing notification HTML unchanged ... --}}
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('backoffice.dashboard') }}" class="btn btn-menubar">
                            <i class="ti ti-chart-bar"></i>
                        </a>
                    </div>

                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-menubar" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside">
                            <i class="ti ti-grid-dots"></i>
                        </a>
                        <div class="dropdown-menu p-3">
                            <ul>
                                <li>
                                    <a href="{{ route('backoffice.vehicles.index') }}"
                                        class="dropdown-item d-inline-flex align-items-center">
                                        <i class="ti ti-car me-2"></i>Car
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-inline-flex align-items-center">
                                        <i class="ti ti-file-symlink me-2"></i>Quotation
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-inline-flex align-items-center">
                                        <i class="ti ti-file-dollar me-2"></i>Seasonal Pricing
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-inline-flex align-items-center">
                                        <i class="ti ti-script-plus me-2"></i>Extra Service
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-inline-flex align-items-center">
                                        <i class="ti ti-dice-6 me-2"></i>Inspection
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-inline-flex align-items-center">
                                        <i class="ti ti-color-filter me-2"></i>Maintenance
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if (auth()->check())
                        <div class="dropdown profile-dropdown">
                            <a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside">
                                <span class="avatar avatar-sm">
                                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar') ?: URL::asset('admin_assets/img/profiles/avatar-05.jpg') }}"
                                        alt="Img" class="img-fluid rounded-circle">
                                </span>
                            </a>

                            <div class="dropdown-menu">
                                <div class="profileset d-flex align-items-center">
                                    <span class="user-img me-2">
                                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar') ?: URL::asset('admin_assets/img/profiles/avatar-05.jpg') }}"
                                            alt="">
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold mb-1">
                                            {{ auth()->user()->name ?? auth()->user()->email }}
                                        </h6>
                                        <p class="fs-13">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>

                                <!-- ✅ KEEP -->
                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('backoffice.profile.edit') }}">
                                    <i class="ti ti-user-edit me-2"></i>
                                    Edit Profile
                                </a>

                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('backoffice.profile.change-password') }}">
                                    <i class="ti ti-exchange me-2"></i>
                                    Change Password
                                </a>

                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('backoffice.profile.edit') }}">
                                    <i class="ti ti-settings me-2"></i>
                                    Settings
                                </a>

                                <div class="dropdown-divider my-2"></div>

                                <!-- ✅ FIX LOGOUT (POST) -->
                                <form method="POST" action="{{ route('backoffice.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item logout d-flex align-items-center justify-content-between w-100"
                                        style="border: none; background: none; cursor: pointer;">
                                        <span><i class="ti ti-logout me-2"></i>Logout Account</span>
                                        <i class="ti ti-chevron-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('backoffice.profile.edit') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('backoffice.profile.edit') }}">Settings</a>

                <form method="POST" action="{{ route('backoffice.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </div>
        </div>
        <!-- /Mobile Menu -->

    </div>
</div>
<!-- /Header -->
