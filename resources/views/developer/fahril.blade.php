<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SITEI | UNIVERSITAS RIAU</title>
  <link href="{{ asset('/assets/css/developer.css') }}" rel="stylesheet">
  <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>
<body>
  <div class="wrapper">
    <div class="left">
      <img src="/assets/img/fahril.jpeg" alt="fahril" width="120">
      <h4>Fahril Hadi, S.T</h4>
      <p>Frontend Developer</p>
      <hr class="batas">
      <img src="/assets/img/pakedi.jpg" alt="fahril" width="100">
      <h4>Edi Susilo, S.Pd., M.Kom., M.Eng.</h4>
      <p>Pembimbing 1</p>
      <hr class="batas">
      <img src="/assets/img/pakanhar.jpg" alt="fahril" width="100">
      <h4>Anhar, S.T., M.T., Ph.D.</h4>
      <p>Pembimbing 2</p>
    </div>

    <div class="right">

      <div class="info">
        <br><br>
        <h3>Informasi</h3>
        <div class="info_data">
          <div class="data">
            <h4>Nim</h4>
            <p>1807111442</p>
          </div>
          <div class="data">
            <h4>Email</h4>
            <p>fahrilhadi77@gmail.com</p>
          </div>
        </div>
      </div>

      <div class="projects">
        <h3>Project</h3>
        <div class="projects_data">
          <div class="data">
            <h4>Nama Aplikasi</h4>
            <p>Sistem Penilaian Seminar Akademik Jurusan Teknik Elektro</p>
          </div>
          <div class="data">
            <h4>Deskripsi Peran</h4>
            <p>Merancang UI/UX dan Mengembangkan Frontend Website Sistem Penilaian Seminar Akademik</p>
          </div>
        </div>
      </div>

      <div class="social_media">
        <ul>
          <li><a formtarget="_blank" target="_blank" href="https://www.instagram.com/fahrilhadii/"><i class="fab fa-instagram"></i></a></li>
          <li><a formtarget="_blank" target="_blank" href="https://www.linkedin.com/in/fahrilhadi/"><i class="fab fa-linkedin"></i></a></li>
          <li><a formtarget="_blank" target="_blank" href="https://api.whatsapp.com/send?phone=6285374411995"><i class="fab fa-whatsapp"></i></a></li>
        </ul>
      </div>

    </div>
  </div>
</body>
</html> -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SITEI | UNIVERSITAS RIAU</title>
    <link rel="stylesheet" href="{{ asset('/assets/dist/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ asset('/assets/css/developer.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <style>
        * {
            font-family: "Josefin Sans", sans-serif;
            /* letter-spacing: -5px; */
            line-height: 20px;
        }

        .hr {
            border-color: #fff;
        }

        .lebar {
            width: 70%;
        }

        @media screen and (min-width: 769px) {
            .profil {
                display: none;
                visibility: hidden;
            }
        }

        @media screen and (max-width: 768px) {
            .lebar {
                width: 95%;
            }

            .atas {
                margin-top: 350px;
            }
        }
    </style>
</head>

<body>

    <div class="container p-5">
        <div class="position-absolute top-50 start-50 translate-middle atas lebar">
            <h3 class="text-white bg-success text-center p-2 rounded-top profil fw-bold">Profil Developer</h3>
            <div class="card-body">

                <div class="row  p-2">


                    <div class="col-lg-4 shadow text-center rounded-start bg-success">
                        <div class="row text-white p-4 pt-4 row-cols-1">
                            <div class="col">
                                <img src="/assets/img/fahril.jpeg" class="rounded-circle" alt="fahril" width="100">
                                <h6 class="mt-2 ">Fahril Hadi, S.T</h6>
                                <small>Frontend Developer</small>
                                <hr class="border border-1">
                            </div>
                            <div class="col">
                                <img src="/assets/img/pakedi.jpg" class="rounded-circle" alt="pak edi" width="100">
                                <h6 class="mt-2 ">Edi Susilo, S.Pd., M.Kom., M.Eng.</h6>
                                <small>Pembimbing 1</small>
                                <hr class="border border-1">
                            </div>
                            <div class="col">
                                <img src="/assets/img/pakanhar.jpg" class="rounded-circle" alt="pak anhar"
                                    width="100">
                                <h6 class="mt-2 ">Anhar, S.T., M.T., Ph.D.</h6>
                                <small>Pembimbing 2</small>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-8 pt-lg-5 shadow bg-white rounded-end">
                        <div class="container pt-5 mb-5">
                            <div class="row row-cols-1">
                                <div class="col">
                                    <h5 class="fw-bold">INFORMASI</h5>
                                    <hr>
                                </div>
                                <div class="col mb-4">
                                    <div class="row row-lg-cols-2 row-md-cols-1">
                                        <div class="col">
                                            <h6 class="fw-bold">Nim</h6>
                                            <p class="text-muted">1807111442</p>
                                        </div>
                                        <div class="col">
                                            <h6 class="fw-bold">Email</h6>
                                            <p class="text-muted">fahrilhadi77@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="col float-center pt-3">
                                        <a class="bg-success text-white p-2 rounded-circle fs-5" formtarget="_blank"
                                            target="_blank" href="https://www.instagram.com/fahrilhadii"><i
                                                class="fab fa-instagram"></i></a>
                                        <a class="bg-success text-white p-2 rounded-circle fs-5" formtarget="_blank"
                                            target="_blank" href="#"><i class="fab fa-linkedin"></i></a>
                                        <a class="bg-success text-white p-2 rounded-circle fs-5" formtarget="_blank"
                                            target="_blank" href="#"><i class="fab fa-whatsapp"></i></a>
                                    </div>
                                </div>
                                <div class="col ">
                                    <h5 class="fw-bold mt-4">PROJECT</h5>
                                    <hr>
                                </div>
                                <div class="col">
                                    <div class="row row-lg-cols-2 row-md-cols-1">
                                        <div class="col-lg-6 col-md-12">
                                            <h6 class="fw-bold">Nama Aplikasi</h6>
                                            <p class="text-muted">Sistem Penilaian Seminar Akademik Jurusan Teknik
                                                Elektro</p>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <h6 class="fw-bold">Deskripsi Peran</h6>
                                            <p class="text-muted">Merancang UI/UX dan Mengembangkan Frontend Website
                                                Sistem Penilaian Seminar Akademik</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>
    </div>
</body>

</html>
