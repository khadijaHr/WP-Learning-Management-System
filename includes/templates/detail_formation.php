<?php
$jour_id = intval($_GET['jour_id']);
$user = cap_get_current_user(); 
global $wpdb;

$jour = $wpdb->get_row($wpdb->prepare(
    "SELECT j.*, f.titre as formation_titre , f.bloc as formation_bloc
     FROM {$wpdb->prefix}formation_jours j
     JOIN {$wpdb->prefix}formations_lms f ON j.formation_id = f.id
     WHERE j.id = %d", 
    $jour_id
));

$query = "SELECT * FROM {$wpdb->prefix}formation_fichiers 
     JOIN {$wpdb->prefix}formation_jours j ON j.id = jour_id
     JOIN {$wpdb->prefix}formations_lms f ON f.id = j.formation_id
     WHERE jour_id = %d";

if($user->role === 'Participant'):
    $query .= " AND visible_participants = 1";
endif;

$query .= " AND statut_approbation = 'approuve' ORDER BY type, nom_fichier";

$fichiers = $wpdb->get_results($wpdb->prepare($query, $jour_id));
?>


<div class="detail_formation">
    <div class="header">
        <div class="breadcrumb">
            <a href="<?= esc_url(get_permalink(1759)) ?>">Blocs de Formations</a> > <a href="<?= esc_url(get_permalink(1764)).'?id='.$jour->formation_bloc ?>"><?= esc_html($jour->formation_titre) ?></a> > <b>Jour <?= $jour->numero_jour ?></b>            
        </div>
        <div class="header-btn">
            <a href="<?= esc_url(get_permalink(1764)).'?id='.$jour->formation_bloc ?>" class="back-button">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.0013 15.8327L4.16797 9.99935L10.0013 4.16602" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.8346 10H4.16797" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Retour
            </a>
            <?php if($user->role === 'Master Coach' || $user->role === 'Coach'): ?>
            <button id="addFileButton" class="add-file-button">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" fill="#04477A"/>
                    <path d="M9 5.66667V12.3333M12.3333 9H5.66667M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" stroke="#F7F9FF" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                 Ajouter un document
            </button>
            
            <!-- Popup pour ajouter des documents -->
            <div id="addFileModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Ajouter des documents</h2>
                        <span class="close">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <input type="hidden" name="jour_id" value="<?= $jour->id ?>">
                            <input type="hidden" name="action" value="upload_formation_file">
                            <?php wp_nonce_field('upload_formation_file', 'upload_nonce'); ?>
                            
                            <div class="file-upload-section">
                                <h3 class="file-upload-title">Importer les fichiers</h3>
                                <div class="file-selector">
                                    <label for="formationFiles">Sélect. fichiers</label>
                                    <input type="file" id="formationFiles" name="formation_files[]" multiple>
                                    <div id="fileNames">Aucun fichier choisi</div>
                                    <p>Vous pouvez sélectionner plusieurs fichiers</p>
                                </div>
                                <button type="submit" class="add-documents-btn">Ajouter les documents</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>           
    </div>

    
    <div class="section_files">
        <h3>Documents</h3>
        <div class="documents-grid">
            <?php foreach ($fichiers as $fichier): ?>
                <?php
                    $extension = strtolower(pathinfo($fichier->nom_fichier, PATHINFO_EXTENSION));
                    if (in_array($extension, ['pdf', 'doc', 'docx', 'ppt', 'pptx'])):
                        // Définir le lien de l'icône en fonction de l'extension
                        $base_url = plugins_url('custom-auth-profile') . '/assets/images/';
                        $icon_url = match ($extension) {
                            'pdf'  => $base_url . 'pdf.png', // Corrected icon for PDF
                            'doc', 'docx' => $base_url . 'doc.png',
                            'ppt', 'pptx' => $base_url . 'ppt.png',
                            default => $base_url . 'doc.png', // Default to doc icon if unknown
                        };

                        // Formater la date de téléchargement
                        $upload_date = date_i18n('j F Y', strtotime($fichier->uploaded_at)); // 'j' for day without leading zeros, 'F' for full month name, 'Y' for year.
                        // date_i18n is a WordPress function for localized dates.
                        //$viewer_url = add_query_arg('file_id', $fichier->id, get_permalink(69));

                        $viewer_url = add_query_arg(array(
                            'file_id' => $fichier->id,
                            'nonce' => wp_create_nonce('file_viewer_' . $fichier->id)
                        ), get_permalink(1782));
                ?>
                <div class="file-card">
                    <div class="card-header">
                        <?php
                            $download_url = add_query_arg([
                                'file_id' => $fichier->id,
                                'action' => 'download',
                                'nonce' => wp_create_nonce('download_'.$fichier->id)
                            ], get_permalink());
                        
                            // Récupérer le nom du fichier sans extension
                            $filename = pathinfo($fichier->nom_fichier, PATHINFO_FILENAME);

                            // Tronquer à 30 caractères et ajouter "..." si nécessaire
                            $display_name = mb_strlen($filename) > 30
                                ? mb_substr($filename, 0, 27) . '...'
                                : $filename;
                        ?>
                        <div class="file-title"><?= esc_html($display_name) ?></div>
                        <div class="dropdown-container">
                            <button class="options-button" aria-haspopup="true" aria-expanded="false">&#x22EE;</button>
                            <ul class="dropdown-menu">
                                <li><a href="<?= esc_url($download_url) ?>" class="download-link">Télécharger</a></li>
                                <li><a href="<?= esc_url($viewer_url) ?>" target="_blank">Voir le fichier</a></li>
                                <?php if($user->role === 'Coach' || $user->role === 'Master Coach'): ?>
                                <li>
                                    <a href="#" class="show-for-participants" data-file-id="<?= esc_attr($fichier->id) ?>" data-visible="<?= esc_attr($fichier->visible_participants) ?>">
                                        <?= $fichier->visible_participants ? 'Masquer des participants' : 'Afficher pour les participants' ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="icon-wrapper">
                        <img src="<?= esc_url($icon_url) ?>" alt="<?= esc_attr($extension) ?>" class="word-icon">
                    </div>
                    <div class="file-date">Ajouté le <?= esc_html($upload_date) ?></div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>    

    <div class="section_videos">
        <div class="section-header">
            <h3>Vidéos</h3>
            <div class="navigation-buttons">
                <button class="nav-btn prev-btn" id="prevBtn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.4006 14.7997L7.60059 9.99971L12.4006 5.19971" stroke="CurrentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button class="nav-btn next-btn" id="nextBtn">
                <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.59941 10.7997L6.39941 5.99971L1.59941 1.19971" stroke="CurrentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </button>
            </div>
        </div>
        
        <div class="videos-container" id="videosContainer">
            <?php
            // Récupérer les vidéos pour ce jour (fichiers de type 'video')
            $videos = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}formation_fichiers 
                JOIN {$wpdb->prefix}formation_jours j ON j.id = jour_id
                JOIN {$wpdb->prefix}formations_lms f ON f.id = j.formation_id
                WHERE jour_id = %d AND type = 'video'
                AND visible_participants = 1
                AND statut_approbation = 'approuve'
                ORDER BY uploaded_at DESC",
                $jour_id
            ));            
            
            if (empty($videos)) {
                echo '<p class="no-videos">Aucune vidéo disponible pour ce jour.</p>';
            } else {
                foreach ($videos as $video) {
                    // Récupérer les infos du coach 
                    $coach_info = $wpdb->get_row($wpdb->prepare(
                        "SELECT u.nom, u.prenom, u.role, u.image 
                        FROM {$wpdb->prefix}lms_users u 
                        JOIN {$wpdb->prefix}formations_lms f ON f.user_id = u.id 
                        JOIN {$wpdb->prefix}formation_jours fj ON fj.formation_id = f.id 
                        JOIN {$wpdb->prefix}formation_fichiers ff ON ff.jour_id = fj.id 
                        WHERE ff.id = %d",
                        $video->id
                    ));
                    
                    // Valeurs par défaut si pas de coach info
                    $coach_name = $coach_info->nom . ' ' . $coach_info->prenom;
                    $coach_role = $coach_info ? $coach_info->role : 'Coach';
                    $coach_avatar = $coach_info && $coach_info->image 
                                ? $coach_info->image 
                                : 'https://via.placeholder.com/32/4A90E2/FFFFFF?text='.mb_substr($coach_name, 0, 1);
                    
                    // Nom du fichier sans extension
                    $video_title = pathinfo($video->nom_fichier, PATHINFO_FILENAME);

                    $video_url = add_query_arg([
                        'file_id' => $video->id,
                        'nonce' => wp_create_nonce('file_viewer_'.$video->id)
                    ], get_permalink(1782));

                    ?>
                    <div class="video-card" data-video-id="<?= esc_attr($video->id) ?>">
                        <a href="<?= esc_url($video_url) ?>" class="video-link">
                            <div class="video-player" data-video-url="<?= esc_url($video->chemin_fichier) ?>">
                                <div class="play-button">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M8 5v14l11-7z" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </a>    
                        <div class="video-info">
                            <div class="video-language">Français</div> <!-- À rendre dynamique plus tard -->
                            <h3 class="video-title"><?= esc_html($video_title) ?></h3>                            
                            <div class="separator"></div>                            
                            <div class="coach-info">
                                <img src="<?= esc_url($coach_avatar) ?>" 
                                    alt="<?= esc_attr($coach_name) ?>" 
                                    class="coach-avatar">
                                <div class="coach-details">
                                    <div class="coach-name"><?= esc_html($coach_name) ?></div>
                                    <div class="coach-role"><?= esc_html($coach_role) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

jQuery(document).ready(function($) {
    // Gérer l'ouverture/fermeture du modal
    const modal = $('#addFileModal');
    const btn = $('#addFileButton');
    const span = $('.close');
    
    btn.on('click', function() {
        modal.show();
    });
    
    span.on('click', function() {
        modal.hide();
    });
    
    $(window).on('click', function(event) {
        if (event.target == modal[0]) {
            modal.hide();
        }
    });
    
    // Afficher les noms des fichiers sélectionnés
    $('#formationFiles').on('change', function() {
        const files = $(this)[0].files;
        const fileNames = $('#fileNames');
        
        if (files.length === 0) {
            fileNames.text('Aucun fichier choisi');
        } else if (files.length === 1) {
            fileNames.text(files[0].name);
        } else {
            fileNames.text(`${files.length} fichiers sélectionnés`);
        }
    });
    
    // Gérer l'envoi du formulaire
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.add-documents-btn').prop('disabled', true).text('Envoi en cours...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Fichiers téléchargés avec succès!');
                    modal.hide();
                    location.reload(); // Recharger la page pour afficher les nouveaux fichiers
                } else {
                    alert('Erreur: ' + response.data);
                }
            },
            error: function() {
                alert('Une erreur est survenue lors du téléchargement.');
            },
            complete: function() {
                $('.add-documents-btn').prop('disabled', false).text('Ajouter les documents');
            }
        });
    });
});

