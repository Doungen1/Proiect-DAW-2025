<?php

require('../inc/essentials.php'); // Acesta este fisierul care contine functiile de baza ale site-ului (conectare la baza de date, functii de selectare, inserare, stergere, filtrare, etc.)
require('../inc/db_config.php'); // Acesta este fisierul care contine datele de conectare la baza de date (host, user, parola, nume baza de date) 
adminLogin(); // Functie care verifica daca adminul este logat sau nu. Daca nu este logat, il redirectioneaza catre pagina de login

    // Adauga imaginea in tabelul carousel 
    if(isset($_POST['add_image'])){ // Daca exista variabila add_image in POST, atunci se executa urmatorul cod
        
        $img_r = uploadImage($_FILES['picture'], CAROUSEL_FOLDER); // Se incarca imaginea in folderul CAROUSEL_FOLDER (incarca imaginea in folderul carousel din folderul img din folderul admin)

        if($img_r == "Invalid Image Format"){ // Daca imaginea nu este in formatul JPG, JPEG sau PNG, atunci se afiseaza mesajul "Invalid Image Format"
            echo $img_r; // Afiseaza mesajul "Invalid Image Format"
        }
        else if($img_r == "Image size should be less than 20MB"){ 
            echo $img_r;
        }
        else if($img_r == "Image cannot be uploaded"){
            echo $img_r;
        }
        else{
            $q = "INSERT INTO `carousel`(`image`) VALUES (?)"; // Se insereaza imaginea in baza de date (in tabelul carousel) in coloana image (coloana image este de tipul VARCHAR) 
            $values = [$img_r]; 
            $res = insert ($q, $values, "s"); // Se executa functia insert din fisierul essentials.php 
            echo $res; // Afiseaza rezultatul functiei insert din fisierul essentials.php
        }
    }

    // Afiseaza imaginile din tabelul carousel 
    if(isset($_POST['get_carousel'])){
        $res = selectAll('carousel'); // Selecteaza toate imaginile din tabelul carousel 
    
        while($row = mysqli_fetch_assoc($res)){ // Loop prin imaginile din tabelul carousel 
            $path = CAROUSEL_IMG_PATH; // Path-ul imaginilor din tabelul carousel
            echo <<<DATA
                <div class="col-md-4 mb-3">
                    <div class="card bg-dark text-white">
                        <img src="{$path}{$row['image']}" class="card-img">
                        <div class="card-img-overlay text-end">
                            <button type="button" onclick="rem_image({$row['sr_no']})" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
    DATA;
        } // End while
    } // End if
    
    
    // Sterge imaginea din tabelul carousel in functie de sr_no 
    if(isset($_POST['rem_image'])){ 
        $frm_data = filteration($_POST); // Se filtreaza datele din POST  
        $values = [$frm_data['rem_image']]; // Se atribuie variabilei values valoarea din frm_data['rem_image']

        $pre_q = "SELECT * FROM `carousel` WHERE `sr_no`=?"; // Se selecteaza imaginea din tabelul carousel in functie de sr_no
        $res = select($pre_q, $values, "i"); // Se executa functia select din fisierul essentials.php
        $img = mysqli_fetch_assoc($res); // Se atribuie variabilei img rezultatul functiei mysqli_fetch_assoc (rezultatul functiei mysqli_fetch_assoc este un array cu datele imaginii din tabelul carousel)

        if(deleteImage($img['image'], CAROUSEL_FOLDER)){ // Daca functia deleteImage din fisierul essentials.php returneaza true, atunci se executa urmatorul cod 
            $q = "DELETE FROM `carousel` WHERE `sr_no`=?"; // Se sterge imaginea din tabelul carousel in functie de sr_no
            $res = delete($q, $values, "i"); // Se executa functia delete din fisierul essentials.php
            echo $res; // Afiseaza rezultatul functiei delete din fisierul essentials.php
        }
        else{
            echo 0; // Afiseaza 0
        } // End if
    } // End if
?>