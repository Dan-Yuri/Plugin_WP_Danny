<?php
/**
* Plugin Name: Custom Registration Form
* Description: Un plugin pour créer un formulaire d'enregistrement personnalisé.
* Version: 3.0
* Author: Danny
*/

function create_custom_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . "custom_form_entries";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name text NOT NULL,
        last_name text NOT NULL,
        dob date NOT NULL,
        current_diploma text NOT NULL,
        current_study_level text NOT NULL,
        target_training text NOT NULL,
        city text NOT NULL,
        mobility int NOT NULL,
        free_text text,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_custom_table');

function display_custom_form() {
    global $wpdb;
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_form'])) {
        $data = array(
            'first_name'         => sanitize_text_field($_POST['first_name']),
            'last_name'          => sanitize_text_field($_POST['last_name']),
            'dob'                => sanitize_text_field($_POST['dob']),
            'current_diploma'    => sanitize_text_field($_POST['current_diploma']),
            'current_study_level'=> sanitize_text_field($_POST['current_study_level']),
            'target_training'    => sanitize_text_field($_POST['target_training']),
            'city'               => sanitize_text_field($_POST['city']),
            'mobility'           => (int) $_POST['mobility'], // Convertir en entier
            'free_text'          => sanitize_textarea_field($_POST['free_text'])
        );

        $wpdb->insert('wp_custom_form_entries', $data);
        
    }

    ob_start();
    ?>
<style> 
div { 
margin-bottom:2px; 
} 

input{ 
margin-bottom:4px; 
} 
</style> 

    <form action="" method="post" id="custom_candidate_form">

        <div>
        <label for="first_name">Prénom</label>
        <input type="text" id="first_name" name="first_name" required>
        </div>

        <div>
        <label for="last_name">Nom</label>
        <input type="text" id="last_name" name="last_name" required>
        </div>

        <div>
        <label for="dob">Date de Naissance</label>
        <input type="date" id="dob" name="dob" required>
        </div>

        <div>
        <label for="current_diploma">Libellé du diplôme actuel</label>
        <input type="text" id="current_diploma" name="current_diploma" required>
        </div>

        <div>
        <label for="current_study_level">Niveau d'études actuel</label>
        <input type="text" id="current_study_level" name="current_study_level" required>
        </div>

        <div>
        <label for="target_training">Formation visée</label>
        <input type="text" id="target_training" name="target_training" required>
        </div>

        <div>
        <label for="city">Ville d'habitation</label>
        <input type="text" id="city" name="city" required>
        </div>

        <div>
        <label for="mobility">Mobilité (en kilomètres depuis le lieu de vie)</label>
        <input type="number" id="mobility" name="mobility" required>
        </div>

        <div>
        <label for="free_text">Texte libre</label>
        <textarea id="free_text" name="free_text"></textarea>
        </div>

        <input type="submit" name="submit_form" value="Envoyer">
    </form>

    <?php
    return ob_get_clean();

}
add_shortcode('custom_form', 'display_custom_form');