<?php
/**
 * Gestion des formations - Backoffice
 */

if (!defined('ABSPATH')) {
    exit; // Empêche l'accès direct
}

// Ajouter le menu d'administration
function cap_admin_menu_formations() {
    add_menu_page(
        'Formations LMS',
        'Formations LMS',
        'edit_others_posts',
        'cap-formations',
        'cap_formations_admin_page',
        'dashicons-welcome-learn-more',
        30
    );
    
    // Sous-menu pour les jours de formation
    add_submenu_page(
        'cap-formations',
        'Jours de Formation',
        'Jours de Formation',
        'edit_others_posts',
        'cap-formation-jours',
        'cap_formation_jours_page'
    );
    
    // Sous-menu pour les fichiers
    add_submenu_page(
        'cap-formations',
        'Fichiers de Formation',
        'Fichiers de Formation',
        'edit_others_posts',
        'cap-formation-fichiers',
        'cap_formation_fichiers_page'
    );
}
add_action('admin_menu', 'cap_admin_menu_formations');


// Page d'administration des formations
function cap_formations_admin_page() {
    global $wpdb;
    
    // Gestion des actions (ajout, modification, suppression)
    if (isset($_GET['action'])) {
        $action = sanitize_text_field($_GET['action']);
        $formation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        switch ($action) {
            case 'edit':
                cap_handle_edit_formation($formation_id);
                return;
            case 'delete':
                cap_delete_formation($formation_id);
                break;
        }
    }
    
    // Gestion de la soumission du formulaire
    if (isset($_POST['submit_formation'])) {
        cap_save_formation($_POST);
    }
    
    // Affichage de la liste des formations
    cap_display_formations_list();
}

// Afficher la liste des formations
// function cap_display_formations_list() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'formations_lms';
//     $formations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    
//     ? >
//     <div class="wrap">
//         <h1 class="wp-heading-inline">Gestion des Formations</h1>
//         <a href="<?php echo admin_url('admin.php?page=cap-formations&action=edit'); ? >" class="page-title-action">Ajouter une formation</a>
        
//         <hr class="wp-header-end">
        
//         <table class="wp-list-table widefat fixed striped">
//             <thead>
//                 <tr>
//                     <th style="width: 50px;"><b>ID</b></th>
//                     <th><b>Titre</b></th>                    
//                     <th><b>Langue</b></th>
//                     <th><b>Durée (par jours)</b></th>
//                     <th><b>Bloc</b></th>
//                     <th><b>Statut</b></th>
//                     <th><b>Date Création</b></th>                    
//                     <th><b>Actions</b></th>
//                 </tr>
//             </thead>
//             <tbody>
//                 <?php if (empty($formations)): ? >
//                     <tr>
//                         <td colspan="7">Aucune formation trouvée</td>
//                     </tr>
//                 < ?php else: ? >
//                     < ?php foreach ($formations as $formation): ? >
//                     <tr>
//                         <td>< ?php echo $formation->id; ? ></td>
//                         <td><?php echo esc_html($formation->titre); ? ></td>
//                         <td><?php echo esc_html($formation->langue); ? ></td>
//                         <td><?php echo esc_html($formation->duree); ? ></td>
//                         <td><?php echo 'BLOC ' . esc_html($formation->bloc); ? ></td>
//                         <td><?php echo ($formation->statut == true) ? '<span style="color:green">Activé</span>' : '<span style="color:red">Non activé</span>'; ? ></td>
//                         <td><?php echo date_i18n('d/m/Y H:i', strtotime($formation->created_at)); ? ></td>
//                         <td>
//                             <a href="< ?php echo admin_url('admin.php?page=cap-formations&action=edit&id=' . $formation->id); ? >" class="button button-primary">Modifier</a>
//                             <a href="< ?php echo admin_url('admin.php?page=cap-formations&action=delete&id=' . $formation->id); ? >" class="button button-link-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">Supprimer</a>
//                         </td>
//                     </tr>
//                     < ?php endforeach; ? >
//                 < ?php endif; ? >
//             </tbody>
//         </table>
//     </div>
//     <?php
// }

