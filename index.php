<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <?php require('inc/links.php'); ?>
        <title><?php echo $settings_r['site_title']?> - HOME</title> <!-- Titlul paginii -->
        
        <style>
          @media screen and (max-width: 575px){
            .availability-form{
              margin-top: 25px;
              padding: 0 35px;
            }
          }
        </style>
    </head>
    <body class="bg-light">

<?php require('inc/header.php'); ?>
<?php require('currency.php'); ?>


<!-- Carousel -->
<div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
    <div class="swiper-wrapper">
      <?php 
        $res = selectAll('carousel');
        while($row = mysqli_fetch_assoc($res)){
          $path = CAROUSEL_IMG_PATH;
          echo <<<data
            <div class="swiper-slide">
              <img src="$path$row[image]" class="w-100 d-block" style="height: 500px; object-fit: cover;"/>
            </div>
          data;
        }
      ?>
    </div>
  </div>
</div>

<!-- Check Available Form -->

<div class="container availability-form">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded">
            <h5 class="mb-4">Verifică Disponibilitatea Rezervării</h5>
            <form>
                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-In</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-Out</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                      <label class="form-label" style="font-weight: 500;">Adult</label>
                      <select class="form-select shadow-none">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                      <label class="form-label" style="font-weight: 500;">Copii</label>
                      <select class="form-select shadow-none">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    </div>
                    <div class="col-lg-1 mb-lg-3 mt-2">
                      <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
                    </div>
                </div> 
            </form>
        </div>
    </div>
</div>

<!-- Our Rooms -->


<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Camerele noastre</h2>

