<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/common.css">


<?php

  session_start();

  require('admin/inc/essentials.php');
  require('admin/inc/db_config.php');

  $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?"; // Query pentru selectare date de contact din baza de date
  $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?"; // Query pentru selectare setari din baza de date
  $values = [1]; // Valori pentru query (din formular)
  $contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i')); // Extrage randul din baza de date ca array asociativ
  $settings_r = mysqli_fetch_assoc(select($settings_q, $values, 'i')); // Extrage randul din baza de date ca array asociativ 
  
  if($settings_r['shutdown']){
    echo <<<alertbar
      <div class='bg-danger text-center p-2 fw-bold'>
        Site-ul este momentan închis. Vă rugăm să reveniți mai târziu.
      </div>
    alertbar;
  }
  
  //print_r($contact_r);
?>
