<?php
// Shortcode pour la page de connexion
add_shortcode('custom_login_page', 'cap_login_page_shortcode');

function cap_login_page_shortcode() {
    ob_start();
    
    if (cap_is_user_logged_in()) {
        echo '<p>Vous êtes déjà connecté. <a href="' . home_url('/profile') . '">Accéder à votre profil</a> ou <a href="' . home_url('/?action=logout') . '">vous déconnecter</a>.</p>';
    } else {
        echo do_shortcode('[custom_login_form]');
    }
    
    return ob_get_clean();
}


// function fichier_viewer_shortcode() {
//     ob_start();
//     include CAP_PLUGIN_DIR . 'includes/templates/header.php';

//     // Validation sécurisée de l'input
//     $file_id = isset($_GET['file_id']) ? absint($_GET['file_id']) : 0;
    
//     if (!$file_id) {
//         return '<p>Fichier introuvable. Veuillez spécifier un ID valide.</p>';
//     }
    
//     global $wpdb;
//     // $fichier = $wpdb->get_row($wpdb->prepare(
//     //     "SELECT * FROM {$wpdb->prefix}formation_fichiers WHERE id = %d", 
//     //     $file_id
//     // ));

//     $fichier = $wpdb->get_row($wpdb->prepare(
//         "SELECT f.*, j.numero_jour, j.titre as jour_titre, 
//                 fo.titre as formation_titre, fo.bloc
//          FROM {$wpdb->prefix}formation_fichiers f
//          JOIN {$wpdb->prefix}formation_jours j ON f.jour_id = j.id
//          JOIN {$wpdb->prefix}formations_lms fo ON j.formation_id = fo.id
//          WHERE f.id = %d", 
//         $file_id
//     ));
    
//     if (!$fichier || empty($fichier->chemin_fichier)) {
//         return '<p>Fichier non trouvé dans la base de données.</p>';
//     }
    
//     // Préparation des variables
//     $extension = strtolower(pathinfo($fichier->nom_fichier, PATHINFO_EXTENSION));
//     $file_url = esc_url_raw($fichier->chemin_fichier);
//     $filename = esc_html(pathinfo($fichier->nom_fichier, PATHINFO_FILENAME));
    
//     // Normalisation de l'URL (relative → absolue)
//     if (strpos($file_url, 'http') !== 0) {
//         $file_url = home_url($file_url);
//     }
    
//     // Conversion en HTTPS si nécessaire
//     $file_url = str_replace('http://', 'https://', $file_url);

//     // Récupérer le nom du fichier sans extension
//     $filename = pathinfo($fichier->nom_fichier, PATHINFO_FILENAME);

//     // Limiter à 30 caractères et ajouter "..." si nécessaire
//     $display_name = (mb_strlen($filename) > 10) 
//         ? mb_substr($filename, 0, 17) . '...' 
//         : $filename;

//     echo '<div class="detail_formation">
//     <div class="header">
//         <div class="breadcrumb">
//             <a href="#">Blocs de Formations</a> >
//             <a href="#">Bloc ' . esc_html($fichier->bloc) . '</a> >  
//             <a href="#">Jour ' . esc_html($fichier->numero_jour) . '</a> > 
//             <b>' . esc_html($display_name) . '</b>            
//         </div>
//         <div class="header-btn">
//             <button>
//                 <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
//                     <path d="M10.0013 15.8327L4.16797 9.99935L10.0013 4.16602" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
//                     <path d="M15.8346 10H4.16797" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
//                 </svg>
//                 Retour
//             </button>
//         </div>           
//     </div>';
    
//     echo '<div class="file-viewer-container">';
    
//     // PDF - Affichage natif
//     if ($extension === 'pdf') {
//         echo '<embed src="' . esc_url($file_url) . '" 
//                 type="application/pdf" 
//                 width="100%" 
//                 height="900px" />';
//     } 
//     // Word - Solutions multiples
//     elseif (in_array($extension, ['doc', 'docx', 'ppt', 'pptx'])) {
//         // 1. Essayer Microsoft Office Online Viewer
//         $office_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($file_url);
        
//         // 2. Essayer Google Docs Viewer (alternative)
//         $google_url = 'https://docs.google.com/gview?url=' . urlencode($file_url) . '&embedded=true';
        
//         echo '<div class="viewer-options">';
        
