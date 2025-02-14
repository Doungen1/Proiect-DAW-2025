<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> <!-- Bootstrap -->

<script>

    
    function alert(type, msg, position = 'alert-box') {
        let bs_class = (type == 'success') ? "alert-success" : "alert-danger";
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3"> ${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        let targetElement = document.getElementById(position);

        if (targetElement) {
            targetElement.append(element);
        } else {
            console.warn(`Element with id "${position}" not found. Appending to body.`);
            document.body.append(element);
            element.classList.add('custom-alert');
        }

        setTimeout(remAlert, 2000);
    }


function remAlert() {
    let alertElement = document.querySelector('.custom-alert');
    if (alertElement) {
        alertElement.remove();
    }
}


// Asigură-te că elementul cu id-ul 'alert-box' există în HTML sau folosește 'body'
function showAlert(type, message) {
    alert(type, message, 'alert-box'); // Schimbă 'alert-box' cu 'body' sau un alt id valid dacă e necesar
}



    
    function setActive(){ // Seteaza link-ul activ din meniul din stanga 
    let navbar = document.getElementById('dashboard-menu'); // Variabila cu meniul din stanga
    let a_tags = navbar.getElementsByTagName('a'); // Variabila cu toate tag-urile a din meniul din stanga

    for(i=0; i<a_tags.length; i++){ // Parcurge toate tag-urile a din meniul din stanga
      let file = a_tags[i].href.split('/').pop(); // Variabila cu numele fisierului din link-ul tag-ului a
      let file_name = file.split('.')[0]; // Variabila cu numele fisierului din link-ul tag-ului a fara extensie

      if (document.location.href.indexOf(file_name)>=0){ // Daca link-ul tag-ului a este egal cu link-ul paginii curente
        a_tags[i].classList.add('active'); // Adauga clasa active tag-ului a
      }
    }
    }

      setActive(); // Seteaza link-ul activ din meniul din stanga
</script> <!-- Scripts -->