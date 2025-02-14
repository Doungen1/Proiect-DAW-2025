<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
require("../inc/sendgrid/sendgrid-php.php");
date_default_timezone_set('Europe/Bucharest');

function send_mail($uemail, $token, $type){ // Functie pentru trimitere mail 

    if($type == "email_confirmation"){
        $page = "email_confirm.php";
        $subject = "Account Verification Link";
        $content = "Click the link to confirm your email";
    }
    else{
        $page = "index.php";
        $subject = "Password Reset Link";
        $content = "Click the link to reset your password";
    }
 
    $email = new \SendGrid\Mail\Mail();  // Instantiere clasa SendGrid\Mail\Mail
    $email->setFrom(SENDGRID_EMAIL, SENDGRID_NAME); // Setare email si nume expeditor
    $email->setSubject($subject); // Setare subiect

    $email->addTo($uemail); // Setare email si nume destinatar
    
    $email->addContent( // Setare continut mail
        "text/html",
        "Click the link to $content: <br>
        <a href='".SITE_URL."$page?$type&email=$uemail&token=$token"."'>
        Click Here
        </a>"
    );

    $sendgrid = new \SendGrid(SENDGRID_API_KEY); // Instantiere clasa SendGrid cu API KEY
        try{
            $sendgrid->send($email);
            return 1;
            }
        catch(Exception $e){
            return 0;
    }
}

if(isset($_POST['register'])){ // Daca s-a apasat butonul de register
    $data = filteration($_POST); // Filtrare date

    //match password and cpassword

    if($data['pass'] != $data['cpass']){ // Daca parola si confirmarea parolei nu coincid
        echo 'pass_mismatch';
        exit;
    }

    //check user exists or not

    $u_exist = select( // Query pentru verificare existenta user in baza de date dupa email sau numar de telefon
        "SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",
        [$data['email'], $data['phonenum']],
        "ss"
    );

    if(mysqli_num_rows($u_exist)!= 0){ // Daca exista un rand in baza de date cu emailul sau numarul de telefon introdus
        $u_exist_fetch = mysqli_fetch_assoc($u_exist); // Extrage randul din baza de date ca array asociativ
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already'; // Daca emailul exista deja in baza de date, afiseaza email_already, altfel afiseaza phone_already
        exit; // Iesire din script
    }

    //upload profile image

    $img = uploadUserImage($_FILES['profile']); // Functie pentru upload imagine de profil

    if($img == 'Invalid Image Format'){
        echo 'Invalid Image Format';
        exit;
    }
    else if($img == 'Image cannot be uploaded'){
        echo 'Image cannot be uploaded';
        exit;
    }

    //send confirmation link to email
    $token = bin2hex(random_bytes(16)); // Generare token pentru confirmare email

    if(!send_mail($data['email'], $token, "email_confirmation")){ // Daca mailul nu a fost trimis cu succes
        echo 'mail_error';
        exit;
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT); // Criptare parola cu BCRYPT - Blowfish Algorithm
    $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`,
        `profile`, `password`, `token`) VALUES (?,?,?,?,?,?,?,?,?)"; // Query pentru inserare date in baza de date

    $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $data['pincode'],$data['dob'],
    $img, $enc_pass, $token]; // Valori pentru query (din formular)

    if (insert($query, $values, "sssssssss")) { // Rulare query cu valori si tipuri de date (sssssssss = string, string, string, string, string, string, string, string, string)
        echo 1;
    } else {
        echo 'failed';
    }
}

if(isset($_POST['login'])){ // Daca s-a apasat butonul de login
        $data = filteration($_POST); // Filtrare date
    
        $u_exist = select( // Query pentru verificare existenta user in baza de date dupa email sau numar de telefon
            "SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",
            [$data['email_mob'], $data['email_mob']],
            "ss" 
        );
    
        if(mysqli_num_rows($u_exist) == 0){ // Daca nu exista un rand in baza de date cu emailul sau numarul de telefon introdus
            echo 'user_not_exist';
        }
        else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
             // Extrage randul din baza de date ca array asociativ
        
            if($u_fetch['is_verified'] == 0){ // Daca userul nu este verificat
                echo 'user_not_verified';
    
            }
            else if($u_fetch['status'] == 0){ // Daca userul este dezactivat
                echo 'user_disabled';
    
            }
            else 
            {
                if(!password_verify($data['pass'], $u_fetch['password'])){ // Daca parola introdusa nu coincide cu parola din baza de date
                echo 'pass_mismatch';
                }
                else{
                session_start();
                $_SESSION['login'] = true; // Setare variabila de sesiune login cu valoarea true
                $_SESSION['uID'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['profile'];
                $_SESSION['uPhone'] = $u_fetch['phonenum'];
                echo 1;
               }
            }
        }
    }
if(isset($_POST['forgot_pass'])){
    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1", [$data['email']], "s");

    if(mysqli_num_rows($u_exist) == 0){
        echo 'user_not_exist';}
    else{
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if($u_fetch['is_verified']==0){
            echo 'user_not_verified';
        }
        else if($u_fetch['status']==0){
            echo 'user_disabled';
        }
        else{
            // send reset link to email
            $token = bin2hex(random_bytes(16));

            if(!send_mail($data['email'], $token, "account_recovery")){
                echo 'mail_error';
            
            }
            else
            {

                $date = date('Y-m-d');

                $query = mysqli_query($con, "UPDATE `user_cred` SET `token`='$token', `t_expire`='$date' WHERE `id` = '$u_fetch[id]'");

                if($query){
                    echo 1;
                }
                else{
                    echo 'failed';
                }
            }
        }
    }
}

if(isset($_POST['recover_user'])){
    $data = filteration($_POST);

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    $query = "UPDATE `user_cred` SET `password`='$enc_pass', `token`='', `t_expire`='' WHERE `email`=? AND `token`=?";
    $values = [$enc_pass, null, null, $data['email'], $data['token']];
    if(update($query, $values, "sssss")){
        echo 1;
    }
    else{
        echo 'failed';
    }
}
?>