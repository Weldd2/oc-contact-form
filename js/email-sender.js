jQuery(document).ready(function($) {
    $('#ccf-form').on('submit', function(event) {
        var nom = $('#ccf-nom').val().trim();
        var prenom = $('#ccf-prenom').val().trim();
        var email = $('#ccf-email').val().trim();
        var tel = $('#ccf-tel').val().trim();

        if(nom === '' || prenom === '' || email === '' || tel === '') {
            alert('Veuillez remplir tous les champs obligatoires.');
            event.preventDefault();
            return;
        }
        
        // Vérification de l'email
        var email = $('#ccf-email').val();
        var emailReg = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailReg.test(email)) {
            alert('Email invalide');
            return false;
        }

        // Vérification du numéro de téléphone
        var tel = $('#ccf-tel').val();
        var telReg = /^(?:\+33|0)[1-9](?:[\s\-]?[0-9]{2}){4}$/;
        if (!telReg.test(tel)) {
            alert('Numéro de téléphone invalide');
            return false;
        }

        event.preventDefault();

        var formData = $(this).serialize();
		console.log(formData);
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'my_send_email_function',
                security: my_send_email_vars.nonce,
                formData: formData
            },
            success: function(response) {
                var jsonResponse = JSON.parse(response);

                if (jsonResponse.status === 'success') {
                    console.log('Données de l\'email : ', jsonResponse.emailData);
                }

                alert(jsonResponse.message);
                window.location.href = '/';
            },
            error: function() {XMLDocument
                alert('Erreur pendant la soumission');
            }
        });
    });
});
