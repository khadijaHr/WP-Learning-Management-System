<?php
$user = cap_get_current_user();

global $wpdb;
$formations = $wpdb->get_results("
    SELECT f.*, 
           COUNT(DISTINCT j.id) as jours_count,
           COUNT(DISTINCT CASE WHEN fich.type = 'fichier' THEN fich.id END) as pdf_count,
           COUNT(DISTINCT CASE WHEN fich.type = 'video' THEN fich.id END) as video_count
    FROM {$wpdb->prefix}formations_lms f
    LEFT JOIN {$wpdb->prefix}formation_inscriptions i ON f.id = i.formation_id
    LEFT JOIN {$wpdb->prefix}formation_jours j ON i.formation_id = j.formation_id
    LEFT JOIN {$wpdb->prefix}formation_fichiers fich ON j.id = fich.jour_id
    WHERE i.user_id = $user->id
    GROUP BY f.id
");

?>

<div class="formation-section">
    <div class="header_mf">
        <h1>Mes Formations</h1>       
    </div>

    <div class="filters">
        <input type="text" class="search" placeholder="Recherche ...">
        <button class="search-button">Rechercher</button>
        <div>
            <?php if($user->role === 'Coach'): ?>
                <button class="btn btn-secondary" id="ajouter-formation-btn">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" fill="#04477A"/>
                        <path d="M9 5.66667V12.3333M12.3333 9H5.66667M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" stroke="white" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    Ajouter une formation
                </button>
                
                <!-- Popup pour créer/modifier une formation -->
                <div id="formation-popup" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 class="modal-title">Ajouter une Formation</h2>
                        
                        <form id="formation-form" class="formation-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="formation_id" id="formation_id" value="0">
                            
                            <div class="form-group">
                                <label for="titre">Titre de la formation <span>*</span></label>
                                <input type="text" id="titre" name="titre" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="form-group">
                                    <label for="duree">Nombre de jours <span>*</span></label>
                                    <input type="number" id="duree" name="duree" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="bloc">N° Bloc *</label>
                                    <input type="number" id="bloc" name="bloc" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="langue">Langue <span>*</span></label>
                                    <select id="langue" name="langue" class="form-control" required>
                                        <option value="">Sélectionnez une langue</option>
                                        <option value="Français">Français</option>
                                        <option value="Arabe">Arabe</option>
                                        <option value="Anglais">Anglais</option>
                                    </select>
                                </div>
                            </div>                           
                            
                            <!-- Section pour les jours de formation -->
                            <div id="jours-section" class="jours-section">
                                <h3>Jours de Formation</h3>
                                <div id="jours-container">
                                    <!-- Les jours seront ajoutés dynamiquement ici -->
                                </div>
                                <button type="button" id="ajouter-jour-btn" class="btn btn-secondary">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" fill="#04477A"/>
                                        <path d="M9 5.66667V12.3333M12.3333 9H5.66667M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" stroke="#F7F9FF" stroke-width="1.3" stroke-linecap="round"/>
                                    </svg>
                                    <span>Ajouter un Jour</span>                                    
                                </button>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-cancel annuler-formation">Annuler</button>
                                <button type="submit" name="submit_formation" class="btn btn-add">Enregistrer</button>
                            </div>

                            <?php wp_nonce_field('save_formation_nonce', 'formation_nonce'); ?>
                        </form>
                    </div>
                </div>
            <?php endif; ?>            
        </div>        
    </div>

    <div class="grid">
        <?php foreach ($formations as $formation): ?>
            <div class="card">
            <div class="top">
                <?php $base_url = plugins_url('custom-auth-profile') . '/assets/images/'; ?>            
                <div class="bloc_img" style="background-image: url('<?php echo $base_url . 'bg_formation.png'; ?>');">
                    <span>Bloc de formation <?= esc_html($formation->bloc) ?></span>
                </div>   
                <div class="lms_top">
                    <span class="lang">
                        <?php
                            $langue = strtolower($formation->langue); 
                            switch ($langue) {
                                case 'fr':
                                    echo esc_html('Français');
                                    break;
                                case 'ar':
                                    echo esc_html('Arabe');
                                    break;
                                case 'en':
                                    echo esc_html('Anglais');
                                    break;
                                default:
                                    echo esc_html($formation->langue); 
                            }
                        ?>
                    </span>
                </div>
                <h4 class="title_formation">Bloc <?= esc_html($formation->bloc) ?></h4>            
            </div>
            <hr class="line" />  
            <div class="middle">                      
                <span>
                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_680_25458)">
                            <path d="M9.49966 0C4.26144 0 0 4.26167 0 9.49989C0 14.7383 4.26144 19 9.49966 19C14.7379 19 18.9995 14.7383 18.9995 9.49989C18.9995 4.26167 14.7376 0 9.49966 0ZM9.49966 17.6265C5.01892 17.6265 1.37325 13.9809 1.37325 9.49989C1.37325 5.01892 5.01869 1.37348 9.49966 1.37348C13.9806 1.37348 17.6261 5.01892 17.6261 9.49989C17.6261 13.9809 13.9806 17.6265 9.49966 17.6265Z" fill="black"/>
                            <path d="M12.248 10.3746C12.2444 10.3746 12.2412 10.3746 12.2375 10.3746L10.186 10.4053V5.03635C10.186 4.65704 9.87855 4.34961 9.49924 4.34961C9.11993 4.34961 8.8125 4.65704 8.8125 5.03635V11.1025C8.8125 11.1039 8.81296 11.1053 8.81296 11.1067C8.81296 11.109 8.8125 11.111 8.8125 11.1128C8.81296 11.1408 8.81822 11.1671 8.82189 11.1939C8.82417 11.2106 8.8244 11.2278 8.82761 11.2442C8.83379 11.2738 8.84409 11.3012 8.85393 11.3289C8.85897 11.3434 8.86217 11.3585 8.86813 11.3722C8.88003 11.4004 8.89605 11.426 8.91139 11.4523C8.91849 11.464 8.92375 11.4768 8.93131 11.4883C8.9487 11.5137 8.96953 11.5363 8.99014 11.5594C8.99884 11.5691 9.00593 11.5801 9.01509 11.5894C9.03729 11.6114 9.06224 11.63 9.0872 11.649C9.09727 11.6565 9.1062 11.6657 9.1165 11.6728C9.14351 11.6911 9.17304 11.7055 9.20257 11.7197C9.21287 11.7245 9.22202 11.7314 9.23255 11.7357C9.26689 11.7501 9.30352 11.7602 9.3406 11.7691C9.34747 11.7707 9.35388 11.7739 9.36098 11.7753C9.40538 11.7842 9.4514 11.7893 9.49855 11.7893C9.50199 11.7893 9.50565 11.7893 9.50908 11.7893L12.2579 11.7481C12.6369 11.7424 12.9398 11.4301 12.9343 11.051C12.929 10.6754 12.6225 10.3746 12.248 10.3746Z" fill="black"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_680_25458">
                                <rect width="19" height="19" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                    <?= $formation->jours_count ?> journées
                </span>
                <span>
                    <svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.25 0.5C1.45507 0.5 0 1.95508 0 3.75V14.25C0 16.0449 1.45507 17.5 3.25 17.5H13.7523C15.5472 17.5 17.0023 16.0449 17.0023 14.25V12.6707L21.5434 15.7824C22.3729 16.3508 23.4999 15.7568 23.4999 14.7512V3.24842C23.4999 2.24298 22.3732 1.64898 21.5436 2.21708L17.0023 5.3272V3.75C17.0023 1.95508 15.5472 0.5 13.7523 0.5H3.25ZM17.0023 10.8524V7.1452L21.9999 3.72263V14.2769L17.0023 10.8524ZM15.5023 3.75V14.25C15.5023 15.2165 14.7187 16 13.7523 16H3.25C2.2835 16 1.5 15.2165 1.5 14.25V3.75C1.5 2.7835 2.2835 2 3.25 2H13.7523C14.7188 2 15.5023 2.7835 15.5023 3.75Z" fill="#212121"/>
                    </svg> 
                    <?= $formation->video_count ?> Cours
                </span>
                <span>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_680_25468)">
                            <path d="M16.8961 12.0029C16.4988 12.0029 16.1789 12.3229 16.1789 12.7201V16.1795H1.82109V12.7201C1.82109 12.3229 1.50117 12.0029 1.10391 12.0029C0.706641 12.0029 0.386719 12.3229 0.386719 12.7201V16.8967C0.386719 17.2939 0.706641 17.6139 1.10391 17.6139H16.8961C17.2934 17.6139 17.6133 17.2939 17.6133 16.8967V12.7201C17.6133 12.3229 17.2934 12.0029 16.8961 12.0029Z" fill="black"/>
                            <path d="M8.47278 12.8535C8.87708 13.2613 9.37278 13.0363 9.52395 12.8535L13.6478 8.4168C13.9185 8.125 13.9009 7.67148 13.6091 7.40078C13.3173 7.13008 12.8638 7.14766 12.5966 7.43945L9.71731 10.5367V1.21328C9.71731 0.816016 9.39739 0.496094 9.00012 0.496094C8.60286 0.496094 8.28294 0.816016 8.28294 1.21328V10.5402L5.40364 7.44297C5.13294 7.15117 4.67942 7.13711 4.38762 7.4043C4.09583 7.675 4.08177 8.12852 4.34895 8.42031L8.47278 12.8535Z" fill="black"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_680_25468">
                            <rect width="18" height="18" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                    <?= $formation->pdf_count ?> Pdf
                </span>
            </div>
            <div class="bottom">
                <a href="<?= home_url("/formation-details?id={$formation->id}") ?>" class="btn_discover">Découvrir</a>
            </div>
            </div>              
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>

