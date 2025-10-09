<?php
    use App\Services\systems\BreadCrumbService;
    $root = BreadCrumbService::bread_crumb('root');
    $child = BreadCrumbService::bread_crumb('child');
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-container breadcrumb-container-light bg-body mb-0">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="/">{{ $root['label'] }}</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $child['label'] }}</li>
    </ol>
</nav>