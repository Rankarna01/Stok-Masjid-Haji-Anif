<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi OTP Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f766e;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .otp-box {
            background-color: #f1f5f9;
            border: 2px dashed #0f766e;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #0f766e;
        }
        .expiry-note {
            font-size: 13px;
            color: #64748b;
            text-align: center;
            margin-top: -15px;
            margin-bottom: 30px;
        }
        .warning-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
            margin-top: 30px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Yayasan Haji Anif</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong> ({{ ucfirst($user->role) }}),</p>
            <p>Kami menerima permintaan untuk mereset kata sandi akun Anda. Gunakan kode verifikasi 6 digit di bawah ini untuk melanjutkan proses perubahan kata sandi:</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <div class="expiry-note">
                ⏱️ Kode verifikasi ini hanya berlaku selama <strong>15 menit</strong>.
            </div>

            <p>Silakan masukkan kode tersebut pada halaman verifikasi di sistem untuk membuat kata sandi baru Anda.</p>

            <div class="warning-box">
                <strong>⚠️ Peringatan Keamanan:</strong><br>
                Jika Anda tidak pernah meminta reset kata sandi, harap abaikan pesan ini. Akun Anda tetap aman dan kata sandi Anda tidak akan berubah.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Stok Masjid') }}. Semua Hak Dilindungi.
        </div>
    </div>
</body>
</html>
