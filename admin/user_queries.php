<?php 
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    if(isset($_GET['seen'])){ // Daca s-a apasat butonul de seen
        $frm_data = filteration($_GET); 

        if($frm_data['seen'] == 'all'){// Daca s-a apasat seen la toate
            $q = "UPDATE `user_queries` SET `seen`=?"; // Query pentru update la toate
            $values = [1]; // Valori pentru query (din formular) 
            if(update($q, $values, 'i')){ // Rulare query cu valori si tipuri de date (i = integer) 
                alert('success', 'Marked all as Seen'); // Alerta de succes
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        }
        else{
            $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?"; // Query pentru update la unul singur
            $values = [1,$frm_data['seen']]; // Valori pentru query (din formular) 
            if(update($q, $values, 'ii')){ // Rulare query cu valori si tipuri de date (ii = integer, integer) 
                alert('success', 'Marked as Seen'); // Alerta de succes
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        }
    }
    if(isset($_GET['delete'])){ // Daca s-a apasat butonul de delete
        $frm_data = filteration($_GET);

        if($frm_data['delete'] == 'all'){
            $q = "DELETE FROM `user_queries`";
            if(mysqli_query($con, $q)){
                alert('success', 'All Deleted Successfully');
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'dang');
            }
        }
        else{
            $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
            $values = [$frm_data['delete']];
            if(update($q, $values, 'i')){
                alert('success', 'Deleted Successfully');
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'dang');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Queries</title>
<?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

<?php require ('inc/header.php');?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">User Queries</h3>

                    <!-- User Queries Sections !-->   
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-end mb-4">
                        <a href="?seen=all" class="btn btn-sm rounded-pill btn-primary">
                        <i class="bi bi-check-all"></i></i>Mark All as Seen</a>
                        <a href="?delete=all" class="btn btn-sm rounded-pill btn-danger">
                        <i class="bi bi-trash3-fill"></i>Delete All</a>
                    </div>




                    <div class="table-responsive-md" style="height:450px; overflow-y: scroll;">
                    <table class="table table-hover border">
                        <thead class="sticky-top"> 
                            <tr class="bg-dark text-light">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col" width="20%">Subject</th>
                            <th scope="col" width="30%">Message</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Date query despre user_queries -->
                            <?php
                                $q = "SELECT * FROM `user_queries` ORDER BY `sr_no` DESC"; // Query pentru selectarea tuturor randurilor din user_queries
                                $data = mysqli_query($con, $q); // Rulare query cu valori si tipuri de date (ii = integer, integer)
                                $i = 1;// Contor pentru randuri

                                while($row = mysqli_fetch_assoc($data)){ // Extrage randurile din baza de date ca array asociativ
                                    $seen=''; // Variabila pentru butonul de seen
                                    if($row['seen']!=1){ // Daca seen nu este 1 (nu este vazut)
                                        $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary'>Mark as Seen</a> <br>";
                                    }   // Butonul de seen va fi afisat doar daca seen nu este 1 
                                    $seen .="<a href='?delete=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger mt-2'>Delete</a>";
                                    echo <<<QUERY
                                    <tr>
                                        <td>$i</td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['subject']}</td>
                                        <td>{$row['message']}</td>
                                        <td>{$row['date']}</td>
                                        <td>$seen</td>
                                    </tr>
                                    QUERY;                                    
                                    $i++; 
                                }
                            ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>

                        <!-- User Queries Modal -->
            <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="carousel_s_form">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Image</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Picture</label>
                                <input type="file" name="carousel_picture" id="carousel_picture_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none">
                            </div>  
                        </div>
        
                    <!-- Settings Modal General Management -->   
        
                        <div class="modal-footer">
                            <button type="button" onclick ="carousel_picture.value=''" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- <?php echo $_SERVER['DOCUMENT_ROOT']; ?> -->

        </div>
    </div>
</div>


<?php require ('inc/scripts.php');?> 


</body>
</html>