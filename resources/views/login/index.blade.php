<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.98.0">
    <title>SITEI | Universitas Riau</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link href="{{ asset('/assets/css/signin.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />

</head>
<style>
    .d-flex-container {
        display: flex;
        align-items: center;
    }

    .gambar {
        margin-right: 5px;
    }

    .vl {
        border-left: 2px solid green;
        height: 86px;
        margin-top: 0;
    }

    h4 {
        margin-top: 0;
    }


    @media only screen and (max-width: 768px) {


        .green-background {
            display: none !important;
        }

        .vl {
            border-left: 2px solid green;
            height: 70px;
            margin-top: 20px;
            padding-left: 10px;
        }

        .caption h4 {
            font-size: 16px;
        }

    }

    @media only screen and (max-width: 992px) {


        .green-background {
            display: none !important;
        }

    }

    .pengembang {
        color: #212529;
    }

    .pengembang:hover {
        color: #28a745;
    }

    .green-background {
        background-color: #28a745;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .carousel-column {
        padding: 0;
    }

    .login-column {
        padding: 0 15px;
    }

    .kotak-masuk {
        border-radius: 10px;
    }

    .hr-line-dashed {
        border-top: 1px dashed #e7eaec;
        color: #ffffff;
        background-color: #ffffff;
        height: 1px;
        margin: 30px 0;
    }
</style>

<body>

    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <!-- Carousel Column -->
            <div class="col-lg-6 green-background">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/img/test.svg" class="d-block w-100" alt="...">
                        </div>
                        {{-- <div class="carousel-item">
                  <img src="assets/img/educator.svg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                  <img src="https://via.placeholder.com/600" class="d-block w-100" alt="...">
                </div> --}}
                    </div>
                    {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button> --}}
                </div>
            </div>

            <!-- Login Column -->
            <div class="col-lg-6 login-column">
                <div class="kotak-masuk p-3">
                    <main class="form-signin w-100 m-auto">
                        @if (session()->has('loginError'))
                            <div class="alert alert-danger alert-dismissible fade show float-left" role="alert">
                                {{ session('loginError') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form class="form-login" action="/" method="POST" class="text-center mt-5">
                            @csrf

                            <div class="d-flex">
                                <div class="gambar p-3">
                                    <img src="assets/dist/img/unri.png" alt="logo_unri" width="86" height="86">
                                </div>
                                <div class="vl mt-3 p-2"></div>
                                <div class="mt-4 caption">
                                    <h4 class="text-left">Sistem Informasi Teknik Elektro <br> dan Informatika</h4>
                                </div>
                            </div>

                            <div class="form-floating mt-5">
                                <input type="text"
                                    class="form-control rounded-1 @error('username') is-invalid @enderror"
                                    name="username" id="username" value="{{ old('username') }}" placeholder="username"
                                    autofocus required>
                                <label class="label-nim" for="username">NIP/NIM <span
                                        class="text-danger">*</span></label>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mt-3 position-relative">
                                <input type="password" class="form-control rounded-1" name="password" id="password"
                                    placeholder="Password" required>
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <div class="position-absolute end-0 top-50 translate-middle-y">
                                    <span class="px-3">
                                        <i class="fas fa-eye-slash pointer" id="togglePassword"></i>
                                    </span>
                                </div>
                            </div>

                            <button class="w-100 btn btn-lg btn-success btn-login mt-4 rounded-1"
                                type="submit">Masuk</button>
                        </form>
                        <small class="kecil d-block text-center mt-4">Belum memiliki akun? <br>Silahkan hubungi Admin
                            Prodi<br></small>

                        <div class="hr-line-dashed"></div>

                        <div class="footer text-center">
                            <span>Dikembangkan oleh</span>
                            <a class="pengembang" href="/developer" target="_blank">Tim Prodi Teknik Informatika</a>
                            <div class="mt-2">
                                <p class="text-center"style="color: #98A2B3;">2023 - {{ now()->year }}© SITEI
                                    Universitas Riau</p>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>


    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            var passwordInput = document.getElementById("password");
            var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.className = type === "password" ? "fas fa-eye-slash pointer" : "fas fa-eye pointer";
        });
    </script>
</body>

</html>