//         // Solution principale
//         echo '<iframe id="office-viewer" 
//                 src="' . esc_url($office_url) . '" 
//                 width="100%" 
//                 height="600px" 
//                 frameborder="0" 
//                 allowfullscreen></iframe>';
        
//         // Solution alternative cachée
//         echo '<iframe id="google-viewer" 
//                 src="' . esc_url($google_url) . '" 
//                 width="100%" 
//                 height="600px" 
//                 frameborder="0" 
//                 style="display:none;"></iframe>';

//         echo '<iframe src="https://docs.google.com/gview?url=https://mycig.astraldigital.ma/wp-content/uploads/2025/06/LimitesContTS1.doc&embedded=true" width="100%" height="600px" frameborder="0"></iframe>';
        
//         // Message d'erreur initialement caché
//         echo '<div id="viewer-error" class="error-message" style="display:none;">
//                 <p>L\'affichage direct n\'est pas disponible.</p>
//                 <a href="' . esc_url($file_url) . '" class="button" download>Télécharger le fichier</a>
//               </div>';
        
//         echo '</div>';
        
//         // JavaScript pour basculer entre les viewers
//         echo '<script>
//                 document.getElementById("office-viewer").onerror = function() {
//                     document.getElementById("office-viewer").style.display = "none";
//                     document.getElementById("google-viewer").style.display = "block";
//                     document.getElementById("google-viewer").onerror = function() {
//                         document.getElementById("google-viewer").style.display = "none";
//                         document.getElementById("viewer-error").style.display = "block";
//                     };
//                 };
//               </script>';
//     } 
//     // Autres formats non supportés
//     else {
//         echo '<p>Format non supporté. <a href="' . esc_url($file_url) . '" download>Télécharger le fichier</a></p>';
//     }
    
//     echo '</div> </div>';
//     return ob_get_clean();
// }
// add_shortcode('fichier_viewer', 'fichier_viewer_shortcode');

