<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Temporarily Unavailable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', sans-serif;
        }
        
        .error-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
        }
        
        .error-icon {
            font-size: 80px;
            color: #ff6b6b;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 16px;
            color: #636e72;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .error-description {
            background: #f0f1f6;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 4px;
            text-align: left;
            margin-bottom: 30px;
            font-size: 14px;
            color: #555;
        }
        
        .btn-retry {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-home {
            background: #ecf0f1;
            border: none;
            color: #2d3436;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background: #bdc3c7;
            color: #2d3436;
            text-decoration: none;
        }
        
        .footer-text {
            margin-top: 30px;
            font-size: 12px;
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-exclamation-circle"></i>
        </div>
        
        <h1 class="error-title">Service Temporarily Unavailable</h1>
        
        <p class="error-message">
            We're experiencing technical difficulties connecting to our database server. 
            Our team has been notified and we're working to restore service.
        </p>
        
        <div class="error-description">
            <strong>What happened?</strong><br>
            The server could not establish a connection to the database. This is typically a temporary issue.
        </div>
        
        <div>
            <button onclick="location.reload();" class="btn-retry">
                <i class="bi bi-arrow-clockwise"></i> Try Again
            </button>
            <a href="{{ route('login') }}" class="btn-home">
                <i class="bi bi-house"></i> Back to Login
            </a>
        </div>
        
        <p class="footer-text">
            Error Code: DB_503 | Time: {{ now()->format('Y-m-d H:i:s') }}
        </p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
