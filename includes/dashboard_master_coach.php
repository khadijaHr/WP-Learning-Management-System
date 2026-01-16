<?php
add_shortcode('dashboard_master_coach', 'dashboard_master_coach_shortcode');

function dashboard_master_coach_shortcode() {
    // Démarrer la session si nécessaire
    if (!session_id()) {
        session_start();
    }

    if (!cap_is_user_logged_in()) {
        return '<p>'.__('Vous devez être connecté pour accéder à cette page.', 'custom-auth-profile').' <a href="'.esc_url(home_url('/login')).'">'.__('Se connecter', 'custom-auth-profile').'</a></p>';
    }
    
    ob_start();
    include CAP_PLUGIN_DIR . 'includes/templates/header_dashboard.php';
    include CAP_PLUGIN_DIR . 'includes/templates/dashboard.php';
    return ob_get_clean();
}


// function get_nouvelles_inscriptions() {
//     global $wpdb;

//     $regions = ['rabat_sale_kenitra', 'casablanca_settat', 'fes_meknes', 'beni_mellal_khenifra'];
//     $roles = ['Participant', 'Coach'];

//     $nouvelles_inscriptions = [];

//     foreach ($regions as $region) {
//         foreach ($roles as $role) {
//             $rows = $wpdb->get_results($wpdb->prepare(
//                 "SELECT nom, prenom, genre
//                  FROM {$wpdb->prefix}lms_users
//                  WHERE region = %s AND role = %s
//                  ORDER BY created_at DESC
//                  LIMIT 6", $region, $role
//             ));

//             $avatars = [];
//             foreach ($rows as $row) {
//                 $initial = strtoupper(substr($row->prenom ?: $row->nom, 0, 1));
//                 $avatars[] = $initial;
//             }

//             $nouvelles_inscriptions[] = [
//                 'label' => $role,
//                 'avatars' => array_slice($avatars, 0, 6),
//                 'extra_count' => max(0, count($avatars) - 6),
//                 'percentage' => rand(80, 95), // à remplacer par un calcul réel si besoin
//                 'location' => $region
//             ];
//         }
//     }

//     return $nouvelles_inscriptions;
// }

function get_nouvelles_inscriptions() {
    global $wpdb;

    $roles = ['Participant', 'Coach'];
    $nouvelles_inscriptions = [];

    foreach ($roles as $role) {
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT nom, prenom, region
             FROM {$wpdb->prefix}lms_users
             WHERE role = %s
             ORDER BY created_at DESC
             LIMIT 4", $role
        ));

        $avatars = [];
        foreach ($rows as $row) {
            $initial = strtoupper(substr($row->prenom ?: $row->nom, 0, 1));
            $avatars[] = $initial;
        }

        $nouvelles_inscriptions[] = [
            'label' => $role,
            'avatars' => array_slice($avatars, 0, 6),
            'extra_count' => max(0, count($avatars) - 6),
            'percentage' => rand(80, 95), // ou un calcul réel
            'location' => $rows[0]->region ?? '---' // région du dernier utilisateur
        ];
    }

    return $nouvelles_inscriptions;
}

/*** */
function get_coachs_by_region() {
    global $wpdb;
    
    return $wpdb->get_results(
        "SELECT 
            region, 
            COUNT(id) as count, 
            ROUND((COUNT(id) * 100 / NULLIF((SELECT COUNT(*) FROM {$wpdb->prefix}lms_users WHERE role = 'Coach'), 0)), 2) as percentage
         FROM 
            {$wpdb->prefix}lms_users 
         WHERE 
            role = 'Coach' AND 
            statut = 1
         GROUP BY 
            region
         ORDER BY 
            count DESC"
    );
}