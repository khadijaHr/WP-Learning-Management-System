<?php
// function cap_users_list_page() {
//     // Vérification des permissions
//     if (!current_user_can('edit_others_posts')) {
//         wp_die(__('Vous n\'avez pas les permissions nécessaires.', 'custom-auth-profile'));
//     }

//     global $wpdb;
//     $table_name = $wpdb->prefix . 'lms_users';
    
//     // Gestion de la suppression
//     if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
//         $wpdb->delete($table_name, array('id' => intval($_GET['id'])));
//         echo '<div class="notice notice-success"><p>Utilisateur supprimé avec succès!</p></div>';
//     }
    
//     // Récupérer tous les utilisateurs
//     $users = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    
// 		// Affichage conditionnel des champs coach
// 		$showCoachFields = (
// 			$is_edit && in_array($user->role, array('Coach', 'Master Coach'), true)
// 		) || (
// 			isset($_POST['role']) && in_array($_POST['role'], array('Coach', 'Master Coach'), true)
// 		);

// 	? >
//     <div class="wrap">
//         <h1 class="wp-heading-inline">Utilisateurs LMS</h1>
//         <a href="<?php echo admin_url('admin.php?page=custom-users-lms-add'); ? >" class="page-title-action">Ajouter un utilisateur</a>
        
//         <table class="wp-list-table widefat fixed striped">
//             <thead>
//                 <tr>
//                     <th>ID</th>
//                     <th>Nom</th>
//                     <th>Email</th>
//                     <th>Rôle</th>
//                     <th>Région</th>
//                     <th>Date création</th>
//                     <th>Actions</th>
//                 </tr>
//             </thead>
//             <tbody>
//                 <?php foreach ($users as $user): ? >
//                 <tr>
//                     <td><?php echo $user->id; ? ></td>
//                     <td><?php echo esc_html($user->nom).' '.esc_html($user->prenom); ? ></td>
//                     <td><?php echo esc_html($user->email); ? ></td>
//                     <td><?php echo esc_html($user->role); ? ></td>
//                     <td><?php echo esc_html($user->region); ? ></td>
//                     <td><?php echo date_i18n('d/m/Y H:i', strtotime($user->created_at)); ? ></td>
//                     <td>
//                         <a href="<?php echo admin_url('admin.php?page=custom-users-lms-add&id=' . $user->id); ? >">Modifier</a> |
//                         <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=custom-users-lms&action=delete&id=' . $user->id), 'delete_user_' . $user->id); ? >" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">Supprimer</a>
//                     </td>
//                 </tr>
//                 <?php endforeach; ? >
//             </tbody>
//         </table>
//     </div>
//     <?php
// }


