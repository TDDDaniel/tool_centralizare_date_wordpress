<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Conflux</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>
<div class="layout">

    <aside class="side">
        <div class="logo"><span class="logo-mark"></span> Conflux</div>
        <nav class="nav">
            <a class="nav-item active">▦ Dashboard</a>
            <a href="#" class="nav-item">⚙ Settings</a>
            <a href="#" class="nav-item">◔ Profile</a>
            <a href="/comenzi/adauga" class="add-btn">Adauga comanda</a>
        </nav>
        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">⇥ Logout</button>
        </form>
    </aside>

    <main class="main">
        <div class="top">
            <div>
                <div class="hi">Comenzi</div>
                <div class="sub">Toate comenzile, de la toate magazinele.</div>
            </div>
            <span class="badge">{{ $orders->total() }} în total</span>
        </div>

        {{-- lista de comenzi, una sub alta --}}
        <div class="orders-list">
            @forelse ($orders as $order)
                {{-- Am schimbat <div> în <a> și am adăugat href --}}
                <a href="/comenzi/{{ $order->id }}" class="order-row" style="text-decoration: none; color: inherit;">
                    <div class="order-main">
                        <div class="order-name">{{ $order->customer_first_name }} {{ $order->customer_last_name }}</div>
                        <div class="order-meta">{{ $order->shop->name }} · {{ $order->order_reference }}</div>
                        <div
                            class="order-items">{{ $order->items->map(fn ($i) => $i->product_name . ' × ' . $i->quantity)->implode(', ') }}</div>
                    </div>
                    <div class="order-side">
                        <div class="order-total">{{ number_format($order->total_price, 2) }} lei</div>
                        <span class="order-status">{{ $order->status }}</span>
                    </div>
                </a>
            @empty
                <p class="lead">Nu există comenzi.</p>
            @endforelse
        </div>

        {{-- paginare: butoane doar daca sunt mai multe pagini --}}
        @if ($orders->hasPages())
            <div class="pagination">
                @if ($orders->onFirstPage())
                    <span class="page-btn disabled">‹ Înapoi</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="page-btn">‹ Înapoi</a>
                @endif

                <span class="page-info">Pagina {{ $orders->currentPage() }} din {{ $orders->lastPage() }}</span>

                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="page-btn">Înainte ›</a>
                @else
                    <span class="page-btn disabled">Înainte ›</span>
                @endif
            </div>
        @endif
    </main>
</div>
</body>
</html>
