<?php
/**
 * Vérifie si l'utilisateur est connecté
 * @return bool
 */
function cap_is_user_logged_in() {
    // Démarrer la session si elle ne l'est pas
    if (!session_id()) {
        session_start();
    }
    return isset($_SESSION['cap_user']) && is_object($_SESSION['cap_user']);
}

/**
 * Récupère l'utilisateur connecté
 * @return object|false
 */
function cap_get_current_user() {
    if (!cap_is_user_logged_in()) {
        return false;
    }
    return $_SESSION['cap_user'];
}


// function cap_is_user_logged_in() {
//     return isset($_SESSION['cap_user']) && !empty($_SESSION['cap_user']->email);
// }

// // Récupérer l'utilisateur connecté
// function cap_get_current_user() {
//     return isset($_SESSION['cap_user']) ? $_SESSION['cap_user'] : false;
// }
// Shortcode pour le formulaire de connexion
add_shortcode('custom_login_form', 'cap_login_form_shortcode');


function cap_login_form_shortcode() {
    ob_start();
    include CAP_PLUGIN_DIR . 'includes/templates/login-form.php';
    return ob_get_clean();
}

// Traitement du formulaire de connexion
add_action('init', 'cap_process_login');


function cap_process_login() {
    if (isset($_POST['cap_login_nonce']) && wp_verify_nonce($_POST['cap_login_nonce'], 'cap_login_action')) {
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        
        $user = cap_get_user_by_email($email);
        
        if ($user && wp_check_password($password, $user->password)) {
            // Démarrer la session
            if (!session_id()) {
                session_start();
            }
            
            // Stocker l'utilisateur en session
            $_SESSION['cap_user'] = $user;
            
            // Redirection en fonction du rôle
            switch ($user->role) {
                case 'Coach':
                    $redirect_url = home_url('/profile-coach');
                    break;
                case 'Master Coach':
                    $redirect_url = home_url('/dashboard-master-coach');
                    break;
                case 'Participant':
                default:
                    $redirect_url = home_url('/formations');
                    break;
            }
            
            wp_redirect($redirect_url);
            exit;
        } else {
            // Erreur de connexion - on reste sur la même page
            if (!session_id()) {
                session_start();
            }
            $_SESSION['login_error'] = 'Email ou le mot de passe incorrect';
            $_SESSION['login_email'] = $email; // Garder l'email saisi
            
            // Recharger la même page sans redirection
            add_action('wp_footer', function() {
                echo '<script>window.history.replaceState({}, document.title, window.location.pathname);</script>';
            });
        }
    }
}

// Traitement du formulaire de connexion
add_action('init', 'cap_process_password');

function cap_process_password() {
    // Gestion de la demande de réinitialisation
    if (isset($_POST['reset_submit']) && wp_verify_nonce($_POST['cap_reset_nonce'], 'cap_reset_action')) {
        $email = sanitize_email($_POST['reset_email']);
        
        // Vérifier si l'email existe dans la base
        global $wpdb;
        $user = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}lms_users WHERE email = %s", 
            $email
        ));
        
        if ($user) {
            // Générer un token unique
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Stocker le token dans la base
            $wpdb->update(
                "{$wpdb->prefix}lms_users",
                array(
                    'reset_token' => $token,
                    'reset_expires' => $expires
                ),
                array('id' => $user->id),
                array('%s', '%s'),
                array('%d')
            );
            
            // Générer le lien
            $reset_link = home_url("/reset-password?token=$token&email=".urlencode($email));
            
            // Envoyer l'email
            $to = $email;
            $subject = 'Réinitialisation de votre mot de passe';
            
            // Récupérer le prénom et le nom de l'utilisateur
            $user_name = trim($user->first_name . ' ' . $user->last_name);
            $greeting = !empty($user_name) ? $user_name : 'Cher utilisateur';
            
            $message = 'Bonjour ' . esc_html($greeting) . ',<br/><br/>';
            $message .= 'Vous avez demandé à réinitialiser votre mot de passe. Voici le lien pour le faire :<br/><br/>';
            $message .= '<a href="' . esc_url($reset_link) . '">' . esc_url($reset_link) . '</a><br/><br/>';
            $message .= 'Ce lien est valable pendant 1 heure.<br/><br/>';
            $message .= 'Si vous n\'avez pas demandé cette réinitialisation, veuillez ignorer cet email.<br/><br/>';
            $message .= 'Cordialement,<br/>L\'équipe LMS';
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            // Envoyer l'email
            $email_sent = wp_mail($to, $subject, $message, $headers);
            
            // Rediriger avec un message de succès
            wp_redirect(add_query_arg('success', 'Un email avec les instructions de réinitialisation a été envoyé à votre adresse email.', wp_get_referer()));
            exit;
            
        } else {
            wp_redirect(add_query_arg('error', 'Aucun compte trouvé avec cet email.', wp_get_referer()));
            exit;
        }
    }
}


