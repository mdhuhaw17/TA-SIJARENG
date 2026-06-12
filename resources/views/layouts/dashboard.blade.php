<!DOCTYPE html>
<html lang="id">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>

    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            margin: 0;
        }

        .header {
            background: #1565c0;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-menu {
            position: relative;
        }

        .user-name {
            cursor: pointer;
            font-weight: bold;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 35px;
            background: red;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            overflow: hidden;
            min-width: 120px;
        }

        .dropdown button {
            width: 100%;
            padding: 10px;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
        }

        .dropdown button:hover {
            background: #ff9494;
        }

        .container {
            padding: 20px;
        }

        .card-container {
            display: flex;
            gap: 15px;
        }

        .card {
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            font-weight: bold;
        }

        .green { background: #c8f7c5; }
        .yellow { background: #fff3b0; }
        .blue { background: #cce5ff; }

        .grafik {
            margin-top: 20px;
            height: 150px;
            background: #ddd;
            border-radius: 10px;
            padding: 10px;
        }

        .menu-container {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .menu-btn {
            text-align: center;
            padding: 20px;
            background: #e0e0e0;
            border-radius: 12px;
            text-decoration: none;
            color: black;
            font-weight: bold;
            transition: 0.2s;
        }

        .menu-btn:hover {
            background: #d5d5d5;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div>
        <b>Absensi Les JARENG</b> | 
        <span>@yield('header', 'Dashboard')</span>
    </div>

    <div class="user-menu">
        <span class="user-name" onclick="toggleDropdown()">
            {{ Auth::user()->name }} 👤
        </span>

        <div id="dropdown" class="dropdown">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="container">
    @yield('content')
</div>

<script>
function toggleDropdown() {
    let dropdown = document.getElementById("dropdown");
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
}

window.onclick = function(event) {
    if (!event.target.matches('.user-name')) {
        let dropdown = document.getElementById("dropdown");
        if (dropdown) {
            dropdown.style.display = "none";
        }
    }
}
</script>

</body>
</html>