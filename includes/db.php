<?php
// Création des tables à l'activation
function cap_install_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // First check and remove 'groupe' column if it exists
    $users_table = $wpdb->prefix . 'lms_users';
    if ($wpdb->get_var("SHOW TABLES LIKE '$users_table'") == $users_table) {
        if ($wpdb->get_var("SHOW COLUMNS FROM $users_table LIKE 'groupe'") == 'groupe') {
            $wpdb->query("ALTER TABLE $users_table DROP COLUMN groupe");
            error_log("Column 'groupe' removed from $users_table");
        }
    }
    
    // Liste de toutes nos tables à créer
    $tables = array(
        'lms_users' => "CREATE TABLE {$wpdb->prefix}lms_users (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nom varchar(100) NOT NULL,
            prenom varchar(100) NOT NULL,            
            email varchar(100) NOT NULL,
            telephone varchar(20) DEFAULT NULL,
            date_naissance date NOT NULL,
            image varchar(255) DEFAULT NULL,
            description text,
            password varchar(255) NOT NULL,
            ville_region varchar(100) NOT NULL,
            region varchar(100) NOT NULL,
            role enum('Participant', 'Coach', 'Master Coach') NOT NULL DEFAULT 'Participant',
            statut boolean NOT NULL DEFAULT false,
            genre enum('Homme', 'Femme') DEFAULT NULL,
            cin varchar(20) DEFAULT NULL,
            specialite varchar(100) DEFAULT NULL,
            adresse varchar(255) DEFAULT NULL,
            date_inscription date DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email)
        ) $charset_collate;",
        
        // Table de relation coach-participant (modifié pour utiliser le préfixe)
        'coach_participant' => "CREATE TABLE {$wpdb->prefix}coach_participant (
            coach_id mediumint(9) NOT NULL,
            participant_id mediumint(9) NOT NULL,
            assigned_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (coach_id, participant_id),
            FOREIGN KEY (coach_id) REFERENCES {$wpdb->prefix}lms_users(id) ON DELETE CASCADE,
            FOREIGN KEY (participant_id) REFERENCES {$wpdb->prefix}lms_users(id) ON DELETE CASCADE
        ) $charset_collate;",
               
        'formations_lms' => "CREATE TABLE {$wpdb->prefix}formations_lms (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_source enum('lms','wp') NOT NULL DEFAULT 'lms',
            user_id mediumint(9) DEFAULT NULL,
            wp_user_id bigint(20) UNSIGNED DEFAULT NULL,
            titre varchar(255) NOT NULL,
            description text,
            duree varchar(50) DEFAULT NULL,
            bloc int DEFAULT NULL,
            langue varchar(10) NOT NULL DEFAULT 'français',
            statut boolean NOT NULL DEFAULT false,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            -- KEY user_id (user_id)
            KEY wp_user_id (wp_user_id),
            KEY user_composite (user_source, user_id)
        ) $charset_collate;",
        
        'formation_jours' => "CREATE TABLE {$wpdb->prefix}formation_jours (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            formation_id mediumint(9) NOT NULL,
            numero_jour smallint NOT NULL,
            titre varchar(255) NOT NULL,
            description text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY formation_id (formation_id)
        ) $charset_collate;",
        
        'formation_fichiers' => "CREATE TABLE {$wpdb->prefix}formation_fichiers (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            jour_id mediumint(9) NOT NULL,
            type enum('fichier','video') NOT NULL,
            nom_fichier varchar(255) NOT NULL,
            chemin_fichier varchar(255) NOT NULL,
            taille bigint NOT NULL,
            uploaded_at datetime DEFAULT CURRENT_TIMESTAMP,
            visible_participants tinyint(1) DEFAULT 0,
            statut_approbation enum('en_attente','approuve','rejete') DEFAULT 'en_attente',
            PRIMARY KEY  (id),
            KEY jour_id (jour_id)
        ) $charset_collate;",
        
        'formation_inscriptions' => "CREATE TABLE {$wpdb->prefix}formation_inscriptions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id mediumint(9) NOT NULL,
            formation_id mediumint(9) NOT NULL,
            date_inscription datetime DEFAULT CURRENT_TIMESTAMP,
            statut enum('en_attente','valide','termine','abandon') DEFAULT 'en_attente',
            PRIMARY KEY (id),
            UNIQUE KEY user_formation (user_id, formation_id),
            KEY user_id (user_id),
            KEY formation_id (formation_id)
        ) $charset_collate;"
    );

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    foreach ($tables as $table_name => $sql) {
        dbDelta($sql);
        error_log("Table {$wpdb->prefix}{$table_name} processed");
    }

    // foreach ($tables as $table_name => $sql) {
    //     // Vérifier si la table existe déjà
    //     if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}{$table_name}'") != $wpdb->prefix.$table_name) {
    //         dbDelta($sql);
            
    //         // Log pour le débogage
    //         error_log("Table {$wpdb->prefix}{$table_name} créée avec succès");
    //     } else {
    //         // Si la table existe, on utilise dbDelta pour mettre à jour sa structure si nécessaire
    //         dbDelta($sql);
            
    //         // Log pour le débogage
    //         error_log("Table {$wpdb->prefix}{$table_name} existe déjà - vérification des modifications");
    //     }
    // }

    $users_table = $wpdb->prefix . 'lms_users';
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $users_table LIKE 'reset_token'");
    
    if (empty($columns)) {
        $wpdb->query("ALTER TABLE $users_table 
                     ADD COLUMN reset_token varchar(255) DEFAULT NULL,
                     ADD COLUMN reset_expires datetime DEFAULT NULL");
        error_log("Colonnes reset_token et reset_expires ajoutées");
    }
    
    // Vérifier et ajouter des contraintes de clé étrangère si elles n'existent pas

    cap_force_foreign_keys();

    cap_add_foreign_keys();
}

