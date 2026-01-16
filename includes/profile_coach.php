<?php
// Shortcode pour la page de profil
add_shortcode('profile_coach', 'cap_profile_coach_shortcode');


function cap_profile_coach_shortcode() {
    // Démarrer la session si nécessaire
    if (!session_id()) {
        session_start();
    }

    if (!cap_is_user_logged_in()) {
        return '<p>'.__('Vous devez être connecté pour accéder à cette page.', 'custom-auth-profile').' <a href="'.esc_url(home_url('/login')).'">'.__('Se connecter', 'custom-auth-profile').'</a></p>';
    }
    
    ob_start();
    global $wpdb;
    // Récupérer l'ID de l'utilisateur connecté
    $current_user = cap_get_current_user();
    
    // Vérifier si l'utilisateur est un coach
    $user_role = $wpdb->get_var($wpdb->prepare(
        "SELECT role FROM {$wpdb->prefix}lms_users WHERE id = %d",
        $current_user->id
    ));
    
    if ($user_role === 'Master Coach') {
        include CAP_PLUGIN_DIR . 'includes/templates/header_dashboard.php';
    }
    else {
        include CAP_PLUGIN_DIR . 'includes/templates/header.php';
    }
    include CAP_PLUGIN_DIR . 'includes/templates/profile_coach.php';
    return ob_get_clean();
}

// Traitement de la mise à jour du profil
add_action('init', 'cap_process_profile_coach_update');

function cap_process_profile_coach_update() {
    if (isset($_POST['cap_profile_coach_nonce']) && wp_verify_nonce($_POST['cap_profile_coach_nonce'], 'cap_profile_coach_action') && cap_is_user_logged_in()) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lms_users';
        $user = cap_get_current_user();
        
        $data = array(
            'nom' => sanitize_text_field($_POST['nom']),
            'prenom' => sanitize_text_field($_POST['prenom']),
            'date_naissance' => sanitize_text_field($_POST['date_naissance']),
            'ville_region' => sanitize_text_field($_POST['ville_region']),
            'email' => sanitize_email($_POST['email']),
            'region' => sanitize_text_field($_POST['region']),
        );
        
        // Gestion du mot de passe (seulement si le champ est rempli)
        if (!empty($_POST['password'])) {
            $password = $_POST['password'];
            if (strlen($password) >= 8) {
                $data['password'] = wp_hash_password($password);
            } else {
                // Gérer l'erreur de mot de passe trop court
                wp_redirect(home_url('/profile-coach?error=password_length'));
                exit;
            }
        }
        
        // Gestion de l'upload d'image        
        if (!empty($_FILES['image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            
            $upload = wp_handle_upload($_FILES['image'], array(
                'test_form' => false,
                'mimes' => array(
                    'jpg|jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif'
                )
            ));
            
            if (isset($upload['error'])) {
                wp_redirect(home_url('/profile-coach?error=upload_error'));
                exit;
            }
            
            $data['image'] = $upload['url'];
        } elseif (isset($_POST['delete_image']) && $_POST['delete_image'] == 1) {
            $data['image'] = ''; // Supprime l'image
        }
        
        // Mise à jour dans la base de données
        $wpdb->update(
            $table_name,
            $data,
            array('id' => $user->id)
        );
        
        // Mettre à jour la session
        $_SESSION['cap_user'] = cap_get_user_by_email($user->email);
        
        // Redirection avec message de succès
        wp_redirect(home_url('/profile-coach?updated=1'));
        exit;
    }
}