function fichier_viewer_shortcode() {
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

    // 1. VALIDATION DES PARAMÈTRES
    if (!isset($_GET['file_id']) || !is_numeric($_GET['file_id'])) {
        return '<div class="error-message">Fichier introuvable. Veuillez spécifier un ID valide.</div>';
    } 

    $file_id = absint($_GET['file_id']);
    
    // Vérification du nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'file_viewer_'.$file_id)) {
        return '<div class="error-message">Accès non autorisé.</div>';
    }

    
    global $wpdb;
    
    // 2. RÉCUPÉRATION DES DONNÉES
    $fichier = $wpdb->get_row($wpdb->prepare(
        "SELECT f.*, j.numero_jour, j.titre as jour_titre, 
                fo.titre as formation_titre, fo.bloc
                -- u.nom as coach_nom, u.role as coach_role, u.image as coach_image
         FROM {$wpdb->prefix}formation_fichiers f
         JOIN {$wpdb->prefix}formation_jours j ON f.jour_id = j.id
         JOIN {$wpdb->prefix}formations_lms fo ON j.formation_id = fo.id
        --  LEFT JOIN {$wpdb->prefix}lms_users u ON f.coach_id = u.id
         WHERE f.id = %d", 
        $file_id
    ));

    
    // Donner accès au fichier si l'utilisateur est connecté et si c'est une video.
    if (isset($_GET['file_id']) && cap_is_user_logged_in() && $fichier->type === 'video') {      
        $user = cap_get_current_user();
        $user_id = $user->id;

        // Récupérer l'ID de la formation
        $formation_id = $wpdb->get_var($wpdb->prepare(
            "SELECT formation_id FROM {$wpdb->prefix}formation_jours WHERE id = %d",
            $fichier->jour_id
        ));

        cap_enregistrer_acces_formation($user_id, $formation_id);
    }

    if (!$fichier) {
        return '<div class="error-message">Fichier non trouvé dans la base de données.</div>';
    }

    // Enregistrer l'accès de l'utilisateur
    if (cap_is_user_logged_in() && isset($fichier->jour_id)) {
        global $wpdb;
        
        $user = cap_get_current_user();
        $user_id = $user->id;

        // Récupérer l'ID de la formation
        $formation_id = $wpdb->get_var($wpdb->prepare(
            "SELECT formation_id FROM {$wpdb->prefix}formation_jours WHERE id = %d",
            $fichier->jour_id
        ));
        
        if ($formation_id) {
            cap_enregistrer_acces_formation($user_id, $formation_id);
        }
    }
    //

    // 3. PRÉPARATION DES URLs
    $file_url = esc_url_raw($fichier->chemin_fichier);
    
    // Correction de l'URL si nécessaire
    if (strpos($file_url, 'http') !== 0) {
        $upload_dir = wp_upload_dir();
        $file_url = $upload_dir['baseurl'] . '/' . ltrim($file_url, '/');
    }

    // URL de retour
    $back_url = remove_query_arg(['file_id', 'nonce']);
    if (isset($_SERVER['HTTP_REFERER'])) {
        $back_url = $_SERVER['HTTP_REFERER'];
    }
    $filename = pathinfo($fichier->nom_fichier, PATHINFO_FILENAME);
    
    //  Limiter à 30 caractères et ajouter "..." si nécessaire
        $display_name = (mb_strlen($filename) > 10) 
            ? mb_substr($filename, 0, 17) . '...' 
            : $filename;

    // 4. AFFICHAGE DU VIEWER
    ?>
    <div class="detail_formation">
        <div class="header">
            <div class="breadcrumb">
                <a href="<?= esc_url(get_permalink(1759)) ?>">Blocs de Formations</a> >
                <a href="<?= esc_url(get_permalink(1764)).'?id='.$fichier->bloc  ?>">Bloc <?= esc_html($fichier->bloc) ?></a> >  
                <a href="<?= esc_url(add_query_arg('jour_id', $fichier->jour_id, $back_url)) ?>">Jour <?= esc_html($fichier->numero_jour) ?> </a> > 
                <b><?= esc_html($display_name) ?></b> 
            </div>            
            <div class="header-btn">
                <a href="<?= esc_url($back_url) ?>" class="back-button">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.0013 15.8327L4.16797 9.99935L10.0013 4.16602" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.8346 10H4.16797" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Retour
                </a>
            </div>          
        </div>
        
               
        <div class="video-viewer-container">
            <?php if ($fichier->type === 'video'): ?>
                <!-- VIEWER VIDÉO -->
                <div class="video-player-wrapper">
                    <video controls autoplay playsinline class="video-player">
                        <source src="<?= esc_url($file_url) ?>" type="video/mp4">
                        Votre navigateur ne supporte pas les vidéos HTML5.
                    </video>                
                </div>
                                
                <div class="related-videos section_videos">  
                    <h3>Vidéos <span style="color: #04477A4D;">(Français)</span></h3>                                     
                    <div class="related-videos-grid ">                        
                        <?php
                        $related_videos = $wpdb->get_results($wpdb->prepare(
                            "SELECT * FROM {$wpdb->prefix}formation_fichiers 
                            WHERE jour_id = %d 
                            AND type = 'video' 
                            AND id != %d
                            ORDER BY uploaded_at",
                            $fichier->jour_id,
                            $fichier->id
                        ));
                                             
                        if (empty($related_videos)) {
                            echo '<p class="no-related-videos">Aucune autre vidéo disponible pour ce jour.</p>';
                        } else {
                            $i = 1;
                            foreach ($related_videos as $related) {
                                // Récupérer le nom du fichier sans extension
                                $video_title = pathinfo($related->nom_fichier, PATHINFO_FILENAME);

                                $related_url = add_query_arg([
                                    'file_id' => $related->id,
                                    'nonce' => wp_create_nonce('file_viewer_'.$related->id)
                                ], get_permalink(1782));
                                ?>
                                <div class="video-card">
                                    <a href="<?= esc_url($related_url) ?>">
                                        <div class="video-thumbnail" style="background-image: url('<?= esc_url(plugins_url('assets/images/video-thumbnail.jpg', __FILE__)) ?>')">
                                            <div class="play-button">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-info">
                                            <h4 class="title-video"><?= esc_html($video_title) ?></h4>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

            <?php else: ?>
                <!-- VIEWER DOCUMENTS -->
                <?php $extension = strtolower(pathinfo($fichier->nom_fichier, PATHINFO_EXTENSION)); ?>
                
                <?php if ($extension === 'pdf'): ?>
                    <embed src="<?= esc_url($file_url) ?>#toolbar=0&navpanes=0" 
                           type="application/pdf" 
                           width="100%" 
                           height="900px">
                <?php elseif (in_array($extension, ['doc', 'docx', 'ppt', 'pptx'])): ?>
                    <div class="office-document">
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?= urlencode($file_url) ?>" 
                                width="100%" 
                                height="900px" 
                                frameborder="0"></iframe>
                    </div>
                <?php else: ?>
                    <div class="unsupported-format">
                        <p>Ce format de fichier ne peut pas être prévisualisé.</p>
                        <a href="<?= esc_url($file_url) ?>" class="button primary" download>
                            Télécharger le fichier
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Contrôles vidéo personnalisés
        if ($('.video-player').length) {
            const video = $('.video-player')[0];
            const playBtn = $('.play-pause-btn');
            const volumeSlider = $('.volume-slider');
            
            playBtn.on('click', function() {
                if (video.paused) {
                    video.play();
                    playBtn.text('Pause');
                } else {
                    video.pause();
                    playBtn.text('Play');
                }
            });
            
            volumeSlider.on('input', function() {
                video.volume = this.value;
            });
        }
    });
    </script>
    <?php
    
    return ob_get_clean();
}
add_shortcode('fichier_viewer', 'fichier_viewer_shortcode');