// 1. Créer le shortcode pour le formulaire de réinitialisation
add_shortcode('custom_reset_password_form', 'custom_reset_password_form_shortcode');
function custom_reset_password_form_shortcode() {
    ob_start();
    
    // Vérifier token et email dans l'URL
    $token = sanitize_text_field($_GET['token'] ?? '');
    $email = sanitize_email($_GET['email'] ?? '');
    
    if (empty($token) || empty($email)) {
        return '<p class="error">Lien de réinitialisation invalide.</p>';
    }
    
    // Vérifier la validité du token en base
    global $wpdb;
    $user = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}lms_users 
        WHERE email = %s AND reset_token = %s AND reset_expires > NOW()",
        $email,
        $token
    ));
    
    if (!$user) {
        return '<p class="error">Lien expiré ou invalide.</p>';
    }
    
    // Afficher le formulaire de nouveau mot de passe
    ?>
    <div class="login-container">
        <div class="password-reset-form">
            <h2>Réinitialisation du mot de passe</h2>
            <form id="reset-password-form" class="reset-password-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="process_password_reset">
                <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>">
                <input type="hidden" name="email" value="<?php echo esc_attr($email); ?>">
                <?php wp_nonce_field('password_reset_action', 'password_reset_nonce'); ?>
                
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" name="new_password" id="new_password" required style="padding-right: 35px; width: 100%;">
                        <span class="eye-icon toggle-password" data-target="new_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; text-decoration: none !important; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; display: inline-block;"><i class="fas fa-eye"></i></span>
                    </div>
                </div>    
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" name="confirm_password" id="confirm_password" required style="padding-right: 35px; width: 100%;">
                        <span class="eye-icon toggle-password" data-target="confirm_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; text-decoration: none !important; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; display: inline-block;"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="form-group" style="padding-top: 20px;">
                    <button type="submit" name="submit_reset">Enregistrer</button>
                </div>  
            </form>
        </div>
    </div>    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script>
    jQuery(document).ready(function($) {
        // Gestion du toggle des mots de passe
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
            icon.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                
                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    } else {
                        input.type = 'password';
                        this.innerHTML = '<i class="fas fa-eye"></i>';
                    }
                }
            });
        });
    });
    </script>
    <?php
    
    return ob_get_clean();
}

// 2. Traitement du formulaire de réinitialisation
add_action('admin_post_nopriv_process_password_reset', 'handle_password_reset');
add_action('admin_post_process_password_reset', 'handle_password_reset');
function handle_password_reset() {
    // Vérifications de sécurité
    if (!isset($_POST['password_reset_nonce']) || !wp_verify_nonce($_POST['password_reset_nonce'], 'password_reset_action')) {
        wp_die('Erreur de sécurité');
    }
    
    $token = sanitize_text_field($_POST['token']);
    $email = sanitize_email($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Vérifier la correspondance des mots de passe
    if ($new_password !== $confirm_password) {
        wp_redirect(add_query_arg('error', 'Les mots de passe ne correspondent pas.', wp_get_referer()));
        exit;
    }
    
    // Vérifier la validité du token
    global $wpdb;
    $user = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}lms_users 
        WHERE email = %s AND reset_token = %s AND reset_expires > NOW()",
        $email,
        $token
    ));
    
    if (!$user) {
        wp_redirect(add_query_arg('error', 'Lien expiré ou invalide.', wp_get_referer()));
        exit;
    }
    
    // Mettre à jour le mot de passe
    $hashed_password = wp_hash_password($new_password);
    $updated = $wpdb->update(
        "{$wpdb->prefix}lms_users",
        array(
            'password' => $hashed_password,
            'reset_token' => NULL,
            'reset_expires' => NULL
        ),
        array('id' => $user->id),
        array('%s', '%s', '%s'),
        array('%d')
    );
    
    if ($updated) {
        wp_redirect(home_url('espace-participant/?success=password_reset'));
    } else {
        wp_redirect(add_query_arg('error', 'Erreur lors de la mise à jour.', wp_get_referer()));
    }
    exit;
}

