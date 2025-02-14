
let carousel_s_form = document.getElementById('carousel_s_form'); // permite accesul la formularul cu id-ul "carousel_s_form"

let carousel_picture_inp = document.getElementById('carousel_picture_inp'); // permite accesul la inputul cu id-ul "carousel_picture_inp"


carousel_s_form.addEventListener('submit', function(e){ // asculta evenimentul de submit al formularului cu id-ul "carousel_s_form"
    e.preventDefault(); // previne reincarcarea paginii
    add_image(); // apeleaza functia add_image
}); 

function add_image(){ // add image function
    let data = new FormData(); // form data object
    data.append('picture', carousel_picture_inp.files[0]); // append picture to form data
    data.append('add_image', ''); // append add_image to form data

    let xhr = new XMLHttpRequest(); // ajax request
    xhr.open("POST", "ajax/carousel_crud.php", true); // open ajax request

    xhr.onload = function () { // ajax response
        console.log(this.responseText); // log response
        var myModal = document.getElementById('carousel-s'); // primeste modalul cu id-ul "carousel-s" 
        var modal = bootstrap.Modal.getInstance(myModal); // primeste instanta modalului cu id-ul "carousel-s"
        modal.hide(); // hide modal

        if (this.responseText == "Invalid Image Format"){ // Daca raspunsul este "Invalid Image Format"
            alert('error', 'Invalid Image Format'); // apeleaza functia alert cu parametrii "error" si "Invalid Image Format"
        }
        else if (this.responseText == "Image size should be less than 20MB"){
            alert('error', 'Image size should be less than 20MB');
        }
        else if (this.responseText == "Image cannot be uploaded"){
            alert('error', 'Image cannot be uploaded');
        }
        else if (this.responseText == 1) { // Daca raspunsul este 1
            alert('success', 'New image added successfully');
            carousel_picture_inp.value = ""; // reseteaza inputul
            get_carousel(); // functia pentru a lua imaginile din baza de date
        } else {
            alert('error', 'Failed to add member');
        } 
    };
    xhr.send(data); // trimite form data prin cererea ajax
}

function get_carousel(){ // functie pentru a lua imaginile din baza de date
    let xhr = new XMLHttpRequest(); // cerere ajax
    xhr.open("POST", "ajax/carousel_crud.php", true); // deschide cererea ajax
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // seteaza headerul

    xhr.onload = function(){ // raspunsul ajax
        document.getElementById('carousel-data').innerHTML = this.responseText; // seteaza continutul divului cu id-ul "carousel-data" cu raspunsul ajax
        console.log(data); // log raspunsul
    }
    xhr.send("get_carousel"); // transmite "get_carousel" prin cererea ajax
}

function rem_image(val){ // functie pentru a sterge o imagine
    let xhr = new XMLHttpRequest(); // cerere ajax
    xhr.open("POST", "ajax/carousel_crud.php", true); // deschide cererea ajax
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // seteaza headerul

    xhr.onload = function(){ // raspunsul ajax
        if(this.responseText == 1){ // daca raspunsul este 1
            alert('success', 'Image removed successfully');
            get_carousel(); // functia pentru a lua imaginile din baza de date
        }
        else{
            alert('error', 'Failed to remove member');
        }
    }
    xhr.send("rem_image=" + val); // transmite "rem_image" si valoarea prin cererea ajax

}

window.onload = function(){ // functia care se executa la incarcarea paginii
    get_carousel(); // functia pentru a lua imaginile din baza de date
}
