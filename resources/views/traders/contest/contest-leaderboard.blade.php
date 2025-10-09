@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Contest Leaderboard')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<style>
/* Leaderboard Container */
.leaderboard-container {
    background: linear-gradient(135deg, rgba(255, 142, 92, 0.1) 0%, rgba(255, 107, 53, 0.15) 100%);
    min-height: 100vh;
    color: #333;
    padding: 10px 0;
    position: relative;
    overflow: hidden;
}

.leaderboard-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
    pointer-events: none;
}

/* Back Button */
.back-button {
    position: fixed;
    top: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    z-index: 1000;
    backdrop-filter: blur(10px);
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.back-button i {
    margin-right: 8px;
}

/* Leaderboard Header */
.leaderboard-header {
    text-align: center;
    margin-bottom: 30px;
    position: relative;
    z-index: 2;
}

.leaderboard-title {
    font-size: 3rem;
    font-weight: 900;
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
    letter-spacing: 2px;
}

/* Countdown Timer */
.countdown-timer {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 142, 92, 0.2);
    position: relative;
    z-index: 2;
}

.countdown-header {
    text-align: center;
    margin-bottom: 15px;
}

.countdown-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.countdown-subtitle {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.countdown-display {
    display: flex !important;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    visibility: visible !important;
    opacity: 1 !important;
}

.countdown-item {
    text-align: center;
    min-width: 80px;
    display: block !important;
    visibility: visible !important;
}

.countdown-number {
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 auto 8px;
    box-shadow: 0 5px 15px rgba(255, 142, 92, 0.3);
    border: 3px solid white;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Specific styling for minutes to ensure visibility */
#minutes {
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    z-index: 1000 !important;
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%) !important;
    color: white !important;
}

.countdown-label {
    font-size: 0.8rem;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.countdown-expired {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

/* Pulsing animation for urgent countdown */
.countdown-number.pulse {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 5px 15px rgba(255, 142, 92, 0.3);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(255, 142, 92, 0.5);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 5px 15px rgba(255, 142, 92, 0.3);
    }
}

/* Top 3 Section */
.top-3-section {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 40px;
    position: relative;
    z-index: 2;
    flex-wrap: wrap;
}

.top-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 2px solid #DEE2E6;
    transition: all 0.3s ease;
    position: relative;
    min-width: 280px;
}

.top-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.top-card.rank-1 {
    background: white;
    border-color: #DAA520;
    transform: scale(1.1);
    z-index: 3;
    box-shadow: 0 20px 40px rgba(255, 215, 0, 0.3);
}

.top-card.rank-2 {
    background: white;
    border-color: #808080;
    z-index: 2;
    box-shadow: 0 15px 30px rgba(192, 192, 192, 0.3);
}

.top-card.rank-3 {
    background: white;
    border-color: #8B4513;
    color: #333;
    z-index: 1;
    box-shadow: 0 15px 30px rgba(205, 127, 50, 0.3);
}

.rank-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.2rem;
    box-shadow: 0 5px 15px rgba(255, 142, 92, 0.4);
    border: 3px solid white;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 auto 15px;
    border: 4px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.user-name {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 8px;
    color: #333;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-rank {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 15px;
    font-weight: 500;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Rank-specific classic colors */
.rank-1 .user-rank {
    color: #8B4513; /* Saddle Brown - Classic Gold */
    font-weight: 600;
}

.rank-2 .user-rank {
    color: #696969; /* Dim Gray - Classic Silver */
    font-weight: 600;
}

.rank-3 .user-rank {
    color: #CD853F; /* Peru - Classic Bronze */
    font-weight: 600;
}

.profit-score {
    font-size: 1.8rem;
    font-weight: 900;
    margin-bottom: 8px;
    color: #28a745;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.lot-score {
    font-size: 1.1rem;
    opacity: 0.8;
    font-weight: 600;
    color: #666;
}

/* Update Note */
.update-note {
    text-align: center;
    margin-bottom: 30px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 142, 92, 0.2);
    position: relative;
    z-index: 2;
}

.asterisk {
    color: #ff8e5c;
    font-weight: bold;
    font-size: 1.2rem;
}

/* Standings Section */
.standings-section {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 142, 92, 0.2);
    position: relative;
    z-index: 2;
}

.standings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.standings-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #333;
    margin: 0;
}

.standings-title span {
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.search-box {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 25px;
    padding: 8px 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(255, 142, 92, 0.2);
}

.search-input {
    border: none;
    outline: none;
    padding: 8px 12px;
    font-size: 0.9rem;
    background: transparent;
    min-width: 200px;
}

.search-btn {
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(255, 142, 92, 0.4);
}

/* Table Styles */
.standings-table {
    overflow-x: auto;
    max-width: 100%;
}

/* Mobile Cards Styles */
.mobile-cards {
    display: none;
    gap: 15px;
    flex-direction: column;
}

.mobile-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(255, 142, 92, 0.2);
    transition: all 0.3s ease;
    position: relative;
}

