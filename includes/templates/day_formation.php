<?php
$formation_id = intval($_GET['id']);
$user = cap_get_current_user(); 
global $wpdb;

$formation = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}formations_lms WHERE id = %d", 
    $formation_id
));

$query = "SELECT j.*, 
            COUNT(DISTINCT CASE WHEN f.type = 'fichier' THEN f.id END) as pdf_count,
            COUNT(DISTINCT CASE WHEN f.type = 'video' THEN f.id END) as video_count
     FROM {$wpdb->prefix}formation_jours j
     LEFT JOIN {$wpdb->prefix}formation_fichiers f ON j.id = f.jour_id
     WHERE j.formation_id = %d";

if($user->role === 'Participant'):
    $query .= " AND f.visible_participants = 1";
endif;

$query .= " GROUP BY j.id";


$jours = $wpdb->get_results($wpdb->prepare($query, $formation_id));
?>

<div class="dayformation-section">
    <div class="header" style="background-image: url('<?php echo plugins_url('./images/bg_profil.png', __FILE__); ?>');">
        <div>
            <p>Blocs de formations</p>
            <h1>Journée</h1>
        </div>
        <div class="header-btn">
            <a href="<?= esc_url(get_permalink(1759)) ?>" class="back-button">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.0013 15.8327L4.16797 9.99935L10.0013 4.16602" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.8346 10H4.16797" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <div class="filters">
        <input type="text" class="search" placeholder="Recherche ...">
        <button class="search-button">Rechercher</button>
    </div>

    <div class="grid">
        <?php foreach ($jours as $jour): ?>
            <div class="card">
            <div class="top">
                <div class="day_black" style="background-image: url('<?php echo plugins_url('./images/day_bg.png', __FILE__); ?>');">
                    <span>Jour <?= $jour->numero_jour ?></span>
                </div>
                <div class="lms_top p-top">
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
                <h4 class="title_formation">Jour <?= $jour->numero_jour ?></h4>            
            </div>
            <hr class="line" />  
            <div class="middle"> 
                <span>
                    <svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.25 0.5C1.45507 0.5 0 1.95508 0 3.75V14.25C0 16.0449 1.45507 17.5 3.25 17.5H13.7523C15.5472 17.5 17.0023 16.0449 17.0023 14.25V12.6707L21.5434 15.7824C22.3729 16.3508 23.4999 15.7568 23.4999 14.7512V3.24842C23.4999 2.24298 22.3732 1.64898 21.5436 2.21708L17.0023 5.3272V3.75C17.0023 1.95508 15.5472 0.5 13.7523 0.5H3.25ZM17.0023 10.8524V7.1452L21.9999 3.72263V14.2769L17.0023 10.8524ZM15.5023 3.75V14.25C15.5023 15.2165 14.7187 16 13.7523 16H3.25C2.2835 16 1.5 15.2165 1.5 14.25V3.75C1.5 2.7835 2.2835 2 3.25 2H13.7523C14.7188 2 15.5023 2.7835 15.5023 3.75Z" fill="#212121"/>
                    </svg> 
                    <?= $jour->video_count ?> Cours
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
                    <?= $jour->pdf_count ?> Pdf
                </span>
            </div>
            <div class="bottom">
                <a href="<?= home_url("/jour-fichiers?jour_id={$jour->id}") ?>" class="btn_discover">Accéder</a>
            </div>
            </div> 
        <?php endforeach; ?>    
    </div>

</div>