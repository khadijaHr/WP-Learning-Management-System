<?php
/*
  * Plugin Name:        WP Learning Management System
  * Description:        Système complet de gestion de formations et d'apprentissage pour WordPress. Offre une authentification personnalisée, une gestion avancée des profils utilisateurs (Participants, Coachs, Master Coachs), un système complet de formations avec inscriptions, un tableau de bord interactif, des statistiques détaillées, et une interface d'administration complète. Idéal pour les plateformes de formation en ligne nécessitant une gestion multi-rôles et un suivi détaillé des participants et des formations.
  * Version:           	1.3.0
  * Requires at least: 	6.7
  * Requires PHP:      	7.0
  * Author:            	Khadija Har
  * Author URI:        	https://github.com/khadijahr  
  * License:           	GPL v3 or later
  * License URI:       	https://www.gnu.org/licenses/gpl-3.0.html
*/

// Sécurité
defined('ABSPATH') or die('Accès interdit!');

// Initialiser les sessions
add_action('init', 'cap_start_session', 1);
function cap_start_session() {
    if (!session_id()) {
        session_start();
    }
}

add_action('wp_head', 'mon_plugin_add_viewport_meta');
function mon_plugin_add_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}

// Charger le CSS
add_action('wp_enqueue_scripts', 'mon_plugin_charger_assets');

function mon_plugin_charger_assets() {
    // CSS
    wp_enqueue_style(
        'mon-plugin-styles',
        plugin_dir_url(__FILE__) . 'assets/css/wp_style_min.css',
        array(),
        '1.0'
    );

    // JS principal
    wp_enqueue_script(
        'mon-plugin-scripts',
        plugin_dir_url(__FILE__) . 'assets/js/wp_script.js',
        array('jquery'),
        '1.0',
        true
    );

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
    
    // JS participant (chargé uniquement si le fichier existe)
    $participant_js_path = plugin_dir_path(__FILE__) . 'assets/js/participant-registration.js';
    $participant_js_url = plugin_dir_url(__FILE__) . 'assets/js/participant-registration.js';

    if (file_exists($participant_js_path)) {

        wp_enqueue_script(
            'participant-registration',
            $participant_js_url,
            array('jquery'),
            filemtime($participant_js_path),
            true
        );

        // Localisation correcte avec le bon handle
        wp_localize_script(
            'participant-registration',
            'participantRegistration', // Nom plus simple et standard
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('participant_reg_nonce')
            )
        );

    }
    $script_coach_path = plugin_dir_path(__FILE__) . 'assets/js/coach-script.js';
    $script_coach_url = plugin_dir_url(__FILE__) . 'assets/js/coach-script.js';

    if (file_exists($script_coach_path)) {
        wp_enqueue_script(
            'coach-script',
            $script_coach_url,
            array('jquery'),
            filemtime($script_coach_path),
            true
        );
    
        // Localisation correcte avec le bon handle
        wp_localize_script(
            'coach-script',
            'coachData', // Nom plus simple et standard
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('coach_ajax_nonce')
            )
        );
    }
    
    // wp_enqueue_script('coach-script', plugin_dir_url(__FILE__)  . 'assets/js/coach-script.js', array('jquery'), '1.0', true);
    
    // wp_localize_script('coach-script', 'coachData', array(
    //     'ajaxurl' => admin_url('admin-ajax.php'),
    //     'nonce' => wp_create_nonce('coach_ajax_nonce')
    // ));
    
}


// Constantes
define('CAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CAP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Inclusions
require_once CAP_PLUGIN_DIR . 'includes/db.php';
require_once CAP_PLUGIN_DIR . 'includes/auth.php';
require_once CAP_PLUGIN_DIR . 'includes/downloads.php';
require_once CAP_PLUGIN_DIR . 'includes/profile.php';
require_once CAP_PLUGIN_DIR . 'includes/profile_coach.php';
require_once CAP_PLUGIN_DIR . 'includes/formations.php';
require_once CAP_PLUGIN_DIR . 'includes/mes_formations.php';
require_once CAP_PLUGIN_DIR . 'includes/inscription.php';
require_once CAP_PLUGIN_DIR . 'includes/day_formation.php';
require_once CAP_PLUGIN_DIR . 'includes/detail_formation.php';
require_once CAP_PLUGIN_DIR . 'includes/shortcodes.php';
require_once CAP_PLUGIN_DIR . 'includes/admin.php';
require_once CAP_PLUGIN_DIR . 'includes/admin-formations.php';
require_once CAP_PLUGIN_DIR . 'includes/participant-registration.php';
require_once CAP_PLUGIN_DIR . 'includes/manage-formation.php';
require_once CAP_PLUGIN_DIR . 'includes/dashboard_master_coach.php';
require_once CAP_PLUGIN_DIR . 'includes/statistiques.php';


// Activation/désactivation
register_activation_hook(__FILE__, 'cap_install_tables');
register_deactivation_hook(__FILE__, 'cap_deactivate');

// Initialisation
add_action('plugins_loaded', 'cap_init');

function cap_init() {
    // Charger les traductions si nécessaire
    load_plugin_textdomain('custom-auth-profile', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    // Ajoutez ces lignes :
    add_filter('manage_users_columns', 'cap_add_user_column');
    add_action('manage_users_custom_column', 'cap_show_user_column_content', 10, 3);
}

// Ajoutez ces fonctions après la fonction cap_init()
function cap_add_user_column($columns) {
    $columns['lms_user'] = 'Utilisateur LMS';
    return $columns;
}

function cap_show_user_column_content($value, $column_name, $user_id) {
    if ('lms_user' == $column_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'lms_users';
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE email = %s", get_userdata($user_id)->user_email));

        return $user ? 'Oui (' . $user->role . ')' : 'Non';
    }
    return $value;
}

// Ajoutez ceci dans la fonction cap_init()
add_action('admin_menu', 'cap_admin_menu');

function cap_admin_menu() {
    add_menu_page(
        'Utilisateurs LMS',
        'Utilisateurs LMS',
        'edit_others_posts',
        'custom-users-lms',
        'cap_users_list_page',
        'dashicons-groups',
        30
    );

    add_submenu_page(
        'custom-users-lms',
        'Ajouter un utilisateur',
        'Ajouter',
        'edit_others_posts',
        'custom-users-lms-add',
        'cap_add_user_page'
    );
}

add_filter('theme_page_templates', 'cap_add_file_viewer_template');
function cap_add_file_viewer_template($templates) {
    $templates['file-viewer-template.php'] = 'File Viewer Template';
    return $templates;
}

add_filter('page_template', 'cap_load_file_viewer_template');
function cap_load_file_viewer_template($template) {
    if (get_page_template_slug() === 'file-viewer-template.php') {
        return plugin_dir_path(__FILE__) . 'templates/file-viewer-template.php';
    }
    return $template;
}