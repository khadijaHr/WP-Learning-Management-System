<?php
add_action('wp_ajax_register_participant', 'handle_participant_registration');
add_action('wp_ajax_nopriv_register_participant', 'handle_participant_registration');

function handle_participant_registration() {
    global $wpdb;
    
    // Vérifier le nonce pour la sécurité
    check_ajax_referer('participant_reg_nonce', 'security');

    // Récupérer les données du formulaire
    $data = array(
        'nom' => sanitize_text_field($_POST['nom']),
        'prenom' => sanitize_text_field($_POST['prenom']),
        'email' => sanitize_email($_POST['email']),
        'telephone' => sanitize_text_field($_POST['telephone']),
        'date_naissance' => sanitize_text_field($_POST['date_naissance']),
        'ville_region' => sanitize_text_field($_POST['ville_region']),
        'region' => sanitize_text_field($_POST['region']),
        'description' => sanitize_textarea_field($_POST['description']),
        'password' => wp_hash_password($_POST['password']),
        'role' => 'Participant',
        'statut' => false,
        'genre' => sanitize_text_field($_POST['genre']),
        'date_inscription' => sanitize_text_field($_POST['date_inscription']),
        'created_at' => current_time('mysql')
    );

    // Vérifier si les mots de passe correspondent
    if ($_POST['password'] !== $_POST['confirm_password']) {
        wp_send_json_error('Les mots de passe ne correspondent pas');
    }

    // Vérifier si l'email existe déjà
    $email_exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}lms_users WHERE email = %s",
        $data['email']
    ));

    if ($email_exists) {
        wp_send_json_error('Cet email est déjà utilisé');
    }

    // Traitement de l'image
    if (!empty($_FILES['photo'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $upload = wp_handle_upload($_FILES['photo'], array('test_form' => false));
        
        if ($upload && !isset($upload['error'])) {
            $data['image'] = $upload['url'];
        } else {
            wp_send_json_error('Erreur lors du téléchargement de l\'image');
        }
    }

    // Insérer l'utilisateur
    $inserted = $wpdb->insert("{$wpdb->prefix}lms_users", $data);
    
    if (!$inserted) {
        wp_send_json_error('Erreur lors de l\'enregistrement');
    }

    $participant_id = $wpdb->insert_id;
    $coach = cap_get_current_user();
    $coach_id = $coach->id; // Ou l'ID du coach sélectionné

    // Créer la relation coach-participant
    $relation_created = $wpdb->insert(
        "{$wpdb->prefix}coach_participant",
        array(
            'coach_id' => $coach_id,
            'participant_id' => $participant_id
        )
    );

    if ($relation_created) {
        wp_send_json_success('Participant enregistré avec succès');
    } else {
        // Rollback si la relation échoue
        $wpdb->delete("{$wpdb->prefix}lms_users", array('id' => $participant_id));
        wp_send_json_error('Erreur lors de la création de la relation coach-participant');
    }
}

//

add_action('wp_ajax_update_participant', 'handle_participant_update');
add_action('wp_ajax_nopriv_update_participant', 'handle_participant_update');

function handle_participant_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users';

    // 1. Vérification du nonce
    if (!check_ajax_referer('participant_update_nonce', 'security', false)) {
        wp_send_json_error('Nonce invalide', 403);
    }

    // Récupération de l'ID du participant
    $participant_id = isset($_POST['participant_id']) ? intval($_POST['participant_id']) : 0;
    if (!$participant_id) {
        wp_send_json_error('ID participant manquant');
    }

    // 3. Préparation des données
    $data = array(
        'nom' => sanitize_text_field($_POST['nom']),
        'prenom' => sanitize_text_field($_POST['prenom']),
        'email' => sanitize_email($_POST['email']),
        'telephone' => sanitize_text_field($_POST['telephone']),
        'date_naissance' => sanitize_text_field($_POST['date_naissance']),
        'ville_region' => sanitize_text_field($_POST['ville_region']),
        'region' => sanitize_text_field($_POST['region']),
        'description' => sanitize_textarea_field($_POST['description']),
        'genre' => sanitize_text_field($_POST['genre']),
        'date_inscription' => sanitize_text_field($_POST['date_inscription']),
        'updated_at' => current_time('mysql')
    );

    // 4. Gestion du mot de passe (si modifié)
    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            wp_send_json_error('Les mots de passe ne correspondent pas');
        }
        $data['password'] = wp_hash_password($_POST['password']);
    }

    // 5. Gestion de l'image
    if (!empty($_FILES['photo']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $upload = wp_handle_upload($_FILES['photo'], array('test_form' => false));
        
        if ($upload && !isset($upload['error'])) {
            $data['image'] = $upload['url'];
            
            // Supprimer l'ancienne image si elle existe
            $old_image = $wpdb->get_var($wpdb->prepare(
                "SELECT image FROM $table_name WHERE id = %d",
                $participant_id
            ));
            if ($old_image && file_exists(ABSPATH . str_replace(home_url(), '', $old_image))) {
                unlink(ABSPATH . str_replace(home_url(), '', $old_image));
            }
        } else {
            wp_send_json_error('Erreur lors du téléchargement de l\'image: ' . $upload['error']);
        }
    } elseif (!empty($_POST['image_id'])) {
        // Garder l'image existante si aucune nouvelle n'est uploadée
        $data['image'] = sanitize_text_field($_POST['image_id']);
    }

    // 6. Vérification de l'email (s'il a changé)
    $current_email = sanitize_text_field($_POST['current_email']);
    if ($data['email'] !== $current_email) {
        $email_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table_name WHERE email = %s AND id != %d",
            $data['email'],
            $participant_id
        ));
        
        if ($email_exists) {
            wp_send_json_error('Cet email est déjà utilisé par un autre participant');
        }
    }

    // 7. Mise à jour dans la base de données
    $updated = $wpdb->update(
        $table_name,
        $data,
        array('id' => $participant_id),
        array(
            '%s', '%s', '%s', '%s', '%s', 
            '%s', '%s', '%s', '%s', '%s',
            '%s', '%s'
        ),
        array('%d')
    );

    // 8. Retour du résultat
    if ($updated !== false) {
        wp_send_json_success('Participant mis à jour avec succès');
    } else {
        error_log('Erreur mise à jour participant: ' . $wpdb->last_error);
        wp_send_json_error('Erreur lors de la mise à jour du participant');
    }
}

