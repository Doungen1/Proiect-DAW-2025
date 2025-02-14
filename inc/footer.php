<div class="container-fluid bg-white mt-5">
  <div class="row">
    <div class="col-lg-4 p-4">
      <h3 class="h-font fw-bold fs-3"><?php echo $settings_r['site_title'] // Afisare titlu site din baza de date 
      ?></h3>
      <p>
        <?php echo $settings_r['site_about'] // Afisare descriere site din baza de date 
        ?> 
      "Răsfață-te cu rafinamentul absolut la Harmony Luxe Resort & Spa, unde luxul se îmbină perfect cu serenitatea. Resortul nostru este un refugiu pentru cei care caută un echilibru desăvârșit între opulență și relaxare. Lasă-te purtat de îmbrățișarea liniștitoare a spa-ului nostru, unde terapeuții noștri dedicați îți vor satisface fiecare nevoie. Cu o gamă variată de cazări meticulos amenajate, fiecare oferind un sanctuar al confortului, și cu peisaje pitorești ce creează un decor natural de o frumusețe aparte, șederea ta la noi va fi o experiență de pură încântare. Descoperă armonia perfectă dintre lux și liniște la Harmony Luxe Resort & Spa."      </p>
    </div>
    <div class="col-lg-4 p-4">
      <h5 class="mb-3">Link-uri</h5>
      <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Acasă</a><br>
      <a href="rooms.php" class="d-inline-block mb-2 text-dark text-decoration-none">Camere</a><br>
      <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilități</a><br>
      <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact</a><br>
      <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">Despre</a><br>
    </div>
    <div class="col-lg-4 p-4">
      <h5 class="mb-3">Urmărește-ne</h5>
      <?php
        if ($contact_r['fb'] != ""){ // Daca exista link de facebook in baza de date 
          echo <<<data
          <a href="$contact_r[fb]" class="d-inline-block text-dark text-decoration-none mb-2"><i class="bi bi-facebook fs-3 me-3"></i> Facebook</a><br>
          data;} // Afisare link de facebook in footer
      ?>
      <a href="<?php echo $contact_r['insta']?>" class="d-inline-block text-dark text-decoration-none mb-2"><i class="bi bi-instagram fs-3 me-3"></i> Instagram</a><br>
      <a href="<?php echo $contact_r['twitter']?>" class="d-inline-block text-dark text-decoration-none"><i class="bi bi-twitter-x fs-3 me-3"></i> Twitter-X</a><br>
    </div>
  </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Proiectat și Dezvoltat de Harmony CRC</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

  function alert(type, msg, position='body'){// Functie pentru afisare alerta
          let bs_class = (type == 'success') ? "alert-success" : "alert-danger";
          let element = document.createElement('div');
          element.innerHTML = `
              <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                  <strong class="me-3"> ${msg}</strong>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `;
          if(position=='body'){
            document.body.append(element);
            element.classList.add('custom-alert');
          }
          else{
            document.getElementById(position).appendChild(element);
          }
          setTimeout(remAlert,3000);
  };

  function remAlert(){ // Functie pentru stergere alerta
      document.getElementsByClassName('alert')[0].remove();
    };

  function setActive(){// Functie pentru setare active pe navbar
    let navbar = document.getElementById('nav-bar');
    let a_tags = navbar.getElementsByTagName('a');

    for(i=0; i<a_tags.length; i++){
      let file = a_tags[i].href.split('/').pop();
      let file_name = file.split('.')[0];

      if (document.location.href.indexOf(file_name)>=0){
        a_tags[i].classList.add('active');
      }
    }
  };

  let register_form = document.getElementById('register-form'); // Formular de register

  register_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData(); // Instantiere obiect FormData pentru trimitere date prin POST

    data.append('name', register_form.elements['name'].value);
    data.append('email', register_form.elements['email'].value);
    data.append('phonenum', register_form.elements['phonenum'].value);
    data.append('address', register_form.elements['address'].value);
    data.append('pincode', register_form.elements['pincode'].value);
    data.append('dob', register_form.elements['dob'].value);
    data.append('pass', register_form.elements['pass'].value);
    data.append('cpass', register_form.elements['cpass'].value);
    data.append('profile', register_form.elements['profile'].files[0]);
    data.append('register','');

    var myModal = document.getElementById('registerModal'); 
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function(){
      if(this.responseText == 'pass_mismatch'){
        alert('error', 'Password and Confirm Password does not match');
      }
      else if(this.responseText == 'email_already'){
        alert('error', 'Email already exists');
      }
      else if(this.responseText == 'phone_already'){
        alert('error', 'Phone number already exists');
      }
      else if(this.responseText == 'Invalid Image Format'){
        alert('error', 'Invalid Image Format');
      }
      else if(this.responseText == 'Image cannot be uploaded'){
        alert('error', 'Image cannot be uploaded');
      }
      else if(this.responseText == 'mail_error'){
        alert('error', 'Mail cannot be sent');
      }
      else if(this.responseText == 'failed'){
        alert('error', 'Registration failed');
      }
      else{
        alert('success', 'Registration successful. Please check your email for verification link');
        register_form.reset(); // Resetare formular
      }
    }
    xhr.send(data);
  });

  let login_form = document.getElementById('login-form'); // Formular de login

  login_form.addEventListener('submit', (e)=>{
    console.log('login');
    e.preventDefault();

    let data = new FormData(); // Instantiere obiect FormData pentru trimitere date prin POST

    console.log(login_form.elements['email_mob'].value);
    console.log(login_form.elements['pass'].value);


    data.append('email_mob', login_form.elements['email_mob'].value);
    data.append('pass', login_form.elements['pass'].value);
    data.append('login','');

    var myModal = document.getElementById('loginModal');// Modal de login
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide(); // Se ascunde dupa logare
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function(){
      console.log(this.responseText);
      if(this.responseText == 'user_not_exist'){
        alert('error', 'User does not exist');
      }
      else if (this.responseText == 'user_not_verified'){
        alert('error', 'User not verified');
      }
      else if (this.responseText == 'user_disabled'){
        alert('error', 'User disabled');
      }
      else if (this.responseText == 'pass_mismatch'){
        alert('error', 'Password mismatch');
      }
      else{
        let fileurl = window.location.href.split('/').pop().split('?').shift();
        if(fileurl =='room_details.php'){
          window.location=window.location.href;
        }
        else{
        window.location = window.location.pathname;
      }
    } 
  }
    xhr.send(data);
  });

  let forgot_form = document.getElementById('forgot-form'); // Formular de forgot password

  forgot_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData(); // Instantiere obiect FormData pentru trimitere date prin POST

    data.append('email', forgot_form.elements['email'].value);
    data.append('forgot_pass','');

    var myModal = document.getElementById('forgotModal'); // Modal de forgot password
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide(); // Se ascunde dupa trimitere
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function(){
      if(this.responseText == 'user_not_exist'){
        alert('error', 'User does not exist');
      }
      else if (this.responseText == 'user_not_verified'){
        alert('error', 'User not verified');
      }
      else if (this.responseText == 'user_disabled'){
        alert('error', 'User disabled');
      }
      else if (this.responseText == 'mail_error'){
        alert('error', 'Mail cannot be sent');
      }
      else{
        alert('success', 'Password reset link sent to your email');
        forgot_form.reset(); // Resetare formular
      }
    }
    xhr.send(data);
  });

  function checkLoginToBook(status,room_id){
    if(status){
      window.location.href='confirm_booking.php?id='+room_id;
    }
    else{
      alert('error', 'Please login to book');
    }
  }
  setActive(); // Setare active pe navbar
</script>