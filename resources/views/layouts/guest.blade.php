<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PAIO Request Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --paio-bg: #e4efe5;
            --paio-green: #055b25;
            --paio-gold: #d2ae47;
            --paio-muted: #538e6b;
            --paio-light: #7baf94;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            color: var(--paio-green);
        }

        .paio-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 36px 20px 28px;
            background:
                radial-gradient(ellipse at 15% 15%, #d2e6d7 0%, transparent 35%),
                radial-gradient(ellipse at 85% 85%, #d8eadd 0%, transparent 35%),
                linear-gradient(135deg, #e4efe5, #f7f9f5);
        }

        .paio-container {
            width: 100%;
            max-width: 710px;
        }

        .paio-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .paio-logo {
            display: block;
            width: 150px;
            height: 150px;
            object-fit: contain;
            margin: 0 auto 12px;
        }

        .paio-heading {
            color: var(--paio-green);
            font-size: clamp(23px, 4vw, 34px);
            line-height: 1.25;
            font-weight: 800;
            margin: 0;
        }

        .paio-gold-line {
            width: 115px;
            height: 5px;
            border-radius: 10px;
            background: var(--paio-gold);
            margin: 16px auto;
        }

        .paio-system-title {
            color: var(--paio-green);
            font-size: clamp(20px, 3vw, 27px);
            font-weight: 700;
            margin: 0;
        }

        .paio-subtitle {
            margin: 8px 0 0;
            color: var(--paio-muted);
            font-size: 16px;
        }

        .paio-card {
            background: rgba(255, 255, 255, .96);
            border: 1px solid #dce9df;
            border-radius: 22px;
            padding: 36px 46px;
            box-shadow: 0 18px 48px rgba(5, 91, 37, .10);
        }

        .paio-footer {
            text-align: center;
            margin-top: 34px;
            color: var(--paio-muted);
            font-size: 15px;
        }

        .paio-footer-line {
            width: 100%;
            height: 5px;
            border-radius: 10px;
            background: linear-gradient(
                90deg,
                #055b25,
                #318d52,
                #72b588,
                #d2ae47
            );
            margin-bottom: 18px;
        }

        @media (max-width: 600px) {
            .paio-page {
                padding: 24px 15px;
            }

            .paio-logo {
                width: 115px;
                height: 115px;
            }

            .paio-header {
                margin-bottom: 24px;
            }

            .paio-card {
                padding: 28px 22px;
                border-radius: 16px;
            }

            .paio-subtitle {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <main class="paio-page">
        <div class="paio-container">

            <header class="paio-header">
                <img
                    src="{{ asset('images/paio-logo.png') }}"
                    alt="Public Affairs and Information Office Logo"
                    class="paio-logo"
                >

                <h1 class="paio-heading">
                    Public Affairs &amp; Information Office
                </h1>

                <div class="paio-gold-line"></div>

                <h2 class="paio-system-title">
                    Request Management System
                </h2>

                <p class="paio-subtitle">
                    Secure access for authorized personnel
                </p>
            </header>

            <section class="paio-card">
                {{ $slot }}
            </section>

            <footer class="paio-footer">
                <div class="paio-footer-line"></div>
                <p>City Government of San Pedro, Laguna</p>
            </footer>

        </div>
    </main>
</body>
</html>