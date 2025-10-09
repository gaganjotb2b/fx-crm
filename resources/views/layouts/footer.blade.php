@php
use App\Models\FooterLink;
$footerLinks = FooterLink::select()->first();
@endphp
<footer class="footer mb-2 w-100">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-4 mb-lg-0 mt-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    {{ get_copyright() }} &copy; {{ date('Y') }}
                </div>
            </div>
            <div class="col-lg-8 mt-4 footer-links-details">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="{{ $footerLinks->aml_policy ?? '#' }}" class="nav-link text-muted" target="_blank">AML
                            Policy</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ $footerLinks->contact_us ?? '#' }}" class="nav-link text-muted" target="_blank">{{ __('page.contact_us') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ $footerLinks->privacy_policy ?? '#' }}" class="nav-link text-muted" target="_blank">{{ __('page.privacy_policy') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ $footerLinks->refund_policy ?? '#' }}" class="nav-link text-muted" target="_blank">{{ __('page.refund_policy') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ $footerLinks->terms_condition ?? '#' }}" class="nav-link text-muted" target="_blank">{{ __('page.Terms&Conditions') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>