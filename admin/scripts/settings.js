
let general_data, contacts_data; // variabile globale

let general_s_form = document.getElementById('general_s_form'); // variabila pentru formularul de general settings
let site_title_inp = document.getElementById('site_title_inp'); // variabila pentru inputul de site title
let site_about_inp = document.getElementById('site_about_inp'); // variabila pentru inputul de site about 

let contacts_s_form = document.getElementById('contacts_s_form'); // variabila pentru formularul de contact settings

let team_s_form = document.getElementById('team_s_form'); // variabila pentru formularul de team settings

let member_name_inp = document.getElementById('member_name_inp'); // variabila pentru inputul de nume al membrului
let member_picture_inp = document.getElementById('member_picture_inp'); // variabila pentru inputul de poza al membrului

function get_general(){ // functie pentru a lua general settings din baza de date
    let site_title = document.getElementById('site_title'); // variabila pentru site title
    let site_about = document.getElementById('site_about');// variabila pentru site about


    let shutdown_toggle = document.getElementById('shutdown-toggle'); // variabila pentru shutdown toggle

    let xhr= new XMLHttpRequest(); 
    xhr.open("POST", "ajax/settings_crud.php", true); // deschide cererea ajax 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function(){ 
        general_data = JSON.parse(this.responseText); // parseaza raspunsul ajax in format json 

        site_title.innerText = general_data.site_title; // seteaza continutul divului cu id-ul "site_title" cu site title-ul din baza de date
        site_about.innerText = general_data.site_about; // seteaza continutul divului cu id-ul "site_about" cu site about-ul din baza de date
 
        site_title_inp.value = general_data.site_title; // seteaza valoarea inputului cu id-ul "site_title_inp" cu site title-ul din baza de date
        site_about_inp.value = general_data.site_about; // seteaza valoarea inputului cu id-ul "site_about_inp" cu site about-ul din baza de date

        if (general_data.shutdown == 0) { // daca shutdown-ul din baza de date este 0
            shutdown_toggle.checked = false;
            shutdown_toggle.value = 0; // seteaza valoarea toggle-ului cu id-ul "shutdown-toggle" cu 0
        }
        else{
            shutdown_toggle.checked = true; // seteaza valoarea toggle-ului cu id-ul "shutdown-toggle" cu 1
            shutdown_toggle.value = 1;
        }
    };
    xhr.send("get_general");
}

general_s_form.addEventListener('submit', function(e){ // asculta evenimentul de submit al formularului cu id-ul "general_s_form"
    e.preventDefault();
    upd_general(site_title_inp.value, site_about_inp.value); // apeleaza functia upd_general cu parametrii site_title_inp.value si site_about_inp.value
});

function upd_general(site_title_val, site_about_val)// functie pentru a updata general settings 
{
    console.log("Updating data..."); 

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {

        var myModal = document.getElementById('general-s'); // variabila pentru modalul de general settings
        var modal = bootstrap.Modal.getInstance(myModal); 
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Data updated successfully');
            get_general();
        } else {
            alert('danger', 'Failed to update data');
        }
        
    };
    xhr.send('site_title=' + site_title_val + '&site_about=' + site_about_val + '&upd_general'); // trimite form data prin cererea ajax 
}
    
function upd_shutdown(val) { // functie pentru a updata shutdown-ul
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (this.responseText == 1) {
            alert('success', 'Shutdown successfully');
            get_general(); // apeleaza functia get_general
        } else {
            alert('error', 'Shutdown unsuccessful');
        }
    };

    xhr.send('upd_shutdown=' + val); // trimite form data prin cererea ajax
}