/**
 * Enregistre l'accès d'un utilisateur à une formation
 * @param int $user_id ID de l'utilisateur
 * @param int $formation_id ID de la formation
 */
function cap_enregistrer_acces_formation($user_id, $formation_id) {
    global $wpdb;
    
    // Vérifier si l'inscription existe déjà
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}formation_inscriptions 
         WHERE user_id = %d AND formation_id = %d",
        $user_id,
        $formation_id
    ));
    
    if (!$exists) {
        $result = $wpdb->insert(
            "{$wpdb->prefix}formation_inscriptions",
            array(
                'user_id' => $user_id,
                'formation_id' => $formation_id,
                'date_inscription' => current_time('mysql')
            ),
            array('%d', '%d', '%s')
        );
        
        // 4. Gestion du résultat
        if ($result === false) {
            error_log('Erreur DB: ' . $wpdb->last_error);
            // echo 'Échec : ' . $wpdb->last_error;
        } else {
            error_log('Insertion réussie, ID: ' . $wpdb->insert_id);
        }
        
        // Optionnel : logger l'action
        error_log("Nouvel accès enregistré - User: $user_id, Formation: $formation_id");
    }
}

// Vérifier si l'utilisateur est connecté
// function cap_is_user_logged_in() {
//     return isset($_SESSION['cap_user']);
// }

// Déconnexion
add_action('init', 'cap_process_logout');
// function cap_process_logout() {
//     if (isset($_GET['action']) && $_GET['action'] === 'logout') {
//         // Détruire la session
//         if (session_id()) {
//             session_destroy();
//         }
        
//         // Redirection
//         //wp_safe_redirect(home_url('/login'));
//         wp_redirect(home_url());
//         exit;
//     }
// }
function cap_process_logout() {
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        // Détruire complètement la session
        if (session_id()) {
            session_unset();
            session_destroy();
        }
        
        // Supprimer le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        wp_redirect(home_url('/connexion'));
        exit;
    }
}

function get_all_participants_by_coach_data() {
    global $wpdb;
    $user = cap_get_current_user();
    $coach_id = $user->id;

    $query = $wpdb->prepare(
        "SELECT p.id as ID, p.nom, p.prenom, p.email, p.telephone, p.ville_region, p.statut, p.created_at 
         FROM {$wpdb->prefix}lms_users p
         INNER JOIN {$wpdb->prefix}coach_participant cp ON p.id = cp.participant_id
         WHERE p.role = 'Participant' AND cp.coach_id = %d",
        $coach_id
    );
    
    $users_data = $wpdb->get_results($query);

    $formatted_users = array();
    foreach ($users_data as $user) {
        $formatted_users[] = array(
            'id'           => $user->ID,
            'nom'          => $user->nom,
            'prenom'       => $user->prenom,
            'email'        => $user->email,
            'telephone'    => $user->telephone,
            'ville_region' => $user->ville_region,
            'statut'       => $user->statut, // Statut validé
            'date'         => date('d - m - Y', strtotime($user->created_at)),
        );
    }

    return json_encode($formatted_users);
}

