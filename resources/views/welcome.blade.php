<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vyaparkranti Animated Box</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        
        .vyaparkranti-box {
            width: 250px;
            height: 250px;
            border-radius: 15px;
            background: linear-gradient(45deg, #0d6efd, #6610f2);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
        }
        
        .vyaparkranti-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .logo-icon {
            font-size: 4rem;
            margin-bottom: 15px;
            animation: pulse 2s infinite;
        }
        
        .company-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
            position: relative;
        }
        
        .company-name::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 50%;
            background-color: #ffffff;
            transition: all 0.5s ease;
        }
        
        .vyaparkranti-box:hover .company-name::after {
            width: 100%;
            left: 0;
        }
        
        .tagline {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: float 4s infinite;
        }
        
        .particle:nth-child(1) {
            width: 15px;
            height: 15px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .particle:nth-child(2) {
            width: 10px;
            height: 10px;
            top: 20%;
            right: 20%;
            animation-delay: 1s;
        }
        
        .particle:nth-child(3) {
            width: 7px;
            height: 7px;
            bottom: 15%;
            left: 15%;
            animation-delay: 2s;
        }
        
        .particle:nth-child(4) {
            width: 12px;
            height: 12px;
            bottom: 25%;
            right: 15%;
            animation-delay: 3s;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(20px);
                opacity: 0;
            }
        }
        
        .btn-custom {
            background-color: white;
            color: #6610f2;
            border: none;
            margin-top: 15px;
            font-weight: bold;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }
        
        .vyaparkranti-box:hover .btn-custom {
            opacity: 1;
            transform: translateY(0);
        }
        
        .spin-effect {
            animation: spin 10s linear infinite;
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, transparent 30%, rgba(255, 255, 255, 0.1) 70%);
            z-index: 0;
        }
        
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="vyaparkranti-box">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="spin-effect"></div>
        
        <div class="logo-icon">
            <i class="fas fa-bolt"></i>
        </div>
        <div class="company-name">VYAPAR KRANTI</div>
        <div class="tagline">Empowering Business Growth</div>
        
    </div>
    @if (Route::has('login'))
    <div style="position: fixed; top: 20px; right: 20px; z-index: 999;">
        @auth
            <a href="{{ url('/new-dashboard') }}" 
               style="color: rgb(244, 238, 238); background-color: rgb(124, 145, 223); font-size: 16px; font-weight: 700; text-decoration: none; padding: 12px 20px; display: inline-block; text-align: center; border-radius: 8px; transition: background-color 0.3s;">
               Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" 
               style="color: rgb(247, 238, 238); background-color: rgb(149, 152, 235); font-size: 16px; font-weight: 700; text-decoration: none; padding: 12px 20px; display: inline-block; text-align: center; border-radius: 8px; transition: background-color 0.3s;">
               Log in
            </a>
        @endauth
    </div>
@endif



    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>