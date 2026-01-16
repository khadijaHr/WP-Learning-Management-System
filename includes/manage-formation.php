<?php
// Enregistrer les actions AJAX
add_action('wp_ajax_save_user_formation', 'handle_save_user_formation');
add_action('wp_ajax_nopriv_save_user_formation', 'handle_save_user_formation');


add_action('wp_ajax_get_formation_data', 'handle_get_formation_data');
add_action('wp_ajax_delete_user_formation', 'handle_delete_user_formation');

// Sauvegarder une formation (création ou mise à jour)
function handle_save_user_formation() {
    global $wpdb;
        
    $current_user = cap_get_current_user();
    $formation_id = isset($_POST['formation_id']) ? intval($_POST['formation_id']) : 0;
    
    // Vérifier les permissions (l'utilisateur ne peut modifier que ses propres formations)
    if ($formation_id > 0) {
        $existing_formation = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}formations_lms WHERE id = %d AND user_id = %d",
            $formation_id,
            $current_user->id
        ));
        
        if (!$existing_formation) {
            wp_send_json_error('Vous n\'avez pas la permission de modifier cette formation.');
        }
    }
    
    // Valider les données
    if (empty($_POST['titre'])) {
        wp_send_json_error('Le titre est obligatoire.');
    }
    
   
    // Préparer les données de la formation
    $formation_data = array(
        'titre' => sanitize_text_field($_POST['titre']),
        'description' => wp_kses_post($_POST['description']),
        'duree' => isset($_POST['duree']) ? intval($_POST['duree']) : 0,
        'bloc' => isset($_POST['bloc']) ? intval($_POST['bloc']) : 0,
        'langue' => sanitize_text_field($_POST['langue']), 
        'user_id' => $current_user->id,
    );
        
    // Gérer l'insertion ou la mise à jour
    if (!empty($_POST['formation_id'])) {
        $result = $wpdb->update(
            "{$wpdb->prefix}formations_lms",
            $formation_data,
            ['id' => intval($_POST['formation_id'])]
        );
        error_log('Résultat update : ' . ($result !== false ? 'succès' : 'échec'));
    } else {
        $formation_data['created_at'] = current_time('mysql');
        $result = $wpdb->insert(
            "{$wpdb->prefix}formations_lms",
            $formation_data
        );
        error_log('Résultat insert - ID : ' . $wpdb->insert_id);
        $formation_id = $wpdb->insert_id; 
    }
    
    if ($result === false) {
        wp_send_json_error('Erreur lors de la sauvegarde de la formation.');
    }
    
    // Gérer les jours de formation
    if (!empty($_POST['jours'])) {
        $jours = $_POST['jours'];
    
        // D'abord, supprimer les anciens jours si c'est une mise à jour
        if ($formation_id > 0) {
            $wpdb->delete(
                "{$wpdb->prefix}formation_jours",
                array('formation_id' => $formation_id),
                array('%d')
            );
        }
    
        // Ajouter les nouveaux jours
        foreach ($jours as $jourIndex => $jour_data) {
            $jour_data = array_map('sanitize_text_field', $jour_data);
        
            $wpdb->insert(
                "{$wpdb->prefix}formation_jours",
                array(
                    'formation_id' => $formation_id,
                    'numero_jour' => $jourIndex,
                    'titre' => $jour_data['titre'],
                    'description' => $jour_data['description'],
                    'created_at' => current_time('mysql')
                )
            );
        
            $jour_id = $wpdb->insert_id;
        
            // Gérer les fichiers uploadés pour ce jour
            if (!empty($_FILES['jours']['tmp_name'][$jourIndex]['fichiers'])) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                
                $document_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'txt', 'odt', 'ods', 'odp'];
                $video_extensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'mpeg', 'mpg', 'm4v'];
                
                foreach ($_FILES['jours']['tmp_name'][$jourIndex]['fichiers'] as $fileIndex => $tmp_name) {
                    if (!empty($tmp_name)) {
                        $file = array(
                            'name' => $_FILES['jours']['name'][$jourIndex]['fichiers'][$fileIndex],
                            'type' => $_FILES['jours']['type'][$jourIndex]['fichiers'][$fileIndex],
                            'tmp_name' => $tmp_name,
                            'error' => $_FILES['jours']['error'][$jourIndex]['fichiers'][$fileIndex],
                            'size' => $_FILES['jours']['size'][$jourIndex]['fichiers'][$fileIndex]
                        );
                        
                        // Déterminer le type en fonction de l'extension
                        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $file_type = 'autre';
                        
                        if (in_array($file_extension, $document_extensions)) {
                            $file_type = 'fichier';
                        } elseif (in_array($file_extension, $video_extensions)) {
                            $file_type = 'video';
                        }
                        
                        $upload = wp_handle_upload($file, array('test_form' => false));
                        
                        if ($upload && !isset($upload['error'])) {
                            // Vérifier si le fichier existe déjà pour ce jour
                            $existing_file = $wpdb->get_row($wpdb->prepare(
                                "SELECT * FROM {$wpdb->prefix}formation_fichiers 
                                WHERE jour_id = %d AND nom_fichier = %s",
                                $jour_id,
                                $file['name']
                            ));
                            
                            // Insérer seulement si le fichier n'existe pas déjà
                            if (!$existing_file) {
                                $wpdb->insert(
                                    "{$wpdb->prefix}formation_fichiers",
                                    array(
                                        'jour_id' => $jour_id,
                                        'type' => $file_type,
                                        'nom_fichier' => $file['name'],
                                        'chemin_fichier' => $upload['url'],
                                        'taille' => $file['size'],
                                        'uploaded_at' => current_time('mysql')
                                    )
                                );
                            }
                        } else {
                            error_log('Erreur upload fichier: ' . $upload['error']);
                        }
                    }
                }
            }
        }
    }
    
    wp_send_json_success('Formation enregistrée avec succès.');
}

