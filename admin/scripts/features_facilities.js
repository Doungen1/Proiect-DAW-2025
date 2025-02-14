
    let feature_s_form = document.getElementById('feature_s_form'); // primeste formularul cu id-ul "feature_s_form"
    let facility_s_form = document.getElementById('facility_s_form'); // primeste formularul cu id-ul "facility_s_form"

    feature_s_form.addEventListener('submit', function(e){ // asculta evenimentul de submit al formularului cu id-ul "feature_s_form"
        e.preventDefault(); // previne reincarcarea paginii
        add_feature(); // apeleaza functia add_feature
    });

    function add_feature(){ // add feature function

        let data = new FormData(); // formeaza un obiect FormData
        data.append('name', feature_s_form.elements['feature_name'].value); // adauga numele feature-ului in obiectul FormData
        data.append('add_feature', ''); // adauga add_feature in obiectul FormData

        let xhr = new XMLHttpRequest(); // cerere ajax
        xhr.open("POST", "ajax/features_facilities.php", true); // deschide cererea ajax

        xhr.onload = function () { // raspunsul ajax
            var myModal = document.getElementById('feature-s'); // primeste modalul cu id-ul "feature-s"
            var modal = bootstrap.Modal.getInstance(myModal); // primeste instanta modalului cu id-ul "feature-s"
            modal.hide(); // ascunde modalul

            if (this.responseText == 1){ // Daca raspunsul este 1
                alert('success', 'Feature Added Successfully'); // apeleaza functia alert cu parametrii "success" si "Feature Added Successfully"
                feature_s_form.elements['feature_name'].value =''; // reseteaza inputul
                get_features(); // functia pentru a lua feature-urile din baza de date
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger') // apeleaza functia alert cu parametrii "error" si "Something went wrong. Please try again later."
            }
        } 
        xhr.send(data); // trimite form data prin cererea ajax
    };

    function get_features(){ // functie pentru a lua feature-urile din baza de date
        let xhr = new XMLHttpRequest(); 
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function(){
            document.getElementById('features-data').innerHTML = this.responseText; // seteaza continutul divului cu id-ul "features-data" cu raspunsul ajax
            console.log(data);
        }
        xhr.send("get_features");
    };

    function rem_feature(val){ // functie pentru a sterge un feature
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function(){
            if(this.responseText == 1){
                alert('success', 'Feature Deleted Successfully');
                get_features();
            }
            else if(this.responseText == 'room_added'){
                alert('error', 'This feature is added to some rooms. Please remove it from there first.', 'danger');
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        }
        xhr.send("rem_feature=" + val); // transmite "rem_feature" prin cererea ajax
    };

    facility_s_form.addEventListener('submit', function(e){ // asculta evenimentul de submit al formularului cu id-ul "facility_s_form"
        e.preventDefault();
        add_facility();
    });

    function add_facility(){// add facility function
        let data = new FormData();
        data.append('name', facility_s_form.elements['facility_name'].value); // adauga numele facility-ului in obiectul FormData
        data.append('icon', facility_s_form.elements['facility_icon'].files[0]);// adauga icon-ul facility-ului in obiectul FormData
        data.append('desc', facility_s_form.elements['facility_desc'].value);// adauga descrierea facility-ului in obiectul FormData
        data.append('add_facility', ''); // adauga add_facility in obiectul FormData

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);

        xhr.onload = function () {
            var myModal = document.getElementById('facility-s');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText == 'Invalid Image Format'){
                alert('error', 'Invalid Image Format. Only SVG is allowed.', 'danger');
            }
            else if(this.responseText =='inv_size'){
                alert('error', 'Image size should be less than 5MB', 'danger');
            }
            else if(this.responseText == 'upd_failed'){
                alert('error', 'Image cannot be uploaded', 'danger');
            }
            else if (this.responseText == 1){
                alert('success', 'Facility Added Successfully'); // apeleaza functia alert cu parametrii "success" si "Facility Added Successfully"
                facility_s_form.elements['facility_name'].value =''; // reseteaza inputul
                facility_s_form.elements['facility_icon'].value =''; // reseteaza inputul
                facility_s_form.elements['facility_desc'].value =''; 
                get_facilities();
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger')
            }
        } 
        xhr.send(data);
    };

    function get_facilities() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            // Setează conținutul elementului cu id-ul 'facilities-data' cu răspunsul AJAX
            document.getElementById('facilities-data').innerHTML = this.responseText;

            // Afișează răspunsul în consolă pentru debugging
            console.log(this.responseText);
        }

        xhr.onerror = function() {
            console.error('An error occurred during the request.');
        }

        xhr.send("get_facilities");
    };

    function rem_facility(val){ // functie pentru a sterge un facility
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function(){
            if(this.responseText == 1){ // daca raspunsul este 1
                alert('success', 'Facility Deleted Successfully');
                get_facilities();
            }
            else if(this.responseText == 'room_added'){ // daca raspunsul este "room_added" 
                alert('error', 'This facility is added to some rooms. Please remove it from there first.', 'danger');
            }
            else{
                alert('error', 'Something went wrong. Please try again later.', 'danger');
            }
        }
        xhr.send("rem_facility=" + val);
    };

    window.onload = function(){ // cand pagina este incarcata
        get_features(); // apeleaza functia get_features();
        get_facilities(); // apeleaza functia get_facilities();
    }