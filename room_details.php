<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require('inc/links.php'); ?>
        <title><?php echo $settings_r['site_title']?> - Room Details</title>
        <style>
          .pop:hover{
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
          }
        </style>
    </head>
    <body class="bg-light">

<?php require('inc/header.php'); ?>

<?php
  if(!isset($_GET['id'])){ // daca nu exista id-ul camerei, redirecteaza catre rooms.php
    redirect('rooms.php');
  }

  $data = filteration($_GET);

  $room_res = select("SELECT * FROM rooms WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii'); // selecteaza toate camerele care nu sunt scoase din uz

  if(mysqli_num_rows($room_res) == 0){ // daca nu exista camera cu id-ul respectiv, redirecteaza catre rooms.php
    redirect('rooms.php');
  } // daca nu exista camera cu id-ul respectiv, redirecteaza catre rooms.php

  $room_data = mysqli_fetch_assoc($room_res); // ia datele camerei din baza de date

?>



<div class="container">
  <div class="row">

    <div class="col-12 my-5 mb-4 px-4">
      <h2 class="fw-bold"><?php echo $room_data['name']?></h2>
      <div style="font-size:14px;">
        <a href="index.php" class="text-decoration-none text-dark">Acasă</a>
        <i class="fas fa-chevron-right"></i>
        <span class="text-secondary"> > </span>
        <a href="rooms.php" class="text-decoration-none text-dark">Camere</a>
        <i class="fas fa-chevron-right"></i>
      </div>
    </div>

  <div class="col-lg-7 col-md-12 px-4">
    <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

        <?php
                // thumbnails de la camere

          $room_img = ROOMS_IMG_PATH."thumbnail-default.jpg"; // thumbnail default
          $img_q = mysqli_query($con, "SELECT * FROM `room_image` WHERE `room_id` = '$room_data[id]'"); // query pentru thumbnails

          if(mysqli_num_rows($img_q) > 0){ // daca exista thumbnails in baza de date 
            $active_class = 'active';// active class pentru prima imagine 

            while($img_res = mysqli_fetch_assoc($img_q)){; // ia thumbnail-ul din baza de date
        
            echo "<div class='carousel-item $active_class'>
                    <img src='".ROOMS_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
              </div>"; // afiseaza thumbnails
              $active_class =''; // scoate active class pentru urmatoarele imagini
          }
        }
          else{
            echo "<div class='carousel-item active'>
            <img src='$room_img' class='d-block w-100'>
              </div>"; // thumbnail default
          }
      ?>
    </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Anteriorul</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Următorul</span>
        </button>
      </div>
  </div>

  <div class="col-lg-5 col-md-12 px-4">
    <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <?php
            echo<<<price
              <h4>$$room_data[price] per noapte</h4>
            price;  
            echo<<<rating
              <div class="mb-3">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </div>
            rating;

            $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f  
            INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
            WHERE rfea.room_id = '$room_data[id]'"); // query pentru features cu inner join la room_features si features 
          
          $features_data = ""; // array pentru features
          while($fea_row = mysqli_fetch_assoc($fea_q)){ // loop prin features
            $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
            $fea_row[name] 
            </span>"; // adauga features in array 
          }

          // facilities de la camere
            echo<<<features
              <div class="mb-3">
                <h6 class="mb-1">Caracteristici</h6>
                $features_data
              </div>
            features;

            $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
            INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
            WHERE rfac.room_id = '$room_data[id]'"); // query pentru facilities

          $facilities_data = ""; // array pentru facilities

          while($fac_row = mysqli_fetch_assoc($fac_q)){ // loop prin facilities
            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
            $fac_row[name] 
            </span>"; // adauga facilities in array 
          }

          echo<<<facilities
            <div class="mb-3">
              <h6 class="mb-1">Facilities</h6>
              $facilities_data
            </div>
          facilities; // afiseaza facilities

          echo<<<guests
            <div class="mb-3">
              <h6 class="mb-1">Guests</h6>
              <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">
                $room_data[adult] Adulți
              </span>
              <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">
                $room_data[children] Copii
              </span>
            </div>
          guests;

          echo<<<area
            <div class="mb-3">
              <h6 class="mb-1">Spațiu</h6>
              <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">
                $room_data[area] m<sup>2</sup>
              </span>
            </div>
          area;

          if(!$settings_r['shutdown']){
            $login = 0;
            if (isset($_SESSION['login']) && $_SESSION['login']  == true) { // Daca userul este logat
              $login=1;}
            echo<<<book
            <button onclick='checkLoginToBook($login,$room_data[id])' class='btn w-100 text-white custom-bg shadow-none mb-4' >Rezervă Acum</button>
            book;
          }

          ?>
        </div>
        </div>
    </div>

    <div class="col-12 mt-4 px-4">
      <div class="mb-5">
      <h5>  
        Description
      </h5>
      <p>
        <?php echo $room_data['description']?>
      </p>
      </div>
        <div>
          <h5 class="mb-3">
           Reviews & Ratings
          </h5>
          <div>
            <div class="d-flex align-items-center mb-2">
              <img src="images/features/star.svg" width="30px">
              <h6 class="m-0 ms-2">Random User</h6>
            </div>
            <p>
              200 characters  max
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
        </div>
    </div>
  </div>


    </div>
  </div>
</div>



<?php require('inc/footer.php'); ?>


</body>
</html>