// Récupérer les données d'une formation pour édition
function handle_get_formation_data() {
    global $wpdb;
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Vous devez être connecté pour effectuer cette action.');
    }
    
    $formation_id = isset($_POST['formation_id']) ? intval($_POST['formation_id']) : 0;

    $current_user = cap_get_current_user();
    
    // Récupérer la formation
    $formation = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}formations_lms WHERE id = %d AND user_id = %d",
        $formation_id,
        $current_user->id
    ));
    
    if (!$formation) {
        wp_send_json_error('Formation non trouvée ou vous n\'avez pas la permission.');
    }
    
    // Récupérer les jours associés
    $jours = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}formation_jours WHERE formation_id = %d ORDER BY id",
        $formation_id
    ));
    
    // Pour chaque jour, récupérer les fichiers
    $jours_with_files = array();
    foreach ($jours as $jour) {
        $fichiers = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}formation_fichiers WHERE jour_id = %d",
            $jour->id
        ));
        
        $jours_with_files[] = array(
            'id' => $jour->id,
            'titre' => $jour->titre,
            'description' => $jour->description,
            'fichiers' => $fichiers
        );
    }
    
    wp_send_json_success(array(
        'formation' => $formation,
        'jours' => $jours_with_files
    ));
}

// Supprimer une formation
function handle_delete_user_formation() {
    global $wpdb;
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Vous devez être connecté pour effectuer cette action.');
    }
    
    $formation_id = isset($_POST['formation_id']) ? intval($_POST['formation_id']) : 0;
    $current_user = cap_get_current_user();
    
    // Vérifier que la formation appartient bien à l'utilisateur
    $formation = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}formations_lms WHERE id = %d AND user_id = %d",
        $formation_id,
        $current_user->id
    ));
    
    if (!$formation) {
        wp_send_json_error('Formation non trouvée ou vous n\'avez pas la permission.');
    }
    
    // Commencer une transaction pour s'assurer que tout est supprimé correctement
    $wpdb->query('START TRANSACTION');
    
    try {
        // 1. Récupérer tous les jours associés
        $jours = $wpdb->get_col($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}formation_jours WHERE formation_id = %d",
            $formation_id
        ));
        
        if (!empty($jours)) {
            // 2. Pour chaque jour, supprimer les fichiers associés
            foreach ($jours as $jour_id) {
                $fichiers = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}formation_fichiers WHERE jour_id = %d",
                    $jour_id
                ));
                
                // Supprimer les fichiers physiques
                foreach ($fichiers as $fichier) {
                    $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $fichier->chemin_fichier);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                
                // Supprimer les entrées en base de données
                $wpdb->delete(
                    "{$wpdb->prefix}formation_fichiers",
                    array('jour_id' => $jour_id),
                    array('%d')
                );
            }
            
            // 3. Supprimer les jours
            $wpdb->delete(
                "{$wpdb->prefix}formation_jours",
                array('formation_id' => $formation_id),
                array('%d')
            );
        }
        
        // 4. Enfin, supprimer la formation elle-même
        $wpdb->delete(
            "{$wpdb->prefix}formations_lms",
            array('id' => $formation_id),
            array('%d')
        );
        
        $wpdb->query('COMMIT');
        wp_send_json_success('Formation supprimée avec succès.');
    } catch (Exception $e) {
        $wpdb->query('ROLLBACK');
        wp_send_json_error('Une erreur est survenue lors de la suppression.');
    }
}


/********/
add_action('wp_ajax_toggle_formation_status', 'handle_toggle_formation_status');
add_action('wp_ajax_nopriv_toggle_formation_status', 'handle_toggle_formation_status');

function handle_toggle_formation_status() {  

    $formation_id = isset($_POST['formation_id']) ? intval($_POST['formation_id']) : 0;
    $nonce = isset($_POST['security']) ? sanitize_text_field($_POST['security']) : '';

    if (!$formation_id || !$nonce) {
        wp_send_json_error(['message' => 'Paramètres manquants']);
    }

    if (!wp_verify_nonce($nonce, 'toggle_formation_' . $formation_id)) {
        wp_send_json_error(['message' => 'Nonce invalide']);
    }

    // Permissions (utilise un droit plus permissif si nécessaire)
    // if (!current_user_can('edit_posts')) {
    //     wp_send_json_error(['message' => 'Accès refusé']);
    // }

    global $wpdb;
    $table = $wpdb->prefix . 'formations_lms';

    // Mise à jour
    $updated = $wpdb->query($wpdb->prepare(
        "UPDATE $table SET statut = NOT statut WHERE id = %d", $formation_id
    ));

    if ($updated === false) {
        wp_send_json_error(['message' => 'Erreur SQL : ' . $wpdb->last_error]);
    }

    // Nouveau statut
    $new_status = (bool)$wpdb->get_var($wpdb->prepare(
        "SELECT statut FROM $table WHERE id = %d", $formation_id
    ));

    wp_send_json_success([
        'new_status' => $new_status,
        'message' => 'Statut mis à jour'
    ]);

    wp_die(); // IMPORTANT
}


// Ajouter cette fonction dans votre classe plugin ou dans functions.php
add_action('wp_ajax_upload_formation_file', 'handle_formation_file_upload');
add_action('wp_ajax_nopriv_upload_formation_file', 'handle_formation_file_upload');

function handle_formation_file_upload() {

    // Vérification renforcée du nonce
    // if (!isset($_POST['upload_nonce']) || !wp_verify_nonce($_POST['upload_nonce'], 'upload_formation_file')) {
    //     wp_send_json_error('Erreur de sécurité');
    // }

    // // Vérification des capacités utilisateur
    // if (!current_user_can('edit_posts')) { // Plus permissif que 'upload_files'
    //     wp_send_json_error(
    //         'Permission refusée. Vous devez être connecté en tant qu\'éditeur ou administrateur.',
    //         403
    //     );
    // }
    
    // Vérifier le nonce
    if (!isset($_POST['upload_nonce'])) {
        wp_send_json_error('Nonce non fourni');
    }

    if (!wp_verify_nonce($_POST['upload_nonce'], 'upload_formation_file')) {
        wp_send_json_error('Nonce invalide');
    }

    // // Vérifier les permissions
    // if (!current_user_can('upload_files')) {
    //     wp_send_json_error('Permission refusée');
    // }

    // Vérifier si des fichiers ont été envoyés
    if (empty($_FILES['formation_files'])) {
        wp_send_json_error('Aucun fichier téléchargé');
    }

    $jour_id = isset($_POST['jour_id']) ? intval($_POST['jour_id']) : 0;
    if ($jour_id <= 0) {
        wp_send_json_error('Jour de formation invalide');
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Définir les extensions autorisées
    $document_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'txt', 'odt', 'ods', 'odp'];
    $video_extensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'mpeg', 'mpg', 'm4v'];
    
    $uploaded_files = [];
    $errors = [];
    $wp_upload_dir = wp_upload_dir();
    $formation_upload_dir = $wp_upload_dir['basedir'] . '/formation_files/';
    
    // Créer le répertoire s'il n'existe pas
    if (!file_exists($formation_upload_dir)) {
        wp_mkdir_p($formation_upload_dir);
    }

    // Traiter chaque fichier uploadé
    foreach ($_FILES['formation_files']['name'] as $key => $value) {
        if ($_FILES['formation_files']['error'][$key] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erreur lors du téléchargement de ' . $value;
            continue;
        }

        $file_name = sanitize_file_name($value);
        $file_tmp = $_FILES['formation_files']['tmp_name'][$key];
        $file_size = $_FILES['formation_files']['size'][$key];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Déterminer le type de fichier
        $file_type = 'autre';
        if (in_array($file_extension, $document_extensions)) {
            $file_type = 'fichier';
        } elseif (in_array($file_extension, $video_extensions)) {
            $file_type = 'video';
        }

        // Préparer le tableau de fichier pour wp_handle_upload
        $file = array(
            'name' => $file_name,
            'type' => $_FILES['formation_files']['type'][$key],
            'tmp_name' => $file_tmp,
            'error' => $_FILES['formation_files']['error'][$key],
            'size' => $file_size
        );

        // Déplacer le fichier et gérer l'upload
        $upload = wp_handle_upload($file, array('test_form' => false));

        if ($upload && !isset($upload['error'])) {
            global $wpdb;
            
            // Vérifier si le fichier existe déjà pour ce jour
            $existing_file = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}formation_fichiers 
                WHERE jour_id = %d AND nom_fichier = %s",
                $jour_id,
                $file_name
            ));

            // Insérer seulement si le fichier n'existe pas déjà
            if (!$existing_file) {
                $wpdb->insert(
                    "{$wpdb->prefix}formation_fichiers",
                    array(
                        'jour_id' => $jour_id,
                        'type' => $file_type,
                        'nom_fichier' => $file_name,
                        'chemin_fichier' => $upload['url'],
                        'taille' => $file_size,
                        'uploaded_at' => current_time('mysql')
                    ),
                    array('%d', '%s', '%s', '%s', '%d', '%s')
                );

                $uploaded_files[] = $file_name;
            } else {
                $errors[] = 'Le fichier ' . $file_name . ' existe déjà pour ce jour';
            }
        } else {
            $errors[] = 'Erreur lors du téléchargement de ' . $file_name . ': ' . $upload['error'];
            error_log('Erreur upload fichier: ' . $upload['error']);
        }
    }

    if (!empty($errors)) {
        wp_send_json_error(implode('<br>', $errors));
    }

    wp_send_json_success(array(
        'message' => count($uploaded_files) . ' fichiers téléchargés avec succès',
        'files' => $uploaded_files
    ));
}


// Assurez-vous que ces actions sont bien définies
add_action('wp_ajax_toggle_file_visibility', 'handle_toggle_file_visibility');
add_action('wp_ajax_nopriv_toggle_file_visibility', 'handle_toggle_file_visibility'); // Seulement si nécessaire

function handle_toggle_file_visibility() {
    // Vérification de sécurité
    if (!check_ajax_referer('formation-nonce', 'nonce', false)) {
        wp_send_json_error('Erreur de sécurité', 403);
    }

    // Vérification des permissions
    // if (!current_user_can('edit_posts')) {
    //     wp_send_json_error('Permission refusée', 403);
    // }

    // Validation des données
    $file_id = isset($_POST['file_id']) ? intval($_POST['file_id']) : 0;
    $visibility = isset($_POST['visibility']) ? intval($_POST['visibility']) : 0;

    if ($file_id <= 0) {
        wp_send_json_error('Fichier invalide', 400);
    }

    // Mise à jour en base de données
    global $wpdb;
    $result = $wpdb->update(
        "{$wpdb->prefix}formation_fichiers",
        array('visible_participants' => $visibility),
        array('id' => $file_id),
        array('%d'),
        array('%d')
    );

    if ($result === false) {
        wp_send_json_error('Erreur de base de données', 500);
    }

    wp_send_json_success(array(
        'message' => 'Visibilité mise à jour',
        'new_visibility' => $visibility
    ));
}

/*** */
function get_formation_stats() {
    global $wpdb;
    
    return [
        'total_participants' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}lms_users WHERE role = 'participant'"),
        'total_coachs' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}lms_users WHERE role = 'coach'"),
        'total_formations' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}formations_lms"),
        'total_regions' => $wpdb->get_var("SELECT COUNT(DISTINCT region) FROM {$wpdb->prefix}lms_users")
    ];
}

