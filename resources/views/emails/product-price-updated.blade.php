<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        .email-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 20px;
        }
        .email-footer {
            background-color: #f1f1f1;
            color: #666;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Product price has been updated</h1>
    </div>
    <div class="email-body">
        <p>
            The price for "{{ $product->name }}" has been updated from {{ $oldPrice }} to {{ $formattedActualPrice }}.
        </p>
    </div>
    <div class="email-footer">
        <p>&copy; {{ date('Y') }} All rights reserved.</p>
    </div>
</div>
</body>
</html>

