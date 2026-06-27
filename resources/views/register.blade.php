<!DOCTYPE HTML>
<html>
<head>
    <meta charset=utf-8">
    <title>A Simple HTML Example</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="card">
    <h1>Inregistrare</h1>

    <form method="POST" action="/register">
        @csrf
        <input type="text" name="name" placeholder="Nume">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Parola">
        <button type="submit">Inregistreaza-te</button>
    </form>
    <p class="link">ai cont? <a href="/login">Autentificare</a></p>
</div>

</body>
</html>