function cap_users_list_page() {
    // Vérification des permissions
    if (!current_user_can('edit_others_posts')) {
        wp_die(__('Vous n\'avez pas les permissions nécessaires.', 'custom-auth-profile'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users';
    
    // Gestion de la suppression
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $wpdb->delete($table_name, array('id' => intval($_GET['id'])));
        echo '<div class="notice notice-success"><p>Utilisateur supprimé avec succès!</p></div>';
    }
    
    // Gestion de l'activation
    if (isset($_GET['action']) && $_GET['action'] == 'activate' && isset($_GET['id']) && check_admin_referer('activate_user_' . $_GET['id'])) {
        $user_id = intval($_GET['id']);
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $user_id));
        
        if ($user && $user->statut != 1) {
            // Générer un mot de passe aléatoire
            $password = wp_generate_password(12);
                        
            if (!is_wp_error($user_id_wp)) {
                // Mettre à jour le statut dans la table custom
                $wpdb->update($table_name, 
                    array(
                        'statut' => 1,
                        'updated_at' => current_time('mysql')
                    ),
                    array('id' => $user_id)
                );
                
                // Envoyer l'email avec les identifiants
                $fullname = $user->prenom . ' ' . $user->nom;
                $email_sent = send_participant_credentials($user->email, $user->email, $password, $fullname);
                
                if ($email_sent) {
                    echo '<div class="notice notice-success"><p>Compte activé avec succès et email envoyé!</p></div>';
                } else {
                    echo '<div class="notice notice-warning"><p>Compte activé mais l\'email n\'a pas pu être envoyé.</p></div>';
                }
            } else {
                echo '<div class="notice notice-error"><p>Erreur lors de la création du compte: ' . $user_id_wp->get_error_message() . '</p></div>';
            }
        }
    }
    
    // Récupérer tous les utilisateurs
    $users = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    ?>
    <style>
        .cap-action-btn {
            padding: 3px 8px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-block;
            margin: 0 2px;
        }

        .cap-action-separator {
            color: #ddd;
            margin: 0 3px;
        }

        .cap-edit-btn {
            color: #2271b1;
            background-color: #f0f6fc;
            border: 1px solid #2271b1;
        }

        .cap-edit-btn:hover {
            background-color: #d0e3f8;
            border-color: #0a4b78;
        }

        .cap-delete-btn {
            color: #d63638;
            background-color: #f8ebea;
            border: 1px solid #d63638;
        }

        .cap-delete-btn:hover {
            background-color: #f5d6d6;
            border-color: #aa2e2e;
        }

        .cap-activate-btn {
            color: #00a32a;
            background-color: #e6f3e8;
            border: 1px solid #00a32a;
            font-weight: 600;
        }

        .cap-activate-btn:hover {
            background-color: #c8e6cb;
            border-color: #007017;
        }

        .wp-list-table td {
            vertical-align: middle;
            padding: 8px 10px;
        }
    </style>
    <div class="wrap">
        <h1 class="wp-heading-inline">Utilisateurs LMS</h1>
        <a href="<?php echo admin_url('admin.php?page=custom-users-lms-add'); ?>" class="page-title-action">Ajouter un utilisateur</a>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 50px;"><b>ID</b></th>
                    <th><b>Nom</b></th>
                    <th><b>Email</b></th>
                    <th><b>Rôle</b></th>
                    <th><b>Région</b></th>
                    <th><b>Statut</b></th>
                    <th><b>Date Création</b></th>
                    <th><b>Actions</b></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user->id; ?></td>
                    <td><?php echo esc_html($user->nom).' '.esc_html($user->prenom); ?></td>
                    <td><?php echo esc_html($user->email); ?></td>
                    <td><?php echo esc_html($user->role); ?></td>
                    <td><?php echo esc_html($user->region); ?></td>
                    <td><?php echo ($user->statut == true) ? '<span style="color:green">Activé</span>' : '<span style="color:red">Non activé</span>'; ?></td>
                    <td><?php echo date_i18n('d/m/Y H:i', strtotime($user->created_at)); ?></td>
                    <!-- <td>
                        <a href="< ?php echo admin_url('admin.php?page=custom-users-lms-add&id=' . $user->id); ?>" class="button button-primary">Modifier</a> 
                        <a href="< ?php echo wp_nonce_url(admin_url('admin.php?page=custom-users-lms&action=delete&id=' . $user->id), 'delete_user_' . $user->id); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')" class="button button-link-delete">Supprimer</a>
                        < ?php if ($user->statut !== true): ?>
                            | <a href="< ?php echo wp_nonce_url(admin_url('admin.php?page=custom-users-lms&action=activate&id=' . $user->id), 'activate_user_' . $user->id); ?>" onclick="return confirm('Êtes-vous sûr de vouloir activer ce compte? Un email sera envoyé à l\'utilisateur.')" style="color:green;font-weight:bold;">Activer</a>
                        < ?php endif; ?>
                    </td> -->
                    <td class="column-actions">
                        <div class="cap-actions-wrapper">
                        <?php if ($user->statut != 1): ?>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=custom-users-lms&action=activate&id=' . $user->id), 'activate_user_' . $user->id); ?>" onclick="return confirm('Êtes-vous sûr de vouloir activer ce compte? Un email sera envoyé à l\'utilisateur.')" class="cap-action-btn cap-activate-btn" title="Activer">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo admin_url('admin.php?page=custom-users-lms-add&id=' . $user->id); ?>" class="cap-action-btn cap-edit-btn" title="Modifier">
                                <span class="dashicons dashicons-edit"></span>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=custom-users-lms&action=delete&id=' . $user->id), 'delete_user_' . $user->id); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')" class="cap-action-btn cap-delete-btn" title="Supprimer">
                                <span class="dashicons dashicons-trash"></span>
                            </a>                            
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
/***** */

