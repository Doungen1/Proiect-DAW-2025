<?php
if(isset($_POST['login']) && isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
    include 'inc/essentials.php';
    include 'inc/db_config.php';
    
    $secret = '6LdjCFQpAAAAADPQCn2v7aCPWyjqmM4GjJ4WJnd1';
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verifyURL = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha_response;
    
    $verifyResponse = file_get_contents($verifyURL);
    $responseData = json_decode($verifyResponse);
    
    if($responseData->success){
        // Este recomandat să filtrezi input-ul
        $email = filteration($_POST['email_mob']);
        $pass  = filteration($_POST['pass']);
        
        // Utilizare interogare parametrizată pentru siguranță
        $stmt = $conn->prepare("INSERT INTO `user_cred`(`email`, `password`) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $pass);
        if($stmt->execute()){
            echo 'success';
        } else {
            echo 'failed';
        }
        $stmt->close();
    }
    else{
        echo 'failed';
    }
}
?>
