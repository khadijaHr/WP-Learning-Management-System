<?php
// Shortcode pour la page de formations
add_shortcode('day_formations', 'cap_day1_formations_shortcode');

function cap_day1_formations_shortcode() {
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
    include CAP_PLUGIN_DIR . 'includes/templates/day_formation.php';
    return ob_get_clean();
}

