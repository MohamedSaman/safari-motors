<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Scripts -->
        <style>
            /* Login page styling */
            .login-container {
                height: 100vh;
                width: 100vw;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .background-image {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset('/images/loginn.jpg') }}');
                background-size: cover;
                background-position: center;
                z-index: 0;
            }

            .login-form-overlay {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                padding: 40px 35px;
                width: 100%;
                max-width: 450px;
                z-index: 1;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            .logo-container {
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto 30px;
                width: 100px;
                height: 100px;
                background: #1b5e85;
                border-radius: 50%;
                backdrop-filter: blur(10px);
                border: 3px solid rgba(255, 255, 255, 0.3);
                padding: 10px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }

            .login-logo {
                max-width: 100%;
                height: auto;
                filter: brightness(1.1) drop-shadow(0 2px 8px rgba(0, 0, 0, 0.4));
            }

            .user-icon-container {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }

            .user-icon-container i {
                font-size: 3rem;
                color: #1B5E85;
                background: #f0f0f0;
                border-radius: 50%;
                width: 70px;
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-control {
                border-radius: 25px;
                padding: 14px 22px;
                border: 1px solid rgba(255, 255, 255, 0.3);
                background: rgba(255, 255, 255, 0.9);
                color: #333;
                font-size: 15px;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                background: rgba(255, 255, 255, 1);
                border-color: rgba(255, 255, 255, 0.6);
                box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
                outline: none;
            }

            .form-control::placeholder {
                color: #999;
            }

            /* Invalid state: red border */
            .form-control.is-invalid {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 0.15rem rgba(220,53,69,0.1);
            }

            /* Shake animation for attention */
            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(-6px); }
                50% { transform: translateX(6px); }
                75% { transform: translateX(-4px); }
                100% { transform: translateX(0); }
            }

            .shake {
                animation: shake 0.45s cubic-bezier(.36,.07,.19,.97);
            }

            .form-options {
                margin-bottom: 25px;
                font-size: 0.9rem;
                color: #fff;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            }

            .form-check-label {
                color: #fff;
            }

            .form-check-input {
                border: 1px solid rgba(255, 255, 255, 0.5);
                background-color: rgba(255, 255, 255, 0.2);
            }

            .form-check-input:checked {
                background-color: #1B5E85;
                border-color: #1B5E85;
            }

            .forgot-link {
                color: #fff;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            }

            .forgot-link:hover {
                color: rgba(255, 255, 255, 0.9);
                text-decoration: underline;
            }

            .login-btn {
                width: 100%;
                border-radius: 25px;
                padding: 14px;
                background: #1b5e85;
                border: none;
                font-weight: 600;
                margin-bottom: 25px;
                color: #fff;
                font-size: 16px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(27, 94, 133, 0.3);
            }

            .login-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(27, 94, 133, 0.4);
                background: #125f86ff;
            }

            .separator-line {
                width: 100%;
                height: 1px;
                background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.4), transparent);
                margin: 25px 0;
            }

            .connect-section {
                text-align: center;
                margin-top: 25px;
            }

            .connect-title {
                color: #fff;
                font-size: 0.95rem;
                font-weight: 500;
                margin-bottom: 15px;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            }

            .connect-links {
                display: flex;
                justify-content: center;
                gap: 20px;
            }

            .connect-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.2);
                color: #fff;
                text-decoration: none;
                transition: all 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(5px);
                font-size: 1.3rem;
            }

            .connect-icon:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            /* Email icon - Red/Orange */
            .connect-icon:has(.bi-envelope-fill) {
                background-color: rgba(234, 67, 53, 0.85);
                border-color: rgba(234, 67, 53, 0.5);
            }

            .connect-icon:has(.bi-envelope-fill):hover {
                background-color: rgba(234, 67, 53, 1);
                border-color: rgba(234, 67, 53, 0.7);
            }

            /* WhatsApp icon - Green */
            .connect-icon:has(.bi-whatsapp) {
                background-color: rgba(37, 211, 102, 0.85);
                border-color: rgba(37, 211, 102, 0.5);
            }

            .connect-icon:has(.bi-whatsapp):hover {
                background-color: rgba(37, 211, 102, 1);
                border-color: rgba(37, 211, 102, 0.7);
            }

            /* Mobile Responsive Styles */
            @media (max-width: 768px) {
                .login-form-overlay {
                    max-width: 90%;
                    padding: 30px 25px;
                    margin: 0 20px;
                }

                .logo-container {
                    width: 90px;
                    height: 90px;
                    padding: 8px;
                    margin-bottom: 25px;
                }

                .form-control {
                    padding: 12px 18px;
                    font-size: 14px;
                }

                .login-btn {
                    padding: 12px;
                    font-size: 15px;
                }

                .connect-title {
                    font-size: 0.9rem;
                }

                .connect-icon {
                    width: 42px;
                    height: 42px;
                    font-size: 1.2rem;
                }
            }

            @media (max-width: 480px) {
                .login-form-overlay {
                    max-width: 95%;
                    padding: 25px 20px;
                    margin: 0 10px;
                }

                .logo-container {
                    width: 80px;
                    height: 80px;
                    padding: 6px;
                    margin-bottom: 20px;
                }

                .form-group {
                    margin-bottom: 15px;
                }

                .form-control {
                    padding: 11px 16px;
                    font-size: 14px;
                }

                .form-options {
                    font-size: 0.85rem;
                    margin-bottom: 20px;
                }

                .login-btn {
                    padding: 11px;
                    font-size: 14px;
                    margin-bottom: 20px;
                }

                .separator-line {
                    margin: 20px 0;
                }

                .connect-section {
                    margin-top: 20px;
                }

                .connect-title {
                    font-size: 0.85rem;
                    margin-bottom: 12px;
                }

                .connect-links {
                    gap: 15px;
                }

                .connect-icon {
                    width: 38px;
                    height: 38px;
                    font-size: 1.1rem;
                }
            }
        </style>
        <!-- Styles -->

    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

       <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>