function get_all_participants_data() {
    global $wpdb;

    $query = $wpdb->prepare(
        "SELECT p.id as ID, p.nom, p.prenom, p.email, p.telephone, p.ville_region, p.statut, p.created_at 
         FROM {$wpdb->prefix}lms_users p
         INNER JOIN {$wpdb->prefix}coach_participant cp ON p.id = cp.participant_id
         WHERE p.role = 'Participant'"
    );
    
    $users_data = $wpdb->get_results($query);

    $formatted_users = array();
    foreach ($users_data as $user) {
        $formatted_users[] = array(
            'id'           => $user->ID,
            'nom'          => $user->nom,
            'prenom'       => $user->prenom,
            'email'        => $user->email,
            'telephone'    => $user->telephone,
            'ville_region' => $user->ville_region,
            'statut'       => $user->statut, // Statut validé
            'date'         => date('d - m - Y', strtotime($user->created_at)),
        );
    }

    return json_encode($formatted_users);
}

function get_custom_coachs_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users'; // Assurez-vous que le préfixe de votre table est correct

    $query = "SELECT id as ID, nom, prenom, email, telephone, ville_region, genre,
    date_naissance, cin, region, specialite, adresse, image, description, created_at FROM {$table_name}" . " WHERE role = 'Coach'";
    $users_data = $wpdb->get_results($query);

    $formatted_users = array();
    foreach ($users_data as $user) {

        $formatted_users[] = array(
            'id'        => $user->ID,
            'nom'       => $user->nom,
            'prenom'    => $user->prenom,  // Mettez à jour si vous avez une colonne téléphone
            'email'     => $user->email,
            'telephone' => $user->telephone,
            'ville_region' => $user->ville_region,
            'date_naissance' => $user->date_naissance,            
            'description' => $user->description ?: 'Aucune description fournie',
            'image'     => $user->image,
            'genre'     => $user->genre,
            'cin' => $user->cin ?: '',
            'region' => $user->region ?: '',
            'specialite' => $user->specialite ?: '',
            'adresse' => $user->adresse ?: '',
            'date'      => date('d - m - Y', strtotime($user->created_at)), // Formatage de la date
        );
    }

    // Retourne les données en JSON pour qu'elles puissent être utilisées par JavaScript
     return json_encode($formatted_users);
}

// Gestion des actions AJAX

// Fonction pour ajouter un coach
// add_action('wp_ajax_coach_data', 'handle_coach_data');
// add_action('wp_ajax_nopriv_coach_data', 'handle_coach_data');

// function handle_coach_data() {
//     // Vérification du nonce
//     check_ajax_referer('coach_ajax_nonce', 'security');

//     // Vérifie que le nonce est présent
//     if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'coach_ajax_nonce')) {
//         wp_send_json_error('Nonce invalide');
//         wp_die();
//     }

//     global $wpdb;

//     // Récupérer les données du formulaire
//     $data = array(
//         'nom' => sanitize_text_field($_POST['nom']),
//         'prenom' => sanitize_text_field($_POST['prenom']),
//         'email' => sanitize_email($_POST['email']),
//         'telephone' => sanitize_text_field($_POST['telephone']),
//         'date_naissance' => sanitize_text_field($_POST['date_naissance']),
//         'ville_region' => sanitize_text_field($_POST['ville_region']),
//         'description' => sanitize_textarea_field($_POST['description']),
//         'password' => wp_hash_password($_POST['password']),
//         'role' => 'Coach',
//         'statut' => true,
//         'created_at' => current_time('mysql')
//     );

//     // Vérifier si les mots de passe correspondent
//     if ($_POST['password'] !== $_POST['confirm_password']) {
//         wp_send_json_error('Les mots de passe ne correspondent pas');
//     }

//     // Vérifier si l'email existe déjà
//     $email_exists = $wpdb->get_var($wpdb->prepare(
//         "SELECT id FROM {$wpdb->prefix}lms_users WHERE email = %s",
//         $data['email']
//     ));

//     if ($email_exists) {
//         wp_send_json_error('Cet email est déjà utilisé');
//     }

//     // Traitement de l'image
//     if (!empty($_FILES['photo'])) {
//         require_once(ABSPATH . 'wp-admin/includes/file.php');
//         require_once(ABSPATH . 'wp-admin/includes/image.php');
        
//         $upload = wp_handle_upload($_FILES['photo'], array('test_form' => false));
        
