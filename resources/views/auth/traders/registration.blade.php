@extends('layouts.trader-auth')
@section('title', 'Trader Registration')
@section('style')
<link rel="stylesheet" href="{{ asset('admin-assets/app-assets/fonts/flag-icon-css/css/flag-icon.min.css') }}">
<style>
    .date_picker_field:focus {
        color: #495057;
        background-color: #fff;
        border-color: var(--custom-primary);
        outline: 0;
        box-shadow: 0 0 0 2px var(--custom-primary);
    }

    #date_of_birth {
        border-top-right-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
        font-size: 0.9rem;
        padding-left: 1rem;
    }

    .input-rang-group-date-logo {
        display: flex;
        align-items: center;
        padding: 0.6rem 0.6rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.45;
        color: #6e6b7b;
        text-align: center;
        white-space: nowrap;
        background-color: #fff;
        border-top-left-radius: 0.5rem !important;
        border-bottom-left-radius: 0.5rem !important;
        border-right: none !important;
    }

    .error-msg {
        color: red;
        font-size: 14px;
        display: block;
    }

    .language-nav {
        float: right;
        position: absolute;
        top: 13px;
        right: -52px;
    }

    .choices__inner .error-msg {
        position: absolute;
        bottom: -26px;
        left: 0;
    }

    .date-of-birth-gp .error-msg {
        position: absolute;
        bottom: -26px;
    }

    #server-grp .choices[data-type*="select-one"] .choices__input,
    #gender-grp .choices[data-type*="select-one"] .choices__input,
    #account-type-grp .choices[data-type*="select-one"] .choices__input {
        display: none;
        width: 100%;
        padding: 10px;
        border-bottom: 1px solid #dddddd;
        background-color: #ffffff;
        margin: 0;
    }

    .flag-icon {
        margin-right: 5px;
    }

    .pasGen-form-group {
        position: relative;
    }

    .copy_btn {
        position: absolute;
        top: -31px;
        right: 0;
        z-index: 99;
        border: none;
        background: var(--custom-primary);
        padding: 0 12px;
        display: none;
        border-radius: 5px !important;
        color: #fff;
    }

    .copy_btn::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        top: 24px;
        left: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid var(--custom-primary);
        border-bottom: 8px solid transparent;
        right: 0;
        margin: 0 auto;
    }

    .btn-gen-password {
        color: #fff;
    }

    .copy_password {
        border: 1px solid #d2d6da !important;
        padding: 0.5rem 0.75rem !important;
    }

    .info-icon {
        margin-right: -5px;
        background: var(--custom-primary);
        color: #fff;
    }

    .input-group-text+.form-control {
        padding-left: 10px !important;
    }

    .pass_toltip_content {
        margin: 0;
        background: #E0E5EA;
        font-size: 13px;
        position: absolute;
        top: -190px;
        padding: 19px 25px;
        border-radius: 5px !important;
        display: none;
        list-style: none;
        z-index: 99999;
    }

    .pass_toltip_content::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        top: 100%;
        left: 3px;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-top: 20px solid #E0E5EA;
        border-bottom: 15px solid transparent;
    }

    .pas_info_text {
        margin: 0;
        font-size: 16px;
    }

    .pass_toltip_content li i {
        margin-right: 5px;
    }

    .page-header {
        overflow: inherit;
    }

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
    
    .phone-input-container:focus-within .country-selector {
        background: #e3f2fd;
        border-right-color: #667eea;
        box-shadow: inset 0 0 0 1px rgba(102, 126, 234, 0.2);
    }
    
    .phone-input-container:focus-within .phone-number-input {
        background: #fafbfc;
        box-shadow: inset 0 0 0 1px rgba(102, 126, 234, 0.1);
    }
    
    .phone-input-container:focus-within .flag-icon {
        transform: scale(1.05);
        border-color: rgba(102, 126, 234, 0.3);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    }
    
    .phone-input-container:focus-within .country-code-select {
        color: #667eea;
        font-weight: 700;
    }
    
    /* Country selector styling */
    .country-selector {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 8px 12px;
        border-right: 1px solid #e0e0e0;
        position: relative;
        min-width: 140px;
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
    
    .country-selector:active .flag-icon {
        transform: scale(0.95);
    }
    
    .country-selector:active .country-code-select {
        color: #666;
        font-weight: 600;
    }
    
    .country-selector:active .dropdown-arrow {
        transform: translateY(0);
        opacity: 0.5;
    }
    
    .flag-container {
        margin-right: 8px;
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    
    .flag-icon {
        width: 18px;
        height: 13px;
        border-radius: 2px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        object-fit: cover;
        display: block;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .country-selector:hover .flag-icon {
        transform: scale(1.05);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        border-color: rgba(0, 0, 0, 0.2);
    }
    
    .country-code-select {
        background: transparent;
        border: none;
        outline: none;
        font-weight: 600;
        color: #333;
        font-size: 13px;
        cursor: pointer;
        min-width: 60px;
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
    
    .phone-number-input:focus::placeholder {
        color: #cbd5e0;
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
        
        .flag-icon {
            width: 18px;
            height: 13px;
        }
        
        .country-code-select {
            font-size: 14px;
            min-width: 60px;
        }
        
        .dropdown-arrow {
            right: 16px;
            font-size: 12px;
        }
        
        .flag-container {
            margin-right: 10px;
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
        
        .flag-icon {
            width: 16px;
            height: 12px;
        }
        
        .country-code-select {
            font-size: 12px;
            min-width: 50px;
        }
        
        .dropdown-arrow {
            right: 12px;
            font-size: 10px;
        }
        
        .flag-container {
            margin-right: 8px;
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
        
        .flag-icon {
            width: 15px;
            height: 11px;
        }
        
        .country-code-select {
            font-size: 11px;
            min-width: 45px;
        }
        
        .dropdown-arrow {
            right: 10px;
            font-size: 9px;
        }
        
        .flag-container {
            margin-right: 6px;
        }
    }
    
    .input-group .choices .choices__list--dropdown {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        max-height: 250px;
        overflow-y: auto;
        margin-top: 4px;
    }
    
    .input-group .choices .choices__list--dropdown .choices__item {
        padding: 10px 12px;
        font-size: 14px;
        color: #495057;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .input-group .choices .choices__list--dropdown .choices__item:hover {
        background: #ff8e5c;
        color: white;
    }
    
    .input-group .choices .choices__list--dropdown .choices__item--selectable {
        cursor: pointer;
    }
    
    .input-group .choices .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background: #ff8e5c;
        color: white;
    }
    
    /* Phone input field styling */
    .input-group input[name="phone"] {
        height: 48px;
        padding: 12px 16px;
        font-size: 16px;
        background: #f8f9fa;
        border: none;
        color: #495057;
        transition: all 0.3s ease;
    }
    
    .input-group input[name="phone"]:focus {
        background: white;
        box-shadow: inset 0 0 0 2px #667eea;
        outline: none;
    }
    
    .input-group input[name="phone"]::placeholder {
        color: #6c757d;
        opacity: 0.7;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .input-group {
            flex-direction: row;
            border-radius: 12px;
            margin: 0;
        }
        
        #country_code {
            flex: 0 0 100px;
            border-radius: 12px 0 0 12px !important;
            min-height: 48px;
        }
        
        .input-group .choices {
            flex: 0 0 100px;
        }
        
        .input-group .choices .choices__inner {
            border-radius: 12px 0 0 12px !important;
            min-height: 48px;
            padding: 12px 8px;
            font-size: 14px;
        }
        
        #country_code + .form-control {
            border-radius: 0 12px 12px 0 !important;
        }
        
        .input-group input[name="phone"] {
            border-radius: 0 12px 12px 0;
            min-height: 48px;
            font-size: 16px;
            padding: 12px 16px;
        }
    }
    
    @media (max-width: 480px) {
        .input-group {
            margin: 0 10px;
        }
        
        .input-group input[name="phone"] {
            font-size: 14px;
            padding: 10px 12px;
        }
        
        .input-group .choices .choices__inner {
            min-height: 44px;
            padding: 10px 8px;
        }
    }
    
    /* Comprehensive Mobile Responsive Styles */
    @media (max-width: 768px) {
        /* Main container adjustments */
        .multisteps-form {
            padding: 0 15px;
            margin-top: 0 !important;
        }
        
        .col-12.col-lg-8.m-auto {
            padding: 0;
        }
        
        /* Remove top spacing */
        .row {
            margin-top: 0 !important;
        }
        
        .mt-5 {
            margin-top: 0.5rem !important;
        }
        
        h3.mt-5 {
            margin-top: 0.25rem !important;
        }
        
        /* Additional top spacing removal */
        .col-12 {
            margin-top: 0 !important;
        }
        
        .text-center {
            margin-top: 0 !important;
        }
        
        /* Remove any top padding from body or main containers */
        body, html {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Target the main registration container */
        .multisteps-form__form {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Form card adjustments */
        .card.multisteps-form__panel {
            margin: 10px 0;
            padding: 20px 15px !important;
        }
        
        /* Row and column adjustments */
        .row {
            margin: 0 -10px;
        }
        
        .col-12, .col-sm-6 {
            padding: 0 10px;
            margin-bottom: 15px;
        }
        
        /* Form group adjustments */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }
        
        /* Input field adjustments */
        .multisteps-form__input,
        .form-control {
            height: 45px;
            font-size: 16px;
            padding: 10px 12px;
            border-radius: 8px;
        }
        
        /* Button adjustments */
        .btn {
            padding: 12px 20px;
            font-size: 14px;
            border-radius: 8px;
        }
        
        .button-row {
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }
        
        .button-row .col-4,
        .button-row .col-8 {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .button-row .btn {
            width: 100%;
            max-width: 280px;
        }
        
        /* Password generator adjustments */
        .password_gen {
            margin-bottom: 20px;
        }
        
        .pasGen-form-group {
            margin-bottom: 15px;
        }
        
        /* Choices.js adjustments for mobile */
        .choices {
            font-size: 16px;
        }
        
        .choices .choices__inner {
            min-height: 45px;
            padding: 10px 12px;
        }
        
        .choices .choices__list--dropdown {
            font-size: 14px;
        }
        
        .choices .choices__list--dropdown .choices__item {
            padding: 12px;
        }
        
        /* Header adjustments */
        h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        h5 {
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        /* Progress indicator adjustments */
        .multisteps-form__progress {
            margin-bottom: 30px;
        }
        
        .multisteps-form__progress-btn {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
    }
    
    @media (max-width: 480px) {
        /* Extra small devices */
        .multisteps-form {
            padding: 0 10px;
            margin-top: 0 !important;
        }
        
        /* Remove more top spacing */
        .mt-5 {
            margin-top: 0.25rem !important;
        }
        
        h3.mt-5 {
            margin-top: 0.125rem !important;
        }
        
        .row {
            margin-top: 0 !important;
        }
        
        /* Additional aggressive spacing removal */
        .col-12 {
            margin-top: 0 !important;
        }
        
        .text-center {
            margin-top: 0 !important;
        }
        
        /* Remove any top padding from body or main containers */
        body, html {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Target the main registration container */
        .multisteps-form__form {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Remove spacing from all possible containers */
        .container, .container-fluid {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        .card.multisteps-form__panel {
            padding: 15px 10px !important;
        }
        
        .col-12, .col-sm-6 {
            padding: 0 5px;
            margin-bottom: 12px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .multisteps-form__input,
        .form-control {
            height: 42px;
            font-size: 16px;
            padding: 8px 10px;
        }
        
        .btn {
            padding: 10px 16px;
            font-size: 13px;
        }
        
        .button-row .btn {
            max-width: 250px;
        }
        
        h3 {
            font-size: 22px;
        }
        
        h5 {
            font-size: 14px;
        }
        
        /* Phone input specific adjustments */
        .input-group {
            margin: 0 5px;
            flex-direction: row;
        }
        
        .input-group input[name="phone"] {
            font-size: 16px;
            padding: 8px 10px;
            height: 42px;
        }
        
        .input-group .choices .choices__inner {
            min-height: 42px;
            padding: 8px 10px;
            font-size: 14px;
        }
        
        #country_code {
            flex: 0 0 90px;
        }
        
        .input-group .choices {
            flex: 0 0 90px;
        }
    }
    
    @media (max-width: 360px) {
        /* Very small devices */
        .multisteps-form {
            padding: 0 5px;
            margin-top: 0 !important;
        }
        
        /* Remove all top spacing */
        .mt-5 {
            margin-top: 0.125rem !important;
        }
        
        h3.mt-5 {
            margin-top: 0.0625rem !important;
        }
        
        .row {
            margin-top: 0 !important;
        }
        
        /* Maximum aggressive spacing removal */
        .col-12 {
            margin-top: 0 !important;
        }
        
        .text-center {
            margin-top: 0 !important;
        }
        
        /* Remove any top padding from body or main containers */
        body, html {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Target the main registration container */
        .multisteps-form__form {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Remove spacing from all possible containers */
        .container, .container-fluid {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Remove any remaining spacing */
        * {
            margin-top: 0 !important;
        }
        
        .card.multisteps-form__panel {
            padding: 10px 5px !important;
        }
        
        .col-12, .col-sm-6 {
            padding: 0 2px;
            margin-bottom: 10px;
        }
        
        .multisteps-form__input,
        .form-control {
            height: 40px;
            font-size: 16px;
            padding: 6px 8px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 12px;
        }
        
        .button-row .btn {
            max-width: 220px;
        }
        
        h3 {
            font-size: 20px;
        }
        
        h5 {
            font-size: 13px;
        }
        
        /* Phone input for very small screens */
        .input-group {
            flex-direction: row;
        }
        
        .input-group input[name="phone"] {
            height: 40px;
            padding: 6px 8px;
        }
        
        .input-group .choices .choices__inner {
            min-height: 40px;
            padding: 6px 8px;
        }
        
        #country_code {
            flex: 0 0 80px;
        }
        
        .input-group .choices {
            flex: 0 0 80px;
        }
    }
    
    /* Touch-friendly improvements */
    @media (hover: none) and (pointer: coarse) {
        .choices .choices__list--dropdown .choices__item {
            padding: 15px 12px;
            min-height: 44px;
        }
        
        .btn {
            min-height: 44px;
        }
        
        .multisteps-form__input,
        .form-control {
            min-height: 44px;
        }
    }
    
    /* Fix input group alignment */
    .input-group > * {
        margin: 0;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h3 class="mt-5">Build Your Profile</h3>
        <h5 class="text-secondary font-weight-normal">This information will let us know more about you.</h5>
        <div class="multisteps-form mb-5">
           
            <!--form panels--->
            <div class="row">
                <div class="col-12 col-lg-8 m-auto">
                    <form class="multisteps-form__form al-custom-validation" id="trader-registration-form" action="{{ route('trader.registration') }}" method="post">
                        @csrf
                        <input type="hidden" name="op" value="step-persional">
                        <input type="hidden" name="op_account" value="{{ $create_meta_account }}">
                        <input type="hidden" name="op_social" value="{{ $social_account }}">
                        <input type="hidden" name="referKey" value="{{ $referKey }}">
                        <input type="hidden" name="manager" value="{{$manager}}">
                        <!--Persional section-->
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Let's start with the basic information</h5>
                                    <p>Let us know your name and email address. Use an address you don't mind other
                                        users contacting you at</p>
                                    <ul class="navbar-nav language-nav" style="width: 200px;">
                                        <li class="nav-item dropdown dropdown-language" style="margin-right: 1rem;">
                                            <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                @if (session()->get('locale') == 'fr')
                                                @php($lang = __('language.french'))
                                                @php($flag = 'fr')
                                                @elseif(session()->get('locale') == 'de')
                                                @php($lang = __('language.german'))
                                                @php($flag = 'de')
                                                @elseif(session()->get('locale') == 'pt')
                                                @php($lang = __('language.portuguese'))
                                                @php($flag = 'pt')
                                                @elseif(session()->get('locale') == 'zh')
                                                @php($lang = __('language.chinese'))
                                                @php($flag = 'cn')
                                                @else
                                                @php($lang = __('language.english'))
                                                @php($flag = 'us')
                                                @endif
                                                <i class="flag-icon flag-icon-{{ $flag }}"></i>
                                                <span class="selected-language">
                                                    {{ $lang }}
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                                                <a class="dropdown-item lang-change" href="#" data-language="en"><i class="flag-icon flag-icon-us"></i>{{ __('language.english') }}</a>
                                                <a class="dropdown-item lang-change" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i>
                                                    {{ __('language.french') }}</a>
                                                <a class="dropdown-item lang-change" href="#" data-language="de"><i class="flag-icon flag-icon-de"></i>
                                                    {{ __('language.german') }}</a>
                                                <a class="dropdown-item lang-change" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i>
                                                    {{ __('language.portuguese') }}</a>
                                                <a class="dropdown-item lang-change" href="#" data-language="zh"><i class="flag-icon flag-icon-cn"></i>
                                                    {{ __('language.chinese') }}</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="multisteps-form__content">
                                <div class="row mt-3 px-4">
                                    <!-- full name -->
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label for="full-name">Full Name</label>
                                            <input class="multisteps-form__input form-control" type="text" placeholder="Eg. Michael" name="full_name" id="full-name" />
                                        </div>
                                    </div>
                                    <!-- country -->
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select class="form-control" name="country" id="country">
                                                <option value="">Select Your Country</option>
                                                @foreach($countries as $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- gender -->
                                    {{-- <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group" id="gender-grp">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Please choose your gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    <!-- email -->
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="multisteps-form__input form-control" type="email" name="email" placeholder="Eg. tomson@example.com" />
                                        </div>
                                    </div>
                                    <!-- confirm email -->
                                    <div class="col-12 col-sm-6 text-start">
                                        <div class="form-group">
                                            <label>Confirm Email</label>
                                            <input class="multisteps-form__input form-control" type="email" name="confirm_email" placeholder="Eg. tomson@example.com" />
                                        </div>
                                    </div>
                                    
                                    <!-- date of birth -->
                                    {{-- <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <div class="col-12 d-flex date-of-birth-gp position-relative">
                                                <span class="input-rang-group-date-logo border">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </span>
                                                <input type="text" id="date_of_birth" class="flatpickr-basic border w-100 date_picker_field" name="date_of_birth" placeholder="YY-MM-DD">
                                            </div>
                                        </div>
                                    </div> --}}

                                    <!-- country -->
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="password_gen">
                                            <label classs="mt-3">Password</label>
                                            {{-- <i class="fas fa-times"></i>  <i class="fas fa-check"></i> --}}
                                            <div class="input-group pasGen-form-group password_ch_toltip">
                                                <ul class="pass_toltip_content">
                                                    <h6 class="pas_info_text">Password Must:</h6>
                                                    <li class="pwd-restriction-length"><i class="fas fa-info-circle"></i> Be between 10-16 characters
                                                        in length</li>
                                                    <li class="pwd-restriction-upperlower"><i class="fas fa-info-circle"></i> Contain at least 1
                                                        lowercase and 1 uppercase letter</li>
                                                    <li class="pwd-restriction-number"><i class="fas fa-info-circle"></i> Contain at least 1 number
                                                        (0–9)</li>
                                                    <li class="pwd-restriction-special"><i class="fas fa-info-circle"></i> Contain at least 1 special
                                                        character (!@#$%^&()'[]"?+-/*)</li>
                                                </ul>
                                                <button class="copy_btn" type="button">Copy</button>
                                                <span class="input-group-text position-relative bg-gradient-primary cursor-pointer  info-icon" style="padding:13px">
                                                    <i class="fas fa-info"></i>
                                                </span>
                                                <input class="form-control copy_password check_password_chrac" name="password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="Password" type="password" id="new-password">
                                                <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <label class="mt-3">Confirm Password </label>
                                                <div class="input-group pasGen-form-group">
                                                    <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" class="multisteps-form__input form-control password_gen copy-pass-input" type="password" name="confirm_password" placeholder="Confirm Password" id="confirm-password" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <div class="phone-input-container">
                                                <div class="country-selector">
                                                    <div class="flag-container">
                                                        <span class="flag-icon flag-icon-pk" id="selected-flag"></span>
                                                    </div>
                                                    <select class="country-code-select" name="country_code" id="country_code">
                                                        <option value="">Select Country</option>
                                                        @foreach($countries as $value)
                                                            <option value="{{ $value->country_code }}" 
                                                                    data-country-id="{{ $value->id }}" 
                                                                    data-iso="{{ $value->iso }}"
                                                                    data-flag="{{ strtolower($value->iso) }}">
                                                                +{{ $value->country_code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="dropdown-arrow">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </div>
                                                </div>
                                                <input class="phone-number-input" type="text" name="phone" placeholder="301 2345678" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- submit button personal info -->
                                    <div class="button-row d-flex mt-4">
                                        <div class="col-4"></div>
                                        <div class="col-8 mx-auto">
                                            <button type="button" data-label="Next" id="personal-submit" data-btnid="address-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto mb-0 float-end" style="width:200px">Next</button>
                                        </div>
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="personal-next" type="button" title="Next">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Address section-->
                        

                        <!-- social section -->
                        @if ($social_account == 1)
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Your Social Accounts.</h5>
                                    <p>Give us more details about you. What do you enjoy doing in your spare time?
                                    </p>
                                </div>
                            </div>
                            <div class="multisteps-form__content">
                                <div class="row mt-4">
                                    <div class="col-sm-3 ms-auto">
                                        <div class="avatar avatar-xxl position-relative">
                                            <img src="{{ asset('admin-assets\app-assets\images\avatars\avater-men.png') }}" class="border-radius-md" alt="team-2">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <div class="form-group">
                                            <label>Skype</label>
                                            <input class="multisteps-form__input form-control" type="text" name="skupe" placeholder="john@example.com" />
                                        </div>
                                        <div class="form-group">
                                            <label>Linkedin</label>
                                            <input class="multisteps-form__input form-control" type="url" name="linkedin" placeholder="http://" />
                                        </div>
                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input class="multisteps-form__input form-control" type="url" name="facebook" placeholder="http://" />
                                        </div>
                                        <div class="form-group">
                                            <label>Twitter</label>
                                            <input class="multisteps-form__input form-control" type="url" name="twitter" placeholder="http://" />
                                        </div>
                                        <div class="form-group">
                                            <label>Telegram</label>
                                            <input class="multisteps-form__input form-control" type="url" name="telegram" placeholder="http://" />
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <div class="col-4"></div>
                                    <div class="col-6 mx-auto">
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="social-submit" data-label="Next" data-btnid="social-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                        <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                    </div>
                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="social-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- account section -->
                        @if ($create_meta_account == 1)
                        <div class="card multisteps-form__panel p-3 border-radius-xl bg-white" data-animation="FadeIn">
                            <div class="row text-center">
                                <div class="col-10 mx-auto">
                                    <h5 class="font-weight-normal">Create Your Trading account.</h5>
                                    <p>Give us more details about you. What do you enjoy doing in your spare time?
                                    </p>
                                </div>
                            </div>
                            <div class="multisteps-form__content">
                                <div class="row mt-4">
                                    <div class="col-sm-3 ms-auto">
                                        <div class="avatar avatar-xxl position-relative">
                                            <img src="{{ asset('trader-assets/assets/img/logos/platform-logo/mt5.png') }}" class="" alt="team-2">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                        <!-- <label>Approximate Investment</label>
                                            <input class="multisteps-form__input form-control" type="email" placeholder="Eg. 2" /> -->
                                        <div class="form-group" id="server-grp">
                                            <label>Platform</label>
                                            <select class="form-control" name="platform" id="server">
                                                <option value="">Please choose a server</option>
                                                @if ($platform === 'mt4')
                                                <option value="mt4">Mt4</option>
                                                @endif
                                                @if ($platform === 'mt5')
                                                <option value="mt5">MT5</option>
                                                @endif
                                                @if ($platform === 'vertex')
                                                <option value="vertex">VERTEX</option>
                                                @endif
                                                @if ($platform === 'both')
                                                <option value="mt4">Mt4</option>
                                                <option value="mt5">MT5</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group" id="account-type-grp">
                                            <label>Account Type</label>
                                            <select class="form-control" name="account_type" id="account-type">
                                                <option value="">Please choose an account type</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Leverage</label>
                                            <select class="form-control" name="leverage" id="leverage">
                                                <option value="">Please choose leverage</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-row d-flex mt-4">
                                    <div class="col-4"></div>
                                    <div class="col-6 mx-auto">
                                        <button class="btn bg-gradient-primary ms-auto mb-0 float-end" id="account-submit" data-label="Next" data-btnid="account-submit" data-callback="trader_reg_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="trader-registration-form" data-el="fg" onclick="_run(this)" type="button" title="Next" style="width: 200px;">Next</button>
                                        <button class="btn bg-gradient-light mb-0 js-btn-prev me-1 float-end" type="button" title="Prev">Prev</button>
                                    </div>
                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden" id="account-next" type="button" title="Next">Next</button>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!--single form panel-->
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-js')
<!-- BEGIN: Page JS-->
<!-- Ensure toastr is loaded -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script>
    // Configure toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/multistep-form.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
<script src="{{ asset('/common-js/password-gen.js') }}"></script>


<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Store country data for mapping
        var countryData = @json($countries);
        console.log('Country data loaded:', countryData);
        
        // ISO to Flag Emoji mapping
        var isoToFlag = {
            'AF': '🇦🇫', 'AL': '🇦🇱', 'DZ': '🇩🇿', 'AS': '🇦🇸', 'AD': '🇦🇩', 'AO': '🇦🇴', 'AI': '🇦🇮', 'AQ': '🇦🇶', 'AG': '🇦🇬', 'AR': '🇦🇷',
            'AM': '🇦🇲', 'AW': '🇦🇼', 'AU': '🇦🇺', 'AT': '🇦🇹', 'AZ': '🇦🇿', 'BS': '🇧🇸', 'BH': '🇧🇭', 'BD': '🇧🇩', 'BB': '🇧🇧', 'BY': '🇧🇾',
            'BE': '🇧🇪', 'BZ': '🇧🇿', 'BJ': '🇧🇯', 'BM': '🇧🇲', 'BT': '🇧🇹', 'BO': '🇧🇴', 'BA': '🇧🇦', 'BW': '🇧🇼', 'BV': '🇧🇻', 'BR': '🇧🇷',
            'IO': '🇮🇴', 'BN': '🇧🇳', 'BG': '🇧🇬', 'BF': '🇧🇫', 'BI': '🇧🇮', 'KH': '🇰🇭', 'CM': '🇨🇲', 'CA': '🇨🇦', 'CV': '🇨🇻', 'KY': '🇰🇾',
            'CF': '🇨🇫', 'TD': '🇹🇩', 'CL': '🇨🇱', 'CN': '🇨🇳', 'CX': '🇨🇽', 'CC': '🇨🇨', 'CO': '🇨🇴', 'KM': '🇰🇲', 'CG': '🇨🇬', 'CD': '🇨🇩',
            'CK': '🇨🇰', 'CR': '🇨🇷', 'CI': '🇨🇮', 'HR': '🇭🇷', 'CU': '🇨🇺', 'CY': '🇨🇾', 'CZ': '🇨🇿', 'DK': '🇩🇰', 'DJ': '🇩🇯', 'DM': '🇩🇲',
            'DO': '🇩🇴', 'EC': '🇪🇨', 'EG': '🇪🇬', 'SV': '🇸🇻', 'GQ': '🇬🇶', 'ER': '🇪🇷', 'EE': '🇪🇪', 'ET': '🇪🇹', 'FK': '🇫🇰', 'FO': '🇫🇴',
            'FJ': '🇫🇯', 'FI': '🇫🇮', 'FR': '🇫🇷', 'GF': '🇬🇫', 'PF': '🇵🇫', 'TF': '🇹🇫', 'GA': '🇬🇦', 'GM': '🇬🇲', 'GE': '🇬🇪', 'DE': '🇩🇪',
            'GH': '🇬🇭', 'GI': '🇬🇮', 'GR': '🇬🇷', 'GL': '🇬🇱', 'GD': '🇬🇩', 'GP': '🇬🇵', 'GU': '🇬🇺', 'GT': '🇬🇹', 'GG': '🇬🇬', 'GN': '🇬🇳',
            'GW': '🇬🇼', 'GY': '🇬🇾', 'HT': '🇭🇹', 'HM': '🇭🇲', 'VA': '🇻🇦', 'HN': '🇭🇳', 'HK': '🇭🇰', 'HU': '🇭🇺', 'IS': '🇮🇸', 'IN': '🇮🇳',
            'ID': '🇮🇩', 'IR': '🇮🇷', 'IQ': '🇮🇶', 'IE': '🇮🇪', 'IM': '🇮🇲', 'IL': '🇮🇱', 'IT': '🇮🇹', 'JM': '🇯🇲', 'JP': '🇯🇵', 'JE': '🇯🇪',
            'JO': '🇯🇴', 'KZ': '🇰🇿', 'KE': '🇰🇪', 'KI': '🇰🇮', 'KP': '🇰🇵', 'KR': '🇰🇷', 'KW': '🇰🇼', 'KG': '🇰🇬', 'LA': '🇱🇦', 'LV': '🇱🇻',
            'LB': '🇱🇧', 'LS': '🇱🇸', 'LR': '🇱🇷', 'LY': '🇱🇾', 'LI': '🇱🇮', 'LT': '🇱🇹', 'LU': '🇱🇺', 'MO': '🇲🇴', 'MK': '🇲🇰', 'MG': '🇲🇬',
            'MW': '🇲🇼', 'MY': '🇲🇾', 'MV': '🇲🇻', 'ML': '🇲🇱', 'MT': '🇲🇹', 'MH': '🇲🇭', 'MQ': '🇲🇶', 'MR': '🇲🇷', 'MU': '🇲🇺', 'YT': '🇾🇹',
            'MX': '🇲🇽', 'FM': '🇫🇲', 'MD': '🇲🇩', 'MC': '🇲🇨', 'MN': '🇲🇳', 'ME': '🇲🇪', 'MS': '🇲🇸', 'MA': '🇲🇦', 'MZ': '🇲🇿', 'MM': '🇲🇲',
            'NA': '🇳🇦', 'NR': '🇳🇷', 'NP': '🇳🇵', 'NL': '🇳🇱', 'NC': '🇳🇨', 'NZ': '🇳🇿', 'NI': '🇳🇮', 'NE': '🇳🇪', 'NG': '🇳🇬', 'NU': '🇳🇺',
            'NF': '🇳🇫', 'MP': '🇲🇵', 'NO': '🇳🇴', 'OM': '🇴🇲', 'PK': '🇵🇰', 'PW': '🇵🇼', 'PS': '🇵🇸', 'PA': '🇵🇦', 'PG': '🇵🇬', 'PY': '🇵🇾',
            'PE': '🇵🇪', 'PH': '🇵🇭', 'PN': '🇵🇳', 'PL': '🇵🇱', 'PT': '🇵🇹', 'PR': '🇵🇷', 'QA': '🇶🇦', 'RE': '🇷🇪', 'RO': '🇷🇴', 'RU': '🇷🇺',
            'RW': '🇷🇼', 'SH': '🇸🇭', 'KN': '🇰🇳', 'LC': '🇱🇨', 'PM': '🇵🇲', 'VC': '🇻🇨', 'WS': '🇼🇸', 'SM': '🇸🇲', 'ST': '🇸🇹', 'SA': '🇸🇦',
            'SN': '🇸🇳', 'RS': '🇷🇸', 'SC': '🇸🇨', 'SL': '🇸🇱', 'SG': '🇸🇬', 'SK': '🇸🇰', 'SI': '🇸🇮', 'SB': '🇸🇧', 'SO': '🇸🇴', 'ZA': '🇿🇦',
            'GS': '🇬🇸', 'ES': '🇪🇸', 'LK': '🇱🇰', 'SD': '🇸🇩', 'SR': '🇸🇷', 'SJ': '🇸🇯', 'SZ': '🇸🇿', 'SE': '🇸🇪', 'CH': '🇨🇭', 'SY': '🇸🇾',
            'TW': '🇹🇼', 'TJ': '🇹🇯', 'TZ': '🇹🇿', 'TH': '🇹🇭', 'TL': '🇹🇱', 'TG': '🇹🇬', 'TK': '🇹🇰', 'TO': '🇹🇴', 'TT': '🇹🇹', 'TN': '🇹🇳',
            'TR': '🇹🇷', 'TM': '🇹🇲', 'TC': '🇹🇨', 'TV': '🇹🇻', 'UG': '🇺🇬', 'UA': '🇺🇦', 'AE': '🇦🇪', 'GB': '🇬🇧', 'US': '🇺🇸', 'UM': '🇺🇲',
            'UY': '🇺🇾', 'UZ': '🇺🇿', 'VU': '🇻🇺', 'VE': '🇻🇪', 'VN': '🇻🇳', 'VG': '🇻🇬', 'VI': '🇻🇮', 'WF': '🇼🇫', 'EH': '🇪🇭', 'YE': '🇾🇪',
            'ZM': '🇿🇲', 'ZW': '🇿🇼'
        };
        
        // Function to convert ISO to flag emoji
        function getFlagEmoji(iso) {
            return isoToFlag[iso] || iso;
        }
        
        // Initialize country dropdown
        if (document.getElementById('country')) {
            var country = document.getElementById('country');
            window.countryChoice = new Choices(country);
            console.log('Country dropdown initialized');
            
            // Add event listener for country selection
            country.addEventListener('change', function(e) {
                var selectedCountryId = e.target.value;
                console.log('Country selected:', selectedCountryId);
                
                if (selectedCountryId) {
                    // Find the selected country data
                    var selectedCountry = countryData.find(function(country) {
                        return country.id == selectedCountryId;
                    });
                    
                    console.log('Selected country data:', selectedCountry);
                    
                    if (selectedCountry && window.countryCodeChoice) {
                        // Set the corresponding country code
                        try {
                            // Try multiple methods to set the value
                            window.countryCodeChoice.setChoiceByValue(selectedCountry.country_code.toString());
                            console.log('Method 1: setChoiceByValue with toString');
                        } catch (e) {
                            
        // Initialize phone input with country flags
        if (document.getElementById('country_code')) {
            var countryCodeSelect = document.getElementById('country_code');
            var selectedFlag = document.getElementById('selected-flag');
            
            // Set default flag (Pakistan)
            if (selectedFlag) {
                selectedFlag.className = 'flag-icon flag-icon-pk';
            }
            
            // Add event listener for country code selection
            countryCodeSelect.addEventListener('change', function(e) {
                var selectedOption = e.target.options[e.target.selectedIndex];
                var flagCode = selectedOption.getAttribute('data-flag');
                
                if (flagCode && selectedFlag) {
                    // Update the flag icon
                    selectedFlag.className = 'flag-icon flag-icon-' + flagCode.toLowerCase();
                    console.log('Flag updated to:', flagCode.toLowerCase());
                }
            });
            
            console.log('Phone input with country flags initialized');
        }
                            try {
                                // Alternative method
                                window.countryCodeChoice.setChoiceByValue(selectedCountry.country_code);
                                console.log('Method 2: setChoiceByValue without toString');
                            } catch (e2) {
                                try {
                                    // Direct DOM manipulation
                                    var countryCodeSelect = document.getElementById('country_code');
                                    countryCodeSelect.value = selectedCountry.country_code;
                                    countryCodeSelect.dispatchEvent(new Event('change'));
                                    console.log('Method 3: Direct DOM manipulation');
                                } catch (e3) {
                                    console.error('All methods failed:', e3);
                                }
                            }
                        }
                        console.log('Setting country code to:', selectedCountry.country_code);
                    }
                }
            });
        }
        
        // Initialize country code dropdown
        if (document.getElementById('country_code')) {
            var countryCode = document.getElementById('country_code');
            
            // Replace ISO codes with just country codes in the options
            var options = countryCode.querySelectorAll('option');
            options.forEach(function(option) {
                if (option.value) {
                    option.textContent = '+' + option.value;
                }
            });
            
            window.countryCodeChoice = new Choices(countryCode, {
                searchEnabled: true,
                searchPlaceholderValue: "Search country code...",
                placeholder: true,
                placeholderValue: "Code",
                itemSelectText: '',
                classNames: {
                    containerOuter: 'choices input-group-select'
                },
                renderChoiceLimit: 0,
                maxItemCount: 1,
                removeItemButton: false,
                shouldSort: false
            });
            console.log('Country code dropdown initialized with codes only');
            
            // Also add reverse mapping - when country code is selected, update country
            countryCode.addEventListener('change', function(e) {
                var selectedCountryCode = e.target.value;
                console.log('Country code selected:', selectedCountryCode);
                
                if (selectedCountryCode) {
                    // Find the country with this country code
                    var selectedCountry = countryData.find(function(country) {
                        return country.country_code == selectedCountryCode;
                    });
                    
                    console.log('Found country for code:', selectedCountry);
                    
                    if (selectedCountry && window.countryChoice) {
                        try {
                            // Try multiple methods to set the value
                            window.countryChoice.setChoiceByValue(selectedCountry.id.toString());
                            console.log('Method 1: setChoiceByValue with toString');
                        } catch (e) {
                            try {
                                // Alternative method
                                window.countryChoice.setChoiceByValue(selectedCountry.id);
                                console.log('Method 2: setChoiceByValue without toString');
                            } catch (e2) {
                                try {
                                    // Direct DOM manipulation
                                    var countrySelect = document.getElementById('country');
                                    countrySelect.value = selectedCountry.id;
                                    countrySelect.dispatchEvent(new Event('change'));
                                    console.log('Method 3: Direct DOM manipulation');
                                } catch (e3) {
                                    console.error('All methods failed:', e3);
                                }
                            }
                        }
                        console.log('Setting country to:', selectedCountry.id);
                    }
                }
            });
        }
        
        // Initialize other dropdowns
        if (document.getElementById('gender')) {
            var gender = document.getElementById('gender');
            const gender_choice = new Choices(gender);
        }
        if (document.getElementById('server')) {
            var server = document.getElementById('server');
            const server_choice = new Choices(server);
        }
    });
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    // password character check 

    // genrate randome password
    $(document).on('click', ".btn-gen-password", function() {
        var field = $(this).closest('div.password_gen').find('input[rel="gp"]');
        field.val(rand_string(field));
        field.attr('type', 'text');
        $(this).closest('div.password_gen').find('.copy_btn').show();
    });
    $('.copy_btn').on("click", function(e) {
        e.preventDefault();
        $(this).html('Copyed');
        setTimeout(() => {
            $(this).hide();
            $(this).html('Copy');
        }, 1000);
        let id = $(this).closest('div.password_gen').find('.copy-pass-input').attr('id');
        $(this).closest('div.password_gen').find('.copy-pass-input').select();
        if ($(this).closest('div.password_gen').find('.copy-pass-input').val() != "") {
            copy_to_clipboard(id);
        }
        $(this).closest('div.password_gen').find('input[rel="gp"]').attr('type', 'password');
    });
    // registration call back
    $('input[name="op"]').val('step-persional');
    $(document).on('click', "#confirm-submit,#account-submit,#social-submit,#personal-submit", function() {
        $(this).prop('disabled', true);
    });

    function trader_reg_call_back(data) {
        console.log('Callback received:', data);
        
        // Test notification
        if (typeof $.toast !== 'undefined') {
            $.toast({
                heading: "Test",
                text: "Notification system is working",
                position: "top-right",
                loaderBg: "#ff6849",
                icon: "info",
                hideAfter: 2000,
                stack: 6
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.info("Notification system is working", "Test");
        } else {
            console.log("No notification library found");
        }

        if (data.persional_status == true) {
            $('input[name="op"]').val('step-confirm');
            $("#personal-next").trigger('click');
        }
        // step address validation check
        if (data.address_status == true) {
            if ($("input[name='op_social']").val() == '1') {
                $('input[name="op"]').val('step-social');
            }
            if ($("input[name='op_account']").val() == '1') {
                $('input[name="op"]').val('step-account');
            }
            if (($("input[name='op_social']").val() !== '1') && ($("input[name='op_account']").val() !== '1')) {
                $('input[name="op"]').val('step-confirm');
            }
            if (($("input[name='op_social']").val() == '1') && ($("input[name='op_account']").val() == '1')) {
                $('input[name="op"]').val('step-social');
            }
            $("#address-next").trigger('click');
        }
        // step address validation check
        if (data.social_status == true) {
            // meta account auto create ativated
            if ($("input[name='op_account']").val() == 1) {
                $('input[name="op"]').val('step-account');
            }
            // meta account auto create disabled
            else {
                $('input[name="op"]').val('step-confirm');
            }
            $("#social-next").trigger('click');
        }
        if (data.account_status == true) {
            $('input[name="op"]').val('step-confirm');
            $("#account-next").trigger('click');
        }
            // check final status
    if (data.status == true) {
        $('input[name="op"]').val('step-persional');
        console.log('Success status:', data);
        console.log('Success message:', data.message);
        
        // Try multiple notification methods
        if (typeof $.toast !== 'undefined') {
            $.toast({
                heading: "Success",
                text: data.message,
                position: "top-right",
                loaderBg: "#ff6849",
                icon: "success",
                hideAfter: 3500,
                stack: 6
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.success(data.message, "Success");
        } else {
            alert("Success: " + data.message);
        }
        
        $("#trader-registration-form").trigger('reset');
        window.location.href = "/success/" + data.userId;
    }
    if (data.status == false) {
        console.log('Error status:', data);
        console.log('Error message:', data.message);
        
        // Try multiple notification methods
        if (typeof $.toast !== 'undefined') {
            $.toast({
                heading: "Error",
                text: data.message,
                position: "top-right",
                loaderBg: "#ff6849",
                icon: "error",
                hideAfter: 3500,
                stack: 6
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.error(data.message, "Error");
        } else {
            alert("Error: " + data.message);
        }
    }
        $("#trader-registration-form").css({
            "height": "600px !important"
        })
        $("#confirm-submit, #account-submit, #social-submit, #address-submit, #personal-submit").prop('disabled', false);
        $.validator("trader-registration-form", data.errors);
        //SETTING PROPER FORM HEIGHT ONRESIZE
        setFormHeight();

    }

    // disable final step button
    $(document).on('click', '#personal-submit', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 3000);
    });
    $(document).on('click', '#address-submit', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 3000);
    });
    $(document).on('click', '#social-submit', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 3000);
    });
    $(document).on('click', '#account-submit', function() {
        $(this).prop('disabled', true);
        setTimeout(() => {
            $(this).prop('disabled', false);
        }, 3000);
    });
    // end disable final step button
    // prev button click 
    $(document).on("click", ".js-btn-prev", function() {
        var currentOP = $('input[name="op"]').val();
        if (currentOP == 'step-address') {
            $('input[name="op"]').val('step-persional');
        } else if (currentOP == 'step-social') {
            $('input[name="op"]').val('step-address');
        } else if (currentOP == 'step-account') {
            $('input[name="op"]').val('step-social');
        } else if (currentOP == 'step-confirm') {
            $('input[name="op"]').val('step-persional');
            // if ($('input[name="op_account"]').val() == 1) {
            //     $('input[name="op"]').val('step-account');
            // } else {
            //     $('input[name="op"]').val('step-address');
            // }
            // if($('input[name="op_social"]').val() ==1 ){
            //     $('input[name="op"]').val('step-social');
            // }
        }
    })

    // get account category data for registrations------------------------------------
    $(document).on("change", "#server", function() {
        let server = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-account-type/' + server,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $("#account-type").html(data);
                $("#account-type").data('server', server);
                if (document.getElementById('account-type')) {
                    var account_type = document.getElementById('account-type');
                    const accountType = new Choices(account_type);
                }
            }
        });
    })
    // end: get account category data------------------------------------------
    // get client group data for registrations------------------------------------
    $(document).on("change", "#account-type", function() {

        let group_id = $(this).val();
        $.ajax({
            url: '/admin/client-management/get-leverage/' + group_id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // $("#account-type").html(data.client_groups);
                $("#leverage").html(data);

                if (document.getElementById('leverage')) {
                    var leverage = document.getElementById('leverage');
                    const leverage_chooice = new Choices(leverage);
                }
            }
        });
    })
    // end: get client group data------------------------------------------
</script>
<!-- language change -->
<script>
    (function(window, document, $) {
        $(document).on('click', ".lang-change", function() {
            let lang = $(this).data('language');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/user/change-language',
                method: 'post',
                dataType: 'json',
                data: {
                    lang: lang
                },
                success: function(data) {
                    if (data.status === true) {
                        location.reload();
                    }
                }
            });
        });
    })(window, document, jQuery);
</script>
<!-- END: Page JS-->
@stop