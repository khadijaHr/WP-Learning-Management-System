<?php $user = cap_get_current_user(); ?>
<div class="mon-plugin-profile">
    <header style="background-image: url('<?php echo plugin_dir_url(dirname(__FILE__, 2)) . 'assets/images/bg_profil.png'; ?>');">
        <div style="display: flex; flex-direction: column; align-items: flex-start;">
            <div class="user-info">                
                <?php if ($user->image): ?>
                    <img src="<?php echo esc_url($user->image); ?>" alt="Photo de profil">
                <?php else: ?>
                    <img src="<?php echo plugin_dir_url(dirname(__FILE__, 2)) . 'assets/images/default-avatar.jpg'; ?>" alt="Photo de profil">
                <?php endif; ?>
                <div>
                    <h2><?php echo esc_html($user->prenom). ' '. esc_html($user->nom); ?></h2>
                    <p class="location">
                        <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 0C2.6865 0 0 2.6865 0 6C0 7.30475 0.42775 8.502 1.13875 9.4825C1.1515 9.506 1.1535 9.53225 1.168 9.55475L5.168 15.5548C5.3535 15.833 5.666 16 6 16C6.334 16 6.6465 15.833 6.832 15.5548L10.832 9.55475C10.8467 9.53225 10.8485 9.506 10.8612 9.4825C11.5723 8.502 12 7.30475 12 6C12 2.6865 9.3135 0 6 0ZM6 8C4.8955 8 4 7.1045 4 6C4 4.8955 4.8955 4 6 4C7.1045 4 8 4.8955 8 6C8 7.1045 7.1045 8 6 8Z" fill="#04477A"/>
                        </svg>
                        <?php echo esc_html($user->ville_region).', Maroc'; ?>
                    </p>      
                </div>
            </div>   
            <a class="logout color-blue" href="<?php echo esc_url(home_url('/?action=logout')); ?>" id="custom-logout" >Se déconnecter</a>
        </div>
        <div class="stats">
            <?php if($user->role === 'Coach'):
              echo afficher_nombre_participants_coach_format(); ?>
            <?php endif; ?>
            <?php echo afficher_nombre_formations_coach_format(); ?>
        </div>
    </header>

    <nav>
        <?php
            // $current_page = basename($_SERVER['REQUEST_URI']);
            $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $pages_formations = ['formations', 'formation-details', 'jour-fichiers', 'view-fichier'];
            $is_formations = in_array(basename($current_path), $pages_formations) || 
                 preg_match('/formation|fichier/', $current_path);
            //$is_formations = strpos($current_page, 'formations') !== false;
            $current_pagee = basename($_SERVER['REQUEST_URI']);
            $is_mes_formations = strpos($current_pagee, 'mes-formations') !== false;
            $is_inscrit = strpos($current_pagee, 'liste-des-participants') !== false;
            $is_profile = strpos($current_pagee, 'profile') !== false;
        ?>

        <a href="<?php echo home_url('/formations'); ?>" class="<?php echo $is_formations && !$is_mes_formations ? 'active' : ''; ?>">
            <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 0V11.4706H10.2857V14.1176H11.5714V15H2.14286C1.51714 15 0.917143 14.7176 0.505712 14.2235C0.180003 13.8265 0 13.3147 0 12.7941V2.20588C0 0.988234 0.96 0 2.14286 0H12ZM9.42857 11.4706H2.23714C1.56 11.4706 0.977144 11.9471 0.874286 12.5647C0.86571 12.6353 0.857142 12.7059 0.857142 12.7676V12.7941C0.857142 13.1118 0.959999 13.4118 1.16571 13.65C1.40572 13.95 1.76572 14.1176 2.14286 14.1176H9.42857V13.2353H1.81714V12.3529H9.42857V11.4706ZM9.00001 7.05882H2.99999V7.94118H8.99999L9.00001 7.05882ZM9.42857 2.64706H2.57143V6.17647H9.42857V2.64706ZM3.42857 5.29412H8.57143V3.52941H3.42857V5.29412Z" fill="<?php echo $is_formations && !$is_mes_formations ? '#04477A' : '#92ADC2'; ?>"/>
            </svg>      
            <span>Toutes les formations</span>
        </a>
        
        <?php if($user->role === 'Coach'): ?>
            <a href="<?php echo home_url('/liste-des-participants'); ?>" class="<?php echo $is_inscrit ? 'active' : ''; ?>">           
                <svg width="25" height="18" viewBox="0 0 25 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24.565 14.4131V16.9984C24.565 17.4741 24.1789 17.8602 23.7032 17.8602H22.8415C22.3658 17.8602 21.9797 17.4741 21.9797 16.9984V14.4131C21.9797 13.9374 22.3658 13.5514 22.8415 13.5514V7.05446L13.1914 6.28232C13.0354 6.50379 12.7906 6.65719 12.5002 6.65719C12.0245 6.65719 11.6384 6.27111 11.6384 5.79541C11.6384 5.31972 12.0245 4.93364 12.5002 4.93364C12.8415 4.93364 13.131 5.13616 13.2706 5.42485L22.9104 6.19614C23.3551 6.23147 23.7032 6.60893 23.7032 7.05533V13.5514C24.1789 13.5514 24.565 13.9374 24.565 14.4131ZM12.5002 12.4526C12.0288 12.4526 11.5574 12.3569 11.1188 12.1648L5.60604 9.75267V11.6718C5.60604 12.6508 6.1593 13.5462 7.03486 13.984C8.75582 14.8449 10.6276 15.2749 12.5002 15.2749C14.3728 15.2749 16.2446 14.8449 17.9647 13.9848C18.8411 13.5471 19.3944 12.6517 19.3944 11.6727V9.75353L13.8816 12.1656C13.443 12.3569 12.9716 12.4526 12.5002 12.4526ZM24.484 5.00603L13.5361 0.216305C13.206 0.071527 12.8535 0 12.5002 0C12.1469 0 11.7944 0.0723887 11.4644 0.217166L0.516417 5.00603C-0.172139 5.30765 -0.172139 6.28404 0.516417 6.5848L11.4635 11.3754C12.1228 11.6632 12.8777 11.6632 13.5361 11.3754L21.6505 7.82403L13.5171 7.17339C13.2267 7.39659 12.8742 7.51896 12.5002 7.51896C11.5497 7.51896 10.7767 6.74595 10.7767 5.79541C10.7767 4.84488 11.5497 4.07187 12.5002 4.07187C12.9759 4.07187 13.4154 4.26491 13.7334 4.59669L22.9794 5.33609C23.705 5.39383 24.2936 5.91003 24.4883 6.58135C25.1717 6.27887 25.1708 5.30679 24.484 5.00603Z" fill="<?php echo $is_inscrit ? '#04477A' : '#92ADC2'; ?>"/>
                </svg>
                <span>Inscrit</span>
            </a>   
        <?php endif; ?>         
    
        <a href="<?php echo $user->role === 'Coach' ? home_url('/profile-coach') : home_url('/profile'); ?>" class="<?php echo $is_profile ? 'active' : ''; ?>">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.3854 8.59375L7.5 12.0007L3.61461 8.59375C1.45026 9.90877 0 12.2816 0 15.0005H15C15 12.2816 13.5497 9.90877 11.3854 8.59375Z" fill="<?php echo $is_profile ? '#04477A' : '#92ADC2'; ?>"/>
                <path d="M7.50051 8.99927C8.90183 8.99927 10.1567 8.41806 11.0239 7.49939C11.7759 6.70295 12.2374 5.65379 12.2374 4.49963C12.2374 2.01471 10.1164 0 7.50051 0C4.88459 0 2.76367 2.01471 2.76367 4.49963C2.76367 5.65379 3.22512 6.70295 3.97709 7.49939C4.84433 8.41806 6.0992 8.99927 7.50051 8.99927Z" fill="<?php echo $is_profile ? '#04477A' : '#92ADC2'; ?>"/>
            </svg> 
            <span>Profile</span>
        </a>
    </nav>

   