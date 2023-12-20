<?php


function ccf_admin_menu() {
    add_menu_page('Custom Contact Form', 'Custom Contact Form', 'manage_options', 'custom_contact_form', 'ccf_admin_page');
}

// Fonction pour afficher le contenu de la page d'administration
function ccf_admin_page() {
    ?>
    <div class="wrap">
        <h2>Custom Contact Form - Paramètres</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('ccf_settings_group');
            do_settings_sections('custom_contact_form');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'ccf_admin_menu');



?>
