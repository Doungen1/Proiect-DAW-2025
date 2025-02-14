<?php
// Assuming you have already set your GOOGLE_APPLICATION_CREDENTIALS
require 'vendor/autoload.php';
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

if(isset($_POST['login'])){ // When the login form is submitted

    // Assume 'login' is your form's submit button name
    $recaptchaResponseToken = $_POST['g-recaptcha-response']; // The response token from reCAPTCHA

    $result = create_assessment(
        '6LcF_1MpAAAAAMJXrp9s_otTWuhSB9D4ahTuz0a7', // Your reCAPTCHA key
        $recaptchaResponseToken,
        'my-project-40392-1705419579903', // Your project ID
        'login' // The action you are expecting
    );

    if (!$result->success) {
        // Handle failed reCAPTCHA verification
        echo 'reCAPTCHA verification failed. Please try again.';
        exit; // Prevent further execution
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

}

function create_assessment(
  string $recaptchaKey,
  string $token,
  string $project,
  string $action
): void {
  // Create the reCAPTCHA client.
  // TODO: Cache the client generation code (recommended) or call client.close() before exiting the method.
  $client = new RecaptchaEnterpriseServiceClient();
  $projectName = $client->projectName($project);

  // Set the properties of the event to be tracked.
  $event = (new Event())
    ->setSiteKey($recaptchaKey)
    ->setToken($token);

  // Build the assessment request.
  $assessment = (new Assessment())
    ->setEvent($event);

  try {
    $response = $client->createAssessment(
      $projectName,
      $assessment
    );

    // Check if the token is valid.
    if ($response->getTokenProperties()->getValid() == false) {
      printf('The CreateAssessment() call failed because the token was invalid for the following reason: ');
      printf(InvalidReason::name($response->getTokenProperties()->getInvalidReason()));
      return;
    }

    // Check if the expected action was executed.
    if ($response->getTokenProperties()->getAction() == $action) {
      // Get the risk score and the reason(s).
      // For more information on interpreting the assessment, see:
      // https://cloud.google.com/recaptcha-enterprise/docs/interpret-assessment
      printf('The score for the protection action is:');
      printf($response->getRiskAnalysis()->getScore());
    } else {
      printf('The action attribute in your reCAPTCHA tag does not match the action you are expecting to score');
    }
  } catch (exception $e) {
    printf('CreateAssessment() call failed with the following error: ');
    printf($e);
  }
}

// TODO: Replace the token and reCAPTCHA action variables before running the sample.
create_assessment(
   '6LcF_1MpAAAAAMJXrp9s_otTWuhSB9D4ahTuz0a7',
   'YOUR_USER_RESPONSE_TOKEN',
   'my-project-40392-1705419579903',
   'LOGIN'
);
?>