var formation_vars = {
    ajaxurl: "<?php echo admin_url('admin-ajax.php'); ?>",
    nonce: "<?php echo wp_create_nonce('formation-nonce'); ?>"
};

jQuery(document).ready(function($) {
    // Gestion du bouton "Afficher pour les participants"
    $(document).on('click', '.show-for-participants', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const fileId = button.data('file-id');
        const isCurrentlyVisible = button.data('visible') == 1;
        const newVisibility = isCurrentlyVisible ? 0 : 1;
        
        // Ajouter un indicateur de chargement
        button.html('<span class="spinner"></span> ' + (isCurrentlyVisible ? 'Masquage...' : 'Affichage...'));
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'toggle_file_visibility',
                file_id: fileId,
                visibility: newVisibility,
                nonce: formation_vars.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Mettre à jour le bouton
                    button.data('visible', newVisibility);
                    button.text(newVisibility ? 'Masquer des participants' : 'Afficher pour les participants');
                    
                    // Afficher un message de confirmation
                    alert(newVisibility 
                        ? 'Le fichier est maintenant visible pour les participants' 
                        : 'Le fichier est maintenant masqué pour les participants');
                } else {
                    alert('Erreur: ' + response.data);
                }
            },
            error: function() {
                alert('Une erreur est survenue');
                button.text(isCurrentlyVisible ? 'Masquer des participants' : 'Afficher pour les participants');
            }
        });
    });
});
</script>