<script>
    jQuery(document).ready(function($) {
    // Ouvrir le popup pour ajouter une formation
    $('#ajouter-formation-btn').click(function() {
        $('#formation_id').val(0);
        $('#formation-form')[0].reset();
        $('#jours-container').empty();
        $('#formation-popup').show();
    });
    
    // Fermer le popup
    $('.close, .annuler-formation').click(function() {
        $('#formation-popup').hide();
    });
    
    // Ajouter un jour de formation
    $('#ajouter-jour-btn').click(function() {
        const jourIndex = $('#jours-container .jour-item').length + 1;
        const jourHTML = `
            <div class="jour-item" data-jour-index="${jourIndex}">
                <h4>Jour ${jourIndex}</h4>
                <div class="form-group">
                    <label for="jour_titre_${jourIndex}">Titre <span>*</span></label>
                    <input type="text" id="jour_titre_${jourIndex}" name="jours[${jourIndex}][titre]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jour_description_${jourIndex}">Description</label>
                    <textarea id="jour_description_${jourIndex}" name="jours[${jourIndex}][description]" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group fichiers-container">
                    <label>Importer les fichiers <span>*</span></label>
                    <input type="file" name="jours[${jourIndex}][fichiers][]" multiple class="form-control">
                    <small>Vous pouvez sélectionner plusieurs fichiers</small>
                </div>
                <div class="jour-actions">
                    <button type="button" class="btn-remove-jour btn btn-danger">Supprimer ce jour</button>
                </div>
            </div>
        `;
        $('#jours-container').append(jourHTML);
    });
    
    // Supprimer un jour (délégué car les éléments sont ajoutés dynamiquement)
    $('#jours-container').on('click', '.btn-remove-jour', function() {
        $(this).closest('.jour-item').remove();
        // Recalculer les numéros de jour
        $('#jours-container .jour-item').each(function(index) {
            $(this).find('h4').text('Jour ' + (index + 1));
            $(this).attr('data-jour-index', index + 1);
        });
    });
    
    // Gérer la soumission du formulaire
    $('#formation-form').submit(function(e) {
        e.preventDefault();
        
        // Récupérer les données du formulaire
        const formData = new FormData(this);
        
        // Ajouter l'action WordPress
        formData.append('formation_nonce', $('[name="formation_nonce"]').val());
        formData.append('action', 'save_user_formation');        
        
        // Envoyer via AJAX
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Réponse complète:', response); // Ajoutez cette ligne
                if (response.success) {
                    alert('Formation enregistrée avec succès!');
                    $('#formation-popup').hide();
                    location.reload();
                } else {
                    alert('Erreur: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', xhr.responseText); // Log détaillé
                alert('Erreur technique: ' + error);
            }
        });
    });        
});
</script>