.mobile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.mobile-card.highlighted {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 237, 78, 0.1) 100%);
    border-color: #ffd700;
}

.mobile-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(255, 142, 92, 0.1);
}

.mobile-rank {
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    box-shadow: 0 4px 15px rgba(255, 142, 92, 0.4);
}

.mobile-user-info {
    flex: 1;
    margin-left: 15px;
}

.mobile-user-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.mobile-account {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.mobile-level {
    background: rgba(255, 142, 92, 0.1);
    color: #ff6b35;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.mobile-card-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.mobile-stat-item {
    text-align: center;
    padding: 12px;
    background: rgba(255, 142, 92, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(255, 142, 92, 0.1);
}

.mobile-stat-label {
    font-size: 0.8rem;
    color: #666;
    font-weight: 600;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.mobile-stat-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: #333;
}

.mobile-stat-value.text-success {
    color: #28a745 !important;
}

.mobile-stat-value.text-danger {
    color: #dc3545 !important;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.table th {
    background: linear-gradient(135deg, #ff8e5c 0%, #ff6b35 100%);
    color: white;
    padding: 18px 15px;
    text-align: left;
    font-weight: 700;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
}

.table td {
    padding: 18px 15px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.3s ease;
    font-weight: 500;
}

.table tr:hover {
    background-color: rgba(255, 142, 92, 0.05);
}

.table tr.highlighted {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 237, 78, 0.1) 100%);
    border-left: 4px solid #ffd700;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .leaderboard-container {
        padding: 5px;
    }
    
    .countdown-timer {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .countdown-title {
        font-size: 1rem;
    }
    
    .countdown-subtitle {
        font-size: 0.8rem;
    }
    
    .countdown-display {
        gap: 10px;
    }
    
    .countdown-item {
        min-width: 60px;
    }
    
    .countdown-number {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .countdown-label {
        font-size: 0.7rem;
    }
    
    .back-button {
        top: 10px;
        left: 10px;
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .leaderboard-title {
        font-size: 1.8rem;
        letter-spacing: 1px;
        margin-top: 50px;
    }
    
    .top-3-section {
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .top-card {
        min-width: 280px;
        max-width: 320px;
        width: 100%;
        transform: none !important;
        padding: 20px 15px;
    }
    
    .top-card.rank-1 {
        transform: scale(1.02) !important;
    }
    
    .profile-avatar {
        width: 60px;
        height: 60px;
        font-size: 2rem;
    }
    
    .user-name {
        font-size: 1.2rem;
    }
    
    .user-rank {
        font-size: 0.75rem;
    }
    
    .profit-score {
        font-size: 1.5rem;
    }
    
    .lot-score {
        font-size: 1rem;
    }
    
    .standings-section {
        padding: 20px 15px;
        margin: 0 5px;
    }
    
    .standings-header {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .standings-title {
        font-size: 1.8rem;
        text-align: center;
    }
    
    .search-box {
        width: 100%;
        padding: 6px 12px;
    }
    
    .search-input {
        min-width: auto;
        flex: 1;
        font-size: 0.85rem;
        padding: 6px 10px;
    }
    
    .search-btn {
        width: 30px;
        height: 30px;
    }
    
    /* Hide desktop table on mobile */
    .desktop-table {
        display: none !important;
    }
    
    /* Show mobile cards on mobile */
    .mobile-cards {
        display: flex !important;
    }
    
    .mobile-card {
        margin-bottom: 15px;
    }
    
    .mobile-card-stats {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    .mobile-stat-item {
        padding: 10px;
    }
    
    .mobile-stat-label {
        font-size: 0.75rem;
    }
    
    .mobile-stat-value {
        font-size: 1rem;
    }
}

/* Extra Small Mobile */
@media (max-width: 480px) {
    .leaderboard-title {
        font-size: 1.5rem;
        margin-top: 60px;
    }
    
    .countdown-timer {
        padding: 12px;
        margin-bottom: 15px;
    }
    
    .countdown-title {
        font-size: 0.9rem;
    }
    
    .countdown-subtitle {
        font-size: 0.75rem;
    }
    
    .countdown-display {
        gap: 8px;
    }
    
    .countdown-item {
        min-width: 50px;
    }
    
    .countdown-number {
        width: 45px;
        height: 45px;
        font-size: 1rem;
    }
    
    .countdown-label {
        font-size: 0.65rem;
    }
    
    .top-card {
        min-width: 260px;
        max-width: 300px;
        padding: 15px 12px;
    }
    
    .profile-avatar {
        width: 50px;
        height: 50px;
        font-size: 1.8rem;
    }
    
    .user-name {
        font-size: 1.1rem;
    }
    
    .profit-score {
        font-size: 1.3rem;
    }
    
    .standings-section {
        padding: 15px 10px;
        margin: 0 3px;
        border-radius: 15px;
    }
    
    .standings-title {
        font-size: 1.5rem;
    }
    
    .mobile-card {
        padding: 15px;
        margin-bottom: 12px;
    }
    
    .mobile-card-stats {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    
    .mobile-stat-item {
        padding: 8px;
    }
    
    .mobile-stat-label {
        font-size: 0.7rem;
    }
    
    .mobile-stat-value {
        font-size: 0.9rem;
    }
    
    .mobile-user-name {
        font-size: 1rem;
    }
    
    .mobile-account {
        font-size: 0.8rem;
    }
}

/* Very Small Mobile - Phones under 360px */
@media (max-width: 360px) {
    .leaderboard-title {
        font-size: 1.3rem;
        margin-top: 70px;
    }
    
    .top-card {
        min-width: 240px;
        max-width: 280px;
        padding: 12px 10px;
    }
    
    .profile-avatar {
        width: 45px;
        height: 45px;
        font-size: 1.6rem;
    }
    
    .user-name {
        font-size: 1rem;
    }
    
    .profit-score {
        font-size: 1.2rem;
    }
    
    .standings-section {
        padding: 12px 8px;
        margin: 0 2px;
    }
    
    .standings-title {
        font-size: 1.3rem;
    }
    
    .mobile-card {
        padding: 12px;
        margin-bottom: 10px;
    }
    
    .mobile-card-stats {
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }
    
    .mobile-stat-item {
        padding: 6px;
    }
    
    .mobile-stat-label {
        font-size: 0.65rem;
    }
    
    .mobile-stat-value {
        font-size: 0.8rem;
    }
    
    .mobile-user-name {
        font-size: 0.9rem;
    }
    
    .mobile-account {
        font-size: 0.75rem;
    }
    
    .mobile-rank {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
    }
}

/* Loading Animation */
.loading-spinner {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ff8e5c;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Color coding for profit and lot values */
.text-success {
    color: #28a745 !important;
    font-weight: 600;
}

.text-danger {
    color: #dc3545 !important;
    font-weight: 600;
}

/* Ensure color coding works in all views */
.profit-cell.text-success,
.lot-cell.text-success,
.mobile-profit-value.text-success,
.mobile-stat-value.text-success,
.profit-score.text-success,
.lot-score.text-success {
    color: #28a745 !important;
    font-weight: 600;
}

.profit-cell.text-danger,
.lot-cell.text-danger,
.mobile-profit-value.text-danger,
.mobile-stat-value.text-danger,
.profit-score.text-danger,
.lot-score.text-danger {
    color: #dc3545 !important;
    font-weight: 600;
}
</style>
@stop

@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop

@section('content')
<div class="leaderboard-container">
    <!-- Back Button -->
    <button class="back-button" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i> Back
    </button>
    
    <div class="container-fluid">
        <!-- Leaderboard Header -->
        <div class="leaderboard-header">
            <h1 class="leaderboard-title" id="contest-title">CONTEST LEADERBOARD</h1>
        </div>
        
        <!-- Countdown Timer -->
        <div class="countdown-timer" id="countdown-timer">
            <div class="countdown-header">
                <div class="countdown-title">Contest Ends In</div>
                <div class="countdown-subtitle" id="contest-end-date">Loading...</div>
            </div>
            <div class="countdown-display" id="countdown-display">
                <div class="countdown-item">
                    <div class="countdown-number" id="days">00</div>
                    <div class="countdown-label">Days</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="hours">00</div>
                    <div class="countdown-label">Hours</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="minutes">00</div>
                    <div class="countdown-label">Minutes</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="seconds">00</div>
                    <div class="countdown-label">Seconds</div>
                </div>
            </div>
        </div>
        
        <!-- Top 3 Section -->
        <div class="top-3-section">
            <div class="top-card rank-2">
                <div class="rank-badge">2</div>
                <div class="profile-avatar" id="rank2-avatar">-</div>
                <div class="user-name" id="rank2-name">Loading...</div>
                <div class="user-rank" id="rank2-rank">Elite Silver</div>
                <div class="profit-score" id="rank2-profit">$0</div>
                <div class="lot-score" id="rank2-lot">Lot: 0.00</div>
            </div>
            
            <div class="top-card rank-1">
                <div class="rank-badge">1</div>
                <div class="profile-avatar" id="rank1-avatar">-</div>
                <div class="user-name" id="rank1-name">Loading...</div>
                <div class="user-rank" id="rank1-rank">Champion Silver</div>
                <div class="profit-score" id="rank1-profit">$0</div>
                <div class="lot-score" id="rank1-lot">Lot: 0.00</div>
            </div>
            
            <div class="top-card rank-3">
                <div class="rank-badge">3</div>
                <div class="profile-avatar" id="rank3-avatar">-</div>
                <div class="user-name" id="rank3-name">Loading...</div>
                <div class="user-rank" id="rank3-rank">Elite Silver</div>
                <div class="profit-score" id="rank3-profit">$0</div>
                <div class="lot-score" id="rank3-lot">Lot: 0.00</div>
            </div>
        </div>
        
        <!-- Update Note -->
        <div class="update-note">
            <span class="asterisk">*</span> Please note that the Contest Leaderboard updates in real-time. Any changes in rankings will be reflected immediately.
        </div>
        
        <!-- Current Standings -->
        <div class="standings-section">
            <div class="standings-header">
                <h2 class="standings-title">CURRENT <span>STANDINGS</span></h2>
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Filter by user's name" id="searchInput">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading Spinner -->
            <div class="loading-spinner" id="loading-spinner">
                <div class="spinner"></div>
            </div>
            
                         <!-- Desktop Table -->
             <div class="standings-table desktop-table">
                 <table class="table">
                     <thead>
                         <tr>
                             <th>RANK</th>
                             <th>ACCOUNT</th>
                             <th>USER</th>
                             <th>LOT</th>
                             <th>PROFIT</th>
                             <th>EQUITY%</th>
                             <th>GAIN%</th>
                         </tr>
                     </thead>
                     <tbody id="standings-table-body">
                         <!-- Data will be loaded here -->
                     </tbody>
                 </table>
             </div>
             
             <!-- Mobile Cards -->
             <div class="mobile-cards" id="mobile-cards">
                 <!-- Mobile cards will be loaded here -->
             </div>
        </div>
    </div>
</div>
@stop

@section('page-js')
<script>
    let currentContestId = null;
    let autoRefreshInterval;
    let countdownInterval;
    let allParticipants = [];
    let contestEndDate = null;
    let modalShown = false; // Flag to track if a modal has been shown
    
    $(document).ready(function() {
        // Get contest ID from URL
        const urlParts = window.location.pathname.split('/');
        currentContestId = urlParts[urlParts.length - 1];
        console.log('Extracted contest ID:', currentContestId);
        
        if (currentContestId) {
            loadContestDetails();
            loadLeaderboard();
            startAutoRefresh();
        } else {
            console.error('No contest ID found in URL');
        }
        
        // Search functionality
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterParticipants(searchTerm);
        });
        
        // Stop auto-refresh when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        });
    });
    
    function loadContestDetails() {
        $.ajax({
            url: '/user/contest/contest-details/' + currentContestId,
            method: 'GET',
            success: function(response) {
                if (response.status) {
                    const contest = response.contest;
                    $('#contest-title').text(contest.contest_name.toUpperCase() + ' LEADERBOARD');
                    
                    // Debug logging
                    console.log('Contest details:', contest);
                    console.log('End date:', contest.end_date);
                    
                    // Check if contest is closed
                    if (contest.status === 'closed' || contest.status === 'ended' || contest.status === 'completed') {
                        // Contest is closed - stop countdown and update display
                        if (countdownInterval) {
                            clearInterval(countdownInterval);
                        }
                        $('#countdown-display').html('<div class="countdown-expired">CONTEST ENDED</div>');
                        // Don't show closed modal here - let checkForAnnouncedResults handle it
                    } else {
                        // Contest is still active - set up countdown timer if end date exists
                        if (contest.end_date) {
                            contestEndDate = new Date(contest.end_date);
                            console.log('Raw end date from server:', contest.end_date);
                            console.log('Parsed end date:', contestEndDate);
                            console.log('End date ISO string:', contestEndDate.toISOString());
                            console.log('End date local string:', contestEndDate.toLocaleString());
                            console.log('Current time:', new Date().toLocaleString());
                            startCountdown();
                            updateEndDateDisplay();
                        } else {
                            $('#countdown-timer').hide();
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading contest details:', error);
            }
        });
    }
    
    function loadLeaderboard() {
        if (!currentContestId) return;
        
        $.ajax({
            url: '/user/contest/leaderboard',
            method: 'GET',
            data: {
                contest_id: currentContestId,
                draw: 1
            },
            success: function(response) {
                $('#loading-spinner').hide();
                
                if (response && response.data && response.data.length > 0) {
                    const formattedData = response.data.map(item => ({
                        name: item.contestant.name,
                        profit: item.profit.replace('$', '').replace(/,/g, ''),
                        lot: (parseFloat(item.lot.replace(/,/g, '')) / 100).toFixed(2),
                        equity: item.equity ? item.equity.replace('%', '') : 'N/A',
                        gain: ((parseFloat(item.profit.replace('$', '').replace(/,/g, '')) / 10000) * 100).toFixed(2),
                        rank: getRankFromPosition(item.rank),
                        level: Math.floor(Math.random() * 40) + 20,
                        position: item.rank,
                        account: item.account,
                        country: item.contestant.description?.country?.name || 'Unknown'
                    }));
                    
                    // Sort by equity in descending order (highest equity first)
                    formattedData.sort((a, b) => {
                        const equityA = parseFloat(a.equity) || 0;
                        const equityB = parseFloat(b.equity) || 0;
                        return equityB - equityA; // Descending order
                    });
                    
                    // Update position numbers after sorting by equity
                    formattedData.forEach((participant, index) => {
                        participant.position = index + 1;
                        participant.rank = index + 1; // Add rank field
                    });
                    
                    allParticipants = formattedData;
                    updateTop3Cards(formattedData.slice(0, 3));
                    updateStandingsTable(formattedData);
                } else {
                    showNoDataMessage();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading leaderboard:', error);
                $('#loading-spinner').hide();
                showNoDataMessage();
            }
        });
    }
    
    function updateTop3Cards(top3) {
        if (top3 && top3.length >= 3) {
            // Rank 1
            $('#rank1-avatar').text(top3[0].name.charAt(0));
            $('#rank1-name').text(top3[0].name);
            $('#rank1-rank').text(top3[0].rank);
            $('#rank1-profit').text('$' + parseFloat(top3[0].profit).toLocaleString()).removeClass('text-success text-danger').addClass(parseFloat(top3[0].profit) >= 0 ? 'text-success' : 'text-danger');
            $('#rank1-lot').text('Lot: ' + top3[0].lot).removeClass('text-success text-danger').addClass(parseFloat(top3[0].lot) >= 0 ? 'text-success' : 'text-danger');
            
            // Rank 2
            $('#rank2-avatar').text(top3[1].name.charAt(0));
            $('#rank2-name').text(top3[1].name);
            $('#rank2-rank').text(top3[1].rank);
            $('#rank2-profit').text('$' + parseFloat(top3[1].profit).toLocaleString()).removeClass('text-success text-danger').addClass(parseFloat(top3[1].profit) >= 0 ? 'text-success' : 'text-danger');
            $('#rank2-lot').text('Lot: ' + top3[1].lot).removeClass('text-success text-danger').addClass(parseFloat(top3[1].lot) >= 0 ? 'text-success' : 'text-danger');
            
            // Rank 3
            $('#rank3-avatar').text(top3[2].name.charAt(0));
            $('#rank3-name').text(top3[2].name);
            $('#rank3-rank').text(top3[2].rank);
            $('#rank3-profit').text('$' + parseFloat(top3[2].profit).toLocaleString()).removeClass('text-success text-danger').addClass(parseFloat(top3[2].profit) >= 0 ? 'text-success' : 'text-danger');
            $('#rank3-lot').text('Lot: ' + top3[2].lot).removeClass('text-success text-danger').addClass(parseFloat(top3[2].lot) >= 0 ? 'text-success' : 'text-danger');
        }
    }
    
    function updateStandingsTable(standings) {
        let tableHtml = '';
        let mobileCardsHtml = '';
        
        standings.forEach((participant, index) => {
            const isHighlighted = index < 3 ? 'highlighted' : '';
            
            // Calculate Gain% (profit / 10000) × 100 - same as contest-status
            const cleanProfit = participant.profit.replace(/,/g, ''); // Remove commas
            const gainPercentage = ((parseFloat(cleanProfit) / 10000) * 100).toFixed(2);
            
            // Desktop table row
            tableHtml += `
                <tr class="${isHighlighted}">
                    <td><strong>${participant.rank}</strong></td>
                    <td>${participant.account}</td>
                    <td>${participant.name}</td>
                    <td class="lot-cell ${parseFloat(participant.lot) >= 0 ? 'text-success' : 'text-danger'}">${participant.lot}</td>
                    <td class="profit-cell ${parseFloat(participant.profit) >= 0 ? 'text-success' : 'text-danger'}">$${parseFloat(participant.profit).toLocaleString()}</td>
                    <td class="equity-cell ${participant.equity && participant.equity !== 'N/A' ? (parseFloat(participant.equity) >= 0 ? 'text-success' : 'text-danger') : 'text-muted'}">${participant.equity && participant.equity !== 'N/A' ? participant.equity : 'N/A'}</td>
                    <td class="gain-cell ${parseFloat(gainPercentage) >= 0 ? 'text-success' : 'text-danger'}">${gainPercentage}%</td>
                </tr>
            `;
            
            // Mobile card
            mobileCardsHtml += `
                <div class="mobile-card ${isHighlighted}">
                    <div class="mobile-card-header">
                        <div class="mobile-rank">${participant.rank}</div>
                        <div class="mobile-user-info">
                            <div class="mobile-user-name">${participant.name}</div>
                            <div class="mobile-account">${participant.account}</div>
                        </div>
                        <div class="mobile-level">Rank ${participant.rank}</div>
                    </div>
                    <div class="mobile-card-stats">
                        <div class="mobile-stat-item">
                            <div class="mobile-stat-label">LOT</div>
                            <div class="mobile-stat-value ${parseFloat(participant.lot) >= 0 ? 'text-success' : 'text-danger'}">${participant.lot}</div>
                        </div>
                        <div class="mobile-stat-item">
                            <div class="mobile-stat-label">PROFIT</div>
                            <div class="mobile-stat-value ${parseFloat(participant.profit) >= 0 ? 'text-success' : 'text-danger'}">$${parseFloat(participant.profit).toLocaleString()}</div>
                        </div>
                        <div class="mobile-stat-item">
                            <div class="mobile-stat-label">EQUITY</div>
                            <div class="mobile-stat-value ${participant.equity && participant.equity !== 'N/A' ? (parseFloat(participant.equity) >= 0 ? 'text-success' : 'text-danger') : 'text-muted'}">${participant.equity && participant.equity !== 'N/A' ? participant.equity : 'N/A'}</div>
                        </div>
                        <div class="mobile-stat-item">
                            <div class="mobile-stat-label">GAIN%</div>
                            <div class="mobile-stat-value ${parseFloat(gainPercentage) >= 0 ? 'text-success' : 'text-danger'}">${gainPercentage}%</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#standings-table-body').html(tableHtml);
        $('#mobile-cards').html(mobileCardsHtml);
    }
    
    function filterParticipants(searchTerm) {
        const filteredParticipants = allParticipants.filter(participant => 
            participant.name.toLowerCase().includes(searchTerm) ||
            participant.account.toLowerCase().includes(searchTerm)
        );
        updateStandingsTable(filteredParticipants);
    }
    
    function showNoDataMessage() {
        const noDataTableHtml = `
            <tr>
                <td colspan="7" class="text-center py-5">
                    <h4>No participants found</h4>
                    <p class="text-muted">This contest has no participants yet.</p>
                </td>
            </tr>
        `;
        
        const noDataMobileHtml = `
            <div class="mobile-card">
                <div class="text-center py-5">
                    <h4>No participants found</h4>
                    <p class="text-muted">This contest has no participants yet.</p>
                </div>
            </div>
        `;
        
        $('#standings-table-body').html(noDataTableHtml);
        $('#mobile-cards').html(noDataMobileHtml);
    }
    
    function getRankFromPosition(position) {
        if (position === 1) return 'Champion Silver';
        if (position === 2) return 'Elite Silver';
        if (position === 3) return 'Elite Silver';
        if (position <= 5) return 'Captain Silver';
        if (position <= 10) return 'Pro Silver';
        if (position <= 20) return 'Intermediate Silver';
        if (position <= 30) return 'Beginner Silver';
        return 'Rookie Silver';
    }
    
    function startAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        
        autoRefreshInterval = setInterval(function() {
            console.log('🔄 Auto-refreshing contest leaderboard...');
            loadLeaderboard();
        }, 30000); // 30 seconds
        
        console.log('✅ Auto-refresh started - leaderboard will update every 30 seconds');
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
            console.log('⏹️ Auto-refresh stopped');
        }
    }
    
    function startCountdown() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        
        // Test calculation with a known time difference
        const testTimeDiff = 6 * 24 * 60 * 60 * 1000 + 6 * 60 * 60 * 1000 + 30 * 60 * 1000 + 10 * 1000; // 6 days, 6 hours, 30 minutes, 10 seconds
        const testTotalSeconds = Math.floor(testTimeDiff / 1000);
        const testDays = Math.floor(testTotalSeconds / (24 * 60 * 60));
        const testHours = Math.floor((testTotalSeconds % (24 * 60 * 60)) / (60 * 60));
        const testMinutes = Math.floor((testTotalSeconds % (60 * 60)) / 60);
        const testSeconds = testTotalSeconds % 60;
        console.log('Test calculation (6d 6h 30m 10s):', { testDays, testHours, testMinutes, testSeconds });
        
        countdownInterval = setInterval(function() {
            updateCountdown();
        }, 1000);
        
        // Initial update
        updateCountdown();
    }
    
    function updateCountdown() {
        if (!contestEndDate) return;
        
        const now = new Date().getTime();
        const endTime = contestEndDate.getTime();
        const timeDifference = endTime - now;
        
        // Debug: Show exact times
        console.log('Current time:', new Date(now).toLocaleString());
        console.log('End time:', new Date(endTime).toLocaleString());
        console.log('Time difference (ms):', timeDifference);
        console.log('Time difference (hours):', timeDifference / (1000 * 60 * 60));
        
        if (timeDifference <= 0) {
            // Contest has ended
            $('#countdown-display').html('<div class="countdown-expired">CONTEST ENDED</div>');
            clearInterval(countdownInterval);
            // Don't show closed modal here - let checkForAnnouncedResults handle it
            return;
        }
        
        // Calculate time units with better precision
        const totalSeconds = Math.floor(timeDifference / 1000);
        const days = Math.floor(totalSeconds / (24 * 60 * 60));
        const hours = Math.floor((totalSeconds % (24 * 60 * 60)) / (60 * 60));
        const minutes = Math.floor((totalSeconds % (60 * 60)) / 60);
        const seconds = totalSeconds % 60;
        
        // Debug logging
        console.log('Total seconds:', totalSeconds);
        console.log('Days calculation:', Math.floor(totalSeconds / (24 * 60 * 60)));
        console.log('Hours calculation:', Math.floor((totalSeconds % (24 * 60 * 60)) / (60 * 60)));
        console.log('Minutes calculation:', Math.floor((totalSeconds % (60 * 60)) / 60));
        console.log('Seconds calculation:', totalSeconds % 60);
        console.log('Countdown values:', { days, hours, minutes, seconds });
        
        // Update display with specific selectors
        $('.countdown-number#days').text(days.toString().padStart(2, '0'));
        $('.countdown-number#hours').text(hours.toString().padStart(2, '0'));
        $('.countdown-number#minutes').text(minutes.toString().padStart(2, '0'));
        $('.countdown-number#seconds').text(seconds.toString().padStart(2, '0'));
        
        // Debug: Check if minutes element exists and is visible
        const minutesElement = $('.countdown-number#minutes');
        console.log('Minutes countdown element:', minutesElement.length > 0 ? 'Found' : 'Not found');
        console.log('Minutes element text:', minutesElement.text());
        console.log('Minutes element visible:', minutesElement.is(':visible'));
        console.log('Minutes element HTML:', minutesElement.html());
        console.log('Minutes element outerHTML:', minutesElement[0]?.outerHTML);
        
        // Force update minutes with direct DOM manipulation using specific selector
        const minutesDomElement = document.querySelector('.countdown-number#minutes');
        if (minutesDomElement) {
            minutesDomElement.textContent = minutes.toString().padStart(2, '0');
            console.log('Direct DOM update - minutes set to:', minutes.toString().padStart(2, '0'));
        } else {
            console.log('Minutes countdown DOM element not found!');
        }
        
        // Check for multiple elements with same ID
        const allMinutesElements = document.querySelectorAll('#minutes');
        console.log('Number of elements with id="minutes":', allMinutesElements.length);
        
        // Show all elements with minutes ID
        allMinutesElements.forEach((el, index) => {
            console.log(`Element ${index + 1} with id="minutes":`, el.tagName, el.outerHTML);
        });
        
        // Add pulsing effect when less than 1 hour remaining
        if (timeDifference < 3600000) { // Less than 1 hour
            $('.countdown-number').addClass('pulse');
        } else {
            $('.countdown-number').removeClass('pulse');
        }
    }
    
    function updateEndDateDisplay() {
        if (!contestEndDate) return;
        
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            timeZoneName: 'short'
        };
        
        const formattedDate = contestEndDate.toLocaleDateString('en-US', options);
        $('#contest-end-date').text(formattedDate);
    }
    
    // Check for announced results when page loads
    function checkForAnnouncedResults() {
        if (!currentContestId) {
            console.log('No contest ID available, skipping results check');
            return;
        }
        console.log('Checking for announced results for contest:', currentContestId);
        
        // Check if CSRF token is available
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
        
        $.ajax({
            url: '/user/contest/check-announced-results',
            method: 'POST',
            data: {
                contest_id: currentContestId,
                _token: csrfToken
            },
            success: function(response) {
                console.log('Response from check-announced-results:', response);
                console.log('Response status:', response.status);
                console.log('Response message:', response.message);
                
                if (response.status && response.data && response.data.winners && response.data.winners.length > 0) {
                    console.log('Results found, showing results modal');
                    console.log('Winners data:', response.data.winners);
                    // Results have been announced - show results modal
                    $('#contestClosedModal').modal('hide'); // Hide any existing closed modal
                    modalShown = true; // Mark that we've shown a modal
                    showAnnouncedResultsModal(response.data);
                } else if (response.status === false && response.message === 'Results not yet announced') {
                    console.log('Contest closed but results not announced yet');
                    // Contest is closed but results not announced yet - show closed modal
                    if (!modalShown) {
                        modalShown = true; // Mark that we've shown a modal
                        showContestClosedModal();
                    }
                } else if (response.status === false && response.message === 'Contest is active') {
                    console.log('Contest is active, no modal needed');
                    // Contest is active - don't show any modal
                } else if (response.status === false && response.message === 'Contest not found') {
                    console.log('Contest not found');
                    // Don't show any modal for invalid contests
                } else {
                    console.log('Contest has other status, no modal needed');
                    console.log('Full response:', response);
                    // Contest has other status - don't show any modal
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking announced results:', error);
                console.log('Response text:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
            }
        });
    }
    
    // Show contest closed modal
    function showContestClosedModal() {
        console.log('showContestClosedModal called');
        let modalHtml = `
            <div class="modal fade" id="contestClosedModal" tabindex="-1" aria-labelledby="contestClosedModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="contestClosedModalLabel">
                                <i class="fas fa-clock me-2"></i>Contest Closed
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-hourglass-half text-warning" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="mb-3">Contest Has Ended</h5>
                            <p class="text-muted mb-4">
                                This contest has been closed. Results will be announced shortly. 
                                Please check back later for the final results and winner announcements.
                            </p>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> All trading activities have been stopped for this contest.
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                <i class="fas fa-check me-2"></i>Understood
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        $('#contestClosedModal').remove();
        
        // Add modal to body
        $('body').append(modalHtml);
        
        // Show modal
        $('#contestClosedModal').modal('show');
        
        // Reset flag when modal is closed
        $('#contestClosedModal').on('hidden.bs.modal', function() {
            modalShown = false;
        });
    }
    
    // Show announced results modal
    function showAnnouncedResultsModal(data) {
        console.log('showAnnouncedResultsModal called with data:', data);
        let modalHtml = `
            <div class="modal fade" id="announcedResultsModal" tabindex="-1" aria-labelledby="announcedResultsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="announcedResultsModalLabel">
                                <i class="fas fa-trophy me-2"></i>Contest Results Announced!
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-success">
                                        <h6 class="mb-3"><i class="fas fa-star me-2"></i>Contest: ${data.contest_name}</h6>
                                        <p class="mb-0">Congratulations to all winners! Results have been officially announced.</p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Winner Name</th>
                                                    <th>Account Number</th>
                                                    <th>Equity</th>
                                                    <th>Profit</th>
                                                    <th>Prize</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${generateWinnerTableRows(data.winners)}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        $('#announcedResultsModal').remove();
        
        // Add modal to body
        $('body').append(modalHtml);
        
        // Show modal
        $('#announcedResultsModal').modal('show');
        
        // Reset flag when modal is closed
        $('#announcedResultsModal').on('hidden.bs.modal', function() {
            modalShown = false;
        });
    }
    
    // Generate winner table rows
    function generateWinnerTableRows(winners) {
        let rows = '';
        winners.forEach((winner, index) => {
            // Use rank from backend or calculate from index
            const rank = winner.rank || (index + 1);
            const rankClass = rank === 1 ? 'table-warning' : rank === 2 ? 'table-light' : rank === 3 ? 'table-info' : '';
            rows += `
                <tr class="${rankClass}">
                    <td><strong>${rank}</strong></td>
                    <td><strong>${winner.user_name}</strong></td>
                    <td>${winner.account_number}</td>
                    <td>$${winner.equity}</td>
                    <td>$${winner.profit}</td>
                    <td><span class="badge bg-success">$${winner.prize_amount}</span></td>
                </tr>
            `;
        });
        return rows;
    }
    
            // Check for announced results function
        function checkForAnnouncedResults() {
            console.log('=== CHECKING FOR ANNOUNCED RESULTS ===');
            console.log('Current contest ID:', currentContestId);
            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            
            $.ajax({
                url: '/user/contest/check-announced-results',
                method: 'POST',
                data: {
                    contest_id: currentContestId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    console.log('Sending AJAX request...');
                },
                success: function(response) {
                    console.log('=== AJAX SUCCESS ===');
                    console.log('Full response:', response);
                    console.log('Response status:', response.status);
                    console.log('Response message:', response.message);
                    console.log('Response data:', response.data);
                    
                    if (response.status && response.data) {
                        console.log('✅ Results found, showing announced results modal');
                        showAnnouncedResultsModal(response.data);
                    } else if (response.message === 'Results not yet announced') {
                        console.log('⏳ Contest closed but results not announced yet');
                        if (!modalShown) {
                            showContestClosedModal();
                        }
                    } else if (response.message === 'Contest is active') {
                        console.log('🟢 Contest is active, no modal needed');
                        // Don't show any modal for active contests
                    } else {
                        console.log('❓ Other status:', response.message);
                        // Don't show any modal for other statuses
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== AJAX ERROR ===');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Status Code:', xhr.status);
                    console.error('Status Text:', xhr.statusText);
                }
            });
        }
    
    // Check for announced results when page loads
    $(document).ready(function() {
        console.log('Page loaded, setting up result checks...');
        
        // Get contest ID from URL
        const urlParts = window.location.pathname.split('/');
        currentContestId = urlParts[urlParts.length - 1];
        console.log('Extracted contest ID:', currentContestId);
        
        if (currentContestId) {
            loadContestDetails();
            loadLeaderboard();
            startAutoRefresh();
        } else {
            console.error('No contest ID found in URL');
        }
        
        // Search functionality
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterParticipants(searchTerm);
        });
        
        // Stop auto-refresh when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        });
        
        // Check for announced results after a short delay
        setTimeout(function() {
            console.log('Initial result check starting...');
            checkForAnnouncedResults();
        }, 2000);
        

        
        // Set up periodic check for results (every 30 seconds)
        setInterval(function() {
            console.log('Periodic result check...');
            checkForAnnouncedResults();
        }, 30000); // Check every 30 seconds
    });
</script>

<style>
/* Contest Closed Modal Styles */
#contestClosedModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

#contestClosedModal .modal-header {
    border-bottom: 2px solid #ffc107;
    border-radius: 15px 15px 0 0;
}

#contestClosedModal .modal-body {
    padding: 2rem;
}

#contestClosedModal .fas.fa-hourglass-half {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

#contestClosedModal .alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #90caf9;
    border-radius: 10px;
}

#contestClosedModal .btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 25px;
    padding: 10px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

#contestClosedModal .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
}
</style>
@endsection
