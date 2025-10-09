@php
use App\Services\checkSettingsService;
use App\Services\PermissionService;
@endphp
@if (PermissionService::has_permission('normal_pamm', 'admin'))
@role('social trade')
<!-- social trade -->
<li class="menu-item {{ Request::is('admin/no-copy/*') ? 'open' : '' }}">
    <a href="javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-brand-tabler"></i>
        <div data-i18n="Social Trades">{{ __('admin-menue-left.social_trade') }}</div>
    </a>
    <ul class="menu-sub">
        <!-- social trade -->
        @if (PermissionService::has_permission('normal_pamm_dashboard', 'admin'))
        @role('pamm dashboard')
        <li
            class="menu-item {{ Request::is('admin/no-copy/pamm-dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.no-copy-pamm-dashboard') }}" class="menu-link">
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        @endrole
        @endif
        <!-- pamm settings -->
        @if (PermissionService::has_permission('normal_pamm_settings', 'admin'))
        @role('pamm settings')
        <li class="menu-item {{ Request::is('admin/no-copy/pamm-settings') ? 'active' : '' }}">
            <a href="{{ route('admin.no-copy.pamm-settings') }}" class="menu-link">
                <div data-i18n="PAMM Settings">{{ __('admin-menue-left.pamm_setting') }}
                </div>
            </a>
        </li>
        @endrole
        @endif
        <!-- pamm manager -->
        @if (PermissionService::has_permission('normal_pamm_manager', 'admin'))
        @role('pamm manager')
        <li
            class="menu-item {{ Request::is('admin/no-copy/pamm-manager') ? 'active' : '' }}">
            <a href="{{ route('admin.no-copy.pamm-manager') }}" class="menu-link">
                <div data-i18n="PAMM Manager">{{ __('admin-menue-left.pamm_manager') }}
                </div>
            </a>
        </li>
        @endrole
        @endif
        <!-- copy trades report -->
        @if (PermissionService::has_permission('normal_pamm_trades', 'admin'))
        @role('pamm trades')
        <li
            class="menu-item {{ Request::is('admin/no-copy/pamm-trades') ? 'active' : '' }}">
            <a href="{{ route('admin.no-copy.pamm-trades') }}" class="menu-link">
                <div data-i18n="PAMM Trades">
                    {{ __('PAMM Trades') }}
                </div>
            </a>
        </li>
        @endrole
        @endif
        <!-- start pamm request -->
        @if (PermissionService::has_permission('normal_pamm_request', 'admin'))
        @role('pamm request')
        <li
            class="menu-item {{ Request::is('admin/no-copy/pamm-request') ? 'active' : '' }}">
            <a href="{{ route('admin.no-copy.pamm-request') }}" class="menu-link">
                <div data-i18n="PAMM Request">PAMM Request</div>
            </a>
        </li>
        @endrole
        @endif
        <!-- investment history -->
        @if (PermissionService::has_permission('normal_pamm_request', 'admin'))
        @role('investment history')
        <li
            class="menu-item {{ Request::is('admin/no-copy/pamm-investment') ? 'active' : '' }}">
            <a href="{{ route('admin.no-copy.pamm-investment') }}" class="menu-link">
                <div data-i18n="Investment History">Investment History</div>
            </a>
        </li>
        @endrole
        @endif
    </ul>
</li>
@endrole
@endif