function cap_display_formations_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formations_lms';
    
    // Gestion de l'activation
    if (isset($_GET['action']) && $_GET['action'] == 'activate' && isset($_GET['id'])) {
        $formation_id = intval($_GET['id']);
        check_admin_referer('activate_formation_' . $formation_id);
        
        $formation = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $formation_id));
        
        if ($formation && $formation->statut != 1) {
            $updated = $wpdb->update(
                $table_name,
                array('statut' => 1, 'updated_at' => current_time('mysql')),
                array('id' => $formation_id),
                array('%d', '%s'),
                array('%d')
            );
            
            // if ($updated !== false) {
            //     wp_redirect(add_query_arg('updated', '1', admin_url('admin.php?page=cap-formations')));
            //     exit;
            // }
        }
    }
    
    // Afficher le message de succès
    if (isset($_GET['updated'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Formation activée avec succès.</p></div>';
    }
    
    $formations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Gestion des Formations</h1>
        <a href="<?php echo admin_url('admin.php?page=cap-formations&action=edit'); ?>" class="page-title-action">Ajouter une formation</a>
        
        <hr class="wp-header-end">
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 50px;"><b>ID</b></th>
                    <th><b>Titre de formation</b></th>
                    <th><b>Langue</b></th>
                    <th><b>Durée (par jours)</b></th>
                    <th style="width: 100px;"><b>Bloc</b></th>
                    <th style="width: 100px;"><b>Statut</b></th>
                    <th><b>Date Création</b></th>
                    <th style="width: 190px;"><b>Actions</b></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($formations)): ?>
                    <tr><td colspan="9">Aucune formation trouvée</td></tr>
                <?php else: foreach ($formations as $formation): ?>
                    <tr>
                        <td><?php echo $formation->id; ?></td>
                        <td><?php echo esc_html($formation->titre); ?></td>
                        <td><?php echo esc_html($formation->langue); ?></td>
                        <td><?php echo esc_html($formation->duree); ?> jours</td>
                        <td>BLOC <?php echo esc_html($formation->bloc); ?></td>
                        <td><?php echo $formation->statut ? '<span style="color:green">Activé</span>' : '<span style="color:red">Non activé</span>'; ?></td>
                        <td><?php echo date_i18n('d/m/Y H:i', strtotime($formation->created_at)); ?></td>
                        <td>
                            <?php if (!$formation->statut): ?>
                                <a href="<?php echo wp_nonce_url(
                                    admin_url('admin.php?page=cap-formations&action=activate&id='.$formation->id),
                                    'activate_formation_'.$formation->id
                                ); ?>" class="button button-small button-activate">
                                    Activer
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo admin_url('admin.php?page=cap-formations&action=edit&id='.$formation->id); ?>" 
                               class="button button-small">Modifier
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=cap-formations&action=delete&id='.$formation->id), 'delete_formation_'.$formation->id); ?>" 
                               class="button button-small button-link-delete" 
                               onclick="return confirm('Êtes-vous sûr ?')">Supprimer
                            </a>                            
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <style>
        .button-small {
            padding: 0 8px;
            height: 30px;
            line-height: 28px;
            font-size: 12px;
            margin: 0 2px;
        }
        .button-link-delete {
            color: #a00;
            border-color: transparent;
            box-shadow: none;
        }
        .button-activate {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        .button-activate:hover {
            background-color: #3e8e41;
            border-color: #3e8e41;
            color: white;
        }
    </style>
    <?php
}

