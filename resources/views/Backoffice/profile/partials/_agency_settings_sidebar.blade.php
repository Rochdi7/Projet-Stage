<div class="settings-sidebar slimscroll">
    <div class="sidebar-menu">
        <ul>
            <li class="menu-title"><span>PARAMÈTRES</span></li>
            <li>
                <ul class="sidebar-links pb-3 mb-3 border-bottom">

                    <li @class(['active' => $active === 'profile'])>
                        <a href="{{ route('backoffice.agencies.profile', $agency) }}">
                            <i class="ti ti-user-edit me-2"></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    <!-- ✅ NOUVEAU : EDIT PASSWORD -->
                    <li @class(['active' => $active === 'change-password'])>
                        <a href="{{ route('backoffice.profile.change-password') }}">
                            <i class="ti ti-lock me-2"></i>
                            <span>Modifier le mot de passe</span>
                        </a>
                    </li>

                    <li @class(['active' => $active === 'notifications'])>
                        <a href="{{ route('backoffice.agencies.settings.notifications', $agency) }}">
                            <i class="ti ti-bell me-2"></i>
                            <span>Notifications</span>
                        </a>
                    </li>

                    <li @class(['active' => $active === 'invoice-template'])>
                        <a href="{{ route('backoffice.agencies.settings.invoice-template', $agency) }}">
                            <i class="ti ti-template me-2"></i>
                            <span>Modèle de facture</span>
                        </a>
                    </li>

                    <li @class(['active' => $active === 'company'])>
                        <a href="{{ route('backoffice.agencies.settings.company', $agency) }}">
                            <i class="ti ti-building me-2"></i>
                            <span>Paramètres système</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
