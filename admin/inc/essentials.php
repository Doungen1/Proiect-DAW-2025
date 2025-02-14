<?php

    // Front End Constants

    define('SITE_URL', 'http://harmonyluxhotel.infinityfreeapp.com/'); 
    define('ABOUT_IMG_PATH', SITE_URL.'images/about/'); 
    define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel/'); 
    define('FACILITIES_IMG_PATH', SITE_URL.'images/facilities/'); 
    define('ROOMS_IMG_PATH', SITE_URL.'images/rooms/'); 
    define('USERS_IMG_PATH', SITE_URL.'images/users/'); 
    // API Constants 

    define('SENDGRID_EMAIL', "doungen1@icloud.com"); // Emailul de la SendGrid
    define('SENDGRID_NAME', "Harmony Lux"); // Numele de la SendGrid

    // Backend Constants
    define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/images/'); // Calea imaginilor de la upload
    define('ABOUT_FOLDER', 'about/'); 
    define('CAROUSEL_FOLDER', 'carousel/');
    define('FACILITIES_FOLDER', 'facilities/');
    define('USERS_FOLDER', 'users/');
    define('ROOMS_FOLDER', 'rooms/');

    function adminLogin(){ // Verifica daca adminul este logat
        session_start(); // Porneste sesiunea
        
        if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)){ // Daca adminul nu este logat
            echo"<script>window.location.href = 'index.php';</script>"; // Redirectioneaza la pagina de login
            exit; // Opreste executia scriptului
        } 
    };

    function redirect($url){ // Redirectioneaza la pagina $url
        // Echo pentru a afisa scriptul de redirectionare
        echo" 
            <script>
                window.location.href = '$url';
            </script>";
            exit; // Opreste executia scriptului
    };

    
    function alert($type, $msg){ // Afiseaza alerta de tip $type cu mesajul $msg
        $bs_class = ($type == "success") ? "alert-success" : "alert-danger"; // Atribuie variabilei bs_class valoarea alert-success daca $type este success, altfel atribuie valoarea alert-danger
        // Echo pentru a afisa alerta
        echo <<<alert
            <div class="alert $bs_class alert-warning alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">$msg</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        alert;
    };

    function uploadImage($image, $folder){ // Functie pentru upload de imagini 
        $valid_mime = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp']; // Array cu tipurile de imagini acceptate 
        $img_mime = $image['type']; // Variabila cu tipul imaginii incarcate 

        if(!in_array($img_mime, $valid_mime)){ // Daca tipul imaginii nu este acceptat
            return "Invalid Image Format"; 
        }
        else if(($image['size']/(1024*1024))>20){ // Daca dimensiunea imaginii este mai mare de 20MB
            return "Image size should be less than 20MB";
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION); // Variabila cu extensia imaginii incarcate
            $rname = 'IMG_'.random_int(111111, 999999).".$ext"; // Variabila cu numele imaginii incarcate

            $img_path = UPLOAD_IMAGE_PATH.$folder.$rname; // Variabila cu calea imaginii incarcate

            if(move_uploaded_file($image['tmp_name'], $img_path)){ // Daca imaginea a fost incarcata
                return $rname; // Returneaza numele imaginii incarcate
            }
            else{
                return "Image cannot be uploaded";
            }
        }
    };


    function deleteImage($image, $folder){ // Functie pentru stergere de imagini
        if(unlink(UPLOAD_IMAGE_PATH.$folder.$image)){ // Daca imaginea a fost stearsa din folder
            return true; 
        }
        else{
            return false;
        }
    };

    function uploadSVGImage($image, $folder){ // Functie pentru upload de imagini SVG
        $valid_mime = ['image/svg+xml']; // Array cu tipurile de imagini acceptate
        $img_mime = $image['type']; // Variabila cu tipul imaginii incarcate

        if(!in_array($img_mime, $valid_mime)){ // Daca tipul imaginii nu este acceptat
            return "Invalid Image Format";
        }
        else if(($image['size']/(1024*1024))>5){ // Daca dimensiunea imaginii este mai mare de 5MB
            return "Image size should be less than 5MB";
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname = 'IMG_'.random_int(111111, 999999).".$ext";

            $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;

            if(move_uploaded_file($image['tmp_name'], $img_path)){ // Daca imaginea a fost incarcata
                return $rname;
            }
            else{
                return "Image cannot be uploaded";
            }
        }
    };

    function uploadUserImage($image) // Functie pentru upload de imagini de la useri
    {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp']; // Array cu tipurile de imagini acceptate
        $img_mime = $image['type'];

        if(!in_array($img_mime, $valid_mime)){
            return "Invalid Image Format";
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname = 'IMG_'.random_int(111111, 999999).".jpeg";

            $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

            if($ext =='png' || $ext == 'PNG'){ // Daca imaginea este de tip png
                $img = imagecreatefrompng($image['tmp_name']); // Creaza imaginea din fisierul temporar al imaginii incarcate
            }
            else if($ext =='webp' || $ext =='WEBP'){
                $img = imagecreatefromwebp($image['tmp_name']);
            }
            else if($ext =='jpg' || $ext =='JPG'){
                $img = imagecreatefromjpeg($image['tmp_name']);
            }
            else{
                return "Image cannot be uploaded";
            }

            if(imagejpeg($img, $img_path, 75)){ // Daca imaginea a fost incarcata
                return $rname; // Returneaza numele imaginii incarcate
            }
            else{
                return "Image cannot be uploaded";
            }
        }
    }
?>