<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>HR COACH</title>

    <style>
        body {
            background-color: white;
            font-family: Calibri, sans-serif;
            overflow: hidden;
        }

        header {
            background-color: rgb(0, 85, 165);
            height: 15vh;
            max-height: 15vh;
            padding-right: 2rem;
        }

        footer {
            height: 5vh;
            max-height: 5vh;
            text-align: center;
        }

        main {
            height: 80vh;
            max-height: 80vh;
        }

        img {
            max-width: 80vw;
        }

        .center {
            text-align: center;
        }

        .about {
            background-color: rgb(247, 143, 30);
            color: white;
            max-width: 80vw;
            padding: 1.5vw 2vw;
            border-radius: 5vw;
            cursor: pointer;
            font-size: 1.3rem;
        }

        /* Extra small devices (portrait phones, less than 576px) */
        /* No media query since this is the default in Bootstrap */
        p {
            font-size: 1.3rem;
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

            .about {
                max-width: 80vw;
            }

            main {
                height: 80vh;
                max-height: 80vh;
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

            .about {
                max-width: 50vw;
            }

            main {
                height: 75vh;
                max-height: 75vh;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            html {
                font-size: 1.2rem;
            }

            .about {
                max-width: 40vw;
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
    <header class="row align-items-center justify-content-end">
        <button class="btn btn-lg" onclick="location.href = '{{ route('login') }}'">Log in</button>
    </header>
    <main>
        <div class="row align-items-center justify-content-center">
            <img src="/logo.jpg" alt="HRCOACH">
        </div>
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 center mt-4">
            <p>
                This website is reserved for certified members of the HR Coach Network.
            </p>
            <div class="about mx-auto mt-4">
                Click here if you would like to find out more about becoming a HR Coach.
            </div>
        </div>
    </main>
    <footer>
        © HR Coach Australasia 2018
    </footer>
</div>
</body>
</html>