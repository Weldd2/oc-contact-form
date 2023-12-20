<?php
// [ccf_form] shortcode pour afficher le formulaire sur le site
function ccf_display_form() {
    ob_start();
   
    $profils = [
        [
            "titre" => "Payer trop d'impôts",
            "picto" => "trop-dimpots.svg"
        ],
        [
            "titre" => "Être insatisfait(e) du livret A",
            "picto" => "insatisfaite-du-livret-A-1.svg"
        ],
        [
            "titre" => "Être amoureux(se) de la pierre",
            "picto" => "Comment-investir-dans-limmobilier.svg"
        ],
        [
            "titre" => "Rechercher des placements éthiques",
            "picto" => "Les-investissements-ethiques.svg"
        ]
    ];

    $besoins = [
        [
            "titre" => "Optimiser votre fiscalité",
            "picto" => "comment-payer-moins-impot.svg"
        ],
        [
            "titre" => "Booster vos placements",
            "picto" => "comment-bosster-son-epargne.svg"
        ],
        [
            "titre" => "Structurer votre épargne",
            "picto" => "epargne.svg"
        ],
        [
            "titre" => "Préparer l'avenir",
            "picto" => "preparer-retraite.svg"
        ]
    ];
    

    ?>
    <form id="ccf-form" class="oc-custom-form">

        <div class="name-container">
            <!-- Nom -->
            <input placeholder="Nom" type="text" id="ccf-nom" name="ccf-nom">
            
            <!-- Prénom -->
            <input placeholder="Prénom" type="text" id="ccf-prenom" name="ccf-prenom">
        </div>
        
        <!-- Email -->
        <input placeholder="Email" type="email" id="ccf-email" name="ccf-email">
        
        <!-- Tel -->
        <input placeholder="Tel" type="tel" id="ccf-tel" name="ccf-tel">

        <div class="ccf-paragraphe">
            <h2 class="ccf-titre h2"><?php echo get_option('titre1', '')?></h2>
            <p class="ccf-paragraphe-text"><?php echo get_option('paragraphe1', '')?></p>
        </div>

        <!-- Catégories -->
        <div class="ccf-field ccf-user_categories categories-container">
            <?php foreach ($profils as $key => $profil) :?>
                <input type="checkbox" name="ccf-user_profile_categories[]" id="profil-<?php echo $key ?>" value="<?php echo $profil['titre'] ?>"></input>
                <label for="profil-<?php echo $key ?>">
                    <div class="img-wrapper"><img src="<?php echo plugin_dir_url(__FILE__); ?>/picto/<?php echo $profil['picto']; ?>" alt="Mon Picto"></div>
                    <span><?php echo $profil['titre']; ?></span>
                </label>
            <?php endforeach;?>
        </div>

        <div class="ccf-paragraphe">
            <h2 class="ccf-titre h2"><?php echo get_option('titre2', '')?></h2>
            <p class="ccf-paragraphe-text"><?php echo get_option('paragraphe2', '')?></p>
        </div>

        <div class="ccf-field ccf-need_categories categories-container">
            <?php foreach ($besoins as $key => $besoin) :?>
                <input type="checkbox" name="ccf-user_need_categories[]" id="besoin-<?php echo $key ?>" value="<?php echo $besoin['titre'] ?>"></input>
                <label for="besoin-<?php echo $key ?>">
                    <div class="img-wrapper"><img src="<?php echo plugin_dir_url(__FILE__); ?>/picto/<?php echo $besoin['picto']; ?>" alt="Mon Picto"></div>
                    <span><?php echo $besoin['titre'] ?></span>
                </label>
            <?php endforeach;?>
        </div>
        <div class="ccf-paragraphe">
            <div class="ccf-titre h2"><?php echo get_option('annotation', '')?></div>
        </div>

        <!-- Message -->
        <textarea placeholder="Entrez votre message ici" id="ccf-message" name="ccf-message" rows="5" cols="100%"></textarea>

        <!-- Bouton de soumission -->
        <button type="submit" id="ccf-btn">Envoyer</button>
    </form>
    <?php
    return ob_get_clean();
}
function my_send_email_function() {
    // Vérifiez le nonce pour la sécurité CSRF
    check_ajax_referer('my_send_email_nonce', 'security');

    // Récupération des données et sanitation
    parse_str(wp_unslash($_POST['formData']), $formData);

    $nom = sanitize_text_field($formData['ccf-nom'] ?? '');
    $prenom = sanitize_text_field($formData['ccf-prenom'] ?? '');
    $email = sanitize_email($formData['ccf-email'] ?? '');
    $tel = sanitize_text_field($formData['ccf-tel'] ?? '');
    $message = sanitize_text_field($formData['ccf-message'] ?? '');
    $user_profile_categories = $formData['ccf-user_profile_categories'] ?? array();
    $user_need_categories = $formData['ccf-user_need_categories'] ?? array();

    // Validation des champs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email invalide']);
        wp_die();
    }
    
    if (!preg_match("/^(?:\+33|0)[1-9](?:[\s\-]?[0-9]{2}){4}$/", $tel)) {
        echo json_encode(['status' => 'error', 'message' => 'Numéro de téléphone invalide']);
        wp_die();
    }

    if (empty($nom) || empty($prenom) || empty($email) || empty($tel) || empty($user_profile_categories) || empty($user_need_categories)) {
        echo json_encode(['status' => 'error', 'message' => 'Veuillez remplir tous les champs obligatoires.']);
        wp_die();
    }

    // Construction du corps de l'email
    $body = "De : " . $nom . " " . $prenom;
    $body .= "\nEmail : " . $email;
    $body .= "\nTel : " . $tel;
    $body .= "\nProfil : " . implode(", ", $user_profile_categories);
    $body .= "\nBesoins : " . implode(", ", $user_need_categories);
    $body .= "\nMessage : " . $message;

    // Destinataire de l'email
    $to = get_option('ccf_email_address', 'contact@confidence-conseils.fr');

    // Sujet de l'email
    $subject = 'Nouvelle soumission de formulaire';

    // Envoi de l'email
    if (wp_mail($to, $subject, $body)) {
        echo json_encode(['status' => 'success', 'message' => 'Email envoyé !', 'emailData' => $body]);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Erreur lors de l\'envoi de l\'email']);
    }

    wp_die();
}



add_shortcode('ccf_form', 'ccf_display_form');
add_action('wp_ajax_my_send_email_function', 'my_send_email_function');
add_action('wp_ajax_nopriv_my_send_email_function', 'my_send_email_function');
?>