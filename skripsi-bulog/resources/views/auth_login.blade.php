<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring SPHP Bulog</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .logo-bulog {
            width: 150px; /* Ukuran disesuaikan */
            height: auto;
            margin-bottom: 25px;
        }
        .title-main {
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
            line-height: 1.4;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #444;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            background-color: #fdfdfd;
            font-size: 0.9rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #28a745;
        }
        .btn-masuk {
            background-color: #28a745; /* Hijau Bulog */
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            color: white;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-top: 10px;
            transition: 0.3s;
        }
        .btn-masuk:hover {
            background-color: #218838;
        }
        .footer-text {
            font-size: 0.75rem;
            color: #aaa;
            margin-top: 30px;
        }
        .alert {
            font-size: 0.8rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="{{ asset('image/logo-bulog.png') }}" alt="Bulog Logo" class="logo-bulog">

    <div class="title-main">
        MONITORING PENDISTRIBUSIAN<br>
        SPHP DI PASAR JAWA BARAT
    </div>
    <div class="subtitle">Pemetaan Pasar Jawa Barat</div>

    @if(session('error'))
        <div class="alert alert-danger py-2">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login.proses') }}" method="POST">
        @csrf <div class="text-start">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required autofocus>
        </div>

        <div class="text-start">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
        </div>

        <button type="submit" class="btn btn-masuk">MASUK KE SISTEM</button>
    </form>

    <div class="footer-text">
        © 2026 Perum BULOG Jawa Barat
    </div>
</div>

</body>
</html>