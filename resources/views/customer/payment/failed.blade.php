<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Bewakoof Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: #ffebee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-box i {
            font-size: 40px;
            color: #f44336;
        }

        h2 {
            color: #c62828;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #d32f2f;
        }

        .btn-secondary {
            background-color: #f5f5f5;
            color: #333;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon-box">
            <i class="fas fa-times"></i>
        </div>
        <h2>Payment Failed</h2>
        <p>We couldn't process your payment. Please try again or use a different payment method.</p>

        <div class="actions">
            <a href="{{ url()->previous() }}" class="btn">Try Again</a>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
