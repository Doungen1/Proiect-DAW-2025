    
    <?php putenv('GOOGLE_APPLICATION_CREDENTIALS=c:\Users\doung\Downloads\my-project-40392-1705419579903-9a19dec1d995.json')?>
    <script src="https://www.google.com/recaptcha/api.js?render=6LcmK1QpAAAAANB_QWEod9CQCNc5DwHmDDUeNe1I"></script>


<?php
function getWeatherData($city) {
  $apiKey = '945326cf2c1d999632ca7942893f8b51';
  $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_URL, $url);
  $result = curl_exec($curl);
  curl_close($curl);

  $data = json_decode($result, true);
  if (isset($data['weather'][0]['icon'])) {
      $data['icon_url'] = "http://openweathermap.org/img/w/" . $data['weather'][0]['icon'] . ".png";
  }
  return $data;
  }
  $weatherData = getWeatherData('Phuket');

  $weatherInfo = '';
  if ($weatherData) {
      $temperature = $weatherData['main']['temp'];
      $weatherCondition = $weatherData['weather'][0]['main'];
      $weatherInfo = "{$temperature}°C, {$weatherCondition}";
  } else {
      $weatherInfo = "Weather data not available";
  }
    ?>


<nav id="nav-bar" class ="navbar navbar-expand-lg navbar-light bg-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php"><?php echo $settings_r['site_title'] ?> </a>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span> 
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link me-2" href="index.php">Acasă</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="rooms.php">Camere</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="facilities.php">Facilități</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="contact.php">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="about.php">Despre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2 ms-2" href="weather.php">
          <?php if (!empty($weatherData['icon_url'])): ?>
            <img src="<?= $weatherData['icon_url'] ?>" alt="Weather Icon">
          <?php endif; ?>
          <?= $weatherInfo,"-", "Ko Samui" ?></a>
        </li>
      </ul>
      <div class="d-flex">


    <?php 
         if (isset($_SESSION['login']) && $_SESSION['login']  == true) { // Daca userul este logat
            $path = USERS_IMG_PATH; // Calea imaginilor de la users
            echo<<<data
              <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                  <img src="$path$_SESSION[uPic]" style ="width: 25px; height: 25px;" class="me-1">
                  $_SESSION[uName]
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end">
                  <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                  <li><a class="dropdown-item" href="bookings.php">Bookings</a></li>
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                  <li><a class="dropdown-item" href="admin/inc/csv.php">Excel</a></li>
                </ul>
              </div>
            data;
          } 
          else{
            echo<<<data
              <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                Autentificare
              </button>
              <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">
                Înregistrare
              </button>
            data;
          }
      ?>

        </div>
    </div>
  </div>
</nav>

<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="login-form" method="POST" action="ajax/login_register.php">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center">
        <i class="bi bi-person-circle fs-3 me-2"></i>Autentificare Utilizator
        </h5>
        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Email / Mobil</label>
            <input type="text" name ="email_mob" required class="form-control shadow-none">
        </div>
        <div class="mb-4">
            <label class="form-label">Parolă</label>
            <input type="password" name="pass" required class="form-control shadow-none">
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="g-recaptcha" data-sitekey="6LdjCFQpAAAAAKJqyCmg8nadH6Gm3XHWRDMtMJTt" data-callback="enableBtn"></div>
          <button type="submit" name="login" class="btn btn-dark shadow-none" id="loginButton" disabled>Autentificare</button>
            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal" id="forgotButton" disabled>
              Ai uitat parola?
            </button></div>
          <div class="mb-3">
          </div>
        </div>  
    </form>
    </div>
  </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
function enableBtn() {
    document.getElementById("loginButton").disabled = false;
    document.getElementById("forgotButton").disabled = false;
}

</script>



<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="register-form">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center">
        <i class="bi bi-person-lines-fill fs-3 me-2"></i>Înregistrare utilizator
        </h5>
        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
        Notă: Detaliile dumneavoastră trebuie să corespundă cu cele din actul de identitate (CI/Pașaport) pentru verificare, care va fi necesară la check-in.        </span>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 ps-0 mb-3">
                    <label class="form-label">Nume</label>
                    <input name="name" type="text" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-6 p-0 mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-6 ps-0 mb-3">
                    <label class="form-label">Telefon</label>
                    <input name="phonenum" type="number" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-6 p-0 mb-3">
                    <label class="form-label">Pictură</label>
                    <input name="profile" type="file" accept = ".jpg, .jpeg, .png" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-12 p-0 mb-3">
                    <label class="form-label">Adresă</label>
                    <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
                    </div>
                <div class="col-md-6 ps-0 mb-3">
                    <label class="form-label">Cod Poștal</label>
                    <input name="pincode" type="number" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-6 p-0 mb-3">
                    <label class="form-label">Zi De Naștere</label>
                    <input name="dob" type="date" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-6 ps-0 mb-3">
                    <label class="form-label">Parolă</label>
                    <input name="pass" type="password" class="form-control shadow-none" required>
                    </div>
                <div class="col-md-6 p-0 mb-3">
                    <label class="form-label">Confirmare Parolă</label>
                    <input name="cpass" type="password" class="form-control shadow-none" required>
                    </div>
                </div>
            </div>
            <div class="text-center my-1">
                <button type="submit" class="btn btn-dark shadow-none">Înregistrare</button>
            </div>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="forgot-form">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center">
        <i class="bi bi-person-circle fs-3 me-2"></i>Parolă Pierdută
        </h5>
      </div>
      <div class="modal-body">
        <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
          Notă: Un link va fi trimis pe adresa dumneavoastră de e-mail pentru a reseta parola.        <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name = "email" required class="form-control shadow-none">
        </div>
        <div class="mb-2 text-end">
            <button type="button" class="btn shadow-none p-0 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
              Anulează
            </button>
            <button type="submit" class="btn btn-dark shadow-none">Trimite Link</button>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>





