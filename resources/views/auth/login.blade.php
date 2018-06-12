<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>HR Login</title>

    <style>
        body {
            background-color: white;
            font-family: Calibri, sans-serif;
            overflow: hidden;
            color: rgb(0, 85, 165);
        }

        header {
            background-color: rgb(0, 85, 165);
            height: 15vh;
            max-height: 15vh;
            padding-right: 2rem;
        }

        footer {
            height: 15vh;
            max-height: 15vh;
            text-align: center;
            color: black;
        }

        button.btn {
            background-color: rgb(175, 171, 171);
            border-color: rgb(47, 82, 143);
            color: white;
            font-weight: bold;
            padding: .5rem 3rem;
        }

        label {
            font-weight: bold;
        }

        main {
            height: 75vh;
            max-height: 75vh;
        }

        img {
            max-width: 80vw;
        }

        legend {
            font-size: 1rem;
            text-align: center;
        }

        form {
            background-color: rgb(231, 230, 230);
            border: 1px solid rgb(47, 82, 143);
            border-radius: 3rem;
        }

        .btn-link {
            text-decoration: underline;
        }

        .form-control {
            border: 1px solid rgb(47, 82, 143);
            border-radius: 0;
        }

        /* Extra small devices (portrait phones, less than 576px) */
        /* No media query since this is the default in Bootstrap */
        p {
            font-size: 1.3rem;
        }

        @media (max-width: 576px) {
            footer.px-4 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) {
            html {
                font-size: 1.2rem;
            }

            header {
                height: 15vh;
                max-height: 15vh;
                padding-right: 3rem;
            }

            img {
                max-width: 60vw;
            }

            main {
                height: 70vh;
                max-height: 70vh;
            }
        }

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) {
            html {
                font-size: 1.2rem;
            }

            header {
                height: 20vh;
                max-height: 20vh;
                padding-right: 4rem;
            }

            img {
                max-width: 40vw;
            }

            main {
                height: 70vh;
                max-height: 70vh;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            html {
                font-size: 1.2rem;
            }

            main {
                height: 70vh;
                max-height: 70vh;
            }

            header {
                padding-right: 5rem;
            }

            .btn {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid mx-0 px-0">
    <header>

    </header>
    <main>
        <div class="row align-items-center justify-content-center">
            <img src="/logo.jpg" alt="HRCOACH">
        </div>
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 offset-sm-1 offset-md-2 offset-lg-3 mt-4">
            <form method="POST" action="{{ route('login') }}" class="py-4">
                <legend class="mb-3">Please enter your email address and password below to log in.</legend>
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-4 col-form-label text-right">{{ __('Email') }}</label>

                    <div class="col-6">
                        <input id="email" type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-4 col-form-label text-right">{{ __('Password') }}</label>

                    <div class="col-6">
                        <input id="password" type="password"
                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                               required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="row align-items-center justify-content-center">
                    <button type="submit" class="btn">
                        {{ __('Login') }}
                    </button>
                </div>
                <div class="row align-items-center justify-content-center">
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        I have forgotten my password
                    </a>
                </div>
            </form>
        </div>
    </main>
    <footer class="px-4">
        © HR Coach Australasia 2018. This website and content is the intellectual property of HR Coach Australasia. Any disclosure, copying, display or misuse of this information is strictly prohibited without the written consent of HR Coach Australasia.
    </footer>
</div>
</body>
</html>