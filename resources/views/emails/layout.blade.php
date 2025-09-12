<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Proper Automobile')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .logo {
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #1e40af;
            margin-top: 0;
            font-size: 24px;
        }
        .content h3 {
            color: #374151;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .details-box {
            background-color: #f3f4f6;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #1e40af;
        }
        .footer {
            background-color: #1f2937;
            color: #9ca3af;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #60a5fa;
            text-decoration: none;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .automobile-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
            background-color: #fafafa;
        }
        .price {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header {
                padding: 20px 15px;
            }
            .content {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
            .details-table th,
            .details-table td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <h1>Proper Automobile</h1>
            </div>
            <p style="margin: 0; opacity: 0.9;">Premier Luxury Car Dealership</p>
        </div>

        <div class="content">
            @yield('content')
        </div>

        <div class="footer">
            <p>
                <strong>Proper Automobile</strong><br>
                123 Luxury Auto Drive, Premium District, Car City, CC 12345<br>
                Phone: <a href="tel:+2347053404846">+234 (705) 340-4846</a><br>
                Email: <a href="mailto:danwayne0902@gmail.com">info@properautomobile.com</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                This email was sent from Proper Automobile. If you have any questions, please contact us at the information above.
            </p>
        </div>
    </div>
</body>
</html>
