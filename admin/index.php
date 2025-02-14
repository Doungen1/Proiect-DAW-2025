
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);    
    require('inc/essentials.php');
    require('inc/db_config.php');

    session_start(); // Start Sesiune
    if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)){ // Daca e admin, redirect la dashboard
        redirect('dashboard.php'); 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('inc/links.php'); ?>
    <style>
        div.login-form{ 
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>
<body class="bg-light">

    <div class="login-form text-center rounded bg-white shadown overflow-hidden">
        <form method="POST">
            <h4 class="bg-dark text-white py-3">Admin Login Panel</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name">
                </div>
                <div class="mb-4">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <button name="login" type="submit" class="btn text-white custom-bg shadow-none">
                    Login
                </button>
            </div>
        </form>
    </div>


    <?php
    
                    
            if(isset($_POST['login'])) {
                $frm_data = filteration($_POST);
                // Hash-uim parola introdusă
                $hashedPass = hash('sha256', $frm_data['admin_pass']);
                $query = "SELECT * FROM `admin_cred` WHERE `admin_name` = ? AND `admin_pass` = ?";
                $values = [$frm_data['admin_name'], $hashedPass];
                $res = select($query, $values, "ss");
                if($res->num_rows == 1) {
                    $row = mysqli_fetch_assoc($res);
                    $_SESSION['adminLogin'] = true;
                    $_SESSION['adminId'] = $row['sr_no'];
                    redirect('dashboard.php');
                } else {
                    alert('error', 'Login failed - Invalid Credentials');
                }
            }


    ?>


    <?php require('inc/scripts.php'); ?>
</body>
</html>