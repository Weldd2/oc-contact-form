<?php
function ccf_settings_init() {
    register_setting('ccf_settings_group', 'ccf_email_address');
    register_setting('ccf_settings_group', 'titre1');
    register_setting('ccf_settings_group', 'paragraphe1');
    register_setting('ccf_settings_group', 'titre2');
    register_setting('ccf_settings_group', 'paragraphe2');
    register_setting('ccf_settings_group', 'annotation');

    add_settings_section('ccf_settings_section', 'Paramètres principaux', null, 'custom_contact_form');

    add_settings_field('ccf_email_field', 'Adresse e-mail pour les notifications', 'ccf_email_field_display', 'custom_contact_form', 'ccf_settings_section');
    add_settings_field('ccf_titre1_field', 'Titre du 1er paragraphe', 'ccf_titre_1_field_display', 'custom_contact_form', 'ccf_settings_section');
    add_settings_field('ccf_paragraphe_1_field', 'Contenu du 1er paragraphe', 'ccf_paragraphe_1_field_display', 'custom_contact_form', 'ccf_settings_section');
    add_settings_field('ccf_titre2_field', 'Titre du 2eme paragraphe', 'ccf_titre_2_field_display', 'custom_contact_form', 'ccf_settings_section');
    add_settings_field('ccf_paragraphe_2_field', 'Contenu du 2eme paragraphe', 'ccf_paragraphe_2_field_display', 'custom_contact_form', 'ccf_settings_section');
    add_settings_field('ccf_annotation_field', 'Annotation 1', 'ccf_annotation_field_display', 'custom_contact_form', 'ccf_settings_section');
}

// Fonction pour afficher le champ email dans le BO
function ccf_email_field_display() {
?>
    <input type="email" name="ccf_email_address" size="100%" value="<?php echo esc_attr(get_option('ccf_email_address')); ?>">
<?php
}

function ccf_titre_1_field_display() {
    ?>
        <input type="text" id="titre1" size="100%" name="titre1" value="<?php echo esc_attr(get_option('titre1')); ?>"/>
    <?php
}

function ccf_paragraphe_1_field_display() {
    ?>
        <textarea id="paragraphe1" name="paragraphe1" rows="5" cols="100%"><?php echo esc_attr(get_option('paragraphe1')); ?></textarea>
    <?php
}

function ccf_titre_2_field_display() {
    ?>
        <input type="text" id="titre2" size="100%" name="titre2" value="<?php echo esc_attr(get_option('titre2')); ?>"/>
    <?php
}

function ccf_paragraphe_2_field_display() {
    ?>
        <textarea id="paragraphe2" name="paragraphe2" rows="5" cols="100%"><?php echo esc_attr(get_option('paragraphe2')); ?></textarea>
    <?php
}

function ccf_annotation_field_display() {
    ?>
        <input type="text" id="annotation" size="100%" name="annotation" value="<?php echo esc_attr(get_option('annotation')); ?>"/>
    <?php
}

add_action('admin_init', 'ccf_settings_init');


?>