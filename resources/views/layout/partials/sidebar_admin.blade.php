<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('backoffice.dashboard') }}" class="logo logo-normal">
            <img src="{{ URL::asset('admin_assets/img/logo.svg') }}" alt="Logo">
        </a>
        <a href="{{ route('backoffice.dashboard') }}" class="logo-small">
            <img src="{{ URL::asset('admin_assets/img/logo-small.svg') }}" alt="Logo">
        </a>
        <a href="{{ route('backoffice.dashboard') }}" class="dark-logo">
            <img src="{{ URL::asset('admin_assets/img/logo-white.svg') }}" alt="Logo">
        </a>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"><span>Main</span></li>
                <li>
                    <ul>
                        @role('super-admin|admin|manager')
                        <li class="{{ request()->routeIs('backoffice.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.dashboard') }}">
                                <i class="ti ti-layout-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>

                @role('super-admin|admin')
                <li class="menu-title"><span>AGENCIES</span></li>
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('backoffice.agencies.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.agencies.index') }}">
                                <i class="ti ti-building"></i>
                                <span>Agencies</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('backoffice.agency-subscriptions.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.agency-subscriptions.index') }}">
                                <i class="ti ti-credit-card"></i>
                                <span>Subscriptions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                @role('super-admin|admin')
                <li class="menu-title"><span>ACCÈS</span></li>
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('backoffice.roles-permissions.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.roles-permissions.index') }}">
                                <i class="ti ti-shield"></i>
                                <span>Rôles & Permissions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                @role('super-admin|admin|manager')
                <li class="menu-title"><span>EMPLOYEE</span></li>
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('backoffice.agents.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.agents.index') }}">
                                <i class="ti ti-users"></i>
                                <span>Agents</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                @role('super-admin|admin|manager')
                <li class="menu-title"><span>CLIENTS</span></li>
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('backoffice.clients.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.clients.index') }}">
                                <i class="ti ti-user-circle"></i>
                                <span>Clients</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                @role('super-admin|admin|manager')
                <li class="menu-title"><span>MANAGEMENT</span></li>
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('backoffice.users.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.users.index') }}">
                                <i class="ti ti-users"></i>
                                <span>Users</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                {{-- ==================== RENTALS ==================== --}}
                @role('super-admin|admin|manager')
                <li class="menu-title"><span>RENTALS</span></li>
                <li>
                    <ul>
                        <li
                            class="{{ request()->routeIs('backoffice.vehicles.index') && !request()->routeIs('backoffice.vehicles.vignettes.*', 'backoffice.vehicles.insurances.*', 'backoffice.vehicles.oil-changes.*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.vehicles.index') }}">
                                <i class="ti ti-car"></i>
                                <span>Cars</span>
                            </a>
                        </li>

                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('backoffice.vehicle-brands.*', 'backoffice.vehicle-models.*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-device-camera-phone"></i>
                                <span>Car Attributes</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('backoffice.vehicle-brands.index') }}"
                                        class="{{ request()->routeIs('backoffice.vehicle-brands.*') ? 'active' : '' }}">
                                        Brands
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('backoffice.vehicle-models.index') }}"
                                        class="{{ request()->routeIs('backoffice.vehicle-models.*') ? 'active' : '' }}">
                                        Models
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endrole
                                {{-- ==================== VÉHICULE SUIVI ==================== --}}
                @role('super-admin|admin|manager')
                @php
                $firstVehicle = App\Models\Vehicle::first();
                $vehicleId = $firstVehicle ? $firstVehicle->id : 1;
                @endphp

                <li class="menu-title"><span>VÉHICULE SUIVI</span></li>
                <li>
                    <ul>
                        {{-- Vignettes --}}
                        <li class="{{ request()->routeIs('backoffice.vehicles.vignettes.index*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.vehicles.vignettes.index', ['vehicle' => $vehicleId]) }}">
                                <i class="ti ti-ticket"></i>
                                <span>Vignettes</span>
                            </a>
                        </li>

                        {{-- Assurances --}}
                        <li class="{{ request()->routeIs('backoffice.vehicles.insurances.index*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.vehicles.insurances.index', ['vehicle' => $vehicleId]) }}">
                                <i class="ti ti-shield"></i>
                                <span>Assurances</span>
                            </a>
                        </li>

                        {{-- Vidanges --}}
                        <li class="{{ request()->routeIs('backoffice.vehicles.oil-changes.index*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.vehicles.oil-changes.index', ['vehicle' => $vehicleId]) }}">
                                <i class="ti ti-droplet"></i>
                                <span>Vidanges</span>
                            </a>
                        </li>
                        {{-- Technical Checks - ALWAYS goes to INDEX page with vehicle_id=1 --}}
                        <li
                            class="{{ request()->routeIs('backoffice.vehicles.technical-checks.index*') ? 'active' : '' }}">
                            <a href="{{ route('backoffice.vehicles.technical-checks.index', ['vehicle' => 1]) }}">
                                <i class="ti ti-clipboard-check"></i>
                                <span>Contrôle technique</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->