function get_contacts(){ // functie pentru a lua contact settings din baza de date
  
    let contacts_p_id = [
        'address',
        'gmap',
        'pn1',
        'pn2',
        'email',
        'fb',
        'insta',
        'twitter'];
    
    let iframe = document.getElementById('iframe'); // variabila pentru iframe 

    let xhr= new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
       contacts_data = JSON.parse(this.responseText); // parseaza raspunsul ajax in format json
       contacts_data = Object.values(contacts_data); // transforma obiectul in array 
       console.log(contacts_data);

       for(i=0; i<contacts_p_id.length; i++){ // parcurge array-ul contacts_p_id 
           document.getElementById(contacts_p_id[i]).innerText = contacts_data[i+1]; // seteaza continutul divului cu id-ul contacts_p_id[i] cu valoarea din array-ul contacts_data de pe pozitia i+1
       }
       iframe.src = contacts_data[9]; // seteaza sursa iframe-ului cu id-ul "iframe" cu valoarea din array-ul contacts_data de pe pozitia 9
       contacts_inp(contacts_data); // apeleaza functia contacts_inp cu parametrul contacts_data
    }
    xhr.send("get_contacts"); 

}

function contacts_inp(data){ // functie pentru a seta valorile inputurilor din contact settings
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'twitter_inp', 'iframe_inp'];

    for(i=0; i<contacts_inp_id.length; i++){ // parcurge array-ul contacts_inp_id
        document.getElementById(contacts_inp_id[i]).value = data[i+1]; // seteaza valoarea inputului cu id-ul contacts_inp_id[i] cu valoarea din array-ul data de pe pozitia i+1
    }
}

contacts_s_form.addEventListener('submit', function(e){
    e.preventDefaut();
    upd_contacts();
});

function upd_contacts(){ // functie pentru a updata contact settings 
    let index = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'twitter', 'iframe']; // array pentru indexul din baza de date
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'twitter_inp', 'iframe_inp']; // array pentru id-ul inputurilor
    
    let data_str=""; // variabila pentru stringul de date

    for(i=0; i<index.length; i++){ // parcurge array-ul index
        data_str += index[i] + "=" + document.getElementById(contacts_inp_id[i]).value + "&"; // adauga indexul si valoarea inputului in stringul de date
    }
    data_str += "upd_contacts"; // adauga "upd_contacts" la stringul de date

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function(){ 
        var myModal = document.getElementById('contacts-s'); // variabila pentru modalul de contact settings
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if(this.responseText == 1){
            alert('success', 'Data updated successfully');
            get_contacts(); // apeleaza functia get_contacts
        }
        else{
            alert('danger', 'Failed to update data');
        }
    }

    xhr.send(data_str); // trimite form data prin cererea ajax
}

team_s_form.addEventListener('submit', function(e){
    e.preventDefault();
    add_member();
});

function add_member(){// add member function 
    let data = new FormData(); // variabila pentru form data
    data.append('name', member_name_inp.value); 
    data.append('picture', member_picture_inp.files[0]); // adauga numele si poza membrului in form data
    data.append('add_member', ''); // adauga "add_member" in form data

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);

    xhr.onload = function () {
        console.log(this.responseText);
        var myModal = document.getElementById('team-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == "Invalid Image Format"){
            alert('error', 'Invalid Image Format');
        }
        else if (this.responseText == "Image size should be less than 2MB"){
            alert('error', 'Image size should be less than 2MB');
        }
        else if (this.responseText == "Image cannot be uploaded"){
            alert('error', 'Image cannot be uploaded');
        }
        else if (this.responseText == 1) {
            alert('success', 'Member added successfully');
            member_name_inp.value = ""; // seteaza valoarea inputului cu id-ul "member_name_inp" cu "" 
            member_picture_inp.value = ""; // seteaza valoarea inputului cu id-ul "member_picture_inp" cu ""
            get_members();
        } else {
            alert('error', 'Failed to add member');
        } 
    };
    xhr.send(data);
}

function get_members(){ // functie pentru a lua membrii din baza de date
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        document.getElementById('team-data').innerHTML = this.responseText; // seteaza continutul divului cu id-ul "team-data" cu raspunsul ajax
        console.log(data);
    }
    xhr.send("get_members");
}

function rem_member(val){ // functie pentru a sterge un membru
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        if(this.responseText == 1){
            alert('success', 'Member removed successfully');
            get_members();
        }
        else{
            alert('error', 'Failed to remove member');
        }
    }
    xhr.send("rem_member=" + val); // trimite form data prin cererea ajax

}

window.onload = function(){ // functie pentru a apela functiile get_general, get_contacts si get_members
    get_general();
    get_contacts();
    get_members();
}
