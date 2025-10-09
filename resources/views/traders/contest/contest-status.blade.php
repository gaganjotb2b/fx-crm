@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Participate contest')
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

/* Winner Announcement Popup */
.winner-announcement-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    z-index: 99999;
    max-width: 350px;
    width: 90%;
    text-align: center;
    color: #333;
    animation: winnerSlideIn 0.8s ease-out;
    border: 2px solid #ff8e5c;
}

.winner-popup-content {
    position: relative;
}

.winner-popup-icon {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #ff8e5c;
    text-shadow: 0 0 10px rgba(255, 142, 92, 0.8);
    animation: trophyGlow 2s ease-in-out infinite alternate;
}

.winner-popup-text h2 {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 8px;
    color: #ff8e5c;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.winner-popup-text p {
    font-size: 0.9rem;
    margin-bottom: 6px;
    color: #333;
    line-height: 1.3;
}

.winner-prize-info {
    background: linear-gradient(135deg, #fff5f0 0%, #ffe8d6 100%);
    border-radius: 8px;
    padding: 10px;
    margin: 8px 0;
    border: 2px solid #ff8e5c;
    box-shadow: 0 3px 8px rgba(255, 142, 92, 0.2);
}

.winner-prize-info h3 {
    font-size: 0.9rem;
    color: #ff8e5c;
    margin-bottom: 6px;
    font-weight: 700;
    text-align: center;
}

.winner-prize-info p {
    font-size: 1rem;
    color: #333;
    margin-bottom: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 8px;
    border-left: 4px solid #ff8e5c;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.winner-prize-info p:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.winner-popup-close {
    position: absolute;
    top: -15px;
    right: -15px;
    background: linear-gradient(45deg, #ff8e5c, #ff6b35);
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
}

.winner-popup-close:hover {
    background: linear-gradient(45deg, #ff6b35, #ff8e5c);
    transform: scale(1.1) rotate(90deg);
}

@keyframes winnerSlideIn {
    from {
        transform: translate(-50%, -60%);
        opacity: 0;
        scale: 0.8;
    }
    to {
        transform: translate(-50%, -50%);
        opacity: 1;
        scale: 1;
    }
}

@keyframes winnerSlideOut {
    from {
        transform: translate(-50%, -50%);
        opacity: 1;
        scale: 1;
    }
    to {
        transform: translate(-50%, -60%);
        opacity: 0;
        scale: 0.8;
    }
}

@keyframes trophyGlow {
    0% {
        text-shadow: 0 0 20px rgba(255, 142, 92, 0.8);
        transform: scale(1);
    }
    100% {
        text-shadow: 0 0 35px rgba(255, 142, 92, 1);
        transform: scale(1.1);
    }
}

/* Winner popup overlay */
.winner-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 99998;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Ensure popup appears above header and all other elements */
.winner-announcement-popup,
.winner-popup-overlay {
    position: fixed !important;
    z-index: 99999 !important;
}

/* Force popup to be on top of everything */
.winner-announcement-popup {
    position: fixed !important;
    z-index: 99999 !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
}

.winner-popup-overlay {
    position: fixed !important;
    z-index: 99998 !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
}

/* Leaderboard Header */
.leaderboard-header {
    text-align: center;
    margin-bottom: 25px;
    position: relative;
    z-index: 2;
}

.leaderboard-title {
    font-size: 3rem;
    font-weight: 900;
    color: #333;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
}

.leaderboard-subtitle {
    font-size: 1.3rem;
    color: #666;
    font-weight: 300;
    letter-spacing: 1px;
}

/* Top 3 Section */
.top-3-section {
    display: flex;
    justify-content: center;
    gap: 25px;
    margin-bottom: 25px;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.top-card {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 20px 15px;
    text-align: center;
    position: relative;
    min-width: 180px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1), 0 0 0 1px rgba(255,142,92,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(255,142,92,0.2);
}

.top-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 0 0 2px rgba(255,142,92,0.3);
}

.top-card.rank-1 {
    border: 3px solid #ffd700;
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3), 0 10px 25px rgba(0,0,0,0.1);
    background: linear-gradient(145deg, #ffffff, #fff8e1);
    position: relative;
}

.top-card.rank-1::before {
    content: '👑';
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 40px;
    z-index: 10;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.top-card.rank-2 {
    border: 3px solid #c0c0c0;
    box-shadow: 0 0 15px rgba(192, 192, 192, 0.3), 0 10px 25px rgba(0,0,0,0.1);
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
}

.top-card.rank-3 {
    border: 3px solid #cd7f32;
    box-shadow: 0 0 15px rgba(205, 127, 50, 0.3), 0 10px 25px rgba(0,0,0,0.1);
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
}

.rank-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #ff8e5c, #ff6b35);
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 18px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.4), 0 0 0 2px rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
}

.profile-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 22px;
    background: linear-gradient(135deg, #ff8e5c, #ff6b35);
    border: 3px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3), inset 0 2px 4px rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.top-card:hover .profile-avatar {
    transform: scale(1.1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.4), inset 0 2px 4px rgba(255,255,255,0.3);
}

.top-card.rank-1 .profile-avatar {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    border-color: rgba(255, 215, 0, 0.5);
}

.top-card.rank-2 .profile-avatar {
    background: linear-gradient(135deg, #c0c0c0, #e5e5e5);
    border-color: rgba(192, 192, 192, 0.5);
}

.top-card.rank-3 .profile-avatar {
    background: linear-gradient(135deg, #cd7f32, #daa520);
    border-color: rgba(205, 127, 50, 0.5);
}

.user-name {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 6px;
    color: #333;
    text-shadow: none;
}

.user-rank {
    font-size: 12px;
    opacity: 0.8;
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.profit-score {
    font-size: 20px;
    font-weight: 900;
    color: #ff8e5c;
    text-shadow: none;
    margin: 8px 0 5px;
}

.lot-score {
    font-size: 14px;
    font-weight: 600;
    color: #666;
    margin-top: 5px;
    padding: 3px 10px;
    background: rgba(255,142,92,0.1);
    border-radius: 12px;
    display: inline-block;
}

/* Standings Section */
.standings-section {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 25px;
    margin: 0 20px;
    border: 1px solid rgba(255,142,92,0.2);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    position: relative;
    z-index: 2;
    overflow: hidden;
}

.standings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.standings-title {
    font-size: 28px;
    font-weight: 800;
    color: #000;
    text-shadow: none;
}

.standings-title span {
    color: #ff8e5c;
    text-shadow: none;
}

.search-box {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-input {
    background: rgba(255,255,255,0.9);
    border: 2px solid rgba(255,142,92,0.3);
    border-radius: 20px;
    padding: 8px 15px;
    color: #333;
    width: 220px;
    font-size: 14px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.search-input:focus {
    outline: none;
    border-color: #ff8e5c;
    box-shadow: 0 0 0 3px rgba(255, 142, 92, 0.2);
    background: rgba(255,255,255,1);
}

.search-input::placeholder {
    color: #999;
}

.search-btn {
    background: linear-gradient(135deg, #ff8e5c, #ff6b35);
    border: none;
    border-radius: 20px;
    padding: 8px 15px;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(255, 142, 92, 0.3);
}

.search-btn:hover {
    background: linear-gradient(135deg, #ff6b35, #ff8e5c);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(255, 142, 92, 0.4);
}

/* Standings Table */
.standings-table {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    overflow-x: auto;
    border: 1px solid rgba(255,142,92,0.2);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    -webkit-overflow-scrolling: touch;
    position: relative;
}

/* Mobile card layout for standings */
.mobile-standings {
    display: none; /* Hidden by default on desktop */
}

@media (max-width: 768px) {
    .standings-table {
        overflow: visible;
        background: transparent;
        border: none;
        box-shadow: none;
    }
    
    .table {
        display: none; /* Hide desktop table on mobile */
    }
    
    /* Mobile card layout */
    .mobile-standings {
        display: block;
    }
    
    .mobile-standing-card {
        background: rgba(255,255,255,0.95);
        border-radius: 12px;
        margin-bottom: 12px;
        padding: 15px;
        border: 1px solid rgba(255,142,92,0.2);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
    }
    
    .mobile-standing-card.highlighted {
        border-left: 4px solid #ff8e5c;
        background: linear-gradient(135deg, rgba(255,142,92,0.05), rgba(255,107,53,0.05));
    }
    
    .mobile-standing-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .mobile-position {
        background: linear-gradient(135deg, #ff8e5c, #ff6b35);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }
    
    .mobile-user-info {
        flex: 1;
        margin-left: 12px;
    }
    
    .mobile-user-name {
        font-weight: 600;
        color: #000;
        font-size: 16px;
        margin-bottom: 4px;
    }
    
    .mobile-account-number {
        color: #666;
        font-size: 11px;
        font-weight: 500;
        font-family: monospace;
        margin-bottom: 2px;
    }
    
    .mobile-user-rank {
        color: #ff8e5c;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    
    .mobile-user-level {
        color: #666;
        font-size: 11px;
    }
    
    .mobile-standing-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .mobile-stat-item {
        background: rgba(255,142,92,0.1);
        border-radius: 8px;
        padding: 8px 12px;
        text-align: center;
    }
    
    .mobile-stat-label {
        font-size: 10px;
        color: #666;
        text-transform: uppercase;
        font-weight: 500;
        margin-bottom: 4px;
    }
    
    .mobile-stat-value {
        font-size: 14px;
        font-weight: 700;
        color: #000;
    }
    
    .mobile-profit-value {
        color: #ff8e5c;
    }
    
    .mobile-standing-actions {
        display: flex;
        justify-content: center;
    }
    
    .mobile-view-stats-btn {
        background: linear-gradient(135deg, #ff8e5c, #ff6b35);
        border: none;
        border-radius: 20px;
        padding: 8px 20px;
        color: white;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        min-height: 36px;
        min-width: 100px;
    }
    
    .mobile-view-stats-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,142,92,0.3);
    }
}

@media (max-width: 480px) {
    .mobile-standing-card {
        padding: 12px;
        margin-bottom: 10px;
    }
    
    .mobile-user-name {
        font-size: 14px;
    }
    
    .mobile-standing-stats {
        gap: 8px;
    }
    
    .mobile-stat-item {
        padding: 6px 8px;
    }
    
    .mobile-stat-value {
        font-size: 12px;
    }
    
    .mobile-view-stats-btn {
        padding: 6px 16px;
        font-size: 11px;
    }
}

.table {
    margin-bottom: 0;
    color: #333;
}

.table thead th {
    background: linear-gradient(135deg, rgba(255, 142, 92, 0.1), rgba(255, 107, 53, 0.1));
    border: none;
    color: #000;
    font-weight: 700;
    padding: 15px 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 12px;
    text-shadow: none;
}

.table tbody tr {
    border-bottom: 1px solid rgba(255,142,92,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.table tbody tr:hover {
    background: rgba(255,142,92,0.05);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table tbody tr.highlighted {
    background: linear-gradient(135deg, rgba(255, 142, 92, 0.1), rgba(255, 107, 53, 0.1));
    border-left: 4px solid #ff8e5c;
    box-shadow: inset 0 0 10px rgba(255, 142, 92, 0.05);
}

.table td {
    padding: 12px;
    border: none;
    vertical-align: middle;
    font-size: 14px;
}

.position-cell {
    font-weight: 900;
    font-size: 16px;
    color: #ff8e5c;
    text-shadow: 0 0 10px rgba(255, 142, 92, 0.5);
}

.profit-cell {
    font-weight: 700;
    color: #000;
    font-size: 16px;
    text-shadow: none;
}

.lot-cell {
    font-weight: 600;
    color: #000;
}

.account-cell {
    font-weight: 600;
    color: #666;
    font-size: 12px;
    font-family: monospace;
}

.user-cell {
    font-weight: 600;
    color: #000;
    font-size: 14px;
}

.rank-cell {
    font-weight: 600;
    color: #ff8e5c;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
}

.level-cell {
    font-weight: 600;
    color: #ff8e5c;
    font-size: 12px;
}

.view-stats-btn {
    background: linear-gradient(135deg, #ff8e5c, #ff6b35);
    border: none;
    border-radius: 15px;
    padding: 6px 12px;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 142, 92, 0.3);
}

.view-stats-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 142, 92, 0.4);
    background: linear-gradient(135deg, #ff6b35, #ff8e5c);
}

.info-buttons {
    position: absolute;
    top: 25px;
    right: 25px;
    display: flex;
    gap: 12px;
}

.info-btn {
    background: rgba(0,0,0,0.6);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    padding: 12px 18px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    backdrop-filter: blur(10px);
}

.info-btn:hover {
    background: rgba(255, 142, 92, 0.3);
    border-color: #ff8e5c;
    transform: translateY(-2px);
}

.update-note {
    text-align: center;
    margin: 15px 0;
    color: #666;
    font-size: 13px;
    padding: 10px;
    background: rgba(255,255,255,0.9);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,142,92,0.2);
}

.update-note .asterisk {
    color: #ff8e5c;
    font-weight: 600;
}

/* Floating Particles Effect */
.particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    animation: float 6s infinite linear;
}

@keyframes float {
    0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .leaderboard-container {
        padding: 5px 0;
    }
    
    .leaderboard-title {
        font-size: 2rem;
        letter-spacing: 2px;
        margin-bottom: 15px;
    }
    
    .leaderboard-subtitle {
        font-size: 1rem;
    }
    
    .top-3-section {
        flex-direction: column;
        align-items: center;
        gap: 20px;
        padding: 0 10px;
    }
    
    .top-card {
        min-width: 280px;
        max-width: 320px;
        padding: 15px 10px;
    }
    
    .top-card .profile-avatar {
        width: 50px;
        height: 50px;
        font-size: 18px;
    }
    
    .top-card .user-name {
        font-size: 14px;
    }
    
    .top-card .profit-score {
        font-size: 18px;
    }
    
    .top-card .lot-score {
        font-size: 12px;
    }
    
    .standings-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        margin-bottom: 15px;
    }
    
    .standings-title {
        font-size: 1.5rem;
    }
    
    .search-box {
        width: 100%;
        justify-content: center;
    }
    
    .search-input {
        width: 100%;
        max-width: 280px;
        font-size: 14px;
        padding: 10px 15px;
    }
    
    .search-btn {
        padding: 10px 15px;
        font-size: 14px;
    }
    
    .standings-section {
        margin: 0 10px;
        padding: 20px 15px;
        border-radius: 15px;
    }
    
    .standings-table {
        border-radius: 10px;
        overflow-x: auto;
        margin: 0 -15px;
        width: calc(100% + 30px);
    }
    
    .table {
        min-width: 600px;
        width: 100%;
    }
    
    .table {
        font-size: 12px;
    }
    
    .table thead th {
        padding: 12px 8px;
        font-size: 10px;
        letter-spacing: 0.5px;
    }
    
    .table td {
        padding: 10px 8px;
        font-size: 12px;
    }
    
    .position-cell {
        font-size: 14px;
    }
    
    .profit-cell {
        font-size: 14px;
    }
    
    .user-cell {
        font-size: 12px;
    }
    
    .view-stats-btn {
        padding: 5px 8px;
        font-size: 10px;
        border-radius: 10px;
    }
    
    .update-note {
        margin: 10px 0;
        padding: 8px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .leaderboard-title {
        font-size: 1.5rem;
        letter-spacing: 1px;
    }
    
    .top-card {
        min-width: 250px;
        max-width: 280px;
        padding: 12px 8px;
    }
    
    .top-card .profile-avatar {
        width: 45px;
        height: 45px;
        font-size: 16px;
    }
    
    .top-card .user-name {
        font-size: 13px;
    }
    
    .top-card .profit-score {
        font-size: 16px;
    }
    
    .top-card .lot-score {
        font-size: 11px;
    }
    
    .standings-title {
        font-size: 1.3rem;
    }
    
    .search-input {
        max-width: 250px;
        font-size: 13px;
    }
    
    .standings-section {
        margin: 0 5px;
        padding: 15px 10px;
        border-radius: 12px;
    }
    
    .standings-table {
        margin: 0 -10px;
        width: calc(100% + 20px);
        border-radius: 8px;
    }
    
    .table {
        min-width: 500px;
    }
    
    /* Mobile table column optimization */
    .table th:nth-child(1), .table td:nth-child(1) { /* Position */
        min-width: 60px;
        width: 60px;
    }
    
    .table th:nth-child(2), .table td:nth-child(2) { /* Account */
        min-width: 80px;
        width: 80px;
    }
    
    .table th:nth-child(3), .table td:nth-child(3) { /* User */
        min-width: 120px;
        width: 120px;
    }
    
    .table th:nth-child(4), .table td:nth-child(4) { /* Lot */
        min-width: 60px;
        width: 60px;
    }
    
    .table th:nth-child(5), .table td:nth-child(5) { /* Profit */
        min-width: 80px;
        width: 80px;
    }
    
    .table th:nth-child(6), .table td:nth-child(6) { /* Gain% */
        min-width: 70px;
        width: 70px;
    }
    
    .table th:nth-child(7), .table td:nth-child(7) { /* Action */
        min-width: 80px;
        width: 80px;
    }
    
    .table thead th {
        padding: 8px 4px;
        font-size: 8px;
        white-space: nowrap;
    }
    
    .table td {
        padding: 6px 4px;
        font-size: 10px;
        white-space: nowrap;
    }
    
    /* Mobile table column optimization for small screens */
    .table th:nth-child(1), .table td:nth-child(1) { /* Position */
        min-width: 50px;
        width: 50px;
    }
    
    .table th:nth-child(2), .table td:nth-child(2) { /* Account */
        min-width: 70px;
        width: 70px;
    }
    
    .table th:nth-child(3), .table td:nth-child(3) { /* User */
        min-width: 100px;
        width: 100px;
    }
    
    .table th:nth-child(4), .table td:nth-child(4) { /* Lot */
        min-width: 70px;
        width: 70px;
    }
    
    .table th:nth-child(5), .table td:nth-child(5) { /* Profit */
        min-width: 50px;
        width: 50px;
    }
    
    .table th:nth-child(6), .table td:nth-child(6) { /* Gain% */
        min-width: 50px;
        width: 50px;
    }
    
    .table th:nth-child(7), .table td:nth-child(7) { /* Action */
        min-width: 70px;
        width: 70px;
    }
    
    .position-cell {
        font-size: 12px;
    }
    
    .profit-cell {
        font-size: 12px;
    }
    
    .user-cell {
        font-size: 11px;
    }
    
    .view-stats-btn {
        padding: 4px 6px;
        font-size: 9px;
    }
    
    .update-note {
        font-size: 11px;
        padding: 6px;
    }
}

@media (max-width: 360px) {
    .leaderboard-title {
        font-size: 1.3rem;
    }
    
    .top-card {
        min-width: 220px;
        max-width: 250px;
    }
    
    .top-card .profile-avatar {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .top-card .user-name {
        font-size: 12px;
    }
    
    .top-card .profit-score {
        font-size: 14px;
    }
    
    .standings-title {
        font-size: 1.1rem;
    }
    
    .search-input {
        max-width: 220px;
        font-size: 12px;
    }
    
    .table thead th {
        padding: 6px 3px;
        font-size: 7px;
        white-space: nowrap;
    }
    
    .table td {
        padding: 5px 3px;
        font-size: 9px;
        white-space: nowrap;
    }
    
    /* Mobile table column optimization for very small screens */
    .table th:nth-child(1), .table td:nth-child(1) { /* Position */
        min-width: 40px;
        width: 40px;
    }
    
    .table th:nth-child(2), .table td:nth-child(2) { /* Account */
        min-width: 60px;
        width: 60px;
    }
    
    .table th:nth-child(3), .table td:nth-child(3) { /* User */
        min-width: 80px;
        width: 80px;
    }
    
    .table th:nth-child(4), .table td:nth-child(4) { /* Lot */
        min-width: 60px;
        width: 60px;
    }
    
    .table th:nth-child(5), .table td:nth-child(5) { /* Profit */
        min-width: 40px;
        width: 40px;
    }
    
    .table th:nth-child(6), .table td:nth-child(6) { /* Gain% */
        min-width: 40px;
        width: 40px;
    }
    
    .table th:nth-child(7), .table td:nth-child(7) { /* Action */
        min-width: 60px;
        width: 60px;
    }
    
    .view-stats-btn {
        padding: 3px 5px;
        font-size: 8px;
    }
}

/* Loading Animation */
.loading {
    text-align: center;
    padding: 50px;
    color: white;
    font-size: 1.3rem;
    font-weight: 600;
}

.loading::after {
    content: '';
    display: inline-block;
    width: 25px;
    height: 25px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
    margin-left: 15px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #ff8e5c, #ff6b35);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #ff6b35, #ff8e5c);
    }

/* Mobile Touch Optimizations */
@media (max-width: 768px) {
    .view-stats-btn {
        min-height: 36px;
        min-width: 60px;
        touch-action: manipulation;
    }
    
    .search-btn {
        min-height: 40px;
        min-width: 50px;
        touch-action: manipulation;
    }
    
    .top-card {
        touch-action: manipulation;
    }
    
    .table tbody tr {
        touch-action: manipulation;
    }
    
    /* Improve scrolling on mobile */
    .standings-table {
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }
    
    /* Better touch targets */
    .table td {
        min-height: 44px;
        display: flex;
        align-items: center;
    }
    
    /* Optimize modal for mobile */
    .stats-modal .modal-dialog {
        margin: 10px;
        max-width: calc(100vw - 20px);
    }
    
    .stats-modal .modal-content {
        border-radius: 15px;
    }
    
    /* Better chart responsiveness */
    .chart-wrapper canvas {
        max-width: 100%;
        height: auto !important;
    }
    
    /* Standings section mobile optimizations */
    .standings-section {
        margin: 0 8px;
        padding: 15px 12px;
    }
    
    .standings-header {
        margin-bottom: 12px;
    }
    
    .standings-title {
        font-size: 1.4rem;
        line-height: 1.2;
    }
    
    /* Table scroll indicator */
    .standings-table::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 20px;
        background: linear-gradient(to right, transparent, rgba(255,142,92,0.1));
        pointer-events: none;
        z-index: 1;
    }
    
    /* Mobile table improvements */
    .table {
        border-collapse: collapse;
    }
    
    .table tbody tr {
        border-bottom: 1px solid rgba(255,142,92,0.1);
    }
    
    .table tbody tr:last-child {
        border-bottom: none;
    }
    }

/* Stats Modal Styles */
.stats-modal {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 249, 250, 0.98) 100%);
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    max-width: 95vw;
    margin: 20px auto;
}

.stats-modal .modal-header {
    background: rgba(255,142,92,0.1);
    border-bottom: 1px solid rgba(255,142,92,0.2);
    border-radius: 20px 20px 0 0;
    padding: 20px 25px;
}

.stats-modal .modal-title {
    color: #333;
    font-weight: 700;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-modal .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.stats-modal .modal-body {
    padding: 30px;
    background: rgba(255,255,255,0.5);
    max-height: 70vh;
    overflow-y: auto;
}

.stats-container {
    color: #333;
}

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,142,92,0.2);
    transition: all 0.3s ease;
    min-height: 100px;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.8;
    margin-bottom: 8px;
    font-weight: 500;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #ff8e5c;
    text-shadow: none;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

.chart-container {
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    padding: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,142,92,0.2);
    min-height: 300px;
}

.chart-container h6 {
    color: #333;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
    font-size: 1.1rem;
}

.chart-wrapper {
    position: relative;
    height: 250px;
    width: 100%;
}

/* Recent Trades */
.recent-trades {
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    padding: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,142,92,0.2);
}

.recent-trades h6 {
    color: #333;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.recent-trades .table {
    color: #333;
    margin-bottom: 0;
}

.recent-trades .table th {
    background: rgba(255,142,92,0.1);
    border: none;
    color: #333;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 15px 10px;
}

.recent-trades .table td {
    border: none;
    border-bottom: 1px solid rgba(255,142,92,0.1);
    font-size: 0.9rem;
    padding: 12px 10px;
    vertical-align: middle;
}

.recent-trades .table tbody tr:hover {
    background: rgba(255,142,92,0.05);
}

.recent-trades .badge {
    font-size: 0.8rem;
    padding: 5px 8px;
}

/* Modal Footer */
.stats-modal .modal-footer {
    background: rgba(255,142,92,0.1);
    border-top: 1px solid rgba(255,142,92,0.2);
    border-radius: 0 0 20px 20px;
    padding: 20px 25px;
}

.stats-modal .btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 12px 25px;
    font-size: 1rem;
}

.stats-modal .btn-primary {
    background: linear-gradient(135deg, #ff8e5c, #ff6b35);
    border: none;
}

.stats-modal .btn-primary:hover {
    background: linear-gradient(135deg, #ff6b35, #ff8e5c);
    transform: translateY(-2px);
}

.stats-modal .btn-secondary {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
}

.stats-modal .btn-secondary:hover {
    background: rgba(255,255,255,0.3);
    color: white;
}

/* Responsive */
@media (max-width: 1200px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-modal {
        margin: 10px;
        max-width: 98vw;
    }
    
    .stats-modal .modal-body {
        padding: 15px;
        max-height: 70vh;
    }
    
    .quick-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .stat-card {
        padding: 15px;
        min-height: 70px;
    }
    
    .stat-icon {
        font-size: 1.8rem;
        width: 45px;
        height: 45px;
    }
    
    .stat-value {
        font-size: 1.3rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
    }
    
    .chart-container {
        padding: 15px;
        min-height: 200px;
    }
    
    .chart-wrapper {
        height: 180px;
    }
    
    .recent-trades {
        padding: 15px;
    }
    
    .recent-trades .table th,
    .recent-trades .table td {
        font-size: 0.85rem;
        padding: 8px 6px;
    }
}

@media (max-width: 480px) {
    .stats-modal {
        margin: 5px;
    }
    
    .stats-modal .modal-body {
        padding: 10px;
        max-height: 75vh;
    }
    
    .stat-card {
        padding: 12px;
        min-height: 60px;
    }
    
    .stat-icon {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
    }
    
    .stat-value {
        font-size: 1.1rem;
    }
    
    .stat-label {
        font-size: 0.8rem;
    }
    
    .chart-container {
        padding: 10px;
        min-height: 180px;
    }
    
    .chart-wrapper {
        height: 150px;
    }
    
    .recent-trades {
        padding: 10px;
    }
    
    .recent-trades .table th,
    .recent-trades .table td {
        font-size: 0.75rem;
        padding: 6px 4px;
    }
    
    .stats-modal .modal-title {
        font-size: 1.2rem;
    }
    
    .stats-modal .btn {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
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
<!-- main content -->
@section('content')
<div class="leaderboard-container">
    <div class="container-fluid">
        <!-- Leaderboard Header -->
        <div class="leaderboard-header">
            <h1 class="leaderboard-title">CONTEST LEADERBOARD</h1>
        </div>
        
        <!-- Winner Announcement Popup -->
        <div class="winner-popup-overlay" id="winnerPopupOverlay" style="display: none;"></div>
        <div class="winner-announcement-popup" id="winnerAnnouncementPopup" style="display: none;">
            <div class="winner-popup-content">
                <div class="winner-popup-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="winner-popup-text">
                    <h2>🏆 WINNERS ANNOUNCED! 🏆</h2>
                    <p>Congratulations to our Trading Contest Winners!</p>
                    
                    <div class="winner-prize-info">
                        <h3>🏆 WINNERS & PRIZES 🏆</h3>
                        
                        <div class="winner-card" style="background: linear-gradient(135deg, #fff5f0 0%, #ffe8d6 100%); border: 2px solid #ff8e5c; border-radius: 6px; padding: 5px; margin: 3px 0; box-shadow: 0 2px 5px rgba(255, 142, 92, 0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 0.8rem; color: #333;">🥇 Pooja</strong><br>
                                    <span style="color: #666; font-size: 0.7rem;">2107824</span>
                                </div>
                                <div style="background: #ff8e5c; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                                    $5000
                                </div>
                            </div>
                        </div>
                        
                        <div class="winner-card" style="background: linear-gradient(135deg, #fff5f0 0%, #ffe8d6 100%); border: 1px solid #ff8e5c; border-radius: 6px; padding: 5px; margin: 3px 0; box-shadow: 0 2px 5px rgba(255, 142, 92, 0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 0.8rem; color: #333;">🥈 Manesh</strong><br>
                                    <span style="color: #666; font-size: 0.7rem;">2107825</span>
                                </div>
                                <div style="background: #ff8e5c; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                                    $2500
                                </div>
                            </div>
                        </div>
                        
                        <div class="winner-card" style="background: linear-gradient(135deg, #fff5f0 0%, #ffe8d6 100%); border: 1px solid #ff8e5c; border-radius: 6px; padding: 5px; margin: 3px 0; box-shadow: 0 2px 5px rgba(255, 142, 92, 0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 0.8rem; color: #333;">🥉 Kamlesh</strong><br>
                                    <span style="color: #666; font-size: 0.7rem;">2108055</span>
                                </div>
                                <div style="background: #ff8e5c; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                                    $1000
                                </div>
                            </div>
                        </div>
                        
                        <div class="winner-card" style="background: linear-gradient(135deg, #fff5f0 0%, #ffe8d6 100%); border: 1px solid #ff8e5c; border-radius: 6px; padding: 5px; margin: 3px 0; box-shadow: 0 2px 5px rgba(255, 142, 92, 0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 0.8rem; color: #333;">4th Amit</strong><br>
                                    <span style="color: #666; font-size: 0.7rem;">2107823</span>
                                </div>
                                <div style="background: #ff8e5c; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                                    $500
                                </div>
                            </div>
                        </div>
                        
                        <div class="winner-card" style="background: linear-gradient(135deg, #fff5f0 0%, #ffe8d6 100%); border: 1px solid #ff8e5c; border-radius: 6px; padding: 5px; margin: 3px 0; box-shadow: 0 2px 5px rgba(255, 142, 92, 0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 0.8rem; color: #333;">5th Ramesh</strong><br>
                                    <span style="color: #666; font-size: 0.7rem;">2107815</span>
                                </div>
                                <div style="background: #ff8e5c; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                                    $100
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p style="font-size: 0.8rem; margin-top: 8px; color: #ff8e5c; font-weight: bold;">
                        🎉 Total: $9100 🎉
                    </p>
                </div>
                <button class="winner-popup-close" onclick="closeWinnerPopup()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Contest Closed Popup -->

        
        <!-- Top 3 Section -->
        <div class="top-3-section">
            <div class="top-card rank-2">
                <div class="rank-badge">2</div>
                <div class="profile-avatar">M</div>
                <div class="user-name">MASTERTERESFORD</div>
                <div class="user-rank">Elite Silver</div>
                <div class="profit-score">$383,646</div>
                <div class="lot-score">Lot: 45.2</div>
            </div>
            
            <div class="top-card rank-1">
                <div class="rank-badge">1</div>
                <div class="profile-avatar">S</div>
                <div class="user-name">SOFTWORK</div>
                <div class="user-rank">Champion Silver</div>
                <div class="profit-score">$405,624</div>
                <div class="lot-score">Lot: 52.8</div>
            </div>
            
            <div class="top-card rank-3">
                <div class="rank-badge">3</div>
                <div class="profile-avatar">A</div>
                <div class="user-name">AYMANEBT</div>
                <div class="user-rank">Elite Silver</div>
                <div class="profit-score">$367,868</div>
                <div class="lot-score">Lot: 41.5</div>
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
                    <input type="text" class="search-input" placeholder="Filter by user's name">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="standings-table">
                        <table class="table">
                    <thead>
                        <tr>
                            <th>LEVEL</th>
                            <th>ACCOUNT</th>
                            <th>USER</th>
                            <th>LOT</th>
                            <th>PROFIT</th>
                            <th>EQUITY%</th>
                            <th>GAIN%</th>
                            <th>USER DASHBOARD</th>
                            </tr>
                    </thead>
                    <tbody id="leaderboard-tbody">
                        <!-- Leaderboard data will be loaded here -->
                    </tbody>
                                    </table>
                
                <!-- Mobile standings cards -->
                <div class="mobile-standings" id="mobile-leaderboard" style="display: none;">
                    <!-- Mobile cards will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Modal -->
<div class="modal fade" id="statsModal" tabindex="-1" aria-labelledby="statsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content stats-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="statsModalLabel">
                    <i class="fas fa-chart-line"></i>
                    <span id="userName">User</span> - Trading Statistics
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="stats-container">
                    <!-- Quick Stats -->
                    <div class="quick-stats">
                        <div class="stat-card">
                            <div class="stat-icon">💰</div>
                            <div class="stat-info">
                                <div class="stat-label">Total Profit</div>
                                <div class="stat-value" id="totalProfit">$0</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">📊</div>
                            <div class="stat-info">
                                <div class="stat-label">Total Lot</div>
                                <div class="stat-value" id="totalLot">0.00</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">📊</div>
                            <div class="stat-info">
                                <div class="stat-label">Gain%</div>
                                <div class="stat-value" id="gainPercentage">0%</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">📈</div>
                            <div class="stat-info">
                                <div class="stat-label">Best Trade</div>
                                <div class="stat-value" id="bestTrade">$0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-section">
                        <div class="chart-container">
                            <h6>Profit Over Time</h6>
                            <div class="chart-wrapper">
                                <canvas id="profitChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-container">
                            <h6>Trade Distribution</h6>
                            <div class="chart-wrapper">
                                <canvas id="tradeChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Trades Table -->
                    <div class="recent-trades">
                        <h6>Recent Trades</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Symbol</th>
                                        <th>Type</th>
                                        <th>Volume</th>
                                        <th>Profit</th>
                                        <th>Time</th>
                                        </tr>
                                </thead>
                                <tbody id="recentTradesBody">
                                    <!-- Trades will be loaded here -->
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="exportStats">Export Data</button>
    </div>
</div>
    </div>
</div>

<!-- include footer -->
@include('layouts.footer')
</div>
@stop
@section('page-js')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<!-- Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
<script>
// Test Chart.js loading
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        console.log('✅ Chart.js loaded successfully');
    } else {
        console.error('❌ Chart.js failed to load');
    }
});
</script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Load leaderboard data
    loadLeaderboard();
    
    // Auto refresh every 30 seconds
    setInterval(loadLeaderboard, 30000);
    
    // Search functionality
    $('.search-input').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#leaderboard-tbody tr').each(function() {
            const userName = $(this).find('.user-cell').text().toLowerCase();
            if (userName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Handle View Stats button clicks
    $(document).on('click', '.view-stats-btn', function() {
        const userName = $(this).closest('tr').find('.user-cell').text();
        const accountNumber = $(this).closest('tr').find('.user-cell').data('account');
        
        console.log('Desktop View Stats clicked:', {
            'userName': userName,
            'accountNumber': accountNumber,
            'userCell': $(this).closest('tr').find('.user-cell').html(),
            'dataAttributes': $(this).closest('tr').find('.user-cell').data()
        });
        
        showUserStats(userName, accountNumber);
    });
    
    // Handle mobile View Stats button clicks
    $(document).on('click', '.mobile-view-stats-btn', function() {
        const userName = $(this).closest('.mobile-standing-card').find('.mobile-user-name').text();
        const accountNumber = $(this).data('account');
        
        console.log('Mobile View Stats clicked:', {
            'userName': userName,
            'accountNumber': accountNumber,
            'buttonData': $(this).data()
        });
        
        showUserStats(userName, accountNumber);
    });
    
    // Handle window resize for responsive switching
    $(window).on('resize', function() {
        updateViewMode();
    });
    
    // Initial view mode setup
    updateViewMode();

    // Export stats functionality
    $('#exportStats').on('click', function() {
        exportUserStats();
    });

    // Modal shown event to ensure charts are created properly
    $('#statsModal').on('shown.bs.modal', function() {
        console.log('Modal shown, checking for charts...');
        // Charts will be recreated when data is loaded, no need to resize
    });

    // Auto-refresh equity every 30 seconds
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        // Clear any existing interval
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        
        // Start new interval - refresh every 30 seconds
        autoRefreshInterval = setInterval(function() {
            console.log('🔄 Auto-refreshing equity data...');
            loadLeaderboard();
        }, 30000); // 30 seconds
        
        console.log('✅ Auto-refresh started - equity will update every 30 seconds');
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
            console.log('⏹️ Auto-refresh stopped');
        }
    }
    
    // Start auto-refresh when page loads
    startAutoRefresh();
    
    // Stop auto-refresh when page is hidden (to save resources)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
            console.log('📱 Page hidden - auto-refresh paused');
        } else {
            startAutoRefresh();
            console.log('📱 Page visible - auto-refresh resumed');
        }
    });
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
});

function loadLeaderboard() {
    // Debug: Log all contest data
    console.log('All contest data:', {!! json_encode($contest) !!});
    
    // Get contest ID from the page
    let contestId = '{{ $contest->first()->id ?? "" }}';
    
    // Fallback: try to get from URL parameter
    if (!contestId || contestId === '') {
        const urlParams = new URLSearchParams(window.location.search);
        contestId = urlParams.get('contest_id') || '';
    }
    
    console.log('Contest ID:', contestId);
    console.log('Contest data type:', typeof contestId);
    
    // Debug: Force use first contest if available
    if (!contestId || contestId === '' || contestId === '0' || contestId === 'null') {
        const allContestData = {!! json_encode($contest) !!};
        console.log('All contest data:', allContestData);
        if (allContestData && allContestData.length > 0) {
            contestId = allContestData[0].id;
            console.log('Using first available contest ID:', contestId);
        } else {
            console.log('No contest data available, showing mock data');
            showMockData();
            return;
        }
    }
    
    $.ajax({
        url: '/user/contest/leaderboard',
        method: 'GET',
        data: {
            contest_id: contestId,
            draw: 1,
            // Remove pagination parameters to get all records
            // start: 0,
            // length: 50
        },
        beforeSend: function() {
            console.log('Sending request with contest ID:', contestId);
            console.log('Requesting all records (no pagination)');
        },
        success: function(response) {
            console.log('Leaderboard response:', response);
            console.log('Contest ID sent:', contestId);
            console.log('Response data type:', typeof response.data);
            console.log('Response data length:', response.data ? response.data.length : 'undefined');
            
            // Check if contest is closed
            if (response.is_closed) {
                console.log('Contest is closed, demo participants filtered out');
            }
            
            if (response && response.data && response.data.length > 0) {
                // Convert backend data format to our format
                const formattedData = response.data.map(item => ({
                    name: item.contestant.name,
                    profit: item.profit.replace('$', '').replace(/,/g, ''),
                    lot: parseFloat(item.lot.replace(/,/g, '')).toFixed(2), // Don't divide by 100, backend sends correct value
                    equity: item.equity ? item.equity.replace('%', '') : 'N/A', // Remove % from equity and handle null
                    rank: getRankFromPosition(item.rank),
                    level: Math.floor(Math.random() * 40) + 20, // Mock level
                    position: item.rank,
                    account: item.account
                }));
                
                // Sort by profit in descending order (highest profit first)
                formattedData.sort((a, b) => {
                    const profitA = parseFloat(a.profit) || 0;
                    const profitB = parseFloat(b.profit) || 0;
                    return profitB - profitA; // Descending order
                });
                
                // Update position numbers after sorting
                formattedData.forEach((participant, index) => {
                    participant.position = index + 1;
                });
                
                updateTop3Cards(formattedData.slice(0, 3));
                updateStandingsTable(formattedData);
                
                console.log('Real data loaded:', formattedData.length, 'participants');
                console.log('Sample data:', formattedData[0]);
                
                // Show total participants count
                if (formattedData.length > 0) {
                    console.log('✅ Successfully loaded', formattedData.length, 'participants');
                    // You can also display this on the page if needed
                    // $('#participant-count').text(formattedData.length + ' Participants');
                }
            } else {
                console.log('No real data available, showing mock data');
                showMockData();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading leaderboard:', error);
            console.log('Showing mock data due to error');
            showMockData();
        }
    });
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

function showMockData() {
    // Mock data for demonstration
    const mockTop3 = [
        { name: 'SOFTWORK', profit: '405,624', lot: '0.53', rank: 'Champion Silver', level: '39' },
        { name: 'MASTERTERESFORD', profit: '383,646', lot: '0.45', rank: 'Elite Silver', level: '38' },
        { name: 'AYMANEBT', profit: '367,868', lot: '0.42', rank: 'Elite Silver', level: '38' }
    ];
    
    const mockStandings = [
        { name: 'SOFTWORK', profit: '405,624', lot: '0.52', equity: '1300.50', rank: 'Champion Silver', level: '39', account: '2107221' },
        { name: 'MASTERTERESFORD', profit: '383,646', lot: '0.45', equity: '1250.75', rank: 'Elite Silver', level: '38', account: '2107222' },
        { name: 'AYMANEBT', profit: '367,868', lot: '0.41', equity: '1180.25', rank: 'Elite Silver', level: '38', account: '2107223' },
        { name: 'LAMIZ04', profit: '326,154', lot: '0.38', equity: '1050.80', rank: 'Captain Silver', level: '37', account: '2107224' },
        { name: 'IAM_AJIBO1', profit: '278,663', lot: '0.35', equity: '890.45', rank: 'Pro Silver', level: '35', account: '2107225' },
        { name: 'OUAIS23', profit: '227,639', lot: '0.32', equity: '720.30', rank: 'Intermediate Silver', level: '33', account: '2107226' },
        { name: 'TRADER_PRO', profit: '198,452', lot: '0.28', equity: '650.15', rank: 'Beginner Silver', level: '31', account: '2107227' },
        { name: 'FX_MASTER', profit: '175,321', lot: '0.26', equity: '580.90', rank: 'Rookie Silver', level: '29', account: '2107228' }
    ];
    
    // Sort mock data by profit in descending order
    mockStandings.sort((a, b) => {
        const profitA = parseFloat(a.profit.replace(/,/g, '')) || 0;
        const profitB = parseFloat(b.profit.replace(/,/g, '')) || 0;
        return profitB - profitA; // Descending order
    });
    
    // Update position numbers after sorting
    mockStandings.forEach((participant, index) => {
        participant.position = index + 1;
    });
    
    updateTop3Cards(mockStandings.slice(0, 3));
    updateStandingsTable(mockStandings);
}

function updateTop3Cards(top3) {
    if (top3 && top3.length >= 3) {
        // Update top 3 cards with real data
        $('.rank-1 .user-name').text(top3[0].name);
        $('.rank-1 .profit-score').text('$' + top3[0].profit).removeClass('text-success text-danger').addClass(parseFloat(top3[0].profit) >= 0 ? 'text-success' : 'text-danger');
        $('.rank-1 .lot-score').text('Lot: ' + top3[0].lot).removeClass('text-success text-danger').addClass(parseFloat(top3[0].lot) >= 0 ? 'text-success' : 'text-danger');
        $('.rank-1 .profile-avatar').text(top3[0].name.charAt(0));
        $('.rank-1 .user-rank').text(top3[0].rank);
        
        $('.rank-2 .user-name').text(top3[1].name);
        $('.rank-2 .profit-score').text('$' + top3[1].profit).removeClass('text-success text-danger').addClass(parseFloat(top3[1].profit) >= 0 ? 'text-success' : 'text-danger');
        $('.rank-2 .lot-score').text('Lot: ' + top3[1].lot).removeClass('text-success text-danger').addClass(parseFloat(top3[1].lot) >= 0 ? 'text-success' : 'text-danger');
        $('.rank-2 .profile-avatar').text(top3[1].name.charAt(0));
        $('.rank-2 .user-rank').text(top3[1].rank);
        
        $('.rank-3 .user-name').text(top3[2].name);
        $('.rank-3 .profit-score').text('$' + top3[2].profit).removeClass('text-success text-danger').addClass(parseFloat(top3[2].profit) >= 0 ? 'text-success' : 'text-danger');
        $('.rank-3 .lot-score').text('Lot: ' + top3[2].lot).removeClass('text-success text-danger').addClass(parseFloat(top3[2].lot) >= 0 ? 'text-success' : 'text-danger');
        $('.rank-3 .profile-avatar').text(top3[2].name.charAt(0));
        $('.rank-3 .user-rank').text(top3[2].rank);
    }
}

function updateStandingsTable(standings) {
    let tableHtml = '';
    let mobileHtml = '';
    
    standings.forEach((participant, index) => {
        const isHighlighted = index < 3 ? 'highlighted' : '';
        
        // Calculate Gain% (profit / 10000) × 100
        const cleanProfit = participant.profit.replace(/,/g, ''); // Remove commas
        const gainPercentage = ((parseFloat(cleanProfit) / 10000) * 100).toFixed(2);
        
        // Desktop table row
        tableHtml += `
            <tr class="${isHighlighted}">
                <td class="position-cell">${index + 1}</td>
                <td class="account-cell">${participant.account}</td>
                <td class="user-cell" data-account="${participant.account}">${participant.name}</td>
                <td class="lot-cell ${parseFloat(participant.lot) >= 0 ? 'text-success' : 'text-danger'}">${participant.lot}</td>
                <td class="profit-cell ${parseFloat(participant.profit) >= 0 ? 'text-success' : 'text-danger'}">$${participant.profit}</td>
                <td class="equity-cell ${participant.equity && participant.equity !== 'N/A' ? (parseFloat(participant.equity) >= 0 ? 'text-success' : 'text-danger') : 'text-muted'}">${participant.equity && participant.equity !== 'N/A' ? participant.equity : 'N/A'}</td>
                <td class="gain-cell text-success">${gainPercentage}%</td>
                <td>
                    <button class="view-stats-btn">View Stats</button>
                </td>
            </tr>
        `;
        
        // Mobile card
        mobileHtml += `
            <div class="mobile-standing-card ${isHighlighted}">
                <div class="mobile-standing-header">
                    <div class="mobile-position">${index + 1}</div>
                    <div class="mobile-user-info">
                        <div class="mobile-user-name">${participant.name}</div>
                        <div class="mobile-account-number">Account: ${participant.account}</div>
                    </div>
                </div>
                <div class="mobile-standing-stats">
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-label">Profit</div>
                        <div class="mobile-stat-value mobile-profit-value ${parseFloat(participant.profit) >= 0 ? 'text-success' : 'text-danger'}">$${participant.profit}</div>
                    </div>
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-label">Equity%</div>
                        <div class="mobile-stat-value ${participant.equity && participant.equity !== 'N/A' ? (parseFloat(participant.equity) >= 0 ? 'text-success' : 'text-danger') : 'text-muted'}">${participant.equity && participant.equity !== 'N/A' ? participant.equity : 'N/A'}</div>
                    </div>
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-label">Gain%</div>
                        <div class="mobile-stat-value text-success">${gainPercentage}%</div>
                    </div>
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-label">Lot</div>
                        <div class="mobile-stat-value ${parseFloat(participant.lot) >= 0 ? 'text-success' : 'text-danger'}">${participant.lot}</div>
                    </div>
                </div>
                <div class="mobile-standing-actions">
                    <button class="mobile-view-stats-btn" data-account="${participant.account}">View Stats</button>
                </div>
            </div>
        `;
    });
    
    $('#leaderboard-tbody').html(tableHtml);
    $('#mobile-leaderboard').html(mobileHtml);
    
    // Show/hide based on screen size
    updateViewMode();
    
    console.log('Updated standings table with', standings.length, 'participants');
}

// User Stats Modal Functions
function showUserStats(userName, accountNumber) {
    console.log('Showing stats for:', userName, 'Account:', accountNumber);
    
    // Debug: Log the account number to verify it's correct
    if (!accountNumber || accountNumber === 'undefined' || accountNumber === 'null') {
        console.error('❌ Invalid account number:', accountNumber);
        alert('Error: Invalid account number. Please try again.');
        return;
    }
    
    console.log('✅ Valid account number received:', accountNumber);
    
    $('#userName').text(userName);
    
    // Show loading state
    $('#statsModal').modal('show');
    
    // Clear previous data
    $('#totalProfit').text('Loading...');
    $('#totalLot').text('Loading...');
    $('#gainPercentage').text('Loading...');
    $('#bestTrade').text('Loading...');
    $('#recentTradesBody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
    
    // Get data from CURRENT STANDINGS table
    getDataFromLeaderboard(userName, accountNumber);
    
    // Also search for MT5 trades to show in Recent Trades
    searchAccountInMT5(accountNumber);
}

function fetchUserTradingData(accountNumber) {
    console.log('Fetching data for account:', accountNumber);
    
    // First test if routing is working
    $.ajax({
        url: '/user/contest/test-route',
        method: 'GET',
        success: function(response) {
            console.log('✅ Test route working:', response);
            // If test route works, proceed with actual request
            fetchActualUserStats(accountNumber);
        },
        error: function(xhr, status, error) {
            console.log('❌ Test route failed:', error);
            console.log('❌ Status:', xhr.status);
            console.log('❌ Response:', xhr.responseText);
            showMockStats();
        }
    });
}

function fetchActualUserStats(accountNumber) {
    console.log('Fetching actual user stats for account:', accountNumber);
    
    const requestData = {
            account_number: accountNumber,
            contest_id: '{{ $contest->first()->id ?? "" }}'
    };
    
    console.log('Request data:', requestData);
    console.log('Request URL:', '/user/contest/user-stats');
    
    $.ajax({
        url: '/user/contest/user-stats',
        method: 'GET',
        data: requestData,
        beforeSend: function(xhr) {
            console.log('Sending AJAX request...');
            console.log('Request headers:', xhr.getAllResponseHeaders());
        },
        success: function(response) {
            console.log('User stats response:', response);
            console.log('Response type:', typeof response);
            console.log('Response length:', response ? response.length : 'undefined');
            
            // Check if response is HTML instead of JSON
            if (typeof response === 'string' && response.includes('<!DOCTYPE html>')) {
                console.error('❌ Received HTML instead of JSON!');
                console.error('Response preview:', response.substring(0, 500));
                showMockStats();
                return;
            }
            
            // Check if response has status property
            if (response && response.status === true) {
                console.log('✅ Valid response with status true');
                updateStatsModal(response);
            } else if (response && response.status === false) {
                console.log('❌ Backend returned error:', response.message || 'Unknown error');
                showMockStats();
        } else {
                // If no status property, assume it's valid data
                console.log('⚠️ No status property, treating as valid data');
                updateStatsModal(response);
            }
        },
        error: function(xhr, status, error) {
            console.log('❌ Error fetching user stats:', error);
            console.log('❌ Status:', xhr.status);
            console.log('❌ Status text:', xhr.statusText);
            console.log('❌ Response text:', xhr.responseText);
            console.log('❌ Response headers:', xhr.getAllResponseHeaders());
            
            // Show mock data if real data fails
            showMockStats();
        }
    });
}

function createProfitChart(profitData) {
    console.log('🔄 Creating profit chart with data:', profitData);
    
    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('❌ Chart.js not available');
        return;
    }
    
    const ctx = document.getElementById('profitChart');
    if (!ctx) {
        console.error('❌ Profit chart canvas not found');
        return;
    }
    
    console.log('✅ Canvas found:', ctx);
    
    // Destroy existing chart if it exists
    if (window.profitChart && typeof window.profitChart.destroy === 'function') {
        console.log('🗑️ Destroying existing profit chart');
        window.profitChart.destroy();
    }
    
    if (!profitData || profitData.length === 0) {
        console.log('⚠️ No profit data, showing placeholder');
        ctx.style.display = 'none';
        const placeholder = document.createElement('div');
        placeholder.className = 'text-center text-white mt-4';
        placeholder.innerHTML = '📊 No profit data available';
        ctx.parentElement.appendChild(placeholder);
        return;
    }
    
    // Ensure canvas is visible and reset
    ctx.style.display = 'block';
    ctx.parentElement.innerHTML = '<canvas id="profitChart"></canvas>';
    const newCtx = document.getElementById('profitChart');
    
    // Extract data from backend format
    const labels = profitData.map(item => item.date || `Day ${item.day || item.index + 1}`);
    const data = profitData.map(item => {
        const profit = parseFloat(item.profit) || 0;
        return profit;
    });
    
    console.log('📊 Chart labels:', labels);
    console.log('📊 Chart data:', data);
    
    // Validate data
    if (data.every(val => val === 0)) {
        console.log('⚠️ All profit values are zero, showing placeholder');
        newCtx.style.display = 'none';
        const placeholder = document.createElement('div');
        placeholder.className = 'text-center text-white mt-4';
        placeholder.innerHTML = '📊 No profit data available';
        newCtx.parentElement.appendChild(placeholder);
        return;
    }
    
    try {
        window.profitChart = new Chart(newCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cumulative Profit',
                    data: data,
                    borderColor: '#ff8e5c',
                    backgroundColor: 'rgba(255, 142, 92, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff8e5c',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#333',
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Profit: $${context.parsed.y.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#333',
                            font: { size: 12 },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#333',
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });
        
        console.log('✅ Profit chart created successfully');
    } catch (error) {
        console.error('❌ Error creating profit chart:', error);
        newCtx.style.display = 'none';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-center text-white mt-4';
        errorDiv.innerHTML = '📊 Chart data loading...';
        newCtx.parentElement.appendChild(errorDiv);
    }
}

function createTradeChart(tradeData) {
    console.log('🔄 Creating trade chart with data:', tradeData);
    
    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('❌ Chart.js not available');
        return;
    }
    
    const ctx = document.getElementById('tradeChart');
    if (!ctx) {
        console.error('❌ Trade chart canvas not found');
        return;
    }
    
    console.log('✅ Canvas found:', ctx);
    
    // Destroy existing chart if it exists
    if (window.tradeChart && typeof window.tradeChart.destroy === 'function') {
        console.log('🗑️ Destroying existing trade chart');
        window.tradeChart.destroy();
    }
    
    if (!tradeData || Object.keys(tradeData).length === 0) {
        console.log('⚠️ No trade data, showing placeholder');
        ctx.style.display = 'none';
        const placeholder = document.createElement('div');
        placeholder.className = 'text-center text-white mt-4';
        placeholder.innerHTML = '📊 No trade distribution data available';
        ctx.parentElement.appendChild(placeholder);
        return;
    }
    
    // Ensure canvas is visible and reset
    ctx.style.display = 'block';
    ctx.parentElement.innerHTML = '<canvas id="tradeChart"></canvas>';
    const newCtx = document.getElementById('tradeChart');
    
    const labels = Object.keys(tradeData);
    const data = Object.values(tradeData).map(val => parseInt(val) || 0);
    const colors = ['#ff8e5c', '#ff6b35', '#ffd700', '#51cf66', '#74c0fc'];
    
    console.log('📊 Trade chart labels:', labels);
    console.log('📊 Trade chart data:', data);
    
    // Validate data
    if (data.every(val => val === 0)) {
        console.log('⚠️ All trade values are zero, showing placeholder');
        newCtx.style.display = 'none';
        const placeholder = document.createElement('div');
        placeholder.className = 'text-center text-white mt-4';
        placeholder.innerHTML = '📊 No trade distribution data available';
        newCtx.parentElement.appendChild(placeholder);
        return;
    }
    
    try {
        window.tradeChart = new Chart(newCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: 'rgba(255,255,255,0.3)',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} trades (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        console.log('✅ Trade chart created successfully');
    } catch (error) {
        console.error('❌ Error creating trade chart:', error);
        newCtx.style.display = 'none';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-center text-white mt-4';
        errorDiv.innerHTML = '📊 Chart data loading...';
        newCtx.parentElement.appendChild(errorDiv);
    }
}

function showMockStats() {
    console.log('🔄 Showing mock stats');
    // Mock data for demonstration
    const mockData = {
        total_profit: '405,624',
        total_lot: '0.53',
        win_rate: 68,
        best_trade: '12,450',
        recent_trades: [
            { symbol: 'EURUSD', type: 'BUY', volume: '1.5', profit: '1250', time: '2024-01-15 14:30' },
            { symbol: 'GBPUSD', type: 'SELL', volume: '2.0', profit: '-450', time: '2024-01-15 13:45' },
            { symbol: 'USDJPY', type: 'BUY', volume: '1.0', profit: '890', time: '2024-01-15 12:20' },
            { symbol: 'AUDUSD', type: 'SELL', volume: '1.8', profit: '2100', time: '2024-01-15 11:15' },
            { symbol: 'USDCAD', type: 'BUY', volume: '1.2', profit: '750', time: '2024-01-15 10:30' }
        ],
        profit_chart: [
            { date: 'Day 1', profit: 15000 },
            { date: 'Day 2', profit: 25000 },
            { date: 'Day 3', profit: 18000 },
            { date: 'Day 4', profit: 32000 },
            { date: 'Day 5', profit: 28000 },
            { date: 'Day 6', profit: 405624 }
        ],
        trade_distribution: {
            'EURUSD': 35,
            'GBPUSD': 25,
            'USDJPY': 20,
            'AUDUSD': 15,
            'USDCAD': 5
        }
    };
    
    updateStatsModal(mockData);
}

function updateStatsModal(data) {
    console.log('🔄 Updating modal with data:', data);
    console.log('📊 Data type check:', {
        'data exists': !!data,
        'total_profit': data?.total_profit,
        'total_profit type': typeof data?.total_profit,
        'total_lot': data?.total_lot,
        'total_lot type': typeof data?.total_lot,
        'win_rate': data?.win_rate,
        'win_rate type': typeof data?.win_rate,
        'has message': !!data?.message
    });
    
    // Convert string values to numbers for proper comparison
    const totalProfit = parseFloat(data?.total_profit) || 0;
    const totalLot = parseFloat(data?.total_lot) || 0;
            const gainPercentage = ((parseFloat(data?.total_profit) || 0) / 10000 * 100).toFixed(2);
    
    console.log('🔢 Converted values:', {
        'totalProfit': totalProfit,
        'totalLot': totalLot,
        'gainPercentage': gainPercentage
    });
    
    // Check if data is empty or has a message indicating no data
            if (!data || (totalProfit === 0 && totalLot === 0) || data.message) {
        console.log('⚠️ Empty data received, showing no data message');
        console.log('🔍 Condition check:', {
            '!data': !data,
            'profit_lot_zero': (totalProfit === 0 && totalLot === 0),
            'has_message': !!data?.message
        });
        
        // Show message if provided by backend
        if (data && data.message) {
            console.log('📝 Backend message:', data.message);
        }
        
        // Update quick stats with no data message
        $('#totalProfit').text('No Data');
        $('#totalLot').text('No Data');
        $('#gainPercentage').text('No Data');
        $('#bestTrade').text('No Data');
        
        // Update recent trades table with no data message
        $('#recentTradesBody').html('<tr><td colspan="5" class="text-center">No trades found for this account</td></tr>');
        
        // Hide charts and show no data message
        $('.chart-wrapper').each(function() {
            $(this).hide();
            $(this).parent().append('<div class="text-center text-muted mt-4">No data available</div>');
        });
        
        return;
    }
    
    console.log('✅ Valid data received, updating modal with real values');
    
    // Update quick stats with proper formatting (same as leaderboard)
    $('#totalProfit').text('$' + (data.total_profit || '0'));
    $('#totalLot').text(((parseFloat(data.total_lot) || 0) / 100).toFixed(2));
    
            // Debug gain percentage setting
        console.log('🎯 Setting gain percentage in modal:', gainPercentage);
            $('#gainPercentage').text(gainPercentage + '%');
            console.log('🎯 Gain percentage element after setting:', $('#gainPercentage').text());
    
    // Debug best trade setting
    console.log('🏆 Setting best trade in modal:', data.best_trade);
    $('#bestTrade').text('$' + (data.best_trade || '0'));
    console.log('🏆 Best trade element after setting:', $('#bestTrade').text());
    
    console.log('📈 Updated values:', {
        'totalProfit': $('#totalProfit').text(),
        'totalLot': $('#totalLot').text(),
                    'gainPercentage': $('#gainPercentage').text(),
        'bestTrade': $('#bestTrade').text()
    });
    
            // Log gain percentage calculation details
        console.log('📊 Gain Percentage Calculation Details:', {
            totalProfit: data.total_profit || 'N/A',
            gainPercentage: gainPercentage + '%',
            formula: `(${data.total_profit || 0} / 10000) × 100 = ${gainPercentage}%`
        });
    
            // Debug: Log the raw data received
        console.log('🔍 Raw data received from backend:', data);
        console.log('🔍 Total profit value:', data.total_profit);
        console.log('🔍 Total profit type:', typeof data.total_profit);
    
    // Log best trade details
    if (data.best_trade_details) {
        console.log('🏆 Best Trade Details:', {
            ticket: data.best_trade_details.ticket,
            symbol: data.best_trade_details.symbol,
            type: data.best_trade_details.type,
            volume: data.best_trade_details.volume,
            profit: data.best_trade_details.profit,
            closeTime: data.best_trade_details.close_time
        });
    } else {
        console.log('🏆 No best trade found (no trades available)');
    }
    
    // Update recent trades table
    updateRecentTradesTable(data.recent_trades || []);
    
    // Create charts with longer delay to ensure modal is fully loaded
    setTimeout(() => {
        console.log('⏰ Creating charts after delay...');
        createProfitChart(data.profit_chart || []);
        createTradeChart(data.trade_distribution || {});
    }, 1500);
}

function updateRecentTradesTable(trades) {
    console.log('Updating trades table with:', trades);
    
    // Check if real data has been updated, if so, don't overwrite
    if (window.realDataUpdated) {
        console.log('🛡️ Real data already updated, skipping old updateRecentTradesTable');
        return;
    }
    
    let tableHtml = '';
    
    if (trades.length === 0) {
        tableHtml = '<tr><td colspan="5" class="text-center">No trades found</td></tr>';
    } else {
        trades.forEach(trade => {
            const profitClass = parseFloat(trade.profit) >= 0 ? 'text-success' : 'text-danger';
            tableHtml += `
                <tr>
                    <td>${trade.symbol}</td>
                    <td><span class="badge ${trade.type === 'BUY' ? 'bg-success' : 'bg-danger'}">${trade.type}</span></td>
                    <td>${trade.volume}</td>
                    <td class="${profitClass}">$${trade.profit}</td>
                    <td>${trade.time}</td>
                </tr>
            `;
        });
    }
    
    $('#recentTradesBody').html(tableHtml);
}

function updateRecentTradesTableWithRealData(trades) {
    console.log('🔍 updateRecentTradesTableWithRealData called with:', trades);
    console.log('🔍 Trades type:', typeof trades);
    console.log('🔍 Trades length:', trades ? trades.length : 'null');
    
    let tableHtml = '';
    let bestTrade = 0;
    let bestTradeDetails = null;
    
    if (!trades || trades.length === 0) {
        console.log('❌ No trades found, showing empty message');
        tableHtml = '<tr><td colspan="5" class="text-center">No trades found for this account</td></tr>';
        $('#bestTrade').text('$0');
    } else {
        console.log('✅ Found trades, processing...');
        
        // Find the best trade (highest profit)
        trades.forEach((trade, index) => {
            const profit = parseFloat(trade.PROFIT) || 0;
            if (profit > bestTrade) {
                bestTrade = profit;
                bestTradeDetails = trade;
            }
        });
        
        console.log('🏆 Best trade found:', {
            'bestTrade': bestTrade,
            'bestTradeDetails': bestTradeDetails
        });
        
        // Update Best Trade card
        $('#bestTrade').text('$' + bestTrade.toFixed(2));
        
        // Take only the first 10 trades for display
        const displayTrades = trades.slice(0, 10);
        console.log('📊 Display trades:', displayTrades);
        
        displayTrades.forEach((trade, index) => {
            console.log(`📈 Processing trade ${index + 1}:`, trade);
            
            const profitClass = parseFloat(trade.PROFIT) >= 0 ? 'text-success' : 'text-danger';
            const tradeType = trade.CMD == 0 ? 'BUY' : 'SELL';
            const badgeClass = tradeType === 'BUY' ? 'bg-success' : 'bg-danger';
            
            // Format the time
            const tradeTime = new Date(trade.CLOSE_TIME).toLocaleString();
            
            const rowHtml = `
                <tr>
                    <td>${trade.SYMBOL}</td>
                    <td><span class="badge ${badgeClass}">${tradeType}</span></td>
                    <td>${trade.VOLUME}</td>
                    <td class="${profitClass}">$${parseFloat(trade.PROFIT).toFixed(2)}</td>
                    <td>${tradeTime}</td>
                </tr>
            `;
            
            console.log(`📝 Row HTML for trade ${index + 1}:`, rowHtml);
            tableHtml += rowHtml;
        });
    }
    
    console.log('🎯 Final table HTML:', tableHtml);
    console.log('🎯 Updating #recentTradesBody with HTML');
    $('#recentTradesBody').html(tableHtml);
    console.log('✅ Recent Trades table updated with real data');
    
    // Create charts from the trades data
    if (trades && trades.length > 0) {
        createChartsFromTrades(trades);
    }
}

function updateViewMode() {
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        $('.table').hide();
        $('.mobile-standings').show();
    } else {
        $('.table').show();
        $('.mobile-standings').hide();
    }
}

function getDataFromLeaderboard(userName, accountNumber) {
    console.log('Getting data from leaderboard for:', userName, 'Account:', accountNumber);
    
    // Find the participant data from the current leaderboard
    let participantData = null;
    
    // Check desktop table first
    $('#leaderboard-tbody tr').each(function() {
        const rowUserName = $(this).find('.user-cell').text().trim();
        const rowAccount = $(this).find('.user-cell').data('account');
        
        if (rowUserName === userName && rowAccount == accountNumber) {
            const profit = $(this).find('.profit-cell').text().replace('$', '').replace(/,/g, '');
            const lot = $(this).find('.lot-cell').text();
            
            participantData = {
                name: rowUserName,
                account: rowAccount,
                profit: profit,
                lot: lot
            };
            return false; // Break the loop
        }
    });
    
    // If not found in desktop table, check mobile cards
    if (!participantData) {
        $('.mobile-standing-card').each(function() {
            const cardUserName = $(this).find('.mobile-user-name').text().trim();
            const cardAccount = $(this).find('.mobile-view-stats-btn').data('account');
            
            if (cardUserName === userName && cardAccount == accountNumber) {
                const profit = $(this).find('.mobile-profit-value').text().replace('$', '').replace(/,/g, '');
                const lot = $(this).find('.mobile-stat-value').not('.mobile-profit-value').first().text();
                
                participantData = {
                    name: cardUserName,
                    account: cardAccount,
                    profit: profit,
                    lot: lot
                };
                return false; // Break the loop
            }
        });
    }
    
    if (participantData) {
        console.log('✅ Found participant data:', participantData);
        
        // Calculate Gain% using the same formula as leaderboard
        const cleanProfit = participantData.profit.replace(/,/g, ''); // Remove commas
        const gainPercentage = ((parseFloat(cleanProfit) / 10000) * 100).toFixed(2);
        
        // Update modal with leaderboard data
        $('#totalProfit').text('$' + participantData.profit);
        $('#totalLot').text(participantData.lot);
        $('#gainPercentage').text(gainPercentage + '%');
        // Best Trade will be updated by MT5 search
        $('#bestTrade').text('Loading...');
        
        // Show loading for Recent Trades - will be updated by MT5 search
        $('#recentTradesBody').html('<tr><td colspan="5" class="text-center">Loading trades...</td></tr>');
        
        // Show charts loading state - will be updated by MT5 search
        $('.chart-wrapper').each(function() {
            $(this).show();
            $(this).parent().find('.text-muted').remove(); // Remove any existing "No data available" messages
        });
        
        console.log('✅ Modal updated with leaderboard data');
    } else {
        console.log('❌ Participant not found in leaderboard');
        $('#totalProfit').text('No Data');
        $('#totalLot').text('No Data');
        $('#gainPercentage').text('No Data');
        $('#bestTrade').text('No Data');
        $('#recentTradesBody').html('<tr><td colspan="5" class="text-center">Participant not found</td></tr>');
    }
}

function searchAccountInMT5(accountNumber) {
    console.log('Searching account in MT5 table:', accountNumber);
    
    $.ajax({
        url: '/user/contest/search-account-mt5',
        method: 'GET',
        data: {
            account_number: accountNumber
        },
        success: function(response) {
            console.log('MT5 search response:', response);
            
            if (response.status === true) {
                // Update the Recent Trades table in the modal
                console.log('Calling updateRecentTradesTableWithRealData with:', response.trades);
                updateRecentTradesTableWithRealData(response.trades);
                
                // Prevent the old updateRecentTradesTable from overwriting our real data
                window.realDataUpdated = true;
            } else {
                // Show no trades found if MT5 search fails
                $('#recentTradesBody').html('<tr><td colspan="5" class="text-center">No trades found</td></tr>');
                // Hide charts if no trades
                $('.chart-wrapper').each(function() {
                    $(this).hide();
                    $(this).parent().append('<div class="text-center text-muted mt-4">No data available</div>');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error searching account in MT5:', error);
            // Show no trades found if MT5 search fails
            $('#recentTradesBody').html('<tr><td colspan="5" class="text-center">No trades found</td></tr>');
            // Hide charts if no trades
            $('.chart-wrapper').each(function() {
                $(this).hide();
                $(this).parent().append('<div class="text-center text-muted mt-4">No data available</div>');
            });
        }
    });
}

function createChartsFromTrades(trades) {
    console.log('📊 Creating charts from trades data:', trades);
    
    // Prepare data for Profit Over Time chart
    const profitChartData = [];
    let cumulativeProfit = 0;
    
    // Sort trades by close time (oldest first)
    const sortedTrades = trades.sort((a, b) => new Date(a.CLOSE_TIME) - new Date(b.CLOSE_TIME));
    
    sortedTrades.forEach((trade, index) => {
        const profit = parseFloat(trade.PROFIT) || 0;
        cumulativeProfit += profit;
        
        profitChartData.push({
            date: new Date(trade.CLOSE_TIME).toLocaleDateString(),
            profit: cumulativeProfit
        });
    });
    
    console.log('📈 Profit chart data:', profitChartData);
    
    // Prepare data for Trade Distribution chart
    const tradeDistribution = {};
    trades.forEach(trade => {
        const symbol = trade.SYMBOL || 'Unknown';
        if (tradeDistribution[symbol]) {
            tradeDistribution[symbol]++;
        } else {
            tradeDistribution[symbol] = 1;
        }
    });
    
    console.log('📊 Trade distribution data:', tradeDistribution);
    
    // Create the charts
    createProfitChart(profitChartData);
    createTradeChart(tradeDistribution);
}

function exportUserStats() {
    const userName = $('#userName').text();
    const data = {
        user: userName,
        total_profit: $('#totalProfit').text(),
        total_lot: $('#totalLot').text(),
        gain_percentage: $('#gainPercentage').text(),
        best_trade: $('#bestTrade').text(),
        export_date: new Date().toISOString()
    };
    
    // Create and download CSV
            const csvContent = `User,Total Profit,Total Lot,Gain%,Best Trade,Export Date\n${userName},${data.total_profit},${data.total_lot},${gainPercentage}%,${data.best_trade},${data.export_date}`;
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${userName}_trading_stats.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Winner Announcement Popup Functions
function showWinnerPopup() {
    const overlay = document.getElementById('winnerPopupOverlay');
    const popup = document.getElementById('winnerAnnouncementPopup');
    
    if (overlay && popup) {
        overlay.style.display = 'block';
        popup.style.display = 'block';
        
        // Add entrance animation
        popup.style.animation = 'winnerSlideIn 0.8s ease-out';
    }
}

function closeWinnerPopup() {
    const overlay = document.getElementById('winnerPopupOverlay');
    const popup = document.getElementById('winnerAnnouncementPopup');
    
    if (overlay && popup) {
        popup.style.animation = 'winnerSlideOut 0.5s ease-out';
        
        setTimeout(() => {
            overlay.style.display = 'none';
            popup.style.display = 'none';
        }, 500);
    }
}

// Auto-show winner popup after 3 seconds
setTimeout(() => {
    showWinnerPopup();
}, 3000);

// Close popup when clicking on overlay
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('winnerPopupOverlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeWinnerPopup();
            }
        });
    }
});

// Check if user is a winner and show popup
function checkIfWinner() {
    // This function can be called to check if the current user is a winner
    // For now, we'll show the popup to all users
    showWinnerPopup();
}
</script>
@endsection