//
add_action('wp_ajax_get_participant_details', 'handle_get_participant_details');
add_action('wp_ajax_nopriv_get_participant_details', 'handle_get_participant_details');

function handle_get_participant_details() {
    global $wpdb;
    
    check_ajax_referer('participant_reg_nonce', 'security');

    $participant_id = intval($_POST['participant_id']);
    
    if (!$participant_id) {
        wp_send_json_error('ID participant invalide');
    }

    $participant = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}lms_users WHERE id = %d",
        $participant_id
    ));

    if (!$participant) {
        wp_send_json_error('Participant non trouvé');
    }

    // Générer le HTML des détails
    ob_start();
    ?>
    <div class="participant-details">
        <div class="detail-row">
            <span class="detail-label">Nom complet :</span>
            <span class="detail-value"><?php echo esc_html($participant->prenom . ' ' . $participant->nom); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email :</span>
            <span class="detail-value"><?php echo esc_html($participant->email); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Téléphone :</span>
            <span class="detail-value"><?php echo esc_html($participant->telephone); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Région :</span>
            <span class="detail-value"><?php echo esc_html($participant->region); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ville :</span>
            <span class="detail-value"><?php echo esc_html(ucwords(str_replace('_', ' ', $participant->region))); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Genre :</span>
            <span class="detail-value"><?php echo esc_html($participant->genre); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date d'inscription :</span>
            <span class="detail-value"><?php echo esc_html($participant->date_inscription); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date de naissance :</span>
            <span class="detail-value"><?php echo esc_html(date('d/m/Y', strtotime($participant->date_naissance))); ?></span>
        </div>
        <?php if ($participant->image): ?>
        <div class="detail-row">
            <span class="detail-label">Photo :</span>
            <img src="<?php echo esc_url($participant->image); ?>" class="participant-photo">
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <span class="detail-label">Description :</span>
            <p class="detail-value"><?php echo esc_html($participant->description); ?></p>
        </div>
    </div>
    <?php
    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}

add_action('wp_ajax_get_c_details', 'handle_get_coach_details');
add_action('wp_ajax_nopriv_get_coach_details', 'handle_get_coach_details');