// Gérer l'édition/ajout d'une formation
function cap_handle_edit_formation($formation_id = 0) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formations_lms';
    
    // Récupérer les données de la formation si édition
    $formation = $formation_id ? $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $formation_id)) : null;
    
    ?>
    <div class="wrap">
        <h2><?php echo $formation ? 'Modifier' : 'Ajouter'; ?> une formation</h2>
        
        <a href="<?php echo admin_url('admin.php?page=cap-formations'); ?>" class="page-title-action">Retour à la liste</a>
        
        <hr class="wp-header-end">
        
        <form method="post" action="<?php echo admin_url('admin.php?page=cap-formations'); ?>">
            <?php wp_nonce_field('cap_save_formation', 'cap_formation_nonce'); ?>
            
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="titre">Titre de la formation <span>*</span></label></th>
                        <td>
                            <input type="text" name="titre" id="titre" class="regular-text" value="<?php echo $formation ? esc_attr($formation->titre) : ''; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="description">Description</label></th>
                        <td>
                            <?php 
                            wp_editor(
                                $formation ? $formation->description : '',
                                'description',
                                array(
                                    'textarea_name' => 'description',
                                    'media_buttons' => false,
                                    'textarea_rows' => 5,
                                    'teeny' => true
                                )
                            ); 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="duree">Durée (nombre en jours) <span>*</span></label></th>
                        <td>
                            <input type="number" name="duree" id="duree" class="regular-text" value="<?php echo $formation ? esc_attr($formation->duree) : ''; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bloc">N° Bloc <span>*</span></label></th>
                        <td>
                            <input type="number" name="bloc" id="bloc" class="regular-text" value="<?php echo $formation ? esc_attr($formation->bloc) : ''; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="langue">Langue <span>*</span></label></th>
                        <td>
                            <select id="langue" name="langue" class="form-control" required>
                                <option value="">Sélectionnez une langue</option>
                                <option value="Français" <?php echo ($formation && $formation->langue == 'Français') ? 'selected' : ''; ?>>Français</option>
                                <option value="Arabe" <?php echo ($formation && $formation->langue == 'Arabe') ? 'selected' : ''; ?>>Arabe</option>
                                <option value="Anglais" <?php echo ($formation && $formation->langue == 'Anglais') ? 'selected' : ''; ?>>Anglais</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <input type="hidden" name="formation_id" value="<?php echo $formation ? $formation->id : 0; ?>">
            
            <p class="submit">
                <button type="submit" name="submit_formation" class="button button-primary"><?php echo $formation ? 'Mettre à jour' : 'Ajouter'; ?> la formation</button>
                <a href="<?php echo admin_url('admin.php?page=cap-formations'); ?>" class="button">Annuler</a>
            </p>
        </form>
    </div>
    <?php
}

// Sauvegarder une formation
function cap_save_formation($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formations_lms';
    
    // Vérifier le nonce
    if (!isset($data['cap_formation_nonce']) || !wp_verify_nonce($data['cap_formation_nonce'], 'cap_save_formation')) {
        wp_die('Action non autorisée');
    }
    
    // Vérifier les permissions
    if (!current_user_can('edit_others_posts')) {
        wp_die('Permissions insuffisantes');
    }
    
    // Préparer les données de base 
    $formation_data = array(
        'titre' => sanitize_text_field($data['titre']),
        'description' => wp_kses_post($data['description']),
        'duree' => sanitize_text_field($data['duree']),
        'bloc' => sanitize_text_field($data['bloc']),
        'langue' => sanitize_text_field($data['langue']),
        'updated_at' => current_time('mysql'),
        'created_at' => current_time('mysql'),
        'statut' => 1
    );

    // Gestion du user_id et user_source
    if (isset($data['user_source']) && $data['user_source'] === 'lms' && !empty($data['user_id'])) {
        // Vérifier que l'utilisateur LMS existe
        global $wpdb;
        $user_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users WHERE id = %d", 
            $data['user_id']
        ));
        
        if ($user_exists) {
            $formation_data['user_source'] = 'lms';
            $formation_data['user_id'] = intval($data['user_id']);
        } else {
            wp_die(__('Utilisateur LMS introuvable', 'lms'));
        }
    } else {
        // Cas par défaut - utilisateur WordPress
        $current_user_id = get_current_user_id();
        echo $current_user_id;
        
        $formation_data['user_source'] = 'wp';
        $formation_data['wp_user_id'] = $current_user_id;        
    }

    
    $formation_id = isset($data['formation_id']) ? intval($data['formation_id']) : 0;
    
    if ($formation_id) {
        $result = $wpdb->update(
            $table_name,
            $formation_data,
            array('id' => $formation_id)
        );
        
        if ($result !== false) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Formation mise à jour avec succès!</p></div>';
            });
        }
    } else {
        // Insertion
        $formation_data['created_at'] = current_time('mysql');
        
        $result = $wpdb->insert(
            $table_name,
            $formation_data
        );
        
        if ($result) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Formation ajoutée avec succès!</p></div>';
            });
        }
    }
    
    if ($result === false) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur lors de la sauvegarde de la formation.</p></div>';
        });
    }
}

// Supprimer une formation
function cap_delete_formation($formation_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formations_lms';
    
    // Vérifier les permissions
    if (!current_user_can('edit_others_posts')) {
        wp_die('Action non autorisée');
    }
    
    // Supprimer la formation
    $result = $wpdb->delete(
        $table_name,
        array('id' => $formation_id),
        array('%d')
    );
    
    if ($result) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Formation supprimée avec succès!</p></div>';
        });
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur lors de la suppression de la formation.</p></div>';
        });
    }
}


