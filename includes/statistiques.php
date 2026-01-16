<?php
add_shortcode('statistiques_coach', 'statistiques_shortcode');

function statistiques_shortcode() {
    // Démarrer la session si nécessaire
    if (!session_id()) {
        session_start();
    }

    if (!cap_is_user_logged_in()) {
        return '<p>'.__('Vous devez être connecté pour accéder à cette page.', 'custom-auth-profile').' <a href="'.esc_url(home_url('/login')).'">'.__('Se connecter', 'custom-auth-profile').'</a></p>';
    }
    
    ob_start();
    include CAP_PLUGIN_DIR . 'includes/templates/header_dashboard.php';
    include CAP_PLUGIN_DIR . 'includes/templates/statistiques.php';
    return ob_get_clean();
}