<?php
    $stats = get_formation_stats();
    $nouvelles_inscriptions = get_nouvelles_inscriptions();
    $user = cap_get_current_user(); 
    global $wpdb;
    $formations = $wpdb->get_results("
        SELECT f.*, 
            COUNT(DISTINCT j.id) as jours_count,
            COUNT(DISTINCT CASE WHEN fich.type = 'fichier' THEN fich.id END) as pdf_count,
            COUNT(DISTINCT CASE WHEN fich.type = 'video' THEN fich.id END) as video_count
        FROM {$wpdb->prefix}formations_lms f
        LEFT JOIN {$wpdb->prefix}formation_jours j ON f.id = j.formation_id
        LEFT JOIN {$wpdb->prefix}formation_fichiers fich ON j.id = fich.jour_id
        GROUP BY f.id
        Limit 3
    ");
?>
    <div class="container">
        <div class="dashboard">
            <!-- Cartes de statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <div class="stat-label">Total Participants</div>
                            <div class="stat-value"><?= str_pad($stats['total_participants'], 2, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="stat-icon purple">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M31.6666 35V31.6667C31.6666 29.8986 30.9642 28.2029 29.714 26.9526C28.4637 25.7024 26.768 25 24.9999 25H14.9999C13.2318 25 11.5361 25.7024 10.2859 26.9526C9.03563 28.2029 8.33325 29.8986 8.33325 31.6667V35" stroke="#975BD7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19.9999 18.3333C23.6818 18.3333 26.6666 15.3486 26.6666 11.6667C26.6666 7.98477 23.6818 5 19.9999 5C16.318 5 13.3333 7.98477 13.3333 11.6667C13.3333 15.3486 16.318 18.3333 19.9999 18.3333Z" stroke="#975BD7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <div class="stat-label">Total Coachs</div>
                            <div class="stat-value"><?= str_pad($stats['total_coachs'], 2, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="stat-icon yellow">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 20H20.0167" stroke="#FFCC33" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M26.6668 9.99992V6.66659C26.6668 5.78253 26.3156 4.93468 25.6905 4.30956C25.0654 3.68444 24.2176 3.33325 23.3335 3.33325H16.6668C15.7828 3.33325 14.9349 3.68444 14.3098 4.30956C13.6847 4.93468 13.3335 5.78253 13.3335 6.66659V9.99992" stroke="#FFCC33" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M36.6668 21.6667C31.7215 24.9317 25.9261 26.6723 20.0002 26.6723C14.0742 26.6723 8.27884 24.9317 3.3335 21.6667" stroke="#FFCC33" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M33.3335 10H6.66683C4.82588 10 3.3335 11.4924 3.3335 13.3333V30C3.3335 31.841 4.82588 33.3333 6.66683 33.3333H33.3335C35.1744 33.3333 36.6668 31.841 36.6668 30V13.3333C36.6668 11.4924 35.1744 10 33.3335 10Z" stroke="#FFCC33" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <div class="stat-label">Total formations</div>
                            <div class="stat-value"><?= str_pad($stats['total_formations'], 2, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="stat-icon red">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 35.0001V11.6667" stroke="#EE4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M26.6665 20.0001L29.9998 23.3334L36.6665 16.6667" stroke="#EE4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M36.6668 10V6.66667C36.6668 6.22464 36.4912 5.80072 36.1787 5.48816C35.8661 5.17559 35.4422 5 35.0002 5H26.6668C24.8987 5 23.203 5.70238 21.9528 6.95262C20.7025 8.20286 20.0002 9.89856 20.0002 11.6667C20.0002 9.89856 19.2978 8.20286 18.0475 6.95262C16.7973 5.70238 15.1016 5 13.3335 5H5.00016C4.55814 5 4.13421 5.17559 3.82165 5.48816C3.50909 5.80072 3.3335 6.22464 3.3335 6.66667V28.3333C3.3335 28.7754 3.50909 29.1993 3.82165 29.5118C4.13421 29.8244 4.55814 30 5.00016 30H15.0002C16.3262 30 17.598 30.5268 18.5357 31.4645C19.4734 32.4021 20.0002 33.6739 20.0002 35C20.0002 33.6739 20.5269 32.4021 21.4646 31.4645C22.4023 30.5268 23.6741 30 25.0002 30H35.0002C35.4422 30 35.8661 29.8244 36.1787 29.5118C36.4912 29.1993 36.6668 28.7754 36.6668 28.3333V26.1667" stroke="#EE4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <div class="stat-label">Total régions</div>
                            <div class="stat-value"><?= str_pad($stats['total_regions'], 2, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="stat-icon green">  
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.51 9.25511C23.9727 9.4863 24.4828 9.60666 25 9.60666C25.5172 9.60666 26.0273 9.4863 26.49 9.25511L32.5883 6.20511C32.8426 6.07804 33.1252 6.01812 33.4092 6.03106C33.6931 6.04399 33.9691 6.12934 34.2108 6.27899C34.4525 6.42865 34.6518 6.63763 34.79 6.88608C34.9281 7.13452 35.0004 7.41417 35 7.69844V28.9718C34.9998 29.2812 34.9135 29.5845 34.7508 29.8477C34.588 30.1108 34.3551 30.3235 34.0783 30.4618L26.49 34.2568C26.0273 34.488 25.5172 34.6083 25 34.6083C24.4828 34.6083 23.9727 34.488 23.51 34.2568L16.49 30.7468C16.0273 30.5156 15.5172 30.3952 15 30.3952C14.4828 30.3952 13.9727 30.5156 13.51 30.7468L7.41167 33.7968C7.15726 33.9239 6.87454 33.9838 6.59043 33.9708C6.30632 33.9578 6.03026 33.8723 5.78853 33.7225C5.5468 33.5726 5.34744 33.3634 5.20942 33.1147C5.07139 32.866 4.9993 32.5862 5.00001 32.3018V11.0301C5.00017 10.7207 5.08648 10.4174 5.24926 10.1542C5.41204 9.89107 5.64487 9.67843 5.92167 9.54011L13.51 5.74511C13.9727 5.51391 14.4828 5.39355 15 5.39355C15.5172 5.39355 16.0273 5.51391 16.49 5.74511L23.51 9.25511Z" stroke="#58DAA5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M25 9.60669V34.6067" stroke="#58DAA5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 5.39331V30.3933" stroke="#58DAA5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section principale avec graphique et calendrier -->
            <div class="main-content">
                <!-- Graphique participants par région -->
                <div class="chart-section">
                    <div class="section-header">
                        <h3>Participants par région</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-dot blue"></span>
                            <span>Filles</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot orange"></span>
                            <span>Garçons</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot green"></span>
                            <span>Total participant</span>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart">
                            <div class="chart-bars">
                                <div class="city-group">
                                    <div class="bars">
                                        <div class="bar blue" style="height: 80px;"></div>
                                        <div class="bar orange" style="height: 40px;"></div>
                                        <div class="bar green" style="height: 120px;"></div>
                                    </div>
                                    <div class="city-label">Rabat</div>
                                </div>
                                
                                <div class="city-group">
                                    <div class="bars">
                                        <div class="bar blue" style="height: 60px;"></div>
                                        <div class="bar orange" style="height: 80px;"></div>
                                        <div class="bar green" style="height: 100px;"></div>
                                    </div>
                                    <div class="city-label">Tanger</div>
                                </div>
                                
                                <div class="city-group highlighted">
                                    <div class="bars">
                                        <div class="bar blue" style="height: 120px;">
                                            <div class="bar-label">165</div>
                                        </div>
                                        <div class="bar orange" style="height: 60px;"></div>
                                        <div class="bar green" style="height: 180px;"></div>
                                    </div>
                                    <div class="city-label">Casablanca</div>
                                </div>
                                
                                <div class="city-group">
                                    <div class="bars">
                                        <div class="bar blue" style="height: 40px;"></div>
                                        <div class="bar orange" style="height: 100px;"></div>
                                        <div class="bar green" style="height: 140px;"></div>
                                    </div>
                                    <div class="city-label">Salé</div>
                                </div>
                                
                                <div class="city-group">
                                    <div class="bars">
                                        <div class="bar blue" style="height: 60px;"></div>
                                        <div class="bar orange" style="height: 120px;"></div>
                                        <div class="bar green" style="height: 200px;"></div>
                                    </div>
                                    <div class="city-label">Beni Mellal</div>
                                </div>
                                
                                <div class="city-group">
                                    <div class="bars">
                                        <div class="bar blue" style="height: 80px;"></div>
                                        <div class="bar orange" style="height: 140px;"></div>
                                        <div class="bar green" style="height: 160px;"></div>
                                    </div>
                                    <div class="city-label">Meknès</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendrier -->
                <div class="calendar-section">
                    <div class="section-header">
                        <h3>Calendrier</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    
                    <div class="calendar">
                        <div class="calendar-header">
                            <button class="nav-btn" id="prevMonth">‹</button>
                            <span class="month-year" id="monthYear"></span>
                            <button class="nav-btn" id="nextMonth">›</button>
                        </div>
                        
                        <div class="calendar-grid">
                            <div class="day-header">SUN</div>
                            <div class="day-header">MON</div>
                            <div class="day-header">TUE</div>
                            <div class="day-header">WED</div>
                            <div class="day-header">THU</div>
                            <div class="day-header">FRI</div>
                            <div class="day-header">SAT</div>
                            <div id="daysContainer" class="days-container"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tableaux des listes -->
            <div class="tables-section">
                <!-- Liste des Participants -->
                <div class="table-container">
                    <div class="table-headerr">
                        <h3>Liste des Participants</h3>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('participants'))); ?>" class="view-all">Voir tout</a>
                    </div>
                    
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Étudiant</th>
                                    <th>Région</th>
                                    <th>Tél</th>
                                    <th>Mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                global $wpdb;
                                $participants = $wpdb->get_results(
                                    "SELECT * FROM {$wpdb->prefix}lms_users 
                                    WHERE role = 'Participant' 
                                    ORDER BY created_at DESC 
                                    LIMIT 4"
                                );

                                foreach ($participants as $participant) {
                                    $initial = mb_substr($participant->prenom, 0, 1, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td><a href="#" class="id-link">ID<?php echo $participant->id; ?></a></td>
                                        <td>
                                            <div class="user-info">
                                                <?php if (!empty($participant->image)): ?>
                                                    <div class="avatar">
                                                        <img src="<?php echo esc_url($participant->image); ?>" alt="<?php echo esc_attr($participant->prenom . ' ' . $participant->nom); ?>" class="small-avatar">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="avatar"><?php echo $initial; ?></div>
                                                <?php endif; ?>
                                                <span><?php echo $participant->prenom . ' ' . $participant->nom; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $participant->ville_region; ?></td>
                                        <td><?php echo $participant->telephone; ?></td>
                                        <td><?php echo $participant->email; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Liste des Coachs -->
                <div class="table-container">
                    <div class="table-headerr">
                        <h3>Liste des Coachs</h3>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('coachs'))); ?>" class="view-all">Voir tout</a>
                    </div>
                    
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Formateur</th>
                                    <th>Région</th>
                                    <th>Tél</th>
                                    <th>Mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $coaches = $wpdb->get_results(
                                    "SELECT * FROM {$wpdb->prefix}lms_users 
                                    WHERE role = 'Coach' 
                                    ORDER BY created_at DESC 
                                    LIMIT 4"
                                );

                                foreach ($coaches as $coach) {
                                    $initial = mb_substr($coach->prenom, 0, 1, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td><a href="#" class="id-link">ID<?php echo $coach->id; ?></a></td>
                                        <td>
                                            <div class="user-info">
                                                <?php if (!empty($coach->image)): ?>
                                                    <div class="avatar">
                                                        <img src="<?php echo esc_url($coach->image); ?>" alt="<?php echo esc_attr($participant->prenom . ' ' . $participant->nom); ?>" class="small-avatar">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="avatar"><?php echo $initial; ?></div>
                                                <?php endif; ?>
                                                <span><?php echo $coach->prenom . ' ' . $coach->nom; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $coach->ville_region; ?></td>
                                        <td><?php echo $coach->telephone; ?></td>
                                        <td><?php echo $coach->email; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

             <!-- Section supérieure -->
            <div class="top-section">
                <!-- Nouvelles inscriptions -->
                <div class="inscriptions-section">
                    <div class="table-headerr">
                        <h3>Nouvelles inscriptions</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    <div class="inscription-list">
                    <?php foreach ($nouvelles_inscriptions as $item): ?>
                        <div class="inscription-item">
                            <div class="item-info">
                                <span class="item-label"><?= esc_html($item['label']) ?></span>
                                <div class="avatars-group">
                                    <?php foreach ($item['avatars'] as $letter): ?>
                                        <div class="avatar"><?= esc_html($letter) ?></div>
                                    <?php endforeach; ?>
                                    <?php if ($item['extra_count'] > 0): ?>
                                        <div class="avatar-count">+<?= $item['extra_count'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-stats">
                                <span class="percentage"><?= $item['percentage'] ?>%</span>
                                <span class="location"><?= esc_html($item['location']) ?></span>
                                <a href="#" class="view-all-link">Voir tout</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    
            
                </div>

                <!-- Coachs par région -->
                <div class="coachs-region-section">
                    <div class="table-headerr">
                        <h3>Coachs par région</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    
                    <?php
                    $coachs_by_region = get_coachs_by_region();
                    
                    // Définir l'ordre des couleurs en fonction du pourcentage décroissant
                    $colors = ['purple', 'blue', 'green', 'orange'];
                    
                    // Trouver le pourcentage maximum pour la mise à l'échelle
                    $max_percentage = !empty($coachs_by_region) ? max(array_column($coachs_by_region, 'percentage')) : 100;
                    $base_size = 120; // Taille de base pour le pourcentage maximum
                    $min_size = 40;   // Taille minimale
                    ?>
                    <div class="chart-wrapper">
                        <!-- Graphique à bulles -->
                        <div class="bubble-chart">
                            <?php foreach(array_slice($coachs_by_region, 0, 4) as $index => $region): 
                                // Calcul de la taille proportionnelle
                                $size = $max_percentage > 0 
                                    ? max($min_size, ($region->percentage / $max_percentage) * $base_size)
                                    : $min_size;
                                
                                // Positions prédéfinites
                                $positions = [
                                    'top: 6%; left: 20%;',
                                    'top: -6%; right: 30%;',
                                    'bottom: 12%; left: 15%;',
                                    'bottom: 30%; right: 25%;'
                                ];
                            ?>
                                <div class="bubble" 
                                    style="width: <?php echo esc_attr($size); ?>px; 
                                            height: <?php echo esc_attr($size); ?>px;
                                            background: var(--<?php echo esc_attr($colors[$index] ?? 'gray'); ?>);
                                            <?php echo esc_attr($positions[$index] ?? ''); ?>">
                                    <span class="bubble-text"><?php echo number_format($region->percentage, 1); ?>%</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Légende -->
                        <div class="chart-legend-cercle">
                            <?php foreach(array_slice($coachs_by_region, 0, 4) as $index => $region): ?>
                                <div class="legend-item" style="margin-bottom: 10px;">
                                    <span class="legend-dot" style="background: var(--<?php echo esc_attr($colors[$index] ?? 'gray'); ?>);"></span>
                                    <span class="legend-label"><?php echo esc_html(ucwords(str_replace('_', ' ', $region->region))); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                   
                </div>
                
            </div>
            <div style="padding: 30px 0">
                <div class="table-headerr">
                    <h3>Blocs de formations</h3>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('formations'))); ?>" class="view-all">Voir tout</a>
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

        </div>
    </div>
</div>