//         if ($upload && !isset($upload['error'])) {
//             $data['image'] = $upload['url'];
//         } else {
//             wp_send_json_error('Erreur lors du téléchargement de l\'image');
//         }
//     }

//     // Insérer l'utilisateur
//     $inserted = $wpdb->insert("{$wpdb->prefix}lms_users", $data);

//     if (!$inserted) {
//         wp_send_json_error('Erreur lors de l\'enregistrement');
//     }

//     $participant_id = $wpdb->insert_id;
//     $coach = cap_get_current_user();
//     $coach_id = $coach->id; // Ou l'ID du coach sélectionné

//     // Créer la relation coach-participant
//     $relation_created = $wpdb->insert(
//         "{$wpdb->prefix}coach_participant",
//         array(
//             'coach_id' => $coach_id,
//             'participant_id' => $participant_id
//         )
//     );

//     if ($relation_created) {
//         wp_send_json_success('Coach enregistré avec succès');
//     } else {
//         // Rollback si la relation échoue
//         $wpdb->delete("{$wpdb->prefix}lms_users", array('id' => $participant_id));
//         wp_send_json_error('Erreur lors de la création de la relation coach-participant');
//     }
// }

add_action('wp_ajax_coach_data', 'handle_coach_data');
add_action('wp_ajax_nopriv_coach_data', 'handle_coach_data');

function handle_coach_data() {
    // Vérification du nonce
    check_ajax_referer('coach_ajax_nonce', 'security');

    global $wpdb;

    // Debug: Log les données reçues
    error_log('Données reçues: ' . print_r($_POST, true));
    error_log('Fichiers reçus: ' . print_r($_FILES, true));

    // 1. Validation des données
    $required_fields = ['nom', 'prenom', 'email', 'password'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error('Le champ ' . $field . ' est obligatoire');
            wp_die();
        }
    }

    // 2. Préparation des données
    $data = [
        'nom' => sanitize_text_field($_POST['nom']),
        'prenom' => sanitize_text_field($_POST['prenom']),
        'email' => sanitize_email($_POST['email']),
        'telephone' => sanitize_text_field($_POST['telephone'] ?? ''),
        'date_naissance' => sanitize_text_field($_POST['date_naissance'] ?? ''),
        'ville_region' => sanitize_text_field($_POST['ville_region'] ?? ''),
        'region' => sanitize_text_field($_POST['region'] ?? ''),
        'description' => sanitize_textarea_field($_POST['description'] ?? ''),
        'password' => wp_hash_password($_POST['password']),
        'role' => 'Coach',
        'statut' => 1, // Utilisez 1 au lieu de true pour MySQL
        'cin' => sanitize_text_field($_POST['cin'] ?? ''),
        'specialite' => sanitize_text_field($_POST['specialite'] ?? ''),
        'adresse' => sanitize_text_field($_POST['adresse'] ?? ''),
        'genre' => sanitize_text_field($_POST['genre'] ?? ''),
        'created_at' => current_time('mysql')
    ];

    // 3. Vérification email unique
    $email_exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}lms_users WHERE email = %s",
        $data['email']
    ));

    if ($email_exists) {
        wp_send_json_error('Cet email est déjà utilisé');
        wp_die();
    }

    // 4. Traitement de l'image
    if (!empty($_FILES['photo']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $upload = wp_handle_upload($_FILES['photo'], ['test_form' => false]);
        
        if ($upload && !isset($upload['error'])) {
            $data['image'] = $upload['url'];
        } else {
            wp_send_json_error('Erreur image: ' . ($upload['error'] ?? 'Erreur inconnue'));
            wp_die();
        }
    }

    // 5. Insertion du coach avec vérification renforcée
    $wpdb->show_errors(); // Active l'affichage des erreurs SQL
    $inserted = $wpdb->insert("{$wpdb->prefix}lms_users", $data);

    if ($inserted === false) {
        $error = $wpdb->last_error;
        error_log('Erreur SQL: ' . $error);
        wp_send_json_error('Erreur base de données: ' . $error);
        wp_die();
    }

    // Debug: Vérifier l'ID inséré
    $new_coach_id = $wpdb->insert_id;
    error_log('Nouveau coach créé avec ID: ' . $new_coach_id);

    // 6. Vérification finale
    $created_coach = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}lms_users WHERE id = %d",
        $new_coach_id
    ));

    if (!$created_coach) {
        wp_send_json_error('La création a semblé réussir mais le coach est introuvable');
        wp_die();
    }

    wp_send_json_success(['message' => 'Coach créé avec succès', 'coach_id' => $new_coach_id]);
    wp_die();
}