// Nouvelle page pour gérer les jours de formation
function cap_formation_jours_page() {
    global $wpdb;
    
    // Gestion des actions
    if (isset($_GET['action'])) {
        $action = sanitize_text_field($_GET['action']);
        $jour_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        switch ($action) {
            case 'edit':
                cap_handle_edit_jour($jour_id);
                return;
            case 'delete':
                cap_delete_jour($jour_id);
                break;
        }
    }
    
    // Gestion de la soumission du formulaire
    if (isset($_POST['submit_jour'])) {
        cap_save_jour($_POST);
    }
    
    // Affichage de la liste
    cap_display_jours_list();
}

// Afficher la liste des jours de formation
function cap_display_jours_list() {
    global $wpdb;
    $table_jours = $wpdb->prefix . 'formation_jours';
    $table_formations = $wpdb->prefix . 'formations_lms';
    
    $jours = $wpdb->get_results("
        SELECT j.*, f.titre as formation_titre 
        FROM $table_jours j
        LEFT JOIN $table_formations f ON j.formation_id = f.id
        ORDER BY j.formation_id, j.numero_jour
    ");
    
    ?>
    <div class="wrap">
        <h2 class="wp-heading-inline">Jours de Formation</h2>
        <a href="<?php echo admin_url('admin.php?page=cap-formation-jours&action=edit'); ?>" class="page-title-action">Ajouter un jour</a>
        
        <hr class="wp-header-end">
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 50px;"><b>ID</b></th>
                    <th><b>Titre de Formation</b></th>
                    <th><b>Numéro du jour</b></th>
                    <th><b>Titre</b></th>
                    <th><b>Description</b></th>
                    <th style="width: 240px;"><b>Actions</b></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jours)): ?>
                    <tr><td colspan="6">Aucun jour de formation trouvé</td></tr>
                <?php else: ?>
                    <?php foreach ($jours as $jour): ?>
                    <tr>
                        <td><?php echo $jour->id; ?></td>
                        <td><?php echo esc_html($jour->formation_titre); ?></td>
                        <td><?php echo 'Jour N°' . esc_html($jour->numero_jour); ?></td>
                        <td><?php echo esc_html($jour->titre); ?></td>
                        <td><?php echo esc_html(wp_trim_words($jour->description, 10)); ?></td>
                        <td>
                        <a href="<?php echo admin_url('admin.php?page=cap-formation-fichiers&jour_id=' . $jour->id); ?>" class="button">Fichiers</a>
                            <a href="<?php echo admin_url('admin.php?page=cap-formation-jours&action=edit&id=' . $jour->id); ?>" class="button button-primary">Modifier</a>
                            <a href="<?php echo admin_url('admin.php?page=cap-formation-jours&action=delete&id=' . $jour->id); ?>" class="button button-link-delete" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Gérer l'édition/ajout d'un jour
function cap_handle_edit_jour($jour_id = 0) {
    global $wpdb;
    $table_jours = $wpdb->prefix . 'formation_jours';
    $table_formations = $wpdb->prefix . 'formations_lms';
    
    // Récupérer les données
    $jour = $jour_id ? $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_jours WHERE id = %d", $jour_id)) : null;
    
    // Liste des formations disponibles
    $formations = $wpdb->get_results("SELECT id, titre FROM $table_formations ORDER BY titre");
    
    ?>
    <div class="wrap">
        <h1><?php echo $jour ? 'Modifier' : 'Ajouter'; ?> un jour de formation</h1>
        
        <a href="<?php echo admin_url('admin.php?page=cap-formation-jours'); ?>" class="page-title-action">Retour à la liste</a>
        
        <hr class="wp-header-end">
        
        <form method="post" action="<?php echo admin_url('admin.php?page=cap-formation-jours'); ?>">
            <?php wp_nonce_field('cap_save_jour', 'cap_jour_nonce'); ?>
            
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="formation_id">Formation *</label></th>
                        <td>
                            <select name="formation_id" id="formation_id" required>
                                <option value="">Sélectionnez une formation</option>
                                <?php foreach ($formations as $formation): ?>
                                <option value="<?php echo $formation->id; ?>" <?php selected($jour && $jour->formation_id == $formation->id); ?>>
                                    <?php echo esc_html($formation->titre); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="numero_jour">Numéro du jour *</label></th>
                        <td>
                            <input type="number" name="numero_jour" id="numero_jour" min="1" value="<?php echo $jour ? esc_attr($jour->numero_jour) : ''; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="titre">Titre *</label></th>
                        <td>
                            <input type="text" name="titre" id="titre" class="regular-text" value="<?php echo $jour ? esc_attr($jour->titre) : ''; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="description">Description</label></th>
                        <td>
                            <?php 
                            wp_editor(
                                $jour ? $jour->description : '',
                                'description',
                                array(
                                    'textarea_name' => 'description',
                                    'media_buttons' => false,
                                    'textarea_rows' => 5,
                                    'teeny' => true
                                )
                            ); 
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <input type="hidden" name="jour_id" value="<?php echo $jour ? $jour->id : 0; ?>">
            
            <p class="submit">
                <button type="submit" name="submit_jour" class="button button-primary"><?php echo $jour ? 'Mettre à jour' : 'Ajouter'; ?> le jour</button>
            </p>
        </form>
    </div>
    <?php
}