function cap_add_foreign_keys() {
    global $wpdb;
    
    // Liste de toutes les contraintes à vérifier/ajouter
    $foreign_keys = array(
        array(
            'table' => 'formations_lms',
            'column' => 'user_id',
            'reference_table' => 'lms_users',
            'reference_column' => 'id',
            'constraint_name' => 'fk_formations_user'            
        ),
        array(
            'table' => 'formations_lms',
            'column' => 'wp_user_id',
            'reference_table' => 'users',
            'reference_column' => 'ID',
            'constraint_name' => 'fk_formations_wp_user'
        ),
        array(
            'table' => 'formation_jours',
            'column' => 'formation_id',
            'reference_table' => 'formations_lms',
            'reference_column' => 'id',
            'constraint_name' => 'fk_formation_jours'
        ),        
        array(
            'table' => 'formation_fichiers',
            'column' => 'jour_id',
            'reference_table' => 'formation_jours',
            'reference_column' => 'id',
            'constraint_name' => 'fk_formation_fichiers'
        ),
        array(
            'table' => 'coach_participant',
            'column' => 'coach_id',
            'reference_table' => 'lms_users',
            'reference_column' => 'id',
            'constraint_name' => 'fk_coach_participant_coach'
        ),
        array(
            'table' => 'coach_participant',
            'column' => 'participant_id',
            'reference_table' => 'lms_users',
            'reference_column' => 'id',
            'constraint_name' => 'fk_coach_participant_participant'
        )
    );

    // foreach ($foreign_keys as $fk) {
    //     // Vérifier si la table existe
    //     if (!table_exists($wpdb->prefix . $fk['table'])) {
    //         continue;
    //     }

    //     // Vérifier si la table référencée existe
    //     if (!table_exists($wpdb->prefix . $fk['reference_table'])) {
    //         continue;
    //     }

    //     // VÉRIFICATION OPTIMISÉE (nouveau code)
    //     $fk_exists = $wpdb->get_var($wpdb->prepare("
    //         SELECT COUNT(*)
    //         FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    //         WHERE CONSTRAINT_SCHEMA = DATABASE()
    //         AND TABLE_NAME = %s
    //         AND CONSTRAINT_NAME = %s
    //         AND CONSTRAINT_TYPE = 'FOREIGN KEY'
    //     ", $wpdb->prefix . $fk['table'], $fk['constraint_name']));

    //     if (!$fk_exists) {
    //         try {
    //             // Ajouter la contrainte
    //             $wpdb->query($wpdb->prepare("
    //                 ALTER TABLE {$wpdb->prefix}{$fk['table']}
    //                 ADD CONSTRAINT {$fk['constraint_name']}
    //                 FOREIGN KEY (`{$fk['column']}`) REFERENCES {$wpdb->prefix}{$fk['reference_table']}(`{$fk['reference_column']}`)
    //                 ON DELETE CASCADE
    //             "));
                
    //             // Log de succès (optionnel)
    //             error_log("Contrainte {$fk['constraint_name']} ajoutée avec succès");
    //         } catch (Exception $e) {
    //             error_log("Erreur lors de l'ajout de la contrainte {$fk['constraint_name']}: " . $e->getMessage());
    //         }
    //     }
    // }

    foreach ($foreign_keys as $fk) {
        // Vérifier si la contrainte existe déjà
        $constraint_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
             WHERE CONSTRAINT_SCHEMA = DATABASE()
             AND TABLE_NAME = %s 
             AND CONSTRAINT_NAME = %s",
            $wpdb->prefix.$fk['table'],
            $fk['constraint_name']
        ));

        if (!$constraint_exists) {
            try {
                // Vérifier d'abord que toutes les valeurs user_id existent dans lms_users
                $invalid_records = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}{$fk['table']} f
                     LEFT JOIN {$wpdb->prefix}{$fk['reference_table']} u ON f.{$fk['column']} = u.{$fk['reference_column']}
                     WHERE u.{$fk['reference_column']} IS NULL AND f.{$fk['column']} IS NOT NULL"
                ));

                if ($invalid_records > 0) {
                    error_log("Impossible d'ajouter la contrainte : $invalid_records enregistrements invalides trouvés");
                    continue;
                }

                // Ajouter la contrainte
                $wpdb->query($wpdb->prepare(
                    "ALTER TABLE {$wpdb->prefix}{$fk['table']}
                     ADD CONSTRAINT %s
                     FOREIGN KEY (%s) REFERENCES {$wpdb->prefix}{$fk['reference_table']}(%s)
                     ON DELETE CASCADE",
                    $fk['constraint_name'],
                    $fk['column'],
                    $fk['reference_column']
                ));
                error_log("Contrainte {$fk['constraint_name']} ajoutée avec succès");
            } catch (Exception $e) {
                error_log("Erreur lors de l'ajout de la contrainte : " . $e->getMessage());
            }
        }
    }
}