// Enregistrement du script
add_action('wp_enqueue_scripts', function() {
    $script_coach_path = plugin_dir_path(__FILE__) . 'assets/js/coach-script.js';
    $script_coach_url = plugin_dir_url(__FILE__) . 'assets/js/coach-script.js';

    if (file_exists($script_coach_path)) {
        wp_enqueue_script(
            'coach-script',
            $script_coach_url,
            ['jquery'],
            filemtime($script_coach_path),
            true
        );
    
        wp_localize_script(
            'coach-script',
            'coachData',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('coach_ajax_nonce')
            ]
        );
    }
});

add_action('wp_ajax_update_coach_data', 'handle_coach_update');
add_action('wp_ajax_nopriv_update_coach_data','handle_coach_update');

function handle_coach_update() {
    // Basic verifications (keep your existing ones)
    if (!defined('DOING_AJAX') || !DOING_AJAX) {
        wp_send_json_error('Accès non autorisé', 403);
        wp_die();
    }

    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'coach_ajax_nonce')) {
        error_log('Nonce invalide reçu: ' . ($_POST['security'] ?? 'null'));
        wp_send_json_error('Session expirée', 403);
        wp_die();
    }

    // Data validation
    $required_fields = ['coach_id', 'email', 'nom', 'prenom', 'telephone', 'date_naissance', 'ville_region', 'description'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            error_log('Champ manquant: ' . $field);
            wp_send_json_error('Données incomplètes', 400);
            wp_die();
        }
    }

    global $wpdb;

    try {
        // Get and validate data
        $coach_id = intval($_POST['coach_id']);
        $email = sanitize_email($_POST['email']);
        
        // Prepare data for update
        $data = array(
            'nom' => sanitize_text_field($_POST['nom']),
            'prenom' => sanitize_text_field($_POST['prenom']),
            'email' => $email,
            'telephone' => sanitize_text_field($_POST['telephone']),
            'date_naissance' => sanitize_text_field($_POST['date_naissance']),
            'ville_region' => sanitize_text_field($_POST['ville_region']),
            'description' => sanitize_textarea_field($_POST['description']),
            'region' => sanitize_text_field($_POST['region'] ?? ''), 
            'genre' => sanitize_text_field($_POST['genre'] ?? ''),
            'cin' => sanitize_text_field($_POST['cin'] ?? ''),
            'specialite' => sanitize_text_field($_POST['specialite'] ?? ''),
            'adresse' => sanitize_text_field($_POST['adresse'] ?? '')
        );

        // IMAGE HANDLING - FIXED VERSION
        // 1. Get current image from database
        $current_image = $wpdb->get_var($wpdb->prepare(
            "SELECT image FROM {$wpdb->prefix}lms_users WHERE id = %d",
            $coach_id
        ));

        // 2. Process new image only if valid file is uploaded
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK && $_FILES['photo']['size'] > 0) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            $upload = wp_handle_upload($_FILES['photo'], array('test_form' => false));

            if ($upload && !isset($upload['error'])) {
                $data['image'] = $upload['url']; // Save new URL
                error_log('New image uploaded: ' . $upload['url']);
            } else {
                error_log('Upload error: ' . ($upload['error'] ?? 'Unknown error'));
                // Keep existing image if upload fails
                $data['image'] = $current_image;
            }
        } else {
            // No valid file uploaded - keep existing image
            $data['image'] = $current_image;
            error_log('No valid image uploaded - keeping existing one');
        }

        // Add this temporarily to check settings
        error_log('Upload max filesize: ' . ini_get('upload_max_filesize'));
        error_log('Post max size: ' . ini_get('post_max_size'));
        error_log('Temp directory: ' . sys_get_temp_dir());

        // Update database
        $updated = $wpdb->update(
            "{$wpdb->prefix}lms_users",
            $data,
            array('id' => $coach_id),
            array_fill(0, count($data), '%s'),
            array('%d')
        );

        if ($updated === false) {
            throw new Exception('Erreur lors de la mise à jour dans la base de données');
        }

        wp_send_json_success([
            'message' => 'Coach mis à jour avec succès',
            'data' => $data
        ]);

    } catch (Exception $e) {
        error_log('Erreur traitement: ' . $e->getMessage());
        wp_send_json_error($e->getMessage(), 500);
    }

    wp_die();
}

