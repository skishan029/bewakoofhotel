<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - Bewakoof Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/toastr/toastr.min.css') }}">
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

        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #e0e0e0;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .logo {
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
            color: #e91e63;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">{{ env('APP_NAME') }}</div>
        <div class="spinner"></div>
        <h2>Processing Payment</h2>
        <p>Please wait while we secure your payment. <br> Do not close or refresh this page.</p>
    </div>

    <script src="{{ asset('assests/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assests/admin/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function() {
            setTimeout(function() {
                callAjax();
            }, 5000);
        });

        function callAjax() {
            const url = "{{ route('payment.verify') }}?id={{ request()->id }}";
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = "{{ route('payment.success') }}";
                    } else {
                        window.location.href = "{{ route('payment.failed') }}";
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    window.location.href = "{{ route('payment.failed') }}";
                }
            });
        }
    </script>
</body>

</html>
