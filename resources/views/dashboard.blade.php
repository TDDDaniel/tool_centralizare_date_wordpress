<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
<h1>Salut, {{ Auth::user()->name }} de la compania {{Auth::user()->company_name}}</h1>
<p>Esti logat. Asta e zona protejata.</p>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>

<div class="dashboard_produse">
    
</div>

</body>
</html>
