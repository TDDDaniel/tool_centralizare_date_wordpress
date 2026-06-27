<html>
<head>
    @vite('resources/css/app.css')
</head>
<body>
<div class="card">
    <h1>Login</h1>
    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Parola">
        <button type="submit">Login</button>
    </form>
    <p class="link">N-ai cont? <a href="/register">Inregistreaza-te</a></p>
</div>
</body>
</html>