function handle_get_coach_details() {
    global $wpdb;
    
    check_ajax_referer('participant_reg_nonce', 'security');

    $coach_id = intval($_POST['coach_id']);
    
    if (!$coach_id) {
        wp_send_json_error('ID Coach invalide');
    }

    $participant = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}lms_users WHERE id = %d",
        $coach_id
    ));

    if (!$participant) {
        wp_send_json_error('Coach non trouvé');
    }

    // Générer le HTML des détails
    ob_start();
    ?>
    <div class="participant-details">
        <div class="detail-row">
            <span class="detail-label">Nom complet:</span>
            <span class="detail-value"><?php echo esc_html($participant->prenom . ' ' . $participant->nom); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value"><?php echo esc_html($participant->email); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Téléphone:</span>
            <span class="detail-value"><?php echo esc_html($participant->telephone); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Région:</span>
            <span class="detail-value"><?php echo esc_html($participant->region); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ville:</span>
            <span class="detail-value"><?php echo esc_html(ucwords(str_replace('_', ' ', $participant->region))); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Genre:</span>
            <span class="detail-value"><?php echo esc_html($participant->genre); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date d'inscription:</span>
            <span class="detail-value"><?php echo esc_html($participant->date_inscription); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date de naissance:</span>
            <span class="detail-value"><?php echo esc_html(date('d/m/Y', strtotime($participant->date_naissance))); ?></span>
        </div>
        <?php if ($participant->image): ?>
        <div class="detail-row">
            <span class="detail-label">Photo:</span>
            <img src="<?php echo esc_url($participant->image); ?>" class="participant-photo">
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <span class="detail-label">Description:</span>
            <p class="detail-value"><?php echo esc_html($participant->description); ?></p>
        </div>
    </div>
    <?php
    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}

// Fonction pour gérer la suppression d'un coach
add_action('wp_ajax_delete_participant', 'handle_delete_participant');
add_action('wp_ajax_nopriv_delete_participant', 'handle_delete_participant'); 
function handle_delete_participant() {
    check_ajax_referer('participant_ajax_nonce', 'security');
    
    global $wpdb;
    $participant_id = isset($_POST['participant_id']) ? intval($_POST['participant_id']) : 0;
    
    if ($participant_id <= 0) {
        wp_send_json_error('ID invalide');
        wp_die();
    }
    
    $deleted = $wpdb->delete(
        "{$wpdb->prefix}lms_users",
        ['id' => $participant_id],
        ['%d']
    );
    
    if ($deleted === false) {
        wp_send_json_error('Erreur suppression');
    } elseif ($deleted === 0) {
        wp_send_json_error('Participant non trouvé');
    } else {
        // Renvoyer les nouvelles données après suppression
        $participants = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lms_users");
        wp_send_json_success([
            'message' => 'Suppression réussie',
            'data' => $participants // Envoyer toutes les données mises à jour
        ]);
    }
    
    wp_die();
}

//
add_action('wp_ajax_validate_participant', 'handle_validate_participant');
add_action('wp_ajax_nopriv_validate_participant', 'handle_validate_participant');

function handle_validate_participant() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users';

    // Vérification du nonce
    if (!check_ajax_referer('validate_participant', 'security', false)) {
        wp_send_json_error('Nonce invalide', 403);
    }

    // Récupération de l'ID
    $participant_id = intval($_POST['participant_id']);
    if (!$participant_id) {
        wp_send_json_error('ID participant invalide');
    }

    // Récupération des données actuelles
    $participant = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d", 
        $participant_id
    ), ARRAY_A);

    if (!$participant) {
        wp_send_json_error('Participant non trouvé');
    }

    // 4. Génération d'un mot de passe temporaire
    $temp_password = wp_generate_password(12, false);
    $hashed_password = wp_hash_password($temp_password);

    // 5. Mise à jour dans la base de données
    $updated = $wpdb->update(
        $table_name,
        [
            'statut' => 1,
            'password' => $hashed_password,
            'updated_at' => current_time('mysql')
        ],
        ['id' => $participant_id],
        ['%d', '%s', '%s'],
        ['%d']
    );

    if ($updated === false) {
        wp_send_json_error('Erreur de mise à jour');
    }

    // 6. Envoi de l'email
    $email_sent = send_participant_credentials(
        $participant['email'],
        $participant['email'], // Le username est l'email
        $temp_password,
        $participant['nom'] . ' ' . $participant['prenom']
    );

    if (!$email_sent) {
        error_log("Échec envoi email à: " . $participant['email']);
    }

    wp_send_json_success('Participant validé avec succès');
}

