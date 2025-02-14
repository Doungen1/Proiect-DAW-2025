<?php
    include('db_credentials.php');
    // Conectare la baza de date
    
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Verificare conexiune
    if(!$con){ // Daca nu se poate conecta la baza de date
        die('Connection Failed: '.mysqli_connect_error()); // Afiseaza eroare
    }

    // Functie de filtrare a datelor
    function filteration($data){ // Array cu datele care trebuie filtrate
        foreach($data as $key => $value){ // Loop prin date de tip pereche cheie-valoare
            $value = trim($value); // Sterge spatiile de la inceput si sfarsit
            $value = htmlspecialchars($value); // Transforma caracterele speciale in entitati HTML
            $value = stripslashes($value);  // Sterge backslashes
            $value = strip_tags($value); // Sterge tag-urile HTML si PHP
            
            $data[$key] = $value; // Atribuie valoarea filtrata cheii din array
        }
        return $data; // Returneaza datele filtrate
    }

    function selectAll($table){ // Selecteaza toate datele din tabelul $table
        $con = $GLOBALS['con']; // Variabila globala 
        $res = mysqli_query($con, "SELECT * FROM $table"); // Query pentru selectarea tuturor datelor din tabelul $table
        return $res; // Returneaza rezultatul query-ului
    }

    function select($sql, $values, $datatypes){ // Selecteaza date din baza de date
        $con = $GLOBALS['con']; 
        if($stmt = mysqli_prepare($con, $sql)){ // Verifica daca query-ul poate fi pregatit
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values); // Leaga variabilele la query 
            if(mysqli_stmt_execute($stmt)){ // Verifica daca query-ul poate fi executat
                $res = mysqli_stmt_get_result($stmt); // Returneaza rezultatul query-ului
                mysqli_stmt_close($stmt); // Inchide query-ul
                return $res;
            }
            else{
                die("Query cannot be executed - Execute");
            }
        }
        else{
            die("Query cannot be prepared - Select");
        }
    }

    function insert($sql, $values, $datatypes){ // Insereaza date in baza de date
        $con = $GLOBALS['con']; 
        if($stmt = mysqli_prepare($con, $sql)) // Verifica daca query-ul poate fi pregatit si il trimite la stmt
        {
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values); // Leaga variabilele la query
            if(mysqli_stmt_execute($stmt)){ 
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Update");
            }
        }
        else{
            die("Query cannot be prepared - Update");
        }
    }

    function update($sql, $values, $datatypes){ // Updateaza date in baza de date
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values); // Leaga variabilele la query
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Update");
            }
        }
        else{
            die("Query cannot be prepared - Update");
        }
    }

    function delete($sql, $values, $datatypes){ // Sterge date din baza de date
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql)) // Verifica daca query-ul poate fi pregatit
        {
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values); // Leaga variabilele la query
            if(mysqli_stmt_execute($stmt)){ 
                $res = mysqli_stmt_affected_rows($stmt); // Returneaza numarul de randuri afectate de query
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Delete");
            }
        }
        else{
            die("Query cannot be prepared - Delete");
        }
    }

    
?>