<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Xác thực tài khoản</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h3 {
            color: #333333;
            margin-top: 0;
        }
        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.5;
        }
        .btn-verify {
            display: inline-block;
            padding: 12px 25px;
            margin: 20px 0;
            background-color: #4a90e2;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
        }
        .footer {
            font-size: 12px;
            color: #999999;
            margin-top: 30px;
        }
        .footer a {
            color: #4a90e2;
            text-decoration: none;
        }
        @media (max-width: 620px) {
            .email-container {
                margin: 10px;
                padding: 20px;
            }
            p, h3 {
                font-size: 14px;
            }
            .btn-verify {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h3>Xin chào {{ $name }},</h3>

        <p>Cảm ơn bạn đã đăng ký tài khoản với chúng tôi.</p>

        <p>Vui lòng nhấn nút bên dưới để xác thực tài khoản của bạn:</p>

        <p>
            <a href="{{ $verificationLink }}" class="btn-verify" target="_blank" rel="noopener noreferrer">
                Xác thực tài khoản
            </a>
        </p>

        <p>Nếu nút trên không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt:</p>
        <p><a href="{{ $verificationLink }}" target="_blank" rel="noopener noreferrer" style="word-break: break-all; color: #4a90e2;">{{ $verificationLink }}</a></p>

        <p class="footer">
            Nếu bạn không đăng ký tài khoản này, vui lòng bỏ qua email này.<br />
            Trân trọng,<br />
            <strong>Đội ngũ hỗ trợ</strong>
        </p>
    </div>
</body>
</html>
