
    let add_room_form = document.getElementById('add_room_form');

    add_room_form.addEventListener('submit', function(e){
        e.preventDefault();
        add_rooms();
    });

    function add_rooms(){

        let data = new FormData();
        data.append('add_room', '');
        data.append('name', add_room_form.elements['name'].value);
        data.append('area', add_room_form.elements['area'].value);
        data.append('price', add_room_form.elements['price'].value);
        data.append('quantity', add_room_form.elements['quantity'].value);
        data.append('adult', add_room_form.elements['adult'].value);
        data.append('children', add_room_form.elements['children'].value);
        data.append('desc', add_room_form.elements['desc'].value);

        let features = [];

            Array.from(add_room_form.elements['features']).forEach((el) => {
                if (el.checked) {
                    features.push(el.value);
                }
            });


        let facilities =[];
            Array.from(add_room_form.elements['facilities']).forEach((el) => {
                    if (el.checked) {
                        facilities.push(el.value);
                    }
                });

        data.append('features', JSON.stringify(features));
        data.append('facilities', JSON.stringify(facilities));

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {
            var myModal = document.getElementById('add-room');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText == 1){
                alert('success', 'Room added successfully');
                add_room_form.reset();
                get_all_rooms();

            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        } 
        xhr.send(data);
    };

    function get_all_rooms(){

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            document.getElementById('room-data').innerHTML = this.responseText;
        } 

        xhr.send('get_all_rooms');
    }

    let edit_room_form = document.getElementById('edit_room_form');

    function edit_details(id){
        console.log(id);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            console.log(JSON.parse(this.responseText));
            let data = JSON.parse(this.responseText);

            edit_room_form.elements['name'].value = data.roomdata.name;
            edit_room_form.elements['area'].value = data.roomdata.area;
            edit_room_form.elements['price'].value = data.roomdata.price;
            edit_room_form.elements['quantity'].value = data.roomdata.quantity;
            edit_room_form.elements['adult'].value = data.roomdata.adult;
            edit_room_form.elements['children'].value = data.roomdata.children;
            edit_room_form.elements['desc'].value = data.roomdata.description;
            edit_room_form.elements['room_id'].value = data.roomdata.id;

            
            edit_room_form.elements['facilities'].forEach((el) => {
                if (data.facilities.includes(Number(el.value))) {
                    el.checked = true;
                }
                else{
                    el.checked = false;
                }
            });

            edit_room_form.elements['features'].forEach((el) => {
                if (data.features.includes(Number(el.value))) {
                    el.checked = true;
                }
                else{
                    el.checked = false;
                }
            });
        }

        xhr.send('get_room='+id);
    }

    edit_room_form.addEventListener('submit', function(e){
        e.preventDefault();
        submit_edit_room();
    });
   

    function submit_edit_room(){

        let data = new FormData();
        data.append('edit_room', '');
        data.append('room_id', edit_room_form.elements['room_id'].value);
        data.append('name', edit_room_form.elements['name'].value);
        data.append('area', edit_room_form.elements['area'].value);
        data.append('price', edit_room_form.elements['price'].value);
        data.append('quantity', edit_room_form.elements['quantity'].value);
        data.append('adult', edit_room_form.elements['adult'].value);
        data.append('children', edit_room_form.elements['children'].value);
        data.append('desc', edit_room_form.elements['desc'].value);


        let features = [];

            Array.from(edit_room_form.elements['features']).forEach((el) => {
                if (el.checked) {
                    features.push(el.value);
                }
            });    

        let facilities =[];
            Array.from(edit_room_form.elements['facilities']).forEach((el) => {
                    if (el.checked) {
                        facilities.push(el.value);
                    }
                });

        data.append('features', JSON.stringify(features));
        data.append('facilities', JSON.stringify(facilities));

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function () {
            var myModal = document.getElementById('edit-room');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText == 1){
                alert('success', 'Room updated successfully');
                edit_room_form.reset();
                get_all_rooms();

            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        }
        xhr.send(data);
    }

    function toggle_status(id,val){ //functie pentru a schimba statusul camerei (active/inactive)

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if(this.responseText ==1){
                alert('success', 'Status updated successfully'); //apeleaza functia alert cu parametrii "success" si "Status updated successfully"
                get_all_rooms(); //apeleaza functia get_all_rooms
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        } 

        xhr.send('toggle_status='+id+'&value='+val); //trimite id-ul camerei si valoarea statusului (active/inactive)
    }
 

    let add_image_form = document.getElementById('add_image_form'); //selecteaza formularul cu id-ul add_image_form din modal room-images

    add_image_form.addEventListener('submit', function(e){
        e.preventDefault();
        add_image();
    });

    function add_image(){ //functie pentru a adauga imaginea in baza de date

        let data = new FormData(); //obiect FormData = trimite datele din formular
        data.append('image', add_image_form.elements['image'].files[0]); //file input la selectare de acelasi fisier
        data.append('room_id', add_image_form.elements['room_id'].value); //hidden input la selectare de acelasi fisier room_id 
        data.append('add_image', ''); //add_image = name din ajax/rooms.php


        let xhr = new XMLHttpRequest(); //request
        xhr.open("POST", "ajax/rooms.php", true); //true = async

        xhr.onload = function () {
            if(this.responseText == 'Invalid Image Format'){
                alert('error', 'Only JPG, JPEG, PNG & SVG files are allowed.', '');
            }
            else if(this.responseText =='Image size must be less than 2MB'){
                alert('error', 'Image size must be less than 2MB', 'danger');
            }
            else if(this.responseText=='Image cannot be uploaded'){
                alert('error', 'Image cannot be uploaded', 'danger');
            }
            else{
                alert('success', 'Image added successfully', 'image-alert'); //image-alert = div din modal room-images
                room_images(add_image_form.elements['room_id'].value, document.querySelector('#room-images .modal-title').innerHTML = rname); //rname = room name din rooms.php la onclick pe butonul de edit room
                add_image_form.reset(); //reset form dupa ce se adauga imaginea in baza de date si se afiseaza in modal room-images 
           }

        } 
        xhr.send(data); //trimite datele din formular
    }

    function room_images(id, rname){ //functie pentru a afisa imaginile camerei in modal room-images (id = id-ul camerei, rname = numele camerei)
        document.querySelector('#room-images .modal-title').innerHTML = rname; //selecteaza din modal room-images titlul
        add_image_form.elements['room_id'].value = id; //hidden input la selectare de acelasi fisier room_id
        add_image_form.elements['image'].value = ''; //file input la selectare de acelasi fisier

        let xhr = new XMLHttpRequest(); //request
        xhr.open("POST", "ajax/rooms.php", true); //true = async
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); //trimite datele in format urlencoded

        xhr.onload = function () {
            document.getElementById('room-image-data').innerHTML = this.responseText; // selecteaza din tbody cu id room-image-data din modal room-images
        } 

        xhr.send('get_room_images='+id); //trimite id-ul camerei
    }

    function rem_image(img_id, room_id){ //functie pentru a sterge imaginea din baza de date (img_id = id-ul imaginii, room_id = id-ul camerei)

        let data = new FormData(); //obiect FormData = trimite datele din formular
        data.append('image_id', img_id); //hidden input la selectare de acelasi fisier rem_image
        data.append('room_id', room_id); //hidden input la selectare de acelasi fisier room_id
        data.append('rem_image', ''); //rem_image = name din ajax/rooms.php (rem_image = id-ul imaginii din baza de date)


        let xhr = new XMLHttpRequest(); //request
        xhr.open("POST", "ajax/rooms.php", true); //true = async

        xhr.onload = function () {
            if(this.responseText == 1){
                alert('success', 'Image deleted successfully', 'image-alert'); //image-alert = div din modal room-images 
                room_images(room_id, document.querySelector('#room-images .modal-title').innerText); //rname = room name din rooms.php la onclick pe butonul de edit room
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'image-alert');
            }
        } 

        xhr.send(data); //trimite datele din formular
    }


    function thumb_image(img_id, room_id){ //functie pentru a seta imaginea ca thumbnail (img_id = id-ul imaginii, room_id = id-ul camerei)
 
        let data = new FormData(); //obiect FormData = trimite datele din formular
        data.append('image_id', img_id); //hidden input la selectare de acelasi fisier rem_image
        data.append('room_id', room_id); //hidden input la selectare de acelasi fisier room_id
        data.append('thumb_image', ''); //thumb_image = name din ajax/rooms.php (thumb_image = id-ul imaginii din baza de date)


        let xhr = new XMLHttpRequest(); //request
        xhr.open("POST", "ajax/rooms.php", true); //true = async

        xhr.onload = function () {
            if(this.responseText == 1){
                alert('success', 'Image Thumbnail Changed', 'image-alert'); //image-alert = div din modal room-images
                room_images(room_id, document.querySelector('#room-images .modal-title').innerText); //rname = room name din rooms.php la onclick pe butonul de edit room
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'image-alert');
            }
        } 

        xhr.send(data); //trimite datele din formular
    }

    function remove_room(room_id){ //functie pentru a sterge camera din baza de date (room_id = id-ul camerei)

        if(confirm("Are you sure to detele this room?")){
            let data = new FormData(); //obiect FormData = trimite datele din formular
            data.append('room_id', room_id); //hidden input la selectare de acelasi fisier room_id
            data.append('remove_room', ''); //remove_room = name din ajax/rooms.php (remove_room = id-ul camerei din baza de date)
        

            let xhr = new XMLHttpRequest(); //request
            xhr.open("POST", "ajax/rooms.php", true); //true = async

            xhr.onload = function () {
                if(this.responseText == 1){
                    alert('success', 'Room Removed'); //image-alert = div din modal room-images
                    get_all_rooms();                }
                else{
                    alert('error', 'Something went wrong. Please try again later.');
                }
            } 

            xhr.send(data); //trimite datele din formular
        }

    }

    function downloadRoomDataPDF() {
        fetch('ajax/rooms.php', {
            method: 'POST',
            body: new URLSearchParams({
                'generate_all_rooms_pdf': '1'
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.blob())
        .then(blob => {
            // Create a link to download the PDF
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'all_rooms_data.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('Error:', error));
    }

    window.onload = function(){
        get_all_rooms(); //functia care afiseaza camerele in admin/rooms.php
    }

