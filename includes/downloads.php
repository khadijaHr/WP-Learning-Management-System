<?php
/**
 * Gestion des téléchargements de fichiers
 */

if (!defined('ABSPATH')) {
    exit; // Sécurité
}

function cap_handle_file_actions() {
    if (isset($_GET['action']) && $_GET['action'] === 'download' && isset($_GET['file_id'])) {
        // Validation
        $file_id = absint($_GET['file_id']);
        $nonce = sanitize_text_field($_GET['nonce'] ?? '');
        
        if (!wp_verify_nonce($nonce, 'download_'.$file_id)) {
            wp_die('Accès non autorisé');
        }
        
        global $wpdb;
        $fichier = $wpdb->get_row($wpdb->prepare(
            "SELECT f.*, j.formation_id 
             FROM {$wpdb->prefix}formation_fichiers f
             JOIN {$wpdb->prefix}formation_jours j ON f.jour_id = j.id
             WHERE f.id = %d",
            $file_id
        ));
        
        if ($fichier && cap_is_user_logged_in()) {
            $user = cap_get_current_user();
            $user_id = $user->id;
            
            // Enregistrer l'accès
            cap_enregistrer_acces_formation($user_id, $fichier->formation_id);
            
            // Rediriger vers le fichier
            wp_redirect(esc_url_raw($fichier->chemin_fichier));
            exit;
        }
    }
}
add_action('init', 'cap_handle_file_actions');