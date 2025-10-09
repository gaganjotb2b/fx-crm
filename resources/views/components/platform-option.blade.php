@if ($useFor == 'user_portal')
    @if ($platform != 'both')
        <div class="form-group">
            <label for="client-group">{{ __('page.server') }}</label>
            <input class="form-control" id="server" name="platform"
                value="{{ strtoupper($system_config->platform_type) }}" readonly>
        </div>
    @else
        <div class="form-group al-error-solve fg">
            <label for="server">{{ __('page.server') }}</label>
            <select class="form-control multisteps-form__input  choice-colors" id="server" name="platform">
                <option value="">{{ __('page.choose-a-server') }}</option>
                {!! $server !!}
            </select>
        </div>
    @endif
@elseif ($useFor == 'admin_portal_menual')
    @if ($platform != 'both')
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-account">Platform</label>
                <input class="form-control" id="platform-account" name="platform"
                    value="{{ strtoupper($system_config->platform_type) }}" readonly>
            </div>
        </div>
    @else
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-account">Platform</label>
                <select name="platform" class="select2 form-select" id="platform-account">
                    <option value="">Select a platform</option>
                    {!! $server !!}
                </select>
            </div>
        </div>
    @endif
@elseif ($useFor == 'admin_portal_auto')
    @if ($platform != 'both')
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-live">Platform</label>
                <input class="form-control" id="platform-live" name="platform"
                    value="{{ strtoupper($system_config->platform_type) }}" readonly>
            </div>
        </div>
    @else
        <div class="col-xl-6 col-md-6 col-12">
            <div class="mb-1 fg">
                <label class="form-label" for="platform-live">Platform</label>
                <select name="platform" class="select2 form-select" id="platform-live">
                    <option value="">Select a platform</option>
                    {!! $server !!}
                </select>
            </div>
        </div>
    @endif
@elseif ($useFor == 'admin_portal_client_group')
    @if ($platform != 'both')
        <div class="col-12 mb-1">
            <div class="form-element other-selector">
                <label class="form-label" for="platform-live">Platform</label>
                <input class="form-control" id="platform-live" name="platform"
                    value="{{ strtoupper($system_config->platform_type) }}" readonly>
            </div>
        </div>
    @else
        <div class="col-12 mb-1">
            <div class="form-element other-selector">
                <label class="form-label" for="server">Platform</label>
                <select class="select2 form-select" name="platform" id="server">
                    <optgroup>
                        <option value="">Select a platform</option>
                        {!! $server !!}
                    </optgroup>
                </select>
            </div>
        </div>
    @endif
@elseif ($useFor == 'admin_portal_report_filter')
    @if ($platform != 'both')
        <div class="col-md-4">
            <label class="form-label" for="platform">{{ __('page.search_by') }}
                {{ __('page.platform') }}</label>
                <input class="form-control" id="platform" name="platform"
                value="{{ strtoupper($system_config->platform_type) }}" readonly>
        </div>
    @else
        <div class="col-md-4">
            <label class="form-label" for="platform">{{ __('page.search_by') }}
                {{ __('page.platform') }}</label>
            <select class="select2 form-select" id="platform">
                <option value="">{{ __('client-management.All') }}</option>
                {!! $server !!}
            </select>
        </div>
    @endif
@endif
