@php
use App\Models\FooterLink;
$footerLinks = FooterLink::select()->first();
@endphp
<footer class="footer py-5">
    <div class="container">
        <div class="row footer-links-details">
            <div class="col-lg-8 mb-4 mx-auto text-center">
                <a href="{{ $footerLinks->aml_policy ?? '#' }}" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    AML Policy
                </a>
                <a href="{{ $footerLinks->contact_us ?? '#' }}" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    {{ __('page.contact_us') }}
                </a>
                <a href="{{ $footerLinks->privacy_policy ?? '#' }}" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    {{ __('page.privacy_policy') }}
                </a>
                <a href="{{ $footerLinks->refund_policy ?? '#' }}" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    {{ __('page.refund_policy') }}
                </a>
                <a href="{{ $footerLinks->terms_condition ?? '#' }}" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                    {{ __('page.Terms&Conditions') }}
                </a>
            </div>
            <div class="col-lg-8 mx-auto text-center mb-4 mt-2">

                @php
                use App\Models\admin\SystemConfig;
                $company_social = SystemConfig::select('com_social_info')->first();
                if($company_social):
                $company_social = json_decode($company_social->com_social_info);
                @endphp
                @foreach($company_social as $key => $value)
                @if($value!="" && $key != "skype" && $key != 'whatsapp')
                <a href="{{$value}}" target="_blank" class="text-secondary me-xl-4 me-4">
                    <span class="text-lg fab fa-{{$key}}"></span>
                </a>
                @endif
                @if($value!="" && $key == "skype")
                <a href="skype:{{$value}}?call" target="_blank" class="text-secondary me-xl-4 me-4">
                    <span class="text-lg fab fa-{{$key}}"></span>
                </a>
                @endif
                @if($value!="" && $key == "whatsapp")
                <a href="https://wa.me/{{$value}}" target="_blank" class="text-secondary me-xl-4 me-4">
                    <span class="text-lg fab fa-{{$key}}"></span>
                </a>
                @endif

                @endforeach
                @php endif; @endphp
            </div>
        </div>
        <div class="row">
            <div class="col-8 mx-auto text-center mt-1">
                <p class="mb-0 text-secondary">
                    {{get_copyright()}} &copy; {{date('Y')}}
                </p>
            </div>
        </div>
    </div>
</footer>