// Sauvegarder un jour de formation
function cap_save_jour($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formation_jours';
    
    // Vérification de sécurité
    if (!isset($data['cap_jour_nonce']) || !wp_verify_nonce($data['cap_jour_nonce'], 'cap_save_jour')) {
        wp_die('Action non autorisée');
    }
    
    if (!current_user_can('edit_others_posts')) {
        wp_die('Permissions insuffisantes');
    }
    
    // Préparation des données
    $jour_data = array(
        'formation_id' => intval($data['formation_id']),
        'numero_jour' => intval($data['numero_jour']),
        'titre' => sanitize_text_field($data['titre']),
        'description' => wp_kses_post($data['description'])
    );
    
    $jour_id = isset($data['jour_id']) ? intval($data['jour_id']) : 0;
    
    if ($jour_id) {
        // Mise à jour
        $result = $wpdb->update(
            $table_name,
            $jour_data,
            array('id' => $jour_id)
        );
    } else {
        // Insertion
        $result = $wpdb->insert($table_name, $jour_data);
    }
    
    // Gestion des notifications
    if ($result !== false) {
        $message = $jour_id ? 'Jour mis à jour avec succès!' : 'Jour ajouté avec succès!';
        add_action('admin_notices', function() use ($message) {
            echo '<div class="notice notice-success is-dismissible"><p>'.esc_html($message).'</p></div>';
        });
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur lors de la sauvegarde.</p></div>';
        });
    }
}

// Supprimer un jour de formation
function cap_delete_jour($jour_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formation_jours';
    
    if (!current_user_can('edit_others_posts')) {
        wp_die('Action non autorisée');
    }
    
    $result = $wpdb->delete(
        $table_name,
        array('id' => $jour_id),
        array('%d')
    );
    
    if ($result) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Jour supprimé avec succès!</p></div>';
        });
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur lors de la suppression.</p></div>';
        });
    }
}

// Page pour gérer les fichiers de formation
function cap_formation_fichiers_page() {
    global $wpdb;
    
    // Récupérer l'ID du jour si spécifié
    $jour_id = isset($_GET['jour_id']) ? intval($_GET['jour_id']) : 0;
    
    // Gestion des actions
    if (isset($_GET['action'])) {
        $action = sanitize_text_field($_GET['action']);
        $fichier_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        switch ($action) {
            case 'delete':
                cap_delete_fichier($fichier_id);
                break;
        }
    }
    
    // Gestion de l'upload
    if (isset($_POST['submit_fichier'])) {
        cap_handle_upload($jour_id);
    }
    
    // Affichage
    cap_display_fichiers_list($jour_id);
}

