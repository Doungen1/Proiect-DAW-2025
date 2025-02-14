<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require('inc/links.php'); ?>
        <title><?php echo $settings_r['site_title'] ?> - CONTACT</title>
        <style>
          .pop:hover{
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
          }
        </style>
      <!--- Recaptcha -->
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    </head>
    <body class="bg-light">

<?php require('inc/header.php'); ?>


<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">Contact</h2>
  <div class="h-line bg-dark"></div>
  <p class="text-center mt-3">
  Contactați-ne la Harmony Luxe Resort & Spa
<br>
Acest titlu transmite ideea că vizitatorii site-ului nostru pot lua legătura cu ușurință cu resortul și spa-ul nostru.
<br>
Îi încurajează să ne contacteze pentru orice întrebări sau solicitări.
  </p>
</div>


<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4">
      <iframe class="w-100 rounded rounded mb-4" height="320px" src="<?php echo $contact_r['iframe']?> " height="450" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      
        <h5>Adresă</h5>  <!-- Font + bold -->
      <a href="<?php echo $contact_r['gmap']?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
        <i class="bi bi-geo-alt-fill"></i><?php echo $contact_r['address']?>
        XYZ, Koh Samui, Thailand 
      </a>
      <h5 class="mt-4">Call Us</h5>
        <a href="tel: +<?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i>+<?php echo $contact_r['pn1']?>
        </a>
        <br>
        <?php  // Daca exista un numar de telefon 2, afiseaza-l 
          if($contact_r['pn2'] != ""){
            echo <<< data
              <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i>+$contact_r[pn2]
              </a>
            data;}
        ?>

        <h5 class="mt-4">Email</h5>
        <a href="mailto: <?php echo $contact_r['email']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
        <i class="bi bi-envelope-fill"></i><?php echo $contact_r['email']?>
        </a>

        <h5 class="mt-4">Urmărește</h5>
        <?php // Daca exista un link de facebook, afiseaza-l 
          if ($contact_r['fb'] != ""){
            echo <<<data
              <a href="$contact_r[fb]" class="d-inline-block text-dark fs-5 me-2">
                <span><i class="bi bi-facebook me-1"></i></span>
              </a>
            data;}
        ?>
        <a href="<?php echo $contact_r['insta']?>" class="d-inline-block text-dark fs-5 me-2">
          <span><i class="bi bi-instagram me-1"></i></span>
        </a>

        <a href="<?php echo $contact_r['twitter']?>" class="d-inline-block text-dark fs-5">
          <span><i class="bi bi-twitter-x me-1"></i></span>
        </a>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 px-4">
      <div class="bg-white rounded shadow p-4">
        <form method="POST">
          <h5>Nu ezitați să ne scrieți</h5>
          <div class="mb-3">
            <label class="form-label" style="font-weight: 500;">Nume</label>
            <input name="name" required type="text" class="form-control shadow-none"
            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight: 500;">Email</label>
            <input name="email" required type="email" class="form-control shadow-none"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight: 500;">Subiect</label>
            <input name="subject" required type="text" class="form-control shadow-none"
            value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight: 500;">Mesaj</label>
            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;">
            <?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : ''; ?>
            </textarea>
          </div>
          <div class="mb-3">
            <div class="g-recaptcha" data-sitekey="6LdjCFQpAAAAAKJqyCmg8nadH6Gm3XHWRDMtMJTt"></div>
          <button type="submit" name="send" class="btn text-white custom-bg mt-3">Trimite</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
  if(isset($_POST['send'])){ // Daca s-a apasat butonul de send 
    $frm_data = filteration($_POST);

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ // Daca s-a bifat recaptcha
      $secret = '6LdjCFQpAAAAADPQCn2v7aCPWyjqmM4GjJ4WJnd1'; // Secret-ul recaptcha
      $recaptcha_response = $_POST['g-recaptcha-response']; // Raspunsul recaptcha
      $verifyURL = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha_response; // URL-ul de verificare a recaptcha

      $verifyResponse = file_get_contents($verifyURL); // Verifica raspunsul recaptcha
      $responseData = json_decode($verifyResponse); // Decodeaza raspunsul recaptcha
      
      error_log("reCAPTCHA verify response: " . $verifyResponse);
        if (isset($responseData->{"error-codes"})) {
            error_log("reCAPTCHA error codes: " . implode(', ', $responseData->{"error-codes"}));
        }
        
    if($responseData->success){
      $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)"; // Query pentru inserare in baza de date 
      $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']]; // Valori pentru query (din formular) 
  
      $res = insert($q, $values, 'ssss'); // Rulare query cu valori si tipuri de date (ssss = string, string, string, string)
      if($res == 1){ // Daca s-a inserat cu succes in baza de date
        alert('success','Mesajul tău a fost transmis cu succes. Vă vom contacta în cel mai scurt timp posibil.', 'contact');
      } 
      else{
        alert('error', 'Something went wrong. Please try again later.', 'danger');
      }
    }
    else{
      alert('error', 'Please verify that you are not a robot', 'danger');
    }
  }
  else{
    alert('error', 'Please complete the reCAPTCHA challenge.', 'danger');
}
}
?>

<?php require('inc/footer.php'); ?>


</body>
</html>