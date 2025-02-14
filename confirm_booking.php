<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> CONFIRM BOOKING</title>
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
  if(!isset($_GET['id']) || $settings_r['shutdown'] == true) { 
    redirect('rooms.php');
  } else if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
    redirect('rooms.php');
  }

  $data = filteration($_GET);

  $room_res = select("SELECT * FROM rooms WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii'); 

  if(mysqli_num_rows($room_res) == 0){
    redirect('rooms.php');
  }

  $room_data = mysqli_fetch_assoc($room_res); 

  $_SESSION['room'] = [
    "id" => $room_data['id'],
    "name" => $room_data['name'],
    "price" => $room_data['price'],
    "payment" => null,
    "available" => false,
  ];

  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  if (!isset($_SESSION['uID'])) {
      echo 'User ID is not set in the session.';
      exit;
  }

  $user_res = select(
      "SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",
      [$_SESSION['uID']],
      "i"
  );

  if (mysqli_num_rows($user_res) > 0) {
      $user_data = mysqli_fetch_assoc($user_res);
  } else {
      echo 'No user found with the given ID.';
  }
?>

<div class="container">
  <div class="row">
    <div class="col-12 my-5 mb-4 px-4">
      <h2 class="fw-bold">Confirmare Rezervare</h2>
      <div style="font-size:14px;">
        <a href="index.php" class="text-decoration-none text-dark">Acasă</a>
        <i class="fas fa-chevron-right"></i>
        <span class="text-secondary"> > </span>
        <a href="rooms.php" class="text-decoration-none text-dark">Camere</a>
        <i class="fas fa-chevron-right"></i>
        <span class="text-secondary"> > </span>
        <a href="#" class="text-decoration-none text-dark">Confirmat</a>
        <i class="fas fa-chevron-right"></i>
      </div>
    </div>

    <div class="col-lg-7 col-md-12 px-4">
      <?php
          $room_thumb = ROOMS_IMG_PATH."thumbnail-default.jpg"; 
          $thumb_q = mysqli_query($con, "SELECT * FROM `room_image` WHERE `room_id` = '$room_data[id]' AND `thumb` = '1'"); 
    
          if(mysqli_num_rows($thumb_q) > 0){ 
            $thumb_data = mysqli_fetch_assoc($thumb_q); 
            $room_thumb = ROOMS_IMG_PATH.$thumb_data['image']; 
          }
    
          echo<<<data
            <div class="card p-3 shadow-sm rounded">
              <img src="$room_thumb" class="img-fluid rounded mb-3">
              <h5>$room_data[name]</h5>
              <h6>$$room_data[price] per noapte</h6>
              </div>
          data;
      ?>
    </div>

    <div class="col-lg-5 col-md-12 px-4">
      <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body">
            <form action="#" id="booking_form">
              <h6 class="mb-3">Detalii Rezervare</h6>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label mb-1">Nume</label>
                  <input name="name" type="text" value="<?php echo $user_data['name']?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label mb-1">Număr Telefon</label>
                  <input name="phonenum" type="text" value="<?php echo $user_data['phonenum']?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-12 mb-3">
                  <label class="form-label mb-1">Adresă</label>
                  <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address']?></textarea>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label">Check-in</label>
                  <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none" required>
            </div>
            <div class="col-md-6 mb-3">
                  <label class="form-label">Check-out</label>
                  <input name="checkout" onchange="check_availability()" type="date" class="form-control shadow-none" required>
            </div>
            <div class="col-12">
              <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                  <span class="visually-hidden">Încărcare...</span>
              </div>
                <h6 class="mb-3 text-danger d-none" id="pay_info">Check-in și Check-out!</h6>
                <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none" onclick="submitBooking()">Plătește</button>
            </div>
          </div>
          </form>
      </div>
    </div>
  </div>
</div>

<?php require('inc/footer.php'); ?>

<script>
    let booking_form = document.getElementById('booking_form');
    let info_loader = document.getElementById('info_loader');
    let pay_info = document.getElementById('pay_info');

    function check_availability() {
    let booking_form = document.getElementById('booking_form');
    let checkin_val = booking_form.elements['checkin'].value;
    let checkout_val = booking_form.elements['checkout'].value;
    let info_loader = document.getElementById('info_loader');
    let pay_info = document.getElementById('pay_info');

    booking_form.elements['pay_now'].setAttribute('disabled', true);

    if (checkin_val !== '' && checkout_val !== '') {
        pay_info.classList.add('d-none');
        info_loader.classList.remove('d-none');

        let data = new FormData();
        data.append('check_availability', '');
        data.append('check_in', checkin_val);
        data.append('check_out', checkout_val);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/confirm_booking.php", true);

        xhr.onload = function () {
            let data = JSON.parse(this.responseText);
            if (data.status == 'check_in_out_equal') {
                pay_info.innerHTML = "You cannot check-out on the same day";
            } else if (data.status == 'check_out_earlier') {
                pay_info.innerHTML = "You cannot check-out earlier";
            } else if (data.status == 'check_in_earlier') {
                pay_info.innerHTML = "Check in earlier";
            } else if (data.status == 'unavailable') {
                pay_info.innerHTML = "Room not available";
            } else {
                pay_info.innerHTML = "No. of days: " + data.days + "<br> Total Amount to Pay: $" + data.payment;
                pay_info.classList.replace('text-danger', 'text-dark');
                booking_form.elements['pay_now'].removeAttribute('disabled');
            }
            pay_info.classList.remove('d-none');
            info_loader.classList.add('d-none');
        };
        xhr.send(data);
    }
}

function submitBooking() {
    console.log("Submitting booking...");

    let form = new FormData(document.getElementById('booking_form'));
    form.append('pay_now', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/confirm_booking.php", true);

    xhr.onload = function () {
        console.log("Response received:", this.responseText);
        let data = JSON.parse(this.responseText);
        if (data.status === 'success') {
            pay_info.innerHTML = data.message;
            pay_info.classList.replace('text-danger', 'text-dark');
        } else {
            pay_info.innerHTML = "Error: " + data.message;
            pay_info.classList.replace('text-dark', 'text-danger');
        }
        pay_info.classList.remove('d-none');
        info_loader.classList.add('d-none');
    };

    xhr.onerror = function () {
        console.log("Request failed");
    };

    xhr.send(form);
}


</script>


</body>
</html>
