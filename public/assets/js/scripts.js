/*!
* Start Bootstrap - Freelancer v7.0.7 (https://startbootstrap.com/theme/freelancer)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-freelancer/blob/master/LICENSE)
*/
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });
});

//Supprime les messages flashs après 5s avec une transition fading
$(document).ready( function(){
    window.setTimeout(function(){
        $('.alert').fadeTo("slow", 0.5, function(){
            $('.alert').alert('close')
        });
    }, 5000)
});

$(document).on('change', '#sortie_form_ville', function () {
    /* chargement des lieux de la ville concernée */
    chargerListeLieux();
})

/* fonction permettant de recuperer les lieux en fonction de la ville selectionnée */
function chargerListeLieux(){
    $.ajax({
        method: "POST",
        url: "/ajax/rechercheLieuByVille",
        data: {
            'ville_id' : $('#sortie_form_ville').val() //Recupération de la ville dans la data
        }
    }).done(function (response)
    {
        $('#sortie_form_lieu').html(''); //Initialisation de la liste des lieux

        for (var i = 0 ; i < response.length ; i++) //Chargement des lieux fournis dans la response, dans la liste deroulante
        {
            var lieu = response[i];
            let option = $('<option value="'+lieu["id"]+'">'+lieu["nom"]+'</option>');
            $('#sortie_form_lieu').append(option);
        }
    })
}