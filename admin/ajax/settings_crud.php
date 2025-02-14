<?php

require('../inc/essentials.php');
require('../inc/db_config.php');
adminLogin();

    // Adauga feature in tabelul features
    if(isset($_POST["get_general"])) // Afiseaza setarile generale din tabelul settings 
    {
        $q = "SELECT * FROM `settings` WHERE `sr_no`=?"; // Selecteaza setarile generale din tabelul settings in functie de sr_no
        $values = [1]; // Se atribuie variabilei values valoarea 1
        $res = select($q, $values, "i"); // Se executa functia select din fisierul essentials.php
        $data = mysqli_fetch_assoc($res); // Se atribuie variabilei data rezultatul functiei mysqli_fetch_assoc
        $json_data = json_encode($data); // Se atribuie variabilei json_data rezultatul functiei json_encode
        echo $json_data; // Afiseaza rezultatul functiei json_encode
    }


    if (isset($_POST['upd_general'])) { // Updateaza setarile generale din tabelul settings
        $frm_data = filteration($_POST);
    
        // functie de update din essentials.php
        $q = "UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `sr_no`=?"; // Updateaza setarile generale din tabelul settings in functie de sr_no
        $values = [$frm_data['site_title'], $frm_data['site_about'], 1]; // Se atribuie variabilei values valorile din frm_data
        $res = update($q, $values, "ssi"); // Se executa functia update din fisierul essentials.php
        echo $res;
    }

    if (isset($_POST['upd_shutdown'])) { // Updateaza setarile generale din tabelul settings in functie de sr_no
        error_log("Valoarea primită pentru upd_shutdown: " . var_export($_POST['upd_shutdown'], true));

        $frm_data = ($_POST['upd_shutdown']==0) ? 1 : 0; // Se atribuie variabilei frm_data valoarea 1 daca frm_data este 0, altfel se atribuie valoarea 0
        error_log("Valoarea calculată pentru shutdown: " . $frm_data);
        // functie de update din essentials.php
        $q = "UPDATE `settings` SET `shutdown`=? WHERE `sr_no`=?"; // Updateaza setarile generale din tabelul settings in functie de sr_no
        $values = [$frm_data, 1]; // Se atribuie variabilei values valorile din frm_data
        $res = update($q, $values, "ii");
        error_log("Numărul de rânduri afectate: " . $res);
        echo $res;
    }


    if(isset($_POST["get_contacts"])) // Afiseaza setarile de contact din tabelul contact_details
    {
        $q = "SELECT * FROM `contact_details` WHERE `sr_no`=?"; // Selecteaza setarile de contact din tabelul contact_details in functie de sr_no
        $values = [1]; // Se atribuie variabilei values valoarea 1
        $res = select($q, $values, "i"); // Se executa functia select din fisierul essentials.php
        $data = mysqli_fetch_assoc($res); // Se atribuie variabilei data rezultatul functiei mysqli_fetch_assoc  
        $json_data = json_encode($data); // Se atribuie variabilei json_data rezultatul functiei json_encode
        echo $json_data;
    }

    if(isset($_POST['upd_contacts'])) { // Updateaza setarile de contact din tabelul contact_details
        $frm_data = filteration($_POST);
        $q = "UPDATE `contact_details` SET `address`=?, `gmap`=?, `pn1`=? `pn2`=? `email`=? `fb`=? `insta`=? `twitter`=? `iframe`=?  WHERE `sr_no`=?";
        $values = [$frm_data['address'], $frm_data['gmap'], $frm_data['pn1'], $frm_data['pn2'], $frm_data['email'], $frm_data['fb'], $frm_data['insta'], $frm_data['twitter'], $frm_data['iframe'],1]; // Se atribuie variabilei values valorile din frm_data
        $res = update($q, $values, "sssssssssi");
        echo $res;
    }

    if(isset($_POST['add_member'])){ // Adauga membrii in tabelul team_details
        $frm_data = filteration($_POST);
        
        $img_r = uploadImage($_FILES['picture'], ABOUT_FOLDER);

        if($img_r == "Invalid Image Format"){
            echo $img_r;
        }
        else if($img_r == "Image size should be less than 2MB"){
            echo $img_r;
        }
        else if($img_r == "Image cannot be uploaded"){
            echo $img_r;
        }
        else{
            $q = "INSERT INTO `team_details`(`name`, `picture`) VALUES (?,?)"; 
            $values = [$frm_data['name'], $img_r]; // Se atribuie variabilei values valorile din frm_data
            $res = insert ($q, $values, "ss"); // Se executa functia insert din fisierul essentials.php
            echo $res;
        }


    }

    if(isset($_POST['get_members'])){ // Afiseaza membrii din tabelul team_details
        $res = selectAll('team_details'); // Se executa functia selectAll din fisierul essentials.php

        while($row = mysqli_fetch_assoc($res)){ // Se atribuie variabilei row rezultatul functiei mysqli_fetch_assoc
            $path = ABOUT_IMG_PATH; // Se atribuie variabilei path valoarea din fisierul db_config.php
            echo <<<data
                <div class="col-md-2 mb-3">
                    <div class="card bg-dark text-white">
                        <img src="$path$row[picture]" class="card-img">
                        <div class="card-img-overlay text-end">
                            <button type="button" onclick="rem_member($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i></i>Delete
                            </button>
                        </div>
                        <p class="card-text text-center px-3 py-2">$row[name]</p>
                    </div>
                </div>
            data; // Afiseaza rezultatul functiei mysqli_fetch_assoc
        }
    }
    
    if(isset($_POST['rem_member'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_member']]; // Se atribuie variabilei values valorile din frm_data
        
        $pre_q = "SELECT * FROM `team_details` WHERE `sr_no`=?"; // Selecteaza membrii din tabelul team_details in functie de sr_no
        $res = select($pre_q, $values, "i"); // Se executa functia select din fisierul essentials.php
        $img = mysqli_fetch_assoc($res); // Se atribuie variabilei img rezultatul functiei mysqli_fetch_assoc 

        if(deleteImage($img['picture'], ABOUT_FOLDER)){ // Sterge imaginea din folderul ABOUT_FOLDER
            $q = "DELETE FROM `team_details` WHERE `sr_no`=?";
            $res = delete($q, $values, "i");
            echo $res;
        }
        else{
            echo 0;
        }
    }
?>