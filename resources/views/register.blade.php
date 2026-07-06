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
        <!--
            Am adaugat logica erorilor la fiecare input 
        -->
        @csrf
        <input type="text" name="name" placeholder="Nume" value="{{ old('name') }}">
        @error('name') <span class="error">{{ $message }}</span> @enderror

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        @error('email') <span class="error">{{ $message }}</span> @enderror

        <input type="password" name="password" placeholder="Parola">
        @error('password') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Inregistreaza-te</button>
    </form>
    <p class="link">ai cont? <a href="/login">Autentificare</a></p>
</div>

</body>
</html>
