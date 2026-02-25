<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background: #f9fafb;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            width:100%;
            max-width:400px;
            background:white;
            padding:40px;
            border-radius:16px;
            box-shadow:0 10px 40px rgba(0,0,0,.1);
        }

        h2 {
            text-align:center;
            margin-bottom:30px;
        }

        input {
            width:100%;
            padding:12px;
            margin-bottom:20px;
            border-radius:8px;
            border:1px solid #ddd;
        }

        button {
            width:100%;
            padding:12px;
            background:#15803d;
            color:white;
            border:none;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        }

        button:hover {
            background:#166534;
        }

        .error {
            color:red;
            font-size:.9rem;
            margin-bottom:15px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Iniciar sesión</h2>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <input type="email" name="email" placeholder="Correo electrónico" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>