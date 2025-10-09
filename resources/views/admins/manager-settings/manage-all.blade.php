@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Manage All - Manager Hierarchy')

@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/charts/apexcharts.css') }}">

@stop

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<style>
    /* Beautiful and responsive phone input styling */
    .phone-input-container {
        display: flex;
        align-items: stretch;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        border: 1px solid #e0e0e0;
        position: relative;
        height: 44px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .phone-input-container:hover {
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
        transform: translateY(-1px);
        border-color: #d0d0d0;
        background: #fefefe;
    }
    
    .phone-input-container:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
        background: #fefefe;
    }
    
    .phone-input-container:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    
    /* Country selector styling */
    .country-selector {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 8px 12px;
        border-right: 1px solid #e0e0e0;
        position: relative;
        min-width: 80px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        border-radius: 10px 0 0 10px;
        height: 44px;
        flex-shrink: 0;
        touch-action: manipulation;
    }
    
    .country-selector:hover {
        background: #e9ecef;
        transform: translateY(-1px);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
        border-right-color: #d0d0d0;
    }
    
    .country-selector:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        background: #dee2e6;
    }
    

    
    .country-selector:active .country-code-select {
        color: #666;
        font-weight: 600;
    }
    
    .country-selector:active .dropdown-arrow {
        transform: translateY(0);
        opacity: 0.5;
    }
    

    
    .country-code-select {
        background: transparent;
        border: none;
        outline: none;
        font-weight: 600;
        color: #333;
        font-size: 13px;
        cursor: pointer;
        min-width: 40px;
        padding-right: 20px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        transition: color 0.3s ease;
        flex-shrink: 0;
    }
    
    .country-code-select::-ms-expand {
        display: none;
    }
    
    .country-selector:hover .country-code-select {
        color: #000;
        font-weight: 700;
    }
    
    .dropdown-arrow {
        position: absolute;
        right: 10px;
        color: #666;
        font-size: 11px;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0.7;
    }
    
    .country-selector:hover .dropdown-arrow {
        transform: translateY(1px);
        opacity: 1;
        color: #333;
        font-size: 13px;
    }
    
    .country-selector:focus-within .dropdown-arrow {
        color: #667eea;
        opacity: 1;
        transform: translateY(1px);
    }
    
    /* Phone number input styling */
    .phone-number-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 8px 12px;
        font-size: 14px;
        color: #495057;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        border-radius: 0 10px 10px 0;
        cursor: text;
        height: 44px;
        min-width: 0;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }
    
    .phone-number-input:hover {
        background: #fafbfc;
    }
    
    .phone-number-input:active {
        background: #f8f9fa;
        transform: scale(0.99);
    }
    
    .phone-number-input:focus {
        background: #fafbfc;
        box-shadow: inset 0 0 0 2px #667eea;
        transform: scale(1.01);
        border-radius: 0 10px 10px 0;
    }
    
    .phone-number-input::placeholder {
        color: #adb5bd;
        font-size: 14px;
        font-style: italic;
        transition: color 0.3s ease;
    }
    
    .phone-input-container:focus-within .country-selector {
        background: #e3f2fd;
        border-right-color: #667eea;
        box-shadow: inset 0 0 0 1px rgba(102, 126, 234, 0.2);
    }
    
    .phone-input-container:focus-within .phone-number-input {
        background: #fafbfc;
        box-shadow: inset 0 0 0 1px rgba(102, 126, 234, 0.1);
    }
    

    
    .phone-input-container:focus-within .country-code-select {
        color: #667eea;
        font-weight: 700;
    }
    
    /* Label styling */
    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Responsive design for mobile and tablet */
    @media (max-width: 768px) {
        .phone-input-container {
            flex-direction: column;
            border-radius: 8px;
            height: auto;
            min-height: 80px;
            width: 100%;
        }
        
        .country-selector {
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
            min-width: 100%;
            justify-content: center;
            border-radius: 8px 8px 0 0;
            height: 40px;
            padding: 8px 16px;
            flex-shrink: 0;
        }
        
        .phone-number-input {
            border-radius: 0 0 8px 8px;
            height: 40px;
            padding: 8px 16px;
            min-width: 0;
        }
        

        
        .country-code-select {
            font-size: 14px;
            min-width: 60px;
        }
        
        .dropdown-arrow {
            right: 16px;
            font-size: 12px;
        }
        

    }
    
    @media (max-width: 480px) {
        .phone-input-container {
            border-radius: 6px;
            height: auto;
            min-height: 72px;
            margin: 0 10px;
            width: calc(100% - 20px);
        }
        
        .country-selector {
            padding: 6px 12px;
            height: 36px;
            border-radius: 6px 6px 0 0;
            flex-shrink: 0;
        }
        
        .phone-number-input {
            padding: 6px 12px;
            font-size: 13px;
            height: 36px;
            border-radius: 0 0 6px 6px;
            min-width: 0;
        }
        

        
        .country-code-select {
            font-size: 12px;
            min-width: 50px;
        }
        
        .dropdown-arrow {
            right: 12px;
            font-size: 10px;
        }
        

    }
    
    @media (max-width: 360px) {
        .phone-input-container {
            margin: 0 5px;
            min-height: 68px;
            width: calc(100% - 10px);
        }
        
        .country-selector {
            padding: 5px 10px;
            height: 34px;
            flex-shrink: 0;
        }
        
        .phone-number-input {
            padding: 5px 10px;
            height: 34px;
            font-size: 12px;
            min-width: 0;
        }
        

        
        .country-code-select {
            font-size: 11px;
            min-width: 45px;
        }
        
        .dropdown-arrow {
            right: 10px;
            font-size: 9px;
        }
        

    }
    .tree-container {
        background: #fff;
        border: 2px solid #ff8e5c;
        border-radius: 8px;
        padding: 20px;
        min-height: 400px;
        box-shadow: 0 4px 12px rgba(255, 142, 92, 0.15);
    }
    
    .admin-manager-item {
        background: #fff;
        border: 1px solid #ff8e5c;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(255, 142, 92, 0.1);
        transition: all 0.3s ease;
    }
    
    .admin-manager-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(255, 142, 92, 0.2);
    }
    
    .admin-manager-header {
        padding: 15px 20px;
        background: #ff8e5c;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .admin-manager-header:hover {
        background: #e67e4c;
    }
    
    .admin-manager-name {
        font-weight: 600;
        font-size: 16px;
    }
    
    .expand-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        font-weight: bold;
    }
    
    .expand-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.05);
    }
    
    .expand-btn.expanded {
        transform: rotate(45deg) scale(1.05);
    }
    
    .expand-btn.country-level {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .expand-btn.account-level {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .country-managers-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .country-managers-container.expanded {
        max-height: none;
        overflow: visible;
    }
    
    .country-manager-item {
        padding: 0;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
    }
    
    .country-manager-item:last-child {
        border-bottom: none;
    }
    
    .country-manager-header {
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        background: #1E3A8A;
        color: white;
        transition: all 0.3s ease;
    }
    
    .country-manager-header:hover {
        background: #1e40af;
    }
    
    .country-manager-name {
        font-weight: 500;
        color: white;
        font-size: 14px;
    }
    
    .managers-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .managers-container.expanded {
        max-height: none;
        overflow: visible;
    }
    
    .manager-item {
        padding: 0;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
        transition: all 0.2s ease;
    }
    
    .manager-item:hover {
        background: #fafafa;
    }
    
    .manager-item:last-child {
        border-bottom: none;
    }
    
    .manager-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px 12px 40px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #10B981;
        color: white;
    }
    
    .manager-header:hover {
        background: #059669;
    }
    
    .manager-name {
        font-weight: 400;
        color: white;
        font-size: 14px;
    }
    
    .add-new-btn {
        background: #ff8e5c;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(255, 142, 92, 0.3);
    }
    
    .add-new-btn:hover {
        background: #e67e4c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 142, 92, 0.4);
    }
    
    /* Statistics Container Styles */
    .stats-container {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin: 15px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        border-left: 4px solid;
    }
    
    .stat-card.admin-level {
        border-left-color: #ff8e5c;
    }
    
    .stat-card.country-level {
        border-left-color: #1E3A8A;
    }
    
    .stat-card.manager-level {
        border-left-color: #10B981;
    }
    
    .stat-card h3 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    
    .stat-card p {
        margin: 5px 0 0 0;
        color: #666;
        font-size: 14px;
    }
    
    .add-new-btn.country-level {
        background: #1E3A8A;
        box-shadow: 0 2px 6px rgba(30, 58, 138, 0.3);
    }
    
    .add-new-btn.country-level:hover {
        background: #1e40af;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.4);
    }
    
    .add-new-btn.account-level {
        background: #10B981;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }
    
    .add-new-btn.account-level:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .no-country-managers {
        padding: 20px;
        text-align: center;
        color: #666;
        font-style: italic;
        background: #fafafa;
        border-radius: 6px;
        margin: 10px;
    }
    
    /* Statistics Cards */
    .stats-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .stats-report-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }
    
    .stats-report-container.expanded {
        max-height: 800px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #ff8e5c 0%, #e67e4c 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(255, 142, 92, 0.3);
    }
    
    .stat-card.admin-level {
        background: linear-gradient(135deg, #ff8e5c 0%, #e67e4c 100%);
        box-shadow: 0 4px 12px rgba(255, 142, 92, 0.3);
    }
    
    .stat-card.country-level {
        background: linear-gradient(135deg, #1E3A8A 0%, #1e40af 100%);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }
    
    .stat-card.account-level {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    /* Today Statistics Cards - Inherit colors from their level */
    .stat-card.today.admin-level {
        background: linear-gradient(135deg, #ff8e5c 0%, #e67e4c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 142, 92, 0.3);
    }
    
    .stat-card.today.country-level {
        background: linear-gradient(135deg, #1E3A8A 0%, #1e40af 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }
    
    .stat-card.today.account-level {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .stat-card.today h3 {
        color: white;
        font-weight: 700;
    }
    
    .stat-card.today p {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }
    
    .stat-card.today.admin-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 142, 92, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card.today.country-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card.today.account-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }
    
    /* This Month Statistics Cards - Inherit colors from their level */
    .stat-card.this-month.admin-level {
        background: linear-gradient(135deg, #ff8e5c 0%, #e67e4c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 142, 92, 0.3);
    }
    
    .stat-card.this-month.country-level {
        background: linear-gradient(135deg, #1E3A8A 0%, #1e40af 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }
    
    .stat-card.this-month.account-level {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .stat-card.this-month h3 {
        color: white;
        font-weight: 700;
    }
    
    .stat-card.this-month p {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }
    
    .stat-card.this-month.admin-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 142, 92, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card.this-month.country-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card.this-month.account-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }
    
    /* Last Month Statistics Cards - Inherit colors from their level */
    .stat-card.last-month.admin-level {
        background: linear-gradient(135deg, #ff8e5c 0%, #e67e4c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 142, 92, 0.3);
    }
    
    .stat-card.last-month.country-level {
        background: linear-gradient(135deg, #1E3A8A 0%, #1e40af 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }
    
    .stat-card.last-month.account-level {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .stat-card.last-month h3 {
        color: white;
        font-weight: 700;
    }
    
    .stat-card.last-month p {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }
    
    .stat-card.last-month.admin-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 142, 92, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card.last-month.country-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card.last-month.account-level:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }
    
    .stat-card h3 {
        font-size: 24px;
        font-weight: bold;
        margin: 0 0 5px 0;
    }
    
    .stat-card p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }
    
    /* Revenue Reports */
    .revenue-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .revenue-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .date-range-picker {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .revenue-chart {
        height: 300px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
    }
    
    /* Modal Styles */
    .modal-header {
        background: #ff8e5c;
        color: white;
        border-radius: 6px 6px 0 0;
    }
    
    .modal-title {
        color: white;
        font-weight: 600;
    }
    
    .close {
        color: white;
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    
    .close:hover {
        color: white;
        opacity: 1;
        transform: scale(1.1);
    }
    
    .modal-content {
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: none;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 20px;
    }
    
    /* Loading Spinner */
    .spinner-border {
        border-color: #ff8e5c;
        border-right-color: transparent;
    }
    
    /* Select2 Customization */
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        min-height: 40px;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #ff8e5c;
        box-shadow: 0 0 0 0.2rem rgba(255, 142, 92, 0.25);
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #ff8e5c;
        border: none;
        border-radius: 12px;
        color: white;
        padding: 4px 10px;
        margin: 3px;
    }
    
    /* Card Header Enhancement */
    .card-header {
        background: #ff8e5c;
        color: white;
        border-radius: 8px 8px 0 0;
        border: none;
    }
    
    .card-title {
        color: white;
        font-weight: 600;
    }
    
    .card {
        border-radius: 8px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        border: none;
    }
    
    /* Alert Enhancement */
    .alert-info {
        background: #ff8e5c;
        border: none;
        color: white;
        border-radius: 8px;
    }
    
    .alert-info h6 {
        color: white;
        font-weight: 600;
    }
    
    /* Button Enhancements */
    .btn-primary {
        background: #ff8e5c;
        border-color: #ff8e5c;
    }
    
    .btn-primary:hover {
        background: #e67e4c;
        border-color: #e67e4c;
    }
    
    /* Type Tags */
    .type-tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 8px;
        color: white;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .admin-tag {
        background: #ff8e5c;
        border-color: #ff8e5c;
    }
    
    .country-tag {
        background: #1E3A8A;
        border-color: #1E3A8A;
    }
    
         .account-tag {
         background: #10B981;
         border-color: #10B981;
     }
     
     /* Bullet indicators for revenue charts */
     .bullet {
         display: inline-block;
         width: 8px;
         height: 8px;
         border-radius: 50%;
         margin-right: 8px;
     }
     
     .bullet-primary {
         background-color: #7367F0;
     }
     
     .bullet-warning {
         background-color: #FF9F43;
     }
     
     .font-small-3 {
         font-size: 0.75rem;
     }
     
     .me-50 {
         margin-right: 0.5rem;
     }
     
     .ms-75 {
         margin-left: 0.75rem;
     }
     
     .font-medium-3 {
         font-size: 1.5rem;
         font-weight: 500;
     }
     
     .mb-25 {
         margin-bottom: 0.25rem;
     }
     
     .fw-bolder {
         font-weight: 700;
     }
     
           .me-25 {
          margin-right: 0.25rem;
      }
      
      /* Month filter dropdown styling */
      .month-filter-item {
          cursor: pointer;
          transition: background-color 0.2s ease;
      }
      
      .month-filter-item:hover {
          background-color: #f8f9fa;
      }
      
      .dropdown-menu {
          z-index: 1050;
      }
  </style>
@stop

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Manager Hierarchy Management</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Manager Settings</a></li>
                                <li class="breadcrumb-item active">Manage All</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            <!-- Date Range Filter -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i data-feather="calendar"></i> Date Range Filter
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate">
                                </div>
                                <div class="col-md-4">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary me-2" id="applyDateFilter">
                                        <i data-feather="filter"></i> Apply Filter
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetDateFilter">
                                        <i data-feather="refresh-cw"></i> Reset
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i data-feather="info"></i> 
                                        Select a date range to filter statistics for all hierarchy levels. The filter will apply to Admin Manager, Country Manager, and Account Manager statistics.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hierarchy Information -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6><i data-feather="info"></i> Manager Hierarchy Structure</h6>
                        <p class="mb-2">Click the + button next to Admin Managers to view their assigned Country Managers and detailed statistics. Each level shows comprehensive financial data and user statistics.</p>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#phoneInputDemoModal">
                            <i class="fas fa-phone"></i> View Phone Input Demo
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i data-feather="users"></i> Admin Manager Hierarchy
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="tree-container" id="adminManagerTree">
                                <!-- Admin Managers will be loaded here -->
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading Admin Managers...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Country Manager Modal -->
<div class="modal fade" id="addCountryManagerModal" tabindex="-1" aria-labelledby="addCountryManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCountryManagerModalLabel">Add Country Managers</h5>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Country Managers (Multiple Selection)</label>
                    <select class="form-control" id="unassignedCountryManagers" multiple>
                        <!-- Unassigned Country Managers will be loaded here -->
                    </select>
                    <small class="form-text text-muted">Select multiple Country Managers to assign</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="assignCountryManagers">Assign Selected</button>
            </div>
        </div>
    </div>
</div>

<!-- Add New Manager Modal -->
<div class="modal fade" id="addManagerModal" tabindex="-1" aria-labelledby="addManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManagerModalLabel">Add Account Managers</h5>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Account Managers (Multiple Selection)</label>
                    <select class="form-control" id="unassignedManagers" multiple>
                        <!-- Unassigned Managers will be loaded here -->
                    </select>
                    <small class="form-text text-muted">Select multiple Account Managers to assign</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="assignManagers">Assign Selected Account Managers</button>
            </div>
        </div>
    </div>
</div>

<!-- Phone Input Demo Modal -->
<div class="modal fade" id="phoneInputDemoModal" tabindex="-1" aria-labelledby="phoneInputDemoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneInputDemoModalLabel">Phone Input Field Demo</h5>
                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PHONE</label>
                            <div class="phone-input-container">
                                <div class="country-selector">
                                    <select class="country-code-select" name="demo_country_code" id="demo_country_code">
                                        <option value="">Select Country</option>
                                        <option value="92">+92</option>
                                        <option value="1">+1</option>
                                        <option value="44">+44</option>
                                        <option value="91">+91</option>
                                        <option value="86">+86</option>
                                        <option value="81">+81</option>
                                        <option value="49">+49</option>
                                        <option value="33">+33</option>
                                        <option value="39">+39</option>
                                        <option value="34">+34</option>
                                    </select>
                                    <div class="dropdown-arrow">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <input class="phone-number-input" type="text" name="demo_phone" placeholder="301 2345678" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>MOBILE</label>
                            <div class="phone-input-container">
                                <div class="country-selector">
                                    <select class="country-code-select" name="demo_mobile_country_code" id="demo_mobile_country_code">
                                        <option value="">Select Country</option>
                                        <option value="1">+1</option>
                                        <option value="92">+92</option>
                                        <option value="44">+44</option>
                                        <option value="91">+91</option>
                                        <option value="86">+86</option>
                                        <option value="81">+81</option>
                                        <option value="49">+49</option>
                                        <option value="33">+33</option>
                                        <option value="39">+39</option>
                                        <option value="34">+34</option>
                                    </select>
                                    <div class="dropdown-arrow">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <input class="phone-number-input" type="text" name="demo_mobile_phone" placeholder="555 1234567" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Features:</h6>
                            <ul class="mb-0">
                                <li>Compact country code selection</li>
                                <li>Responsive design for mobile and desktop</li>
                                <li>Smooth animations and hover effects</li>
                                <li>Professional styling with focus states</li>
                                <li>Clean and simple interface</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="testPhoneInput()">Test Phone Input</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>

<script>
$(document).ready(function() {
    let currentAdminManagerId = null;
    let currentCountryManagerId = null;
    
    // Load Admin Managers on page load
    loadAdminManagers();
    
    // Initialize Select2 for the modal dropdowns
    $('#unassignedCountryManagers').select2({
        placeholder: "Select Country Managers",
        allowClear: true,
        closeOnSelect: false
    });
    
    $('#unassignedManagers').select2({
        placeholder: "Select Account Managers",
        allowClear: true,
        closeOnSelect: false
    });
    
    // Initialize phone input
    initializePhoneInputs();
    
    // Initialize phone input
    function initializePhoneInputs() {
        console.log('Phone inputs initialized');
    }
    
    // Test phone input function
    window.testPhoneInput = function() {
        var phone1 = document.querySelector('input[name="demo_phone"]').value;
        var country1 = document.querySelector('#demo_country_code').value;
        var phone2 = document.querySelector('input[name="demo_mobile_phone"]').value;
        var country2 = document.querySelector('#demo_mobile_country_code').value;
        
        var message = 'Phone Input Test Results:\n\n';
        message += 'Phone 1: ' + (country1 ? '+' + country1 + ' ' : '') + phone1 + '\n';
        message += 'Phone 2: ' + (country2 ? '+' + country2 + ' ' : '') + phone2 + '\n';
        
        alert(message);
    };
    
    // Load Admin Managers
    function loadAdminManagers() {
        $.get('/admin/manager-settings/manage-all/available-users?level=admin_manager', function(response) {
            if (response.status && response.users.length > 0) {
                displayAdminManagers(response.users);
            } else {
                $('#adminManagerTree').html('<div class="text-center text-muted"><p>No Admin Managers found</p></div>');
            }
        }).fail(function() {
            $('#adminManagerTree').html('<div class="text-center text-danger"><p>Error loading Admin Managers</p></div>');
        });
    }
    
    // Display Admin Managers
    function displayAdminManagers(adminManagers) {
        let html = '';
        adminManagers.forEach(function(adminManager) {
            html += `
                <div class="admin-manager-item" data-admin-id="${adminManager.id}">
                    <div class="admin-manager-header" onclick="toggleCountryManagers(${adminManager.id})">
                        <div class="admin-manager-name">
                            ${adminManager.name} (${adminManager.email})
                            <span class="type-tag admin-tag">Admin Manager</span>
                        </div>
                        <button class="expand-btn" id="expand-${adminManager.id}">+</button>
                    </div>
                    <div class="country-managers-container" id="country-container-${adminManager.id}">
                        <div class="text-center p-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span class="ms-2">Loading Country Managers...</span>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#adminManagerTree').html(html);
    }
    
    // Toggle Country Managers visibility
    window.toggleCountryManagers = function(adminManagerId) {
        const container = $(`#country-container-${adminManagerId}`);
        const btn = $(`#expand-${adminManagerId}`);
        
        if (container.hasClass('expanded')) {
            // Collapse
            container.removeClass('expanded');
            btn.removeClass('expanded');
        } else {
            // Expand
            container.addClass('expanded');
            btn.addClass('expanded');
            
            // Load Country Managers if not already loaded
            if (container.find('.country-manager-item').length === 0) {
                loadCountryManagers(adminManagerId);
                loadAdminManagerStats(adminManagerId);
            }
        }
    };
    
    // Load Country Managers for a specific Admin Manager
    function loadCountryManagers(adminManagerId) {
        const container = $(`#country-container-${adminManagerId}`);
        
        // First, get assigned Country Managers from the assignments table
        $.get(`/admin/manager-settings/manage-all/get-assigned-country-managers?admin_manager_id=${adminManagerId}`, function(response) {
            if (response.status) {
                displayCountryManagers(adminManagerId, response.country_managers);
            } else {
                container.html('<div class="no-country-managers">No Country Managers assigned yet</div>');
            }
        }).fail(function() {
            container.html('<div class="text-center text-danger p-3">Error loading Country Managers</div>');
        });
    }
    

    
    // Display Country Managers
    function displayCountryManagers(adminManagerId, countryManagers) {
        const container = $(`#country-container-${adminManagerId}`);
        
        let html = '';
        
        if (countryManagers.length === 0) {
            // Show "Add New" button even when no Country Managers are assigned
            html = `
                <div class="country-manager-item">
                                         <div class="country-manager-header">
                         <div class="country-manager-name text-muted">No Country Managers assigned yet</div>
                         <button class="add-new-btn country-level" onclick="showAddCountryManagerModal(${adminManagerId})">
                             Add New
                         </button>
                     </div>
                </div>
            `;
        } else {
            // Show existing Country Managers with expandable structure
            countryManagers.forEach(function(countryManager) {
                html += `
                    <div class="country-manager-item">
                        <div class="country-manager-header" onclick="toggleManagers(${adminManagerId}, ${countryManager.id})">
                            <div class="country-manager-name">
                                ${countryManager.name} (${countryManager.email})
                                <span class="type-tag country-tag">Country Manager</span>
                            </div>
                                                         <button class="expand-btn country-level" id="expand-manager-${adminManagerId}-${countryManager.id}" onclick="event.stopPropagation(); toggleManagers(${adminManagerId}, ${countryManager.id});">+</button>
                        </div>
                        <div class="managers-container" id="managers-container-${adminManagerId}-${countryManager.id}">
                            <div class="text-center p-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <span class="ms-2">Loading Account Managers...</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Add "Add New" button at the end
            html += `
                                 <div class="country-manager-item">
                     <div class="country-manager-header">
                         <div class="country-manager-name text-muted">Add more Country Managers</div>
                         <button class="add-new-btn country-level" onclick="showAddCountryManagerModal(${adminManagerId})">
                             Add New
                         </button>
                     </div>
                 </div>
            `;
        }
        
        container.html(html);
    }
    
    // Display Admin Manager Statistics
    function displayAdminManagerStats(adminManagerId, stats) {
        // console.log('Displaying Admin Manager stats for ID:', adminManagerId, 'Stats:', stats);
        const container = $(`#country-container-${adminManagerId}`);
        // console.log('Container found:', container.length > 0);
        
        // Remove existing stats if any
        container.find('.stats-container').remove();
        
        const statsHtml = `
            <div class="stats-container admin-manager-stats" data-admin-manager-id="${adminManagerId}">
                <h5>Admin Manager Statistics</h5>
                <div class="stats-grid">
                    <div class="stat-card admin-level">
                        <h3>${stats.total_traders || 0}</h3>
                        <p>Total Traders</p>
                    </div>
                    <div class="stat-card admin-level">
                        <h3>${stats.total_ibs || 0}</h3>
                        <p>Total IBs</p>
                    </div>
                    <div class="stat-card admin-level">
                        <h3>$${stats.total_deposit || 0}</h3>
                        <p>Total Deposit</p>
                    </div>
                    <div class="stat-card admin-level">
                        <h3>$${stats.total_withdraw || 0}</h3>
                        <p>Total Withdraw</p>
                    </div>
                </div>
                
                <div class="stats-grid mt-3">
                    <div class="stat-card admin-level today">
                        <h3>${stats.today_traders || 0}</h3>
                        <p>Today Traders</p>
                    </div>
                    <div class="stat-card admin-level today">
                        <h3>${stats.today_ibs || 0}</h3>
                        <p>Today IBs</p>
                    </div>
                    <div class="stat-card admin-level today">
                        <h3>$${stats.today_deposit || 0}</h3>
                        <p>Today Deposit</p>
                    </div>
                    <div class="stat-card admin-level today">
                        <h3>$${stats.today_withdraw || 0}</h3>
                        <p>Today Withdraw</p>
                    </div>
                </div>
                
                <div class="stats-grid mt-3">
                    <div class="stat-card admin-level this-month">
                        <h3>${stats.this_month_traders || 0}</h3>
                        <p>This Month Traders</p>
                    </div>
                    <div class="stat-card admin-level this-month">
                        <h3>${stats.this_month_ibs || 0}</h3>
                        <p>This Month IBs</p>
                    </div>
                    <div class="stat-card admin-level this-month">
                        <h3>$${stats.this_month_deposit || 0}</h3>
                        <p>This Month Deposit</p>
                    </div>
                    <div class="stat-card admin-level this-month">
                        <h3>$${stats.this_month_withdraw || 0}</h3>
                        <p>This Month Withdraw</p>
                    </div>
                </div>
                
                <div class="stats-grid mt-3">
                    <div class="stat-card admin-level last-month">
                        <h3>${stats.last_month_traders || 0}</h3>
                        <p>Last Month Traders</p>
                    </div>
                    <div class="stat-card admin-level last-month">
                        <h3>${stats.last_month_ibs || 0}</h3>
                        <p>Last Month IBs</p>
                    </div>
                    <div class="stat-card admin-level last-month">
                        <h3>$${stats.last_month_deposit || 0}</h3>
                        <p>Last Month Deposit</p>
                    </div>
                    <div class="stat-card admin-level last-month">
                        <h3>$${stats.last_month_withdraw || 0}</h3>
                        <p>Last Month Withdraw</p>
                    </div>
                </div>
                
                <div class="revenue-container">
                    <div class="revenue-header">
                        <h5>Revenue Reports</h5>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                                <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                <span>Deposit</span>
                            </div>
                            <div class="d-flex align-items-center ms-75">
                                <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                <span>Withdraw</span>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group justify-content-between d-flex mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @php echo(date('F')) @endphp
                        </button>
                        <div class="dropdown-menu" style="max-height: 200px; overflow:scroll;">
                            @php $months_name = \App\Services\AllFunctionService::months_with_name(); @endphp
                            @for ($i = 0; $i < (int) date('m'); $i++) 
                                <a class="dropdown-item month-filter-item" href="javascript:void(0);" data-month="{{ $months_name['month'][$i] }}">{{ $months_name['name'][$i] }}</a>
                            @endfor
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h2 class="mb-25 d-flex font-medium-3" id="deposit-amount-admin-${adminManagerId}">
                            <span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $0
                        </h2>
                        <div class="d-flex">
                            <span class="fw-bolder me-25 bg-secondary me-2 rounded" title="Withdraw" style="width: 28px;"></span>
                            <span id="withdraw-amount-admin-${adminManagerId}">$0</span>
                        </div>
                    </div>
                    <div id="revenue-chart-admin-${adminManagerId}" style="height: 230px;"></div>
                </div>
            </div>
        `;
        
        // Add stats to the container
        container.prepend(statsHtml);
        
        // Ensure container is expanded to show stats
        if (!container.hasClass('expanded')) {
            container.addClass('expanded');
            $(`#expand-${adminManagerId}`).addClass('expanded');
        }
        
        // Load initial revenue data and create chart
        loadAdminManagerRevenueData(adminManagerId);
        
        // Initialize month filter
        initializeMonthFilter(adminManagerId, 'admin');
    }
    
    // Show Add Country Manager Modal
    window.showAddCountryManagerModal = function(adminManagerId) {
        currentAdminManagerId = adminManagerId;
        
        // Load unassigned Country Managers
        $.get('/admin/manager-settings/manage-all/available-users?level=country_admin', function(response) {
            if (response.status) {
                // Filter out ALL assigned Country Managers (globally)
                $.get('/admin/manager-settings/manage-all/get-all-assigned-country-managers', function(assignedResponse) {
                    let assignedIds = [];
                    if (assignedResponse.status) {
                        assignedIds = assignedResponse.country_managers.map(cm => cm.id);
                    }
                    
                    const unassignedManagers = response.users.filter(user => !assignedIds.includes(user.id));
                    
                    let options = '';
                    unassignedManagers.forEach(function(manager) {
                        options += `<option value="${manager.id}">${manager.name} (${manager.email})</option>`;
                    });
                    
                    $('#unassignedCountryManagers').html(options);
                    $('#unassignedCountryManagers').val(null).trigger('change');
                    
                    if (unassignedManagers.length === 0) {
                        $('#unassignedCountryManagers').html('<option value="" disabled>No unassigned Country Managers available</option>');
                    }
                    
                    $('#addCountryManagerModal').modal('show');
                });
            } else {
                Swal.fire('Error', 'Failed to load Country Managers', 'error');
            }
        });
    };
    
    // Toggle Managers visibility
    window.toggleManagers = function(adminManagerId, countryManagerId) {
        // console.log('toggleManagers called with:', adminManagerId, countryManagerId);
        const container = $(`#managers-container-${adminManagerId}-${countryManagerId}`);
        const btn = $(`#expand-manager-${adminManagerId}-${countryManagerId}`);
        
        // console.log('Container found:', container.length > 0);
        // console.log('Container ID:', container.attr('id'));
        
        if (container.hasClass('expanded')) {
            // Collapse
            container.removeClass('expanded');
            btn.removeClass('expanded');
        } else {
            // Expand
            container.addClass('expanded');
            btn.addClass('expanded');
            
            // Load Managers if not already loaded
            if (container.find('.manager-item').length === 0) {
                // console.log('Loading managers for Country Manager:', countryManagerId);
                // Load managers first, then stats after a short delay
                loadManagers(adminManagerId, countryManagerId);
                setTimeout(function() {
                    // console.log('Loading Country Manager stats for ID:', countryManagerId);
                    loadCountryManagerStats(countryManagerId);
                }, 500);
            }
        }
    };
    
    // Load Managers for a specific Country Manager
    function loadManagers(adminManagerId, countryManagerId) {
        const container = $(`#managers-container-${adminManagerId}-${countryManagerId}`);
        
        // Get assigned Managers from the assignments table
        $.get(`/admin/manager-settings/manage-all/get-assigned-managers?country_manager_id=${countryManagerId}`, function(response) {
            if (response.status) {
                displayManagers(adminManagerId, countryManagerId, response.managers);
            } else {
                // Even if there's an error, show the "Add New" button
                displayManagers(adminManagerId, countryManagerId, []);
            }
        }).fail(function() {
            // Even if the request fails, show the "Add New" button
            displayManagers(adminManagerId, countryManagerId, []);
        });
    }
    

    
         // Load Admin Manager Revenue Data
     function loadAdminManagerRevenueData(adminManagerId) {
         $.get(`/admin/manager-settings/manage-all/get-revenue-data`, {
             admin_manager_id: adminManagerId
         }, function(response) {
             if (response.status) {
                 updateAdminManagerRevenueChart(adminManagerId, response.data, response.monthly_data);
             }
         });
     }
    
    // Display Managers
    function displayManagers(adminManagerId, countryManagerId, managers) {
        const container = $(`#managers-container-${adminManagerId}-${countryManagerId}`);
        
        let html = '';
        
        if (managers.length === 0) {
            // Show "Add New" button even when no Managers are assigned
            html = `
                <div class="manager-item">
                                         <div class="manager-header">
                         <div class="manager-name text-muted">No Account Managers assigned yet</div>
                         <button class="add-new-btn account-level" onclick="showAddManagerModal(${adminManagerId}, ${countryManagerId})">
                             Add New Account Managers
                         </button>
                     </div>
                </div>
            `;
        } else {
            // Show existing Managers
            managers.forEach(function(manager) {
                html += `
                    <div class="manager-item">
                        <div class="manager-header" onclick="toggleManagerStats(${adminManagerId}, ${countryManagerId}, ${manager.id})">
                            <div class="manager-name">
                                ${manager.name} (${manager.email})
                                <span class="type-tag account-tag">Account Manager</span>
                            </div>
                                                         <div class="expand-btn account-level" id="expand-btn-${manager.id}">+</div>
                        </div>
                        <div class="stats-report-container" id="stats-report-${manager.id}">
                            <div class="text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
                         // Add "Add New" button at the end
             html += `
                 <div class="manager-item">
                     <div class="manager-header">
                         <div class="manager-name text-muted">Add more Account Managers</div>
                         <button class="add-new-btn account-level" onclick="showAddManagerModal(${adminManagerId}, ${countryManagerId})">
                             Add New Account Managers
                         </button>
                     </div>
                 </div>
             `;
        }
        
        container.html(html);
        // console.log('Displayed managers for Country Manager:', countryManagerId, 'Managers:', managers);
    }
    
          // Display Country Manager Statistics
      function displayCountryManagerStats(adminManagerId, countryManagerId, stats) {
          // console.log('Displaying Country Manager stats for ID:', countryManagerId, 'Stats:', stats);
          const container = $(`#managers-container-${adminManagerId}-${countryManagerId}`);
          // console.log('Container found:', container.length > 0);
          
          // Remove existing stats if any
          container.find('.country-manager-stats').remove();
          
          const statsHtml = `
               <div class="stats-container country-manager-stats" data-country-manager-id="${countryManagerId}">
                   <h5>Country Manager Statistics</h5>
                   <div class="stats-grid">
                       <div class="stat-card country-level">
                           <h3>${stats.total_traders || 0}</h3>
                           <p>Total Traders</p>
                       </div>
                       <div class="stat-card country-level">
                           <h3>${stats.total_ibs || 0}</h3>
                           <p>Total IBs</p>
                       </div>
                       <div class="stat-card country-level">
                           <h3>$${stats.total_deposit || 0}</h3>
                           <p>Total Deposit</p>
                       </div>
                       <div class="stat-card country-level">
                           <h3>$${stats.total_withdraw || 0}</h3>
                           <p>Total Withdraw</p>
                       </div>
                   </div>
                   
                                       <div class="stats-grid mt-3">
                        <div class="stat-card country-level today">
                            <h3>${stats.today_traders || 0}</h3>
                            <p>Today Traders</p>
                        </div>
                        <div class="stat-card country-level today">
                            <h3>${stats.today_ibs || 0}</h3>
                            <p>Today IBs</p>
                        </div>
                        <div class="stat-card country-level today">
                            <h3>$${stats.today_deposit || 0}</h3>
                            <p>Today Deposit</p>
                        </div>
                        <div class="stat-card country-level today">
                            <h3>$${stats.today_withdraw || 0}</h3>
                            <p>Today Withdraw</p>
                        </div>
                    </div>
                    
                    <div class="stats-grid mt-3">
                        <div class="stat-card country-level this-month">
                            <h3>${stats.this_month_traders || 0}</h3>
                            <p>This Month Traders</p>
                        </div>
                        <div class="stat-card country-level this-month">
                            <h3>${stats.this_month_ibs || 0}</h3>
                            <p>This Month IBs</p>
                        </div>
                        <div class="stat-card country-level this-month">
                            <h3>$${stats.this_month_deposit || 0}</h3>
                            <p>This Month Deposit</p>
                        </div>
                        <div class="stat-card country-level this-month">
                            <h3>$${stats.this_month_withdraw || 0}</h3>
                            <p>This Month Withdraw</p>
                        </div>
                    </div>
                    
                    <div class="stats-grid mt-3">
                        <div class="stat-card country-level last-month">
                            <h3>${stats.last_month_traders || 0}</h3>
                            <p>Last Month Traders</p>
                        </div>
                        <div class="stat-card country-level last-month">
                            <h3>${stats.last_month_ibs || 0}</h3>
                            <p>Last Month IBs</p>
                        </div>
                        <div class="stat-card country-level last-month">
                            <h3>$${stats.last_month_deposit || 0}</h3>
                            <p>Last Month Deposit</p>
                        </div>
                        <div class="stat-card country-level last-month">
                            <h3>$${stats.last_month_withdraw || 0}</h3>
                            <p>Last Month Withdraw</p>
                        </div>
                    </div>
                  
                  <div class="revenue-container">
                       <div class="revenue-header">
                           <h5>Revenue Reports</h5>
                           <div class="d-flex align-items-center">
                               <div class="d-flex align-items-center me-2">
                                   <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                   <span>Deposit</span>
                               </div>
                               <div class="d-flex align-items-center ms-75">
                                    <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                    <span>Withdraw</span>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group justify-content-between d-flex mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @php echo(date('F')) @endphp
                            </button>
                            <div class="dropdown-menu" style="max-height: 200px; overflow:scroll;">
                                @php $months_name = \App\Services\AllFunctionService::months_with_name(); @endphp
                                @for ($i = 0; $i < (int) date('m'); $i++) 
                                    <a class="dropdown-item month-filter-item" href="javascript:void(0);" data-month="{{ $months_name['month'][$i] }}">{{ $months_name['name'][$i] }}</a>
                                @endfor
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h2 class="mb-25 d-flex font-medium-3" id="deposit-amount-country-${countryManagerId}">
                                <span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $0
                            </h2>
                            <div class="d-flex">
                                <span class="fw-bolder me-25 bg-secondary me-2 rounded" title="Withdraw" style="width: 28px;"></span>
                                <span id="withdraw-amount-country-${countryManagerId}">$0</span>
                            </div>
                        </div>
                        <div id="revenue-chart-country-${countryManagerId}" style="height: 230px;"></div>
                    </div>
               </div>
           `;
           
          // Add stats to the container at the beginning
          container.prepend(statsHtml);
          
          // Ensure container is expanded to show stats
          if (!container.hasClass('expanded')) {
              container.addClass('expanded');
              $(`#expand-manager-${adminManagerId}-${countryManagerId}`).addClass('expanded');
          }
          
          // Load initial revenue data and create chart
          loadCountryManagerRevenueData(countryManagerId);
          
          // Initialize month filter
          initializeMonthFilter(countryManagerId, 'country');
    }
    
    // Toggle Manager Statistics
    window.toggleManagerStats = function(adminManagerId, countryManagerId, managerId) {
        event.stopPropagation();
        const statsContainer = $(`#stats-report-${managerId}`);
        const expandBtn = $(`#expand-btn-${managerId}`);
        
        if (statsContainer.hasClass('expanded')) {
            // Collapse
            statsContainer.removeClass('expanded');
            expandBtn.removeClass('expanded');
        } else {
            // Expand and load stats
            statsContainer.addClass('expanded');
            expandBtn.addClass('expanded');
            loadManagerStats(managerId);
        }
    };
    

    
    // Display Manager Statistics
    function displayManagerStats(adminManagerId, countryManagerId, managerId, stats) {
        const container = $(`#stats-report-${managerId}`);
        
                 const statsHtml = `
             <div class="stats-container manager-stats" data-manager-id="${managerId}">
                 <h5>Statistics for Account Manager</h5>
                 <div class="stats-grid">
                     <div class="stat-card account-level">
                         <h3>${stats.total_traders || 0}</h3>
                         <p>Total Traders</p>
                     </div>
                     <div class="stat-card account-level">
                         <h3>${stats.total_ibs || 0}</h3>
                         <p>Total IBs</p>
                     </div>
                     <div class="stat-card account-level">
                         <h3>$${stats.total_deposit || 0}</h3>
                         <p>Total Deposit</p>
                     </div>
                     <div class="stat-card account-level">
                         <h3>$${stats.total_withdraw || 0}</h3>
                         <p>Total Withdraw</p>
                     </div>
                 </div>
                 
                                   <div class="stats-grid mt-3">
                      <div class="stat-card account-level today">
                          <h3>${stats.today_traders || 0}</h3>
                          <p>Today Traders</p>
                      </div>
                      <div class="stat-card account-level today">
                          <h3>${stats.today_ibs || 0}</h3>
                          <p>Today IBs</p>
                      </div>
                      <div class="stat-card account-level today">
                          <h3>$${stats.today_deposit || 0}</h3>
                          <p>Today Deposit</p>
                      </div>
                      <div class="stat-card account-level today">
                          <h3>$${stats.today_withdraw || 0}</h3>
                          <p>Today Withdraw</p>
                      </div>
                  </div>
                  
                  <div class="stats-grid mt-3">
                      <div class="stat-card account-level this-month">
                          <h3>${stats.this_month_traders || 0}</h3>
                          <p>This Month Traders</p>
                      </div>
                      <div class="stat-card account-level this-month">
                          <h3>${stats.this_month_ibs || 0}</h3>
                          <p>This Month IBs</p>
                      </div>
                      <div class="stat-card account-level this-month">
                          <h3>$${stats.this_month_deposit || 0}</h3>
                          <p>This Month Deposit</p>
                      </div>
                      <div class="stat-card account-level this-month">
                          <h3>$${stats.this_month_withdraw || 0}</h3>
                          <p>This Month Withdraw</p>
                      </div>
                  </div>
                  
                  <div class="stats-grid mt-3">
                      <div class="stat-card account-level last-month">
                          <h3>${stats.last_month_traders || 0}</h3>
                          <p>Last Month Traders</p>
                      </div>
                      <div class="stat-card account-level last-month">
                          <h3>${stats.last_month_ibs || 0}</h3>
                          <p>Last Month IBs</p>
                      </div>
                      <div class="stat-card account-level last-month">
                          <h3>$${stats.last_month_deposit || 0}</h3>
                          <p>Last Month Deposit</p>
                      </div>
                      <div class="stat-card account-level last-month">
                          <h3>$${stats.last_month_withdraw || 0}</h3>
                          <p>Last Month Withdraw</p>
                      </div>
                  </div>
                
                                 <div class="revenue-container">
                     <div class="revenue-header">
                         <h5>Revenue Reports</h5>
                         <div class="d-flex align-items-center">
                             <div class="d-flex align-items-center me-2">
                                 <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                 <span>Deposit</span>
                             </div>
                             <div class="d-flex align-items-center ms-75">
                                 <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                 <span>Withdraw</span>
                             </div>
                         </div>
                     </div>
                     <div class="btn-group justify-content-between d-flex mb-3">
                         <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             @php echo(date('F')) @endphp
                         </button>
                         <div class="dropdown-menu" style="max-height: 200px; overflow:scroll;">
                             @php $months_name = \App\Services\AllFunctionService::months_with_name(); @endphp
                             @for ($i = 0; $i < (int) date('m'); $i++) 
                                 <a class="dropdown-item month-filter-item" href="javascript:void(0);" data-month="{{ $months_name['month'][$i] }}">{{ $months_name['name'][$i] }}</a>
                             @endfor
                         </div>
                     </div>
                     <div class="d-flex justify-content-between mb-3">
                         <h2 class="mb-25 d-flex font-medium-3" id="deposit-amount-${managerId}">
                             <span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $0
                         </h2>
                         <div class="d-flex">
                             <span class="fw-bolder me-25 bg-secondary me-2 rounded" title="Withdraw" style="width: 28px;"></span>
                             <span id="withdraw-amount-${managerId}">$0</span>
                         </div>
                     </div>
                     <div id="revenue-chart-${managerId}" style="height: 230px;"></div>
                 </div>
            </div>
        `;
        
                 // Replace loading spinner with stats
         container.html(statsHtml);
         
         // Load initial revenue data and create chart
         loadRevenueData(managerId);
         
         // Initialize month filter
         initializeMonthFilter(managerId, 'manager');
    }
    
         // Load Revenue Data
     function loadRevenueData(managerId) {
         $.get(`/admin/manager-settings/manage-all/get-revenue-data`, {
             manager_id: managerId
         }, function(response) {
             if (response.status) {
                 updateRevenueChart(managerId, response.data, response.monthly_data);
             }
         });
     }
    
         // Load Country Manager Revenue Data
     function loadCountryManagerRevenueData(countryManagerId) {
         $.get(`/admin/manager-settings/manage-all/get-revenue-data`, {
             country_manager_id: countryManagerId
         }, function(response) {
             if (response.status) {
                 updateCountryManagerRevenueChart(countryManagerId, response.data, response.monthly_data);
             }
         });
     }
    
         // Update Revenue Chart
     function updateRevenueChart(managerId, data, monthlyData) {
         // Update amounts
         $(`#deposit-amount-${managerId}`).html(`<span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $${data.deposits || 0}`);
         $(`#withdraw-amount-${managerId}`).text(`$${data.withdrawals || 0}`);
         
         // Create revenue chart
         const chartContainer = $(`#revenue-chart-${managerId}`);
         
         if (monthlyData && monthlyData.months.length > 0) {
             const chartOptions = {
                 chart: {
                     height: 230,
                     stacked: true,
                     type: 'bar',
                     toolbar: { show: false }
                 },
                 plotOptions: {
                     bar: {
                         columnWidth: '17%',
                         endingShape: 'rounded'
                     },
                     distributed: true
                 },
                 colors: ['#7367F0', '#FF9F43'],
                 series: [{
                     name: 'Deposit',
                     data: monthlyData.deposits
                 }, {
                     name: 'Withdraw',
                     data: monthlyData.withdrawals
                 }],
                 dataLabels: { enabled: false },
                 legend: { show: false },
                 grid: {
                     padding: { top: -20, bottom: -10 },
                     yaxis: { lines: { show: false } }
                 },
                 xaxis: {
                     categories: monthlyData.months,
                     labels: {
                         style: { colors: '#6E6B7B', fontSize: '0.86rem' }
                     },
                     axisTicks: { show: false },
                     axisBorder: { show: false }
                 },
                 yaxis: {
                     labels: {
                         style: { colors: '#6E6B7B', fontSize: '0.86rem' },
                         formatter: function(value) {
                             return '$' + value.toFixed(2);
                         }
                     }
                 }
             };
             
             // Destroy existing chart if it exists
             if (window[`revenueChartManager${managerId}`]) {
                 window[`revenueChartManager${managerId}`].destroy();
             }
             
             // Create new chart
             window[`revenueChartManager${managerId}`] = new ApexCharts(chartContainer[0], chartOptions);
             window[`revenueChartManager${managerId}`].render();
         } else {
             chartContainer.html('<div class="text-center text-muted"><p>No revenue data available</p></div>');
         }
     }
    
         // Update Admin Manager Revenue Chart
     function updateAdminManagerRevenueChart(adminManagerId, data, monthlyData) {
         // Update amounts
         $(`#deposit-amount-admin-${adminManagerId}`).html(`<span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $${data.deposits || 0}`);
         $(`#withdraw-amount-admin-${adminManagerId}`).text(`$${data.withdrawals || 0}`);
         
         // Create revenue chart
         const chartContainer = $(`#revenue-chart-admin-${adminManagerId}`);
         
         if (monthlyData && monthlyData.months.length > 0) {
             const chartOptions = {
                 chart: {
                     height: 230,
                     stacked: true,
                     type: 'bar',
                     toolbar: { show: false }
                 },
                 plotOptions: {
                     bar: {
                         columnWidth: '17%',
                         endingShape: 'rounded'
                     },
                     distributed: true
                 },
                 colors: ['#7367F0', '#FF9F43'],
                 series: [{
                     name: 'Deposit',
                     data: monthlyData.deposits
                 }, {
                     name: 'Withdraw',
                     data: monthlyData.withdrawals
                 }],
                 dataLabels: { enabled: false },
                 legend: { show: false },
                 grid: {
                     padding: { top: -20, bottom: -10 },
                     yaxis: { lines: { show: false } }
                 },
                 xaxis: {
                     categories: monthlyData.months,
                     labels: {
                         style: { colors: '#6E6B7B', fontSize: '0.86rem' }
                     },
                     axisTicks: { show: false },
                     axisBorder: { show: false }
                 },
                 yaxis: {
                     labels: {
                         style: { colors: '#6E6B7B', fontSize: '0.86rem' },
                         formatter: function(value) {
                             return '$' + value.toFixed(2);
                         }
                     }
                 }
             };
             
             // Destroy existing chart if it exists
             if (window[`revenueChartAdmin${adminManagerId}`]) {
                 window[`revenueChartAdmin${adminManagerId}`].destroy();
             }
             
             // Create new chart
             window[`revenueChartAdmin${adminManagerId}`] = new ApexCharts(chartContainer[0], chartOptions);
             window[`revenueChartAdmin${adminManagerId}`].render();
         } else {
             chartContainer.html('<div class="text-center text-muted"><p>No revenue data available</p></div>');
         }
     }
    
         // Update Country Manager Revenue Chart
     function updateCountryManagerRevenueChart(countryManagerId, data, monthlyData) {
         // Update amounts
         $(`#deposit-amount-country-${countryManagerId}`).html(`<span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $${data.deposits || 0}`);
         $(`#withdraw-amount-country-${countryManagerId}`).text(`$${data.withdrawals || 0}`);
         
         // Create revenue chart
         const chartContainer = $(`#revenue-chart-country-${countryManagerId}`);
         
         if (monthlyData && monthlyData.months.length > 0) {
             const chartOptions = {
                 chart: {
                     height: 230,
                     stacked: true,
                     type: 'bar',
                     toolbar: { show: false }
                 },
                 plotOptions: {
                     bar: {
                         columnWidth: '17%',
                         endingShape: 'rounded'
                     },
                     distributed: true
                 },
                 colors: ['#7367F0', '#FF9F43'],
                 series: [{
                     name: 'Deposit',
                     data: monthlyData.deposits
                 }, {
                     name: 'Withdraw',
                     data: monthlyData.withdrawals
                 }],
                 dataLabels: { enabled: false },
                 legend: { show: false },
                 grid: {
                     padding: { top: -20, bottom: -10 },
                     yaxis: { lines: { show: false } }
                 },
                 xaxis: {
                     categories: monthlyData.months,
                     labels: {
                         style: { colors: '#6E6B7B', fontSize: '0.86rem' }
                     },
                     axisTicks: { show: false },
                     axisBorder: { show: false }
                 },
                 yaxis: {
                     labels: {
                         style: { colors: '#6E6B7B', fontSize: '0.86rem' },
                         formatter: function(value) {
                             return '$' + value.toFixed(2);
                         }
                     }
                 }
             };
             
             // Destroy existing chart if it exists
             if (window[`revenueChartCountry${countryManagerId}`]) {
                 window[`revenueChartCountry${countryManagerId}`].destroy();
             }
             
             // Create new chart
             window[`revenueChartCountry${countryManagerId}`] = new ApexCharts(chartContainer[0], chartOptions);
             window[`revenueChartCountry${countryManagerId}`].render();
         } else {
             chartContainer.html('<div class="text-center text-muted"><p>No revenue data available</p></div>');
         }
     }
    
    // Show Add Manager Modal
    window.showAddManagerModal = function(adminManagerId, countryManagerId) {
        // console.log('showAddManagerModal called with:', adminManagerId, countryManagerId);
        currentAdminManagerId = adminManagerId;
        currentCountryManagerId = countryManagerId;
        
        // Load unassigned Managers (type 5 - Account Managers)
        $.get('/admin/manager-settings/manage-all/available-users?level=manager', function(response) {
            // console.log('Available users response:', response);
            if (response.status) {
                // Filter out ALL assigned Managers (globally)
                $.get('/admin/manager-settings/manage-all/get-all-assigned-managers', function(assignedResponse) {
                    // console.log('Assigned managers response:', assignedResponse);
                    let assignedIds = [];
                    if (assignedResponse.status) {
                        assignedIds = assignedResponse.managers.map(m => m.id);
                    }
                    
                    const unassignedManagers = response.users.filter(user => !assignedIds.includes(user.id));
                    // console.log('Unassigned managers:', unassignedManagers);
                    
                    let options = '';
                    unassignedManagers.forEach(function(manager) {
                        options += `<option value="${manager.id}">${manager.name} (${manager.email}) - ${manager.group_name || 'No Group'}</option>`;
                    });
                    
                    $('#unassignedManagers').html(options);
                    $('#unassignedManagers').val(null).trigger('change');
                    
                    if (unassignedManagers.length === 0) {
                        $('#unassignedManagers').html('<option value="" disabled>No unassigned Account Managers available</option>');
                    }
                    
                    $('#addManagerModal').modal('show');
                });
            } else {
                // console.error('Failed to load managers:', response);
                Swal.fire('Error', 'Failed to load Account Managers', 'error');
            }
        });
    };
    
    // Handle Country Manager assignment button click
    $('#assignCountryManagers').on('click', function() {
        const selectedIds = $('#unassignedCountryManagers').val();
        
        if (!selectedIds || selectedIds.length === 0) {
            Swal.fire('Error', 'Please select at least one Country Manager', 'error');
            return;
        }
        
        // Submit the assignment
        $.post('/admin/manager-settings/manage-all/assign-country-admins', {
            admin_manager_id: currentAdminManagerId,
            country_admin_ids: selectedIds,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            if (response.status) {
                Swal.fire('Success', response.message, 'success');
                $('#addCountryManagerModal').modal('hide');
                
                // Refresh the Country Managers list
                loadCountryManagers(currentAdminManagerId);
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }).fail(function() {
            Swal.fire('Error', 'Failed to assign Country Managers', 'error');
        });
    });
    
    // Handle Manager assignment button click
    $('#assignManagers').on('click', function() {
        const selectedIds = $('#unassignedManagers').val();
        
        if (!selectedIds || selectedIds.length === 0) {
            Swal.fire('Error', 'Please select at least one Manager', 'error');
            return;
        }
        
        // Submit the assignment
        $.post('/admin/manager-settings/manage-all/assign-managers', {
            country_manager_id: currentCountryManagerId,
            manager_ids: selectedIds,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            if (response.status) {
                Swal.fire('Success', response.message, 'success');
                $('#addManagerModal').modal('hide');
                
                // Refresh the Managers list
                loadManagers(currentAdminManagerId, currentCountryManagerId);
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }).fail(function() {
            Swal.fire('Error', 'Failed to assign Managers', 'error');
                 });
     });
     
           // Initialize month filter functionality
      function initializeMonthFilter(managerId, type) {
          // Remove any existing event handlers to prevent duplicates
          $(document).off('click', `.month-filter-item[data-month]`);
          
          $(document).on('click', `.month-filter-item[data-month]`, function(e) {
              e.preventDefault();
              e.stopPropagation();
              
              var month = $(this).attr('data-month');
              var monthName = $(this).text();
              
              // console.log('Month clicked:', month, monthName, 'Type:', type, 'ManagerId:', managerId);
              
              // Update the dropdown button text
              $(this).closest('.revenue-container').find('.budget-dropdown').text(monthName);
              
              // Determine which type of manager and call appropriate function
              if (type === 'admin') {
                  loadAdminManagerRevenueDataByMonth(managerId, month);
              } else if (type === 'country') {
                  loadCountryManagerRevenueDataByMonth(managerId, month);
              } else if (type === 'manager') {
                  loadManagerRevenueDataByMonth(managerId, month);
              }
          });
      }
     
     // Load Admin Manager Revenue Data by Month
     function loadAdminManagerRevenueDataByMonth(adminManagerId, month) {
         $.get(`/admin/manager-settings/manage-all/get-revenue-data`, {
             admin_manager_id: adminManagerId,
             month: month
         }, function(response) {
             if (response.status) {
                 updateAdminManagerRevenueChart(adminManagerId, response.data, response.monthly_data);
             }
         });
     }
     
     // Load Country Manager Revenue Data by Month
     function loadCountryManagerRevenueDataByMonth(countryManagerId, month) {
         $.get(`/admin/manager-settings/manage-all/get-revenue-data`, {
             country_manager_id: countryManagerId,
             month: month
         }, function(response) {
             if (response.status) {
                 updateCountryManagerRevenueChart(countryManagerId, response.data, response.monthly_data);
             }
         });
     }
     
     // Load Manager Revenue Data by Month
     function loadManagerRevenueDataByMonth(managerId, month) {
         $.get(`/admin/manager-settings/manage-all/get-revenue-data`, {
             manager_id: managerId,
             month: month
         }, function(response) {
             if (response.status) {
                 updateRevenueChart(managerId, response.data, response.monthly_data);
             }
         });
     }
     
     // Global date range variables
     let globalStartDate = null;
     let globalEndDate = null;
     
     // Initialize date range filter
     $(document).ready(function() {
         // Set default dates (current month)
         const today = new Date();
         const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
         const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
         
         $('#startDate').val(firstDay.toISOString().split('T')[0]);
         $('#endDate').val(lastDay.toISOString().split('T')[0]);
         
         globalStartDate = $('#startDate').val();
         globalEndDate = $('#endDate').val();
     });
     
     // Apply date range filter
     $('#applyDateFilter').on('click', function() {
         const startDate = $('#startDate').val();
         const endDate = $('#endDate').val();
         
         if (!startDate || !endDate) {
             Swal.fire('Error', 'Please select both start and end dates', 'error');
             return;
         }
         
         if (new Date(startDate) > new Date(endDate)) {
             Swal.fire('Error', 'Start date cannot be after end date', 'error');
             return;
         }
         
         globalStartDate = startDate;
         globalEndDate = endDate;
         
         // Show loading indicator
         Swal.fire({
             title: 'Applying Filter...',
             text: 'Updating statistics for all hierarchy levels',
             allowOutsideClick: false,
             didOpen: () => {
                 Swal.showLoading();
             }
         });
         
         // Refresh all expanded statistics
         refreshAllStatistics();
         
         // Close loading indicator after a short delay
         setTimeout(() => {
             Swal.close();
             Swal.fire('Success', 'Date range filter applied successfully', 'success');
         }, 1000);
     });
     
     // Reset date range filter
     $('#resetDateFilter').on('click', function() {
         const today = new Date();
         const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
         const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
         
         $('#startDate').val(firstDay.toISOString().split('T')[0]);
         $('#endDate').val(lastDay.toISOString().split('T')[0]);
         
         globalStartDate = $('#startDate').val();
         globalEndDate = $('#endDate').val();
         
         // Refresh all statistics
         refreshAllStatistics();
         
         Swal.fire('Success', 'Date range filter reset to current month', 'success');
     });
     
     // Function to refresh all statistics
     function refreshAllStatistics() {
         // Refresh Admin Manager statistics
         $('.admin-manager-stats').each(function() {
             const adminManagerId = $(this).data('admin-manager-id');
             if (adminManagerId) {
                 loadAdminManagerStats(adminManagerId);
             }
         });
         
         // Refresh Country Manager statistics
         $('.country-manager-stats').each(function() {
             const countryManagerId = $(this).data('country-manager-id');
             if (countryManagerId) {
                 loadCountryManagerStats(countryManagerId);
             }
         });
         
         // Refresh Account Manager statistics
         $('.manager-stats').each(function() {
             const managerId = $(this).data('manager-id');
             if (managerId) {
                 loadManagerStats(managerId);
             }
         });
     }
     
         // Update loadAdminManagerStats function to include date parameters
    function loadAdminManagerStats(adminManagerId) {
        // console.log('Loading Admin Manager stats for ID:', adminManagerId);
        $.get('/admin/manager-settings/manage-all/get-admin-manager-stats', {
            admin_manager_id: adminManagerId,
            start_date: globalStartDate,
            end_date: globalEndDate
        }, function(response) {
            // console.log('Admin Manager stats response:', response);
            if (response.status) {
                displayAdminManagerStats(adminManagerId, response.stats);
            } else {
                // console.error('Failed to load Admin Manager stats:', response);
            }
        }).fail(function(xhr, status, error) {
            // console.error('Error loading Admin Manager stats:', error);
        });
    }
     
         // Update loadCountryManagerStats function to include date parameters
    function loadCountryManagerStats(countryManagerId) {
        // console.log('Loading Country Manager stats for ID:', countryManagerId);
        $.get('/admin/manager-settings/manage-all/get-country-manager-stats', {
            country_manager_id: countryManagerId,
            start_date: globalStartDate,
            end_date: globalEndDate
        }, function(response) {
            // console.log('Country Manager stats response:', response);
            if (response.status) {
                // Find the admin manager ID from the container
                const container = $(`[id*="managers-container-"][id*="-${countryManagerId}"]`);
                if (container.length > 0) {
                    const containerId = container.attr('id');
                    const adminManagerId = containerId.match(/managers-container-(\d+)-/)[1];
                    displayCountryManagerStats(adminManagerId, countryManagerId, response.stats);
                } else {
                    // console.error('Container not found for Country Manager:', countryManagerId);
                }
            } else {
                // console.error('Failed to load Country Manager stats:', response);
            }
        }).fail(function(xhr, status, error) {
            // console.error('Error loading Country Manager stats:', error);
        });
    }
     
     // Update loadManagerStats function to include date parameters
     function loadManagerStats(managerId) {
         $.get('/admin/manager-settings/manage-all/get-manager-stats', {
             manager_id: managerId,
             start_date: globalStartDate,
             end_date: globalEndDate
         }, function(response) {
             if (response.status) {
                 displayManagerStats(null, null, managerId, response.stats);
             }
         });
     }
 });
 </script>
@endsection