function send_participant_credentials($to, $username, $password, $fullname) {
    $subject = 'Vos identifiants Plateforme LMS';
    
    $message = "
        <html>
        <body>
            <p>Bonjour $fullname,</p>
            <p>Votre compte a été validé avec succès.</p>
            <p><strong>Identifiants de connexion:</strong></p>
            <ul>
                <li>Email: $username</li>
                <li>Mot de passe temporaire: $password</li>
            </ul>
            <p>Nous vous recommandons de changer votre mot de passe après votre première connexion.</p>
            <p>Cordialement,<br>L'équipe LMS</p>
        </body>
        </html>
    ";
   
	// En-têtes pour l'email
    $headers = [
        "From: LMS <wordpress@lms-skills.com>",
        "Return-Path: wordpress@lms-skills.com",
        "Content-Type: text/html; charset=UTF-8",
    ];

    return wp_mail($to, $subject, $message, $headers);
}

//
function afficher_nombre_participants_coach_format() {
    global $wpdb;
    
    // Récupérer l'ID de l'utilisateur connecté
    $current_user = cap_get_current_user();
    
    // Vérifier si l'utilisateur est un coach
    $user_role = $wpdb->get_var($wpdb->prepare(
        "SELECT role FROM {$wpdb->prefix}lms_users WHERE id = %d",
        $current_user->id
    ));
    
    if ($user_role !== 'Coach' && $user_role !== 'Master Coach') {
        return ''; // Retourne vide si l'utilisateur n'est pas un coach
    }
    
    // Compter le nombre de participants pour ce coach
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) 
         FROM {$wpdb->prefix}coach_participant 
         WHERE coach_id = %d",
         $current_user->id
    ));
    
    // Retourner le format demandé
    return sprintf(
        '<p><strong>%d</strong><span>Inscription</span></p>',
        $count

    );
}

function afficher_nombre_formations_coach_format() {
    global $wpdb;
    
    // Récupérer l'ID de l'utilisateur connecté
    $current_user = cap_get_current_user();
    
    // Vérifier si l'utilisateur est un coach
    $user_role = $wpdb->get_var($wpdb->prepare(
        "SELECT role FROM {$wpdb->prefix}lms_users WHERE id = %d",
        $current_user->id
    ));
    
    if ($user_role !== 'Coach' && $user_role !== 'Master Coach') {
        return ''; // Retourne vide si l'utilisateur n'est pas un coach
    }
    
    // Compter le nombre de formations pour ce coach
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) 
         FROM {$wpdb->prefix}formations_lms
         WHERE user_id = %d",
        $current_user->id
    ));
    
    // Formater le nombre avec un zéro devant si <10
    $formatted_count = ($count < 10) ? '0' . $count : $count;
    
    // Retourner le format demandé
    return sprintf(
        '<p><strong>%s</strong><span>Formations</span></p>',
        $formatted_count
    );
}

// Enregistrement des handlers AJAX
add_action('wp_ajax_get_participants_for_export', 'handle_export_request');
function handle_export_request() {
    // Debug: log toutes les données reçues
    error_log(print_r($_POST, true));
    
    // Vérification plus robuste du nonce
    if (empty($_POST['security'])) {
        wp_send_json_error('Security token missing', 400);
    }

    if (!wp_verify_nonce($_POST['security'], 'participant_export_action')) {
        error_log('Nonce verification failed. Received: ' . $_POST['security']);
        wp_send_json_error('Invalid security token', 403);
    }

    global $wpdb;
    
    // Requête préparée pour plus de sécurité
    $query = $wpdb->prepare(
        "SELECT id, nom, prenom, email, telephone, ville_region, date_naissance 
         FROM {$wpdb->prefix}lms_users 
         WHERE role = %s",
        'Participant'
    );
    
    $participants = $wpdb->get_results($query, ARRAY_A);
    
    if ($wpdb->last_error) {
        error_log('DB Error: ' . $wpdb->last_error);
        wp_send_json_error('Database error', 500);
    }
    
    wp_send_json_success($participants);
}
