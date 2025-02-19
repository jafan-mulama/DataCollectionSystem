<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Data Collection System') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .navbar-nav {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            color: #666;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: #f0f0f0;
        }

        .hero {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .features {
            padding: 4rem 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .feature-card h3 {
            color: #333;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="navbar-brand">{{ config('app.name', 'Data Collection System') }}</a>
        <div class="navbar-nav">
            @if (Route::has('login'))
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    @elseif(auth()->user()->isLecturer())
                        <a href="{{ route('lecturer.dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="nav-link">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <div class="hero">
        <h1>Welcome to Data Collection System</h1>
        <p>A comprehensive platform for creating, managing, and analyzing questionnaires</p>
        @guest
            <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
        @endguest
    </div>

    <div class="features">
        <div class="feature-card">
            <h3>For Lecturers</h3>
            <p>Create and manage questionnaires, track responses, and analyze data with ease.</p>
        </div>
        <div class="feature-card">
            <h3>For Students</h3>
            <p>Submit responses to questionnaires and track your participation history.</p>
        </div>
        <div class="feature-card">
            <h3>For Administrators</h3>
            <p>Manage users, monitor system usage, and maintain platform integrity.</p>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Data Collection System. All rights reserved.</p>
    </footer>
</body>
</html>
