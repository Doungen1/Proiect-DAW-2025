<?php
    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');

    if(isset($_GET['email_confirmation'])){ // Daca s-a apasat butonul de confirmare email  
        $data = filteration($_GET);
        
        $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? LIMIT 1",
        [$data['email'], $data['token']], "ss");

        if(mysqli_num_rows($query) ==1){ // Daca exista un rand in baza de date cu emailul si tokenul introduse 

            $fetch = mysqli_fetch_assoc($query); // Extrage randul din baza de date ca array asociativ 

            if($fetch['is_verified']==1){ // Daca emailul a fost deja confirmat 
                echo"<script> alert('Email Already Confirmed!')</script>"; 
                redirect('index.php');
        }
        else{
            $update = update("UPDATE `user_cred` SET `is_verified`=? WHERE `id`=?", 
            [1, $fetch['id']], "ii"); // Query pentru update la email confirmat
            if($update){
                echo "<script> alert('Email Confirmed!')</script>";
            }
            else{
                echo "<script> alert('Email Confirmation Failed!')</script>";
            
            }
        }
            redirect('index.php');

        }
        else{
            echo "<script> alert('Invalid Link!')</script>";
            redirect('index.php');
        }
    }

    
?>