function cap_add_user_page() {
    // Vérification des permissions
    if (!current_user_can('edit_others_posts')) {
        wp_die(__('Vous n\'avez pas les permissions nécessaires.', 'custom-auth-profile'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'lms_users';
    
    $is_edit = isset($_GET['id']);
    $user = null;
    
    if ($is_edit) {
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_GET['id']));
        if (!$user) {
            wp_die('Utilisateur non trouvé');
        }
    }
    
    // Traitement du formulaire 
    if (isset($_POST['submit'])) {
        if (!isset($_POST['cap_user_nonce']) || !wp_verify_nonce($_POST['cap_user_nonce'], 'cap_save_user')) {
            wp_die(__('Action non autorisée.', 'custom-auth-profile'));
        }

        $data = array(
            'nom' => sanitize_text_field($_POST['nom']),
            'prenom' => sanitize_text_field($_POST['prenom']),
            'email' => sanitize_email($_POST['email']),
            'telephone' => isset($_POST['telephone']) ? sanitize_text_field($_POST['telephone']) : '',
            'date_naissance' => sanitize_text_field($_POST['date_naissance']),
            'ville_region' => sanitize_text_field($_POST['ville_region']),
            'role' => in_array($_POST['role'], array('Participant', 'Coach', 'Master Coach')) ? $_POST['role'] : 'Participant',
            'description' => sanitize_textarea_field($_POST['description']), 
            'region' => sanitize_text_field($_POST['region']),
            'genre' => sanitize_text_field($_POST['genre']),
            'cin' => sanitize_text_field($_POST['cin']),
            'specialite' => sanitize_text_field($_POST['specialite']),
            'adresse' => sanitize_textarea_field($_POST['adresse']),
            'date_inscription' => sanitize_text_field($_POST['date_inscription']),
            'statut' => isset($_POST['statut']) ? 1 : 0,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );
        
        // Gestion du mot de passe
        if (!empty($_POST['password'])) {
            $data['password'] = wp_hash_password($_POST['password']);
        } elseif (!$is_edit) {
            $data['password'] = wp_hash_password(wp_generate_password());
        }
        
        // Gestion de l'image
        if (!empty($_FILES['image']['name'])) {
            $upload = wp_handle_upload($_FILES['image'], array('test_form' => false));
            if (!isset($upload['error'])) {
                $data['image'] = $upload['url'];
            }
        }
        
        // Définir $message avant de l'utiliser
        $message = ''; // Initialisation
        
        if ($is_edit) {
            $wpdb->update($table_name, $data, array('id' => $user->id));
            $message = 'Utilisateur mis à jour avec succès!';
        } else {
            $wpdb->insert($table_name, $data);
            $message = 'Utilisateur ajouté avec succès!';
            
            // Synchronisation avec les utilisateurs WordPress
            $wp_user_id = cap_create_wp_user($data['email'], $_POST['password']);
            if ($wp_user_id) {
                $wpdb->update($table_name, array('wp_user_id' => $wp_user_id), array('id' => $wpdb->insert_id));
            }
        }

        // Afficher le message seulement si $message n'est pas vide
        if (!empty($message)) {
            echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
        }
        
        // Rafraîchir les données utilisateur
        if ($is_edit) {
            $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $user->id));
        }
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo $is_edit ? 'Modifier l\'utilisateur' : 'Ajouter un utilisateur'; ?></h1>
        
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('cap_save_user', 'cap_user_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th><label for="nom">Nom</label></th>
                    <td>
                        <input type="text" name="nom" id="nom" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->nom) : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <th><label for="nom">Prénom</label></th>
                    <td>
                        <input type="text" name="prenom" id="prenom" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->prenom) : ''; ?>" required>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="email">Email</label></th>
                    <td>
                        <input type="email" name="email" id="email" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->email) : ''; ?>" required>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="password">Mot de passe</label></th>
                    <td>
                        <input type="password" name="password" id="password" class="regular-text" placeholder="<?php echo $is_edit ? 'Laisser vide pour ne pas changer' : ''; ?>" <?php echo !$is_edit ? 'required' : ''; ?>>
                        <?php if ($is_edit): ?>
                        <p class="description">Laisser vide pour ne pas modifier le mot de passe</p>
                        <?php endif; ?>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="date_naissance">Date de naissance</label></th>
                    <td>
                        <input type="date" name="date_naissance" id="date_naissance" value="<?php echo $is_edit ? esc_attr($user->date_naissance) : ''; ?>" required>
                    </td>
                </tr>

                <tr>
                    <th><label for="region">Région</label></th>
                    <td>
                        <select name="region" id="region" required>
                            <option value="">Sélectionnez une Région</option>
                            <option value="rabat_sale_kenitra">Rabat, Salé, Kénitra</option>
                            <option value="casablanca_settat">Casablanca, Settat</option>
                            <option value="fes_meknes">Fés, Meknès</option>
                            <option value="beni_mellal_khenifra">Béni Mellal, Khenifra</option>
                            <option value="marrakech_safi">Marrakech Safi</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="ville_region">Ville</label></th>
                    <td>
                        <input type="text" name="ville_region" id="ville_region" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->ville_region) : ''; ?>" required>
                    </td>
                </tr>

                <tr>
                    <th><label for="telephone">Téléphone</label></th>
                    <td>
                        <input type="text" name="telephone" id="telephone" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->telephone) : ''; ?>">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="role">Rôle</label></th>
                    <td>
                        <select name="role" id="role" required>
                            <option value="Participant" <?php selected($is_edit && $user->role == 'Participant', true); ?>>Participant</option>
                            <option value="Coach" <?php selected($is_edit && $user->role == 'Coach', true); ?>>Coach</option>
                            <option value="Master Coach" <?php selected($is_edit && $user->role == 'Master Coach', true); ?>>Master Coach</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="image">Image de profil</label></th>
                    <td>
                        <?php if ($is_edit && !empty($user->image)): ?>
                            <img src="<?php echo esc_url($user->image); ?>" style="max-width: 150px; display: block; margin-bottom: 10px;">
                        <?php endif; ?>
                        <input type="file" name="image" id="image">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="description">Description</label></th>
                    <td>
                        <textarea name="description" id="description" rows="5" class="large-text"><?php echo $is_edit ? esc_textarea($user->description) : ''; ?></textarea>
                    </td>
                </tr>
                                              
                <tr>
                    <th><label for="genre">Genre</label></th>
                    <td>
                        <select name="genre" id="genre">
                            <option value="">Sélectionner un genre</option>
                            <option value="Homme" <?php echo ($is_edit && $user->genre == 'Homme') ? 'selected' : ''; ?>>Homme</option>
                            <option value="Femme" <?php echo ($is_edit && $user->genre == 'Femme') ? 'selected' : ''; ?>>Femme</option>
                        </select>
                    </td>
                </tr>
                
                <tr id="row-cin" style="<?php echo $showCoachFields ? '' : 'display: none;'; ?>">
                    <th><label for="cin">CIN</label></th>
                    <td>
                        <input type="text" name="cin" id="cin" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->cin) : ''; ?>" maxlength="20">
                        <p class="description">Numéro de carte d'identité nationale</p>
                    </td>
                </tr>
                
                <tr id="row-specialite" style="<?php echo $showCoachFields ? '' : 'display: none;'; ?>">
                    <th><label for="specialite">Spécialité</label></th>
                    <td>
                        <input type="text" name="specialite" id="specialite" class="regular-text" value="<?php echo $is_edit ? esc_attr($user->specialite) : ''; ?>">
                        <p class="description">Spécialité professionnelle (pour les coachs)</p>
                    </td>
                </tr>
                
                <tr id="row-adresse" style="<?php echo $showCoachFields ? '' : 'display: none;'; ?>">
                    <th><label for="adresse">Adresse complète</label></th>
                    <td>
                        <textarea name="adresse" id="adresse" rows="3" class="large-text"><?php echo $is_edit ? esc_textarea($user->adresse) : ''; ?></textarea>
                        <p class="description">Adresse postale complète</p>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="date_inscription">Date d'inscription</label></th>
                    <td>
                        <input type="date" name="date_inscription" id="date_inscription" value="<?php echo $is_edit ? esc_attr($user->date_inscription) : date('Y-m-d'); ?>">
                        <p class="description">Date d'inscription au système</p>
                    </td>
                </tr>
                
            </table>
            
            <?php submit_button($is_edit ? 'Mettre à jour' : 'Ajouter l\'utilisateur'); ?>
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var roleSelect = document.getElementById('role');
            var rows = [
                document.getElementById('row-cin'),
                document.getElementById('row-specialite'),
                document.getElementById('row-adresse')
            ];
            function updateVisibility() {
                if (!roleSelect) return;
                var show = roleSelect.value === 'Coach' || roleSelect.value === 'Master Coach';
                rows.forEach(function(row){
                    if (row) row.style.display = show ? 'table-row' : 'none';
                });
            }
            if (roleSelect) {
                roleSelect.addEventListener('change', updateVisibility);
                updateVisibility();
            }
        });
        </script>
    </div>
    <?php
}

// Page admin
function cap_formations_page() {
    include CAP_PLUGIN_DIR . 'includes/templates/admin-formations.php';
}
