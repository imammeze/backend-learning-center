<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Siswa Berhasil Dibuat</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #881337; /* Rose 900 / Maroon */
            color: #ffffff;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px;
        }
        .content p {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 16px;
            color: #4b5563;
        }
        .credentials {
            background-color: #fff1f2; /* Rose 50 */
            border: 1px solid #ffe4e6; /* Rose 100 */
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .credentials-row {
            margin-bottom: 10px;
        }
        .credentials-row:last-child {
            margin-bottom: 0;
        }
        .credentials-label {
            font-size: 14px;
            color: #881337;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }
        .credentials-value {
            font-size: 18px;
            color: #111827;
            font-weight: 700;
            word-break: break-all;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #881337;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
        }
        .alert {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            font-size: 14px;
            color: #92400e;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 20px 40px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di Al-Haq!</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $studentUser->name }}</strong>,</p>
            
            <p>Akun siswa Anda telah berhasil didaftarkan oleh orang tua Anda. Sekarang Anda dapat mengakses dashboard pembelajaran kami menggunakan informasi login di bawah ini:</p>
            
            <div class="credentials">
                <div class="credentials-row">
                    <span class="credentials-label">Email Anda</span>
                    <span class="credentials-value">{{ $studentUser->email }}</span>
                </div>
                <div class="credentials-row">
                    <span class="credentials-label">Password Sementara</span>
                    <span class="credentials-value">{{ $plainPassword }}</span>
                </div>
            </div>

            <div class="alert">
                <strong>Penting:</strong> Demi keamanan akun Anda, harap segera mengubah kata sandi setelah Anda berhasil login untuk pertama kalinya.
            </div>

            <div class="button-container">
                <a href="{{ config('app.url') }}/login" class="button">Masuk ke Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Al-Haq Learning Center</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