// Afficher la liste des fichiers
function cap_display_fichiers_list($jour_id) {
    global $wpdb;
    $table_fichiers = $wpdb->prefix . 'formation_fichiers';
    $table_jours = $wpdb->prefix . 'formation_jours';
    
    // Récupérer les informations du jour
    $jour = $jour_id ? $wpdb->get_row($wpdb->prepare(
        "SELECT j.*, f.titre as formation_titre 
         FROM $table_jours j
         LEFT JOIN {$wpdb->prefix}formations_lms f ON j.formation_id = f.id
         WHERE j.id = %d", 
        $jour_id
    )) : null;
    
    // Récupérer les fichiers
    $fichiers = $jour_id ? $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_fichiers WHERE jour_id = %d ORDER BY uploaded_at DESC",
        $jour_id
    )) : array();
    
    ?>
    <div class="wrap">
        <?php if ($jour): ?>
            <h2><b>Fichiers du Formation: </b> <?php echo esc_html($jour->formation_titre); ?> - Jour <b>N°<?php echo esc_html($jour->numero_jour); ?></b></h2>
        <?php else: ?>
            <h3>Tous les fichiers de formation</h3>
        <?php endif; ?>
        
        <hr class="wp-header-end">
        
        <?php if ($jour_id): ?>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('cap_upload_fichier', 'cap_fichier_nonce'); ?>
            
            <h2>Ajouter un fichier</h2>
            <table class="form-table">
                <tr>
                    <th><label for="fichier">Fichier</label></th>
                    <td><input type="file" name="fichier" id="fichier" required></td>
                </tr>
                <tr>
                    <th><label for="type">Type</label></th>
                    <td>
                        <select name="type" id="type" required>
                            <option value="fichier">fichier</option>
                            <option value="video">Vidéo</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <input type="hidden" name="jour_id" value="<?php echo $jour_id; ?>">
            <button type="submit" name="submit_fichier" class="button button-primary">Uploader</button>
        </form>
        
        <hr>
        <?php endif; 
                
        // Traitement des actions - DOIT ÊTRE LA PREMIÈRE LIGNE DU FICHIER
        if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['jour_id'])) {
            $fichier_id = intval($_GET['id']);
            $jour_id = intval($_GET['jour_id']);
            $table_name = $wpdb->prefix . 'formation_fichiers';
            
            // URL de base pour la redirection
            $base_url = admin_url('admin.php?page=cap-formation-fichiers&jour_id='.$jour_id);
            
            if ($_GET['action'] === 'approve' && check_admin_referer('approve_file_'.$fichier_id)) {
                $wpdb->update(
                    $table_name,
                    array('statut_approbation' => 'approuve'),
                    array('id' => $fichier_id),
                    array('%s'),
                    array('%d')
                );
                
                // Redirection immédiate avec JavaScript comme fallback
                echo '<script>window.location.href="'.add_query_arg('updated', 'approved', $base_url).'";</script>';
                wp_redirect(add_query_arg('updated', 'approved', $base_url));
                exit;
            }
            
            if ($_GET['action'] === 'reject' && check_admin_referer('reject_file_'.$fichier_id)) {
                $wpdb->update(
                    $table_name,
                    array('statut_approbation' => 'rejete'),
                    array('id' => $fichier_id),
                    array('%s'),
                    array('%d')
                );
                
                // Redirection immédiate avec JavaScript comme fallback
                echo '<script>window.location.href="'.add_query_arg('updated', 'rejected', $base_url).'";</script>';
                wp_redirect(add_query_arg('updated', 'rejected', $base_url));
                exit;
            }
        }

        // Affichage des messages
        if (isset($_GET['updated'])) {
            $message = ($_GET['updated'] === 'approved') 
                ? '<div class="notice notice-success is-dismissible"><p>Document approuvé avec succès.</p></div>'
                : '<div class="notice notice-info is-dismissible"><p>Document rejeté avec succès.</p></div>';
            echo $message;
        }

        ?>
                
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 80px;"><b>ID</b></th>
                    <th><b>Nom de fichier</b></th>
                    <th><b>Type</b></th>
                    <th><b>Statut</b></th>
                    <th><b>Taille de fichier</b></th>
                    <th><b>Date Création</b></th>
                    <th style="width: 315px"><b>Actions</b></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($fichiers)): ?>
                    <tr><td colspan="7">Aucun fichier trouvé</td></tr>
                <?php else: ?>
                    <?php foreach ($fichiers as $fichier): ?>
                    <tr>
                        <td><?php echo $fichier->id; ?></td>
                        <td><?php echo esc_html($fichier->nom_fichier); ?></td>
                        <td><?php echo esc_html($fichier->type); ?></td>
                        <td>
                            <?php 
                            $statuts = array(
                                'en_attente' => '<span style="color:orange">En attente</span>',
                                'approuve' => '<span style="color:green">Approuvé</span>',
                                'rejete' => '<span style="color:red">Rejeté</span>'
                            );
                            echo $statuts[$fichier->statut_approbation];
                            ?>
                        </td>
                        <td><?php echo size_format($fichier->taille); ?></td>
                        <td><?php echo date_i18n('d/m/Y H:i', strtotime($fichier->uploaded_at)); ?></td>
                        <td>
                            <!-- Bouton Autoriser -->
                            <?php if ($fichier->statut_approbation !== 'approuve'): ?>
                                <a href="<?php echo esc_url(wp_nonce_url(
                                    add_query_arg(array(
                                        'action' => 'approve',
                                        'id' => $fichier->id,
                                        'jour_id' => $jour_id
                                    ), 'admin.php?page=cap-formation-fichiers'),
                                    'approve_file_'.$fichier->id
                                )); ?>" class="button button-primary">Autoriser</a>
                            <?php endif; ?>
                            
                            <?php if ($fichier->statut_approbation !== 'rejete'): ?>
                                <a href="<?php echo esc_url(wp_nonce_url(
                                    add_query_arg(array(
                                        'action' => 'reject',
                                        'id' => $fichier->id,
                                        'jour_id' => $jour_id
                                    ), 'admin.php?page=cap-formation-fichiers'),
                                    'reject_file_'.$fichier->id
                                )); ?>" class="button button-link-delete">Rejeter</a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($fichier->chemin_fichier); ?>" class="button" download>Télécharger</a>
                            <a href="<?php echo admin_url('admin.php?page=cap-formation-fichiers&action=delete&id='.$fichier->id.'&jour_id='.$jour_id); ?>" class="button button-link-delete" onclick="return confirm('Êtes-vous sûr ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($jour_id): ?>
        <p><a href="<?php echo admin_url('admin.php?page=cap-formation-jours'); ?>" class="button">Retour aux jours de formation</a></p>
        <?php endif; ?>
    </div>
    <?php
}

