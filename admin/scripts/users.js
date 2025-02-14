

    function get_users(){

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/users.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            document.getElementById('users-data').innerHTML = this.responseText;
        } 

        xhr.send('get_users');
    }


    function toggle_status(id,val){ //functie pentru a schimba statusul camerei (active/inactive)

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/users.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if(this.responseText ==1){
                alert('success', 'Status updated successfully'); //apeleaza functia alert cu parametrii "success" si "Status updated successfully"
                get_users(); 
            }
            else{
                alert('error', 'Something went wrong. Please try again later.');
            }
        } 

        xhr.send('toggle_status='+id+'&value='+val); 
        }
 

    function remove_user(user_id){ //functie pentru a sterge camera din baza de date (room_id = id-ul camerei)

        if(confirm("Are you sure to detele this user?")){
            let data = new FormData(); //obiect FormData = trimite datele din formular
            data.append('user_id', user_id); //hidden input la selectare de acelasi fisier room_id
            data.append('remove_user', ''); //remove_room = name din ajax/rooms.php (remove_room = id-ul camerei din baza de date)
        

            let xhr = new XMLHttpRequest(); //request
            xhr.open("POST", "ajax/users.php", true); //true = async

            xhr.onload = function () {
                if(this.responseText == 1){
                    alert('success', 'User Removed'); //image-alert = div din modal room-images
                    get_users();                }
                else{
                    alert('error', 'Something went wrong. Please try again later.')
                }
            } 
            xhr.send(data); //trimite datele din formular
        }
    }

    function search_user(username){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/users.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            document.getElementById('users-data').innerHTML = this.responseText;
        } 

        xhr.send('search_user&name='+username);
    }


    window.onload = function(){
        get_users(); //functia care afiseaza camerele in admin/rooms.php
    }