<div class="container">
  <div class="row">
  <?php

    $apiKey = 'YOUR_API_KEY'; // Replace with your actual API key
    $usdToRonRate = getUsdToRonRate("c4b88a99bf4a4d05ac83945c5f74084e");

    $room_res = select("SELECT * FROM rooms WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3",[1, 0], 'ii'); // selecteaza toate camerele care nu sunt scoase din uz
    while($room_data = mysqli_fetch_assoc($room_res)){
      // features de la camere

      $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
        INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
        WHERE rfea.room_id = '$room_data[id]'"); // query pentru features
      
      $features_data = ""; // array pentru features
      while($fea_row = mysqli_fetch_assoc($fea_q)){ // loop prin features
        $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
        $fea_row[name] 
        </span>"; // adauga features in array 
      }

      // facilities de la camere

      $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f 
        INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
        WHERE rfac.room_id = '$room_data[id]'"); // query pentru facilities

      $facilities_data = ""; // array pentru facilities
      while($fac_row = mysqli_fetch_assoc($fac_q)){ // loop prin facilities
        $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
        $fac_row[name] 
        </span>"; // adauga facilities in array 
      }

      // thumbnails de la camere

      $room_thumb = ROOMS_IMG_PATH."thumbnail-default.jpg"; // thumbnail default
      $thumb_q = mysqli_query($con, "SELECT * FROM `room_image` WHERE `room_id` = '$room_data[id]' AND `thumb` = '1'"); // query pentru thumbnails

      if(mysqli_num_rows($thumb_q) > 0){ // daca exista thumbnails in baza de date 
        $thumb_data = mysqli_fetch_assoc($thumb_q); // ia thumbnail-ul din baza de date
        $room_thumb = ROOMS_IMG_PATH.$thumb_data['image']; // ia thumbnail-ul din baza de date
      }
      
          $priceInUsd = $room_data['price']; // Assume this is your room price in USD
          $priceInRon = $priceInUsd * $usdToRonRate; // Conversion to RON
          $priceInRonFormatted = number_format($priceInRon, 0); // Format the price to 2 decimal places

      $book_btn = "";

      if(!$settings_r['shutdown']){
        $login = 0;
        if (isset($_SESSION['login']) && $_SESSION['login']  == true) { // Daca userul este logat
          $login=1;}
        $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Book Now</button>";}
      // afiseaza camerele

      echo <<< data

            <div class="col-lg-4 col-md-6 my-3">
            <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
              <img src="$room_thumb" class="card-img-top">
              <div class="card-body">
                <h5>$room_data[name]</h5>
                <h6 class="mb-4">{$priceInUsd} USD / {$priceInRonFormatted} RON per noapte</h6>
                <div class="features mb-4">
                  <h6 class="mb-1">Caracteristici</h6>
                  $features_data
                </div>
                <div class="facilities mb-4">
                  <h6 class="mb-1">Facilități</h6>
                  $facilities_data  
                </div>
                <div class="guests mb-4">
                  <h6 class="mb-1">Oaspeți</h6>
                  <span class="badge rounded-pill bg-light text-dark text-wrap">
                    $room_data[adult] Adulți
                  </span>
                  <span class="badge rounded-pill bg-light text-dark text-wrap">
                    $room_data[children] Copii
                  </span>
                </div>
                <div class="rating mb-4">
                  <h6 class="mb-1">Notă</h6>
                  <span class="badge rounded-pill bg-light">
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  <i class="bi bi-star-fill text-warning"></i>
                  </span>
                </div>
                <div class="d-flex justify-content-evenly mb-2">
                  $book_btn
                  <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">Detalii</a>
                </div>
              </div>
            </div>
          </div>
        data;
    }
  ?>
    <div class="col-lg-12 text-center mt-5">
      <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Mai multe camere >>></a>
    </div>
  </div>
</div>



<!-- Our Facilities-->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Facilitățile noastre</h2>

<div class="container">
  <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
    <?php
    $res = mysqli_query($con, "SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 6 ");
    $path = FACILITIES_IMG_PATH;

    while($row = mysqli_fetch_assoc($res)){
      echo <<<data
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="$path$row[icon]" width="60px">
        <h5 class="mt-3">$row[name]</h5>
      </div>
    data;
    }
  ?>

    <div class="col-lg-12 text-center mt-5">
      <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities >>></a>
    </div>
  </div>
</div>

<!-- Testimonials -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Testimoniale</h2>

<div class="container mt-5">
  <div class="swiper swiper-testimonials">
    <div class="swiper-wrapper mb-5">

      <div class="swiper-slide bg-white p-4">
        <div class="profile d-flex align-items-center mb-3">
          <img src="images/features/star.svg" width="30px">
          <h6 class="m-0 ms-2">Dankun Ronald</h6>
        </div>
        <p>
        "Am avut recent plăcerea de a sta la acest hotel de 5 stele situat pe malul mării, iar experiența a depășit toate așteptările mele. De la sosire, personalul atent și prietenos m-a făcut să mă simt ca un oaspete de onoare. Priveliștile uimitoare ale oceanului din camera mea erau de-a dreptul impresionante, iar plaja impecabilă, la doar câțiva pași, a oferit cadrul perfect pentru relaxare. Facilitățile luxoase, serviciile impecabile și opțiunile gourmet de restaurant au transformat șederea mea într-o experiență de neuitat. Acest hotel este un adevărat paradis pentru cei care caută o combinație perfectă de lux, confort și frumusețe naturală. Abia aștept să revin și să redescopăr acest colț de rai!"        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
      <div class="swiper-slide bg-white p-4">
        <div class="profile d-flex align-items-center mb-3">
          <img src="images/features/star.svg" width="30px">
          <h6 class="m-0 ms-2">Roberta Valor</h6>
        </div>
        <p>
        <b>"Un Sejur Excepțional la Harmony-Lux Hotel"</b>

        "Șederea noastră la Harmony-Lux Hotel a fost excepțională. Camerele erau impecabile, personalul a fost prietenos și atent, iar facilitățile erau de top. Ne-a încântat în mod special priveliștea uimitoare de pe balconul nostru. A fost o experiență cu adevărat memorabilă."
        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
      <div class="swiper-slide bg-white p-4">
        <div class="profile d-flex align-items-center mb-3">
          <img src="images/features/star.svg" width="30px">
          <h6 class="m-0 ms-2">Samor San</h6>
        </div>
        <p>
        <b>"Perfect pentru Călătorii de Afaceri"</b>
        "Deoarece călătoresc frecvent în interes de afaceri, Harmony-Lux Hotel a devenit alegerea mea preferată. Camerele executive bine dotate, Wi-Fi-ul de mare viteză și locația convenabilă fac din acest hotel opțiunea ideală pentru deplasările de serviciu. Profesionalismul și amabilitatea personalului sunt de neegalat."
        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
      <div class="swiper-slide bg-white p-4">
        <div class="profile d-flex align-items-center mb-3">
          <img src="images/features/star.svg" width="30px">
          <h6 class="m-0 ms-2">Kindley Kole</h6>
        </div>
        <p>
        <b>"Vacanță Grozavă în Familie</b>
        "Familia noastră a petrecut momente fantastice la Harmony-Lux Hotel. Copiii au fost încântați de piscină, iar noi am apreciat spațiul generos al suitei familiale. Personalul a fost extrem de amabil și ne-am simțit ca acasă. A fost vacanța perfectă!"
        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
      <div class="swiper-slide bg-white p-4">
        <div class="profile d-flex align-items-center mb-3">
          <img src="images/features/star.svg" width="30px">
          <h6 class="m-0 ms-2">Luman Khan</h6>
        </div>
        <p>
        <b>"Escapadă Romantică la Harmony-Lux Hotel"</b>
        "Am ales Harmony-Lux Hotel pentru aniversarea noastră și a fost un adevărat paradis romantic. Cina la lumina lumânărilor, petalele de trandafiri din cameră și priveliștea superbă a apusului din suita noastră cu vedere la ocean au făcut ca această sărbătoare să fie de neuitat."
        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
      <div class="swiper-slide bg-white p-4">
        <div class="profile d-flex align-items-center mb-3">
          <img src="images/features/star.svg" width="30px">
          <h6 class="m-0 ms-2">Maria Beograd</h6>
        </div>
        <p>
        <b>"Nuntă de Vis la Harmony-Lux Hotel"</b>
        "Nunta noastră la Harmony-Lux Hotel a fost un vis devenit realitate. Echipa de organizare a evenimentului a depășit toate așteptările pentru a face din această zi una perfectă. Eleganța sălii de bal, serviciile de catering rafinate și atenția la detalii au transformat totul într-o amintire de neuitat."
        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>

<!-- Reach Us -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Contactați-ne</h2>

<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
      <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe']?>" height="450" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="col-lg-4 col-md-4">
      <div class="bg-white p-4 rounded mb-4">
        <h5>Call Us</h5>
        <a href="tel: +<?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1']?>
        </a>
        <br>
        <?php  // Daca exista un numar de telefon 2, afiseaza-l 
          if($contact_r['pn2'] != ""){
            echo <<<data
                <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
              </a>
            data;
          }?>

      </div>
      <div class="bg-white p-4 rounded mb-4">
        <h5>Urmărește-ne</h5>
        <?php // Daca exista un link de facebook, afiseaza-l
          if($contact_r['fb'] != ""){
            echo <<<data
                <a href="$contact_r[fb]" class="d-inline-block mb-3">
                <span class="badge bg-light text-dark fs-6 p-2">
                  <i class="bi bi-facebook"></i>Facebook
                </span>    
              </a>
            data;
          }?>
          <br>
        <a href="<?php echo $contact_r['insta']?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-instagram"></i>Instagram
          </span>    
        </a>
        <br>
        <a href="<?php echo $contact_r['twitter']?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-twitter-x"></i>Twitter-X
          </span>    
        </a>
      </div>
  </div>
</div>



<!-- Modal Resetare Parola -->

<div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="recovery-form">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center">
        <i class="bi bi-shield-lock fs-3 me-2"></i>Setează noua parolă
        </h5>
      </div>
      <div class="modal-body">
        <div class="mb-4">
            <label class="form-label">Parola nouă</label>
            <input type="password" name = "pass" required class="form-control shadow-none">
            <input type="hidden" name="email">
            <input type="hidden" name="token">
        </div>
        <div class="mb-2 text-end">
            <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">
              Anulează
            </button>
            <button type="submit" class="btn btn-dark shadow-none">Trimite</button>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>

<?php require('inc/footer.php'); ?>

<?php 

if(isset($_GET['account_recovery'])){
    $data = filteration($_POST);
    // Check if both 'email' and 'token' keys are set in $data
    if(isset($data['email']) && isset($data['token'])) {
        $t_date = date('Y-m-d');

        $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1", [$data['email'], $data['token'], $t_date], "sss");

        if(mysqli_num_rows($query) == 1) {
            echo <<<showModal
            <script>
            var myModal = document.getElementById('recoveryModal');

            myModal.querySelector('input[name="email"]').value = "{$data['email']}";
            myModal.querySelector('input[name="token"]').value = "{$data['token']}";

            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
            modal.show();
            </script>
showModal;
        } else {
            alert("error", "Invalid Token");
        }
    } else {
        // Handle the scenario where 'email' or 'token' is not set
        alert("error", "Missing email or token");
    }
}

?>

    
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay:{
        delay: 3500,
        disableOnInteraction: false,
      }
    });
    
    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints:{
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });


    //recuperare parola-cont
    let recovery_form = document.getElementById('recovery-form'); // Formular de recuperare parola

      recovery_form.addEventListener('submit', (e)=>{
        e.preventDefault();

        let data = new FormData(); // Instantiere obiect FormData pentru trimitere date prin POST

        data.append('email', forgot_form.elements['email'].value);
        data.append('token', forgot_form.elements['token'].value);
        data.append('pass', forgot_form.elements['pass'].value);
        data.append('recovery_pass','');

        var myModal = document.getElementById('recoveryModal'); 
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide(); // Se ascunde dupa trimitere
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function(){
          if (this.responseText == 'failed'){
            alert('error', 'Password reset failed');
          }
          else{
            alert('success', 'Password reset successfull');
            recovery_form.reset(); // Resetare formular
          }
        }
        xhr.send(data);
      });


  </script>

</body>
</html>