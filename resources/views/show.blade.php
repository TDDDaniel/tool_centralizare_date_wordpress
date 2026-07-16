<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/orders.css'])
</head>
<body>
<aside class="side">
    <div class="logo"><span class="logo-mark"></span> Conflux</div>
    <nav class="nav">
        <a href="/dashboard" class="nav-item active">▦ Dashboard</a>
        <a href="#" class="nav-item">⚙ Settings</a>
        <a href="#" class="nav-item">◔ Profile</a>
    </nav>
    <form method="POST" action="/logout" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn">⇥ Logout</button>
    </form>
</aside>
<div class="orderDetail">
    <h1>Order Information</h1>
    <div class="details">
        <h4>Total comanda: {{$order->total_price}} RON</h4>
        <h4>Comanda id: {{$order->id}}</h4>
        <div class="clientInformation">
            <h4>Informatii personale</h4>
            <h4>Client: {{$order->customer_first_name}} {{$order->customer_last_name}}</h4>
            <h4>Email: {{$order->customer_email}}</h4>
            <h4>Nr telefon: {{$order->customer_phone}}</h4>
            <h4>Adresa: Judet {{$order->address_county}} | Oras: {{$order->address_city}}</h4>
            <h4>Strada {{$order->address_street}} | Numar: {{$order->address_number}}</h4>
            <h4>Cod postal {{$order->address_postal_code}} | Oras: {{$order->address_city}}</h4>
            <h4>Strada {{$order->address_street}} | Cladire: {{$order->address_building}}</h4>
        </div>
        <div>
            @foreach($order->items as $item)
                <div>
                    {{$item->product_name}}
                    {{$item->quantity}}
                    {{$item->price_per_unit}}
                </div>

            @endforeach
        </div>
        <!--
        'shop_id',
        'order_reference',
        'customer_first_name',
        'customer_last_name',
        'customer_phone',
        'customer_email',
        'address_county',
        'address_city',
        'address_street',
        'address_number',
        'address_postal_code',
        'address_building',
        'address_entrance',
        'address_floor',
        'address_apartment',
        'total_price',
        'status',
        'employee_notes',
        -->

    </div>
</div>

<a href="/dashboard">Back to Dashboard</a>
</body>
</html>
