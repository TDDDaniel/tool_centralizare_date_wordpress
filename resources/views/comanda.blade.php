<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/dashboard.css', 'resources/css/comanda.css', 'resources/js/comanda.js'])
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
<form method="POST" action="/comenzi">
    @csrf
    {{-- Magazin --}}
    <div class="form-card">
        <h2 class="form-card-title">Magazin</h2>
        <div class="field">
            <label for="shop_id">Alege magazinul</label>
            <select name="shop_id" id="shop_id">
                <option value="">— alege —</option>
                <!--Ca sa imi ia fiecare magazin care e deja in test -->
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}" @selected(old('shop_id') == $shop->id)>{{ $shop->name }}</option>
                @endforeach
            </select>
            @error('shop_id') <span class="err">{{ $message }}</span> @enderror
        </div>
    </div>
    {{-- Client --}}
    <div class="form-card">
        <h2 class="form-card-title">Client</h2>
        <div class="grid-2">
            <div class="field">
                <label for="customer_first_name">Prenume</label>
                <input type="text" name="customer_first_name" id="customer_first_name"
                       value="{{ old('customer_first_name') }}">
                @error('customer_first_name') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="customer_last_name">Nume</label>
                <input type="text" name="customer_last_name" id="customer_last_name"
                       value="{{ old('customer_last_name') }}">
                @error('customer_last_name') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="customer_phone">Telefon</label>
                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}">
                @error('customer_phone') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="customer_email">Email (opțional)</label>
                <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}">
                @error('customer_email') <span class="err">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    {{-- Adresă --}}
    <div class="form-card">
        <h2 class="form-card-title">Adresă de livrare</h2>
        <div class="grid-2">
            <div class="field">
                <label for="address_county">Județ</label>
                <input type="text" name="address_county" id="address_county" value="{{ old('address_county') }}">
                @error('address_county') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="address_city">Localitate</label>
                <input type="text" name="address_city" id="address_city" value="{{ old('address_city') }}">
                @error('address_city') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="address_street">Stradă</label>
                <input type="text" name="address_street" id="address_street" value="{{ old('address_street') }}">
                @error('address_street') <span class="err">{{ $message }}</span> @enderror
                <div id="strada_sugestii" class="strada-sugestii"></div>
            </div>
            <div class="field">
                <label for="address_number">Număr</label>
                <input type="text" name="address_number" id="address_number" value="{{ old('address_number') }}">
                @error('address_number') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="address_postal_code">Cod poștal (opțional)</label>
                <input type="text" name="address_postal_code" id="address_postal_code" maxlength="6"
                       placeholder="lasă gol ca să-l calculăm noi" value="{{ old('address_postal_code') }}">
                @error('address_postal_code') <span class="err">{{ $message }}</span> @enderror
                <div id="postal_variante" class="postal-variante"></div>
            </div>
            <div class="field">
                <label for="address_building">Bloc (opțional)</label>
                <input type="text" name="address_building" id="address_building" value="{{ old('address_building') }}">
            </div>
            <div class="field">
                <label for="address_entrance">Scară (opțional)</label>
                <input type="text" name="address_entrance" id="address_entrance" value="{{ old('address_entrance') }}">
            </div>
            <div class="field">
                <label for="address_floor">Etaj (opțional)</label>
                <input type="text" name="address_floor" id="address_floor" value="{{ old('address_floor') }}">
            </div>
            <div class="field">
                <label for="address_apartment">Apartament (opțional)</label>
                <input type="text" name="address_apartment" id="address_apartment"
                       value="{{ old('address_apartment') }}">
            </div>
        </div>
    </div>
    {{-- Produs --}}
    <div class="form-card">
        <h2 class="form-card-title">Produs</h2>
        <div class="grid-3">
            <div class="field">
                <label for="product_name">Nume produs</label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}">
                @error('product_name') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="quantity">Cantitate</label>
                <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}">
                @error('quantity') <span class="err">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="price_per_unit">Preț/bucată (lei)</label>
                <input type="number" step="0.01" name="price_per_unit" id="price_per_unit"
                       value="{{ old('price_per_unit') }}">
                @error('price_per_unit') <span class="err">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <button type="submit" class="submit-btn">Salvează comanda</button>
</form>

{{-- TEMPORAR: ce a gasit cautarea de cod postal. De scos dupa ce testezi. --}}
@if (session('postal'))
    <pre style="background:#f4f4f5;padding:16px;overflow:auto;font-size:13px">{{ print_r(session('postal'), true) }}</pre>
@endif

@if (session('postal'))
    <pre style="background:#f4f4f5;padding:16px;overflow:auto">{{ print_r(session('postal'), true) }}</pre>
@endif

</body>
</html>