// Gérer l'upload de fichier
function cap_handle_upload($jour_id) {
    global $wpdb;
    
    if (!isset($_POST['cap_fichier_nonce']) || !wp_verify_nonce($_POST['cap_fichier_nonce'], 'cap_upload_fichier')) {
        wp_die('Action non autorisée');
    }
    
    if (!current_user_can('edit_others_posts')) {
        wp_die('Permissions insuffisantes');
    }
    
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    
    $uploadedfile = $_FILES['fichier'];
    $upload_overrides = array('test_form' => false);
    
    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
    
    if ($movefile && !isset($movefile['error'])) {
        // Préparer les données pour la base de données
        $fichier_data = array(
            'jour_id' => $jour_id,
            'type' => sanitize_text_field($_POST['type']),
            'nom_fichier' => sanitize_text_field($uploadedfile['name']),
            'chemin_fichier' => esc_url_raw($movefile['url']),
            'taille' => $uploadedfile['size']
        );
        
        // Insérer dans la base de données
        $wpdb->insert(
            $wpdb->prefix . 'formation_fichiers',
            $fichier_data
        );
        
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Fichier uploadé avec succès!</p></div>';
        });
    } else {
        add_action('admin_notices', function() use ($movefile) {
            echo '<div class="notice notice-error is-dismissible"><p>Erreur lors de l\'upload: '.esc_html($movefile['error']).'</p></div>';
        });
    }
}

// Supprimer un fichier
function cap_delete_fichier($fichier_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formation_fichiers';
    
    if (!current_user_can('edit_others_posts')) {
        wp_die('Action non autorisée');
    }
    
    // Récupérer le fichier pour supprimer le fichier physique
    $fichier = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $fichier_id
    ));
    
    if ($fichier) {
        $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $fichier->chemin_fichier);
        
        // Supprimer le fichier physique
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Supprimer l'entrée en base de données
        $result = $wpdb->delete(
            $table_name,
            array('id' => $fichier_id),
            array('%d')
        );
        
        if ($result) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Fichier supprimé avec succès!</p></div>';
            });
            return;
        }
    }
    
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error is-dismissible"><p>Erreur lors de la suppression du fichier.</p></div>';
    });
}

// Ajouter des styles et scripts pour l'admin
function cap_admin_formation_scripts($hook) {
    if ($hook === 'custom-auth-profile_page_cap-formations') {
        wp_enqueue_style('cap-admin-styles', CAP_PLUGIN_URL . 'assets/css/admin.css');
        
        // Ajouter des scripts JS si nécessaire
        wp_enqueue_script(
            'cap-admin-script',
            CAP_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            '1.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'cap_admin_formation_scripts');