function cap_force_foreign_keys() {
    global $wpdb;
    
    // Désactiver temporairement les vérifications de clés étrangères
    $wpdb->query("SET FOREIGN_KEY_CHECKS=0");
    
    // Liste des contraintes à forcer
    $constraints = [
        [
            'table' => 'formations_lms',
            'column' => 'user_id',
            'reference' => 'lms_users(id)',
            'name' => 'fk_formations_user'
        ]
        // Ajoutez d'autres contraintes si nécessaire
    ];
    
    foreach ($constraints as $constraint) {
        // Supprimer d'abord la contrainte si elle existe
        $wpdb->query("ALTER TABLE {$wpdb->prefix}{$constraint['table']} 
                      DROP FOREIGN KEY IF EXISTS {$constraint['name']}");
        
        // Puis ajouter la contrainte
        $wpdb->query("ALTER TABLE {$wpdb->prefix}{$constraint['table']} 
                      ADD CONSTRAINT {$constraint['name']}
                      FOREIGN KEY ({$constraint['column']}) 
                      REFERENCES {$wpdb->prefix}{$constraint['reference']}
                      ON DELETE CASCADE");
    }
    
    // Réactiver les vérifications
    $wpdb->query("SET FOREIGN_KEY_CHECKS=1");
}


function table_exists($table_name) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare(
        "SHOW TABLES LIKE %s", 
        $wpdb->esc_like($table_name)
    )) === $table_name;
}

// function cap_prepare_for_foreign_key() {
//     global $wpdb;
    
//     // 1. Vérifier si la colonne user_id existe
//     $column_exists = $wpdb->get_var($wpdb->prepare(
//         "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
//          WHERE TABLE_NAME = %s AND COLUMN_NAME = 'user_id'",
//         $wpdb->prefix.'formations_lms'
//     ));

//     if (!$column_exists) {
//         // Ajouter la colonne si elle n'existe pas
//         $wpdb->query("ALTER TABLE {$wpdb->prefix}formations_lms ADD COLUMN user_id mediumint(9) NOT NULL AFTER id");
//     }

//     // 2. Mettre à jour les valeurs NULL ou invalides
//     // Par défaut, mettez l'ID d'un admin ou un utilisateur valide
//     $default_user = $wpdb->get_var("SELECT MIN(id) FROM {$wpdb->prefix}lms_users WHERE role = 'Coach'");
    
//     if (!$default_user) {
//         $default_user = 1; // Fallback
//     }

//     $wpdb->query($wpdb->prepare(
//         "UPDATE {$wpdb->prefix}formations_lms 
//          SET user_id = %d 
//          WHERE user_id IS NULL OR user_id NOT IN (SELECT id FROM {$wpdb->prefix}lms_users)",
//         $default_user
//     ));

