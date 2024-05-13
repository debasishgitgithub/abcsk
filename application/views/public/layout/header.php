<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-LVWXRC6XL1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-LVWXRC6XL1');
  </script>
  
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= $title ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- jQuery -->
  <script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>

  <!-- Favicons -->
  <link href="<?= base_url("public_assets/img/myicon.jpg"); ?>" rel="icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url("public_assets/vendor/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet">
  <link href="<?= base_url("public_assets/vendor/bootstrap-icons/bootstrap-icons.css"); ?>" rel="stylesheet">
  <link href="<?= base_url("public_assets/vendor/swiper/swiper-bundle.min.css"); ?>" rel="stylesheet">
  <link href="<?= base_url("public_assets/vendor/glightbox/css/glightbox.min.css"); ?>" rel="stylesheet">
  <link href="<?= base_url("public_assets/vendor/aos/aos.css"); ?>" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="<?= base_url("public_assets/css/variables.css"); ?>" rel="stylesheet">
  <link href="<?= base_url('public_assets/css/main.css'); ?>" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="public_assets/img/logo.png" alt=""> -->
        <h1>Mr.Das</h1>
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="<?= base_url(); ?>">Blog</a></li>
          <li class="dropdown"><a href="#"><span>Categories</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="#">Search Result</a></li>
            </ul>
          </li>

          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </nav>
      <!-- .navbar -->

      <div class="position-relative">
        <!-- <a href="#" class="mx-2"><span class="bi-facebook"></span></a>
        <a href="#" class="mx-2"><span class="bi-twitter"></span></a>
        <a href="#" class="mx-2"><span class="bi-instagram"></span></a> -->

        <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
        <i class="bi bi-list mobile-nav-toggle"></i>

        <!-- ======= Search Form ======= -->
        <div class="search-form-wrap js-search-form-wrap">
          <form action="search-result.html" class="search-form">
            <span class="icon bi-search"></span>
            <input type="text" placeholder="Search" class="form-control">
            <button class="btn js-search-close"><span class="bi-x"></span></button>
          </form>
        </div>
        <!-- End Search Form -->

      </div>

    </div>

  </header>