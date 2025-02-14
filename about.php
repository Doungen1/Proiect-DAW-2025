<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/> 
        <?php require('inc/links.php'); ?>
        <title><?php echo $settings_r['site_title'] ?> - ABOUT</title> <!-- Titlu din baza de date -->

        <style>
            .box{
                border-top-color: var(--teal) !important; /* Culoare din baza de date */
            }
        </style>

    </head>
    <body class="bg-light">

<?php require('inc/header.php'); ?>

<div class="my-5 px-4"> <!-- Padding top/bottom -->
  <h2 class="fw-bold h-font text-center">Despre noi</h2> <!-- Font + bold -->
  <div class="h-line bg-dark"></div> <!-- Linie -->
  <p class="text-center mt-3"> <!-- Text center + margin top -->
  Bun venit la Harmony-Lux Hotel, unde luxul întâlnește liniștea.
Situat în inima insulei Ko Samui, hotelul nostru este un simbol al excelenței încă din 1990. Vă invităm să descoperiți povestea noastră și experiențele excepționale care vă așteaptă în timpul sejurului dumneavoastră.

O moștenire a excelenței
De la înființarea sa în 1990, Harmony-Lux Hotel a devenit un reper al ospitalității de top. Istoria noastră bogată și angajamentul față de servicii excepționale ne-au adus reputația de a fi unul dintre cele mai apreciate hoteluri din regiune.

Misiunea noastră
La Harmony-Lux Hotel, misiunea noastră este simplă: să oferim oaspeților noștri un refugiu armonios și luxos, unde fiecare moment este prețuit. Ne dedicăm depășirii așteptărilor dumneavoastră și asigurării unui sejur cu adevărat de neuitat.
  <br> 
  </p>
</div>

<div class="container">
    <div class="row justify-content-between align-items-center"> <!-- Aliniere elemente -->
        <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2"> <!-- Ordine elemente -->
            <h3 class="mb-3"> <!-- Margin bottom -->
             Mesaj din partea Managerilor Noștri
            </h3>
            <p>La Harmony-Lux Hotel, ne mândrim cu faptul că oferim oaspeților noștri experiențe de neuitat. În spatele fiecărui moment special se află o echipă dedicată de profesioniști care lucrează neobosit pentru a transforma fiecare sejur într-o amintire prețioasă.

          Sub conducerea managerului nostru general, Gerald Bloom, echipa noastră este animată de pasiunea pentru ospitalitate, creând o atmosferă caldă și primitoare pentru fiecare oaspete. Ne dorim ca fiecare detaliu să reflecte angajamentul nostru față de excelență și confort, astfel încât să vă simțiți ca acasă, oriunde v-ați afla în cadrul hotelului nostru.

            Vă mulțumim că ați ales Harmony-Lux Hotel – locul unde ospitalitatea întâlnește rafinamentul.</p>
        </div>
        <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1"> <!-- Ordine elemente -->
            <img src="images/about/R (1).jpg" class="w-100">
        </div>
    </div>
</div>

<div class="container mt-5"> <!-- Margin top -->
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4 px-4"> <!-- Padding left/right -->
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box"> <!-- Border top + border color -->
                <img src="images/about/rooms.svg" width="70px">
                <h4 class="mt-3">100+ Camere</h4> 
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="images/about/customers.svg" width="70px">
                <h4 class="mt-3">2000+ Clienți</h4>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="images/about/rating.svg" width="70px">
                <h4 class="mt-3">1000+ Recenzii</h4>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="images/about/staffs.svg" width="70px">
                <h4 class="mt-3">150+ Personal</h4>
            </div>
        </div>
    </div>
</div>

<h3 class="my-5 fw-bold h-font text-center">Echipa noastră</h3>

<div class="container px-4">
     <!-- Swiper -->
  <div class="swiper mySwiper">
    <div class="swiper-wrapper mb-5">
      <?php
        $about_r= selectAll('team_details'); // Selectare toate randurile din baza de date din tabelul team_details 
        $path = ABOUT_IMG_PATH; // Path to about images
        while($row = mysqli_fetch_assoc($about_r)){ // Extrage randurile din baza de date ca array asociativ
          echo <<<data
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
              <img src="$path$row[picture]" class="w-100" style="height: auto;">
              <h5 class="mt-2">$row[name]</h5>
            </div>
          data; // Afisare randuri in swiper 
        }
      ?>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>

<?php require('inc/footer.php'); ?>


  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
    slidesPerView: 3,
    spaceBetween: 40,
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
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
  </script>


</body>
</html>