//     // 3. Ajouter l'index si nécessaire
//     $index_exists = $wpdb->get_var($wpdb->prepare(
//         "SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
//          WHERE TABLE_NAME = %s AND INDEX_NAME = 'user_id'",
//         $wpdb->prefix.'formations_lms'
//     ));

//     if (!$index_exists) {
//         $wpdb->query("ALTER TABLE {$wpdb->prefix}formations_lms ADD INDEX (user_id)");
//     }
// }

// function cap_add_foreign_keys() {
//     global $wpdb;
    
//     // Vérifier si la clé étrangère formation_jours.formation_id existe
//     $fk_check = $wpdb->get_row("
//         SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
//         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
//         WHERE TABLE_NAME = '{$wpdb->prefix}formation_jours' 
//         AND COLUMN_NAME = 'formation_id' 
//         AND CONSTRAINT_NAME != 'PRIMARY'
//     ");
    
//     if (empty($fk_check)) {
//         $wpdb->query("
//             ALTER TABLE {$wpdb->prefix}formation_jours
//             ADD CONSTRAINT fk_formation_jours
//             FOREIGN KEY (formation_id) REFERENCES {$wpdb->prefix}formations_lms(id) ON DELETE CASCADE
//         ");
//     }
    
//     // Vérifier si la clé étrangère formation_fichiers.jour_id existe
//     $fk_check = $wpdb->get_row("
//         SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
//         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
//         WHERE TABLE_NAME = '{$wpdb->prefix}formation_fichiers' 
//         AND COLUMN_NAME = 'jour_id' 
//         AND CONSTRAINT_NAME != 'PRIMARY'
//     ");
    
//     if (empty($fk_check)) {
//         $wpdb->query("
//             ALTER TABLE {$wpdb->prefix}formation_fichiers
//             ADD CONSTRAINT fk_formation_fichiers
//             FOREIGN KEY (jour_id) REFERENCES {$wpdb->prefix}formation_jours(id) ON DELETE CASCADE
//         ");
//     }

//     // Vérifier si la contrainte coach_id existe déjà
//     $fk_check = $wpdb->get_row("
//         SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
//         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
//         WHERE TABLE_NAME = '{$wpdb->prefix}coach_participant' 
//         AND COLUMN_NAME = 'coach_id' 
//         AND CONSTRAINT_NAME != 'PRIMARY'
//     ");
    
//     if (empty($fk_check)) {
//         $wpdb->query("
//             ALTER TABLE {$wpdb->prefix}coach_participant
//             ADD CONSTRAINT fk_coach_participant_coach
//             FOREIGN KEY (coach_id) REFERENCES {$wpdb->prefix}lms_users(id) ON DELETE CASCADE
//         ");
//     }

//     // Vérifier si la contrainte participant_id existe déjà
//     $fk_check = $wpdb->get_row("
//         SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
//         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
//         WHERE TABLE_NAME = '{$wpdb->prefix}coach_participant' 
//         AND COLUMN_NAME = 'participant_id' 
//         AND CONSTRAINT_NAME != 'PRIMARY'
//     ");
    
//     if (empty($fk_check)) {
//         $wpdb->query("
//             ALTER TABLE {$wpdb->prefix}coach_participant
//             ADD CONSTRAINT fk_coach_participant_participant
//             FOREIGN KEY (participant_id) REFERENCES {$wpdb->prefix}lms_users(id) ON DELETE CASCADE
//         ");
//     }
// }



// function table_exists($table_name) {
//     global $wpdb;
//     return $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
// }

// Désactivation
function cap_deactivate() {
    // Nettoyage si nécessaire
}

// Vérifier si l'utilisateur existe
function cap_user_exists($email) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users';
    
    return $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE email = %s", 
        $email
    )) > 0;
}

// Récupérer un utilisateur par email
function cap_get_user_by_email($email) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users';
    
    return $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE email = %s", 
        $email
    ));
}


function cap_create_wp_user($email, $password, $role = 'subscriber') {
    if (email_exists($email)) {
        return false; // L'email existe déjà
    }
    
    $user_id = wp_create_user($email, $password, $email);
    
    if (is_wp_error($user_id)) {
        return false;
    }
    
    $user = new WP_User($user_id);
    $user->set_role($role);
    
    // Mettre à jour le nom affiché
    wp_update_user(array(
        'ID' => $user_id,
        'display_name' => sanitize_text_field($_POST['nom'])
    ));
    
    return $user_id;
}