//

function video_viewer_shortcode() {
    ob_start();
    include CAP_PLUGIN_DIR . 'includes/templates/header.php';

    // Validation sécurisée
    if (!isset($_GET['video_id'])) {
        return '<p>Vidéo introuvable. Veuillez spécifier un ID valide.</p>';
    }

    $video_id = absint($_GET['video_id']);

    echo $video_id;
    $nonce = sanitize_text_field($_GET['nonce'] ?? '');

    if (!wp_verify_nonce($nonce, 'video_viewer_'.$video_id)) {
        return '<p>Accès non autorisé.</p>';
    }

    global $wpdb;
    
    // Récupérer la vidéo avec les infos du jour et formation
    $video = $wpdb->get_row($wpdb->prepare(
        "SELECT v.*, j.numero_jour, j.titre as jour_titre, 
                f.titre as formation_titre, f.bloc
                -- c.nom as coach_nom, c.role as coach_role, c.image as coach_image
         FROM {$wpdb->prefix}formation_fichiers v
         JOIN {$wpdb->prefix}formation_jours j ON v.jour_id = j.id
         JOIN {$wpdb->prefix}formations_lms f ON j.formation_id = f.id
        --  LEFT JOIN {$wpdb->prefix}lms_users c ON v.coach_id = c.id
         WHERE v.id = %d AND v.type = 'video'", 
        $video_id
    ));

    if (!$video) {
        return '<p>Vidéo non trouvée dans la base de données.</p>';
    }

    $video_url = esc_url_raw($video->chemin_fichier);

    $video_title = esc_html(pathinfo($video->nom_fichier, PATHINFO_FILENAME));
    $coach_avatar = $video->coach_image ? esc_url($video->coach_image) : 
        'https://via.placeholder.com/100/4A90E2/FFFFFF?text='.mb_substr($video->coach_nom, 0, 1);
    ?>
    
    <div class="detail_formation">
        <div class="header">
            <div class="breadcrumb">
                <a href="#">Bloc <?= esc_html($video->bloc) ?></a> > 
                <a href="#"><?= esc_html($video->formation_titre) ?></a> > 
                <a href="#">Jour <?= esc_html($video->numero_jour) ?></a> > 
                <span><?= $video_title ?></span>
            </div>
            <div class="header-btn">
                <button onclick="window.history.back();">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10.0013 15.8327L4.16797 9.99935L10.0013 4.16602" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.8346 10H4.16797" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Retour
                </button>
            </div>           
        </div>
        
        <div class="video-viewer-container">
            <div class="video-wrapper">
                <video controls autoplay class="video-player">
                    <source src="<?= $video_url ?>" type="video/mp4">
                    Votre navigateur ne supporte pas les vidéos HTML5.
                </video>
            </div>
            
            <div class="video-details">
                <h2><?= $video_title ?></h2>
                
                <div class="meta-info">
                    <span class="duration">Durée: 15:32</span> <!-- À rendre dynamique -->
                    <span class="language">Langue: Français</span> <!-- À rendre dynamique -->
                </div>
                
                <div class="description">
                    <h3>Description</h3>
                    <p><?= nl2br(esc_html($video->description ?? 'Aucune description disponible')) ?></p>
                </div>
                
                <div class="coach-info">
                    <img src="<?= $coach_avatar ?>" alt="<?= esc_attr($video->coach_nom) ?>">
                    <div>
                        <div class="name"><?= esc_html($video->coach_nom) ?></div>
                        <div class="role"><?= esc_html($video->coach_role) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}
add_shortcode('video_viewer', 'video_viewer_shortcode');