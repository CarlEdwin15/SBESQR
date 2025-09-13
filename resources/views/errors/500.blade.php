<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! Something went wrong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .error-box {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .error-box h1 {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .error-box p {
            font-size: 18px;
            color: #6c757d;
        }

        .error-box a {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="error-box">
        <h1>500</h1>
        <p>Oops! Something went wrong on our server.</p>
        <p>If you were logged in, try logging in again or contact the administrator.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
    </div>
</body>

</html>