// function handle_coach_update() {
//     check_ajax_referer('coach_nonce_action', 'security'); // même nom que ton wp_localize_script

//     // Vérifie si l'ID est présent
//     if (!isset($_POST['ID']) || empty($_POST['ID'])) {
//         wp_send_json_error('ID du coach manquant.');
//     }


//     $update_data = [
//         'ID'         => $user_id,
//         'email' => sanitize_email($_POST['email']),
//         'first_name' => sanitize_text_field($_POST['prenom']),
//         'last_name'  => sanitize_text_field($_POST['nom']),
//     ];

//     // Mot de passe facultatif
//     if (!empty($_POST['password'])) {
//         $update_data['user_pass'] = $_POST['password'];
//     }

//     $user_id = wp_update_user($update_data);

//     if (is_wp_error($user_id)) {
//         wp_send_json_error($user_id->get_error_message());
//     }

//     // Champs personnalisés (meta)
//     update_user_meta($user_id, 'telephone', sanitize_text_field($_POST['telephone']));
//     update_user_meta($user_id, 'cin', sanitize_text_field($_POST['cin']));
//     update_user_meta($user_id, 'date_naissance', sanitize_text_field($_POST['date_naissance']));
//     update_user_meta($user_id, 'adresse', sanitize_text_field($_POST['adresse']));
//     update_user_meta($user_id, 'genre', sanitize_text_field($_POST['genre']));
//     update_user_meta($user_id, 'region', sanitize_text_field($_POST['region']));
//     update_user_meta($user_id, 'ville_region', sanitize_text_field($_POST['ville_region']));
//     update_user_meta($user_id, 'specialite', sanitize_text_field($_POST['specialite']));
//     update_user_meta($user_id, 'description', sanitize_textarea_field($_POST['description']));
//     update_user_meta($user_id, 'coach_image_id', intval($_POST['coach_image_id']));

//     wp_send_json_success(['message' => 'Coach mis à jour avec succès.']);
// }


// Fonction pour gérer la suppression d'un coach
add_action('wp_ajax_delete_coach', 'handle_delete_coach');
add_action('wp_ajax_nopriv_delete_coach', 'handle_delete_coach'); 
function handle_delete_coach() {
    check_ajax_referer('coach_ajax_nonce', 'security');
    
    global $wpdb;
    $coach_id = isset($_POST['coach_id']) ? intval($_POST['coach_id']) : 0;
    
    if ($coach_id <= 0) {
        wp_send_json_error('ID invalide');
        wp_die();
    }
    
    $deleted = $wpdb->delete(
        "{$wpdb->prefix}lms_users",
        ['id' => $coach_id],
        ['%d']
    );
    
    if ($deleted === false) {
        wp_send_json_error('Erreur suppression');
    } elseif ($deleted === 0) {
        wp_send_json_error('Coach non trouvé');
    } else {
        // Renvoyer les nouvelles données après suppression
        $coaches = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lms_users");
        wp_send_json_success([
            'message' => 'Suppression réussie',
            'data' => $coaches // Envoyer toutes les données mises à jour
        ]);
    }
    
    wp_die();
}

// Fonction pour récupérer les données
add_action('wp_ajax_get_coaches_data', 'get_coaches_data');
function get_coaches_data() {
    check_ajax_referer('coach_ajax_nonce', 'security');
    
    global $wpdb;
    $coaches = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lms_users");
    
    wp_send_json_success($coaches);
    wp_die();
}

?>