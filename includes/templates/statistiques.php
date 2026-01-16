<?php   
    $user = cap_get_current_user(); 
    $stats = get_formation_stats();
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
                        <h3>Région Rabat Salé Kénitra</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    <?php
                        global $wpdb;

                        $total_participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra' AND role = 'participant'"
                        );

                        $total_formations = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}formations_lms"
                        );
                        
                        $total_telechargement = $wpdb->get_var(
                            "SELECT COUNT(DISTINCT fi.id) from {$wpdb->prefix}formation_inscriptions fi left join {$wpdb->prefix}lms_users u ON u.id = fi.user_id where u.region = 'rabat_sale_kenitra'"
                        );
                    ?>
                    <div class="dashboard-grid">
                        <div class="kpi-card coaches">
                            <h2>Total Coachs</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value">06</span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3092" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M35.3094 34.7733H35.3247" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3813 25.6658V22.6298C41.3813 21.8246 41.0615 21.0524 40.4921 20.4831C39.9228 19.9137 39.1506 19.5939 38.3454 19.5939H32.2735C31.4683 19.5939 30.6961 19.9137 30.1268 20.4831C29.5574 21.0524 29.2375 21.8246 29.2375 22.6298V25.6658" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 36.2909C45.9853 39.2646 40.707 40.8498 35.3097 40.8498C29.9125 40.8498 24.6341 39.2646 20.13 36.2909" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M47.4535 25.6653H23.1659C21.4892 25.6653 20.13 27.0245 20.13 28.7012V43.881C20.13 45.5577 21.4892 46.9169 23.1659 46.9169H47.4535C49.1302 46.9169 50.4894 45.5577 50.4894 43.881V28.7012C50.4894 27.0245 49.1302 25.6653 47.4535 25.6653Z" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card participants">
                            <h2>Total Participants</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_participants, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M45.9353 48.4349V45.3989C45.9353 43.7886 45.2956 42.2441 44.1569 41.1054C43.0182 39.9667 41.4738 39.327 39.8634 39.327H30.7556C29.1452 39.327 27.6008 39.9667 26.4621 41.1054C25.3234 42.2441 24.6837 43.7886 24.6837 45.3989V48.4349" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M35.3096 33.2553C38.663 33.2553 41.3814 30.5368 41.3814 27.1834C41.3814 23.83 38.663 21.1115 35.3096 21.1115C31.9561 21.1115 29.2377 23.83 29.2377 27.1834C29.2377 30.5368 31.9561 33.2553 35.3096 33.2553Z" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card formations">
                            <h2>Total formations</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_formations, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="71" viewBox="0 0 70 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="35.4696" r="34.6098" fill="white"/>
                                        <path d="M35.3093 49.1316V27.88" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3812 35.4697L44.4171 38.5056L50.489 32.4337" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 26.362V23.3261C50.4894 22.9235 50.3295 22.5374 50.0448 22.2527C49.7601 21.968 49.374 21.8081 48.9714 21.8081H41.3816C39.7712 21.8081 38.2268 22.4478 37.0881 23.5865C35.9494 24.7252 35.3097 26.2696 35.3097 27.88C35.3097 26.2696 34.67 24.7252 33.5313 23.5865C32.3926 22.4478 30.8481 21.8081 29.2378 21.8081H21.6479C21.2453 21.8081 20.8592 21.968 20.5745 22.2527C20.2899 22.5374 20.1299 22.9235 20.1299 23.3261V43.0597C20.1299 43.4623 20.2899 43.8484 20.5745 44.1331C20.8592 44.4178 21.2453 44.5777 21.6479 44.5777H30.7557C31.9635 44.5777 33.1218 45.0575 33.9759 45.9115C34.8299 46.7655 35.3097 47.9238 35.3097 49.1316C35.3097 47.9238 35.7895 46.7655 36.6435 45.9115C37.4975 45.0575 38.6558 44.5777 39.8636 44.5777H48.9714C49.374 44.5777 49.7601 44.4178 50.0448 44.1331C50.3295 43.8484 50.4894 43.4623 50.4894 43.0597V41.0864" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card telechargement">
                            <h2>Téléchargement</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_telechargement, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <g clip-path="url(#clip0_726_1436)"><path d="M49.0988 39.6699C48.4146 39.6699 47.8637 40.2209 47.8637 40.9051V46.8629H23.1363V40.9051C23.1363 40.2209 22.5854 39.6699 21.9012 39.6699C21.217 39.6699 20.666 40.2209 20.666 40.9051V48.098C20.666 48.7822 21.217 49.3332 21.9012 49.3332H49.0988C49.783 49.3332 50.334 48.7822 50.334 48.098V40.9051C50.334 40.2209 49.783 39.6699 49.0988 39.6699Z" fill="#5B74D7"/>
                                        <path d="M34.592 41.1365C35.2883 41.8388 36.142 41.4513 36.4024 41.1365L43.5045 33.4955C43.9708 32.9929 43.9405 32.2119 43.4379 31.7457C42.9354 31.2794 42.1543 31.3097 41.6942 31.8123L36.7354 37.1464V21.0894C36.7354 20.4052 36.1844 19.8542 35.5002 19.8542C34.8161 19.8542 34.2651 20.4052 34.2651 21.0894V37.1525L29.3063 31.8183C28.8401 31.3158 28.059 31.2916 27.5565 31.7517C27.054 32.2179 27.0297 32.999 27.4899 33.5015L34.592 41.1365V41.1365Z" fill="#5B74D7"/>
                                        </g><defs><clipPath id="clip0_726_1436"><rect width="31" height="31" fill="white" transform="translate(20 19)"/>
                                        </clipPath></defs>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-formation-section">
                        <h3>Nombre de participants par Blocs de formations</h3>
                        
                        <?php                        
                        // 1. Récupérer toutes les formations
                        $formations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}formations_lms ORDER BY id");
                        
                        if (empty($formations)) {
                            echo '<div class="error">Aucune formation trouvée.</div>';
                            return;
                        }   

                        $participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra'"
                        );                                            
                        
                        echo '<div class="chart-title">' . count($formations) . ' Formations </div>';
                        ?>
                        
                        <div class="bar-chart">
                            <?php foreach ($formations as $formation): 
                                // Compter les participants pour cette formation
                                $count = $wpdb->get_var($wpdb->prepare(
                                    "SELECT COUNT(*) FROM {$wpdb->prefix}formation_inscriptions fi
                                    INNER JOIN {$wpdb->prefix}lms_users u ON fi.user_id = u.id
                                    WHERE fi.formation_id = %d 
                                    AND fi.statut = 'en_attente'
                                    AND u.region = 'rabat_sale_kenitra'",
                                    $formation->id
                                ));
                                
                                // Calculer le pourcentage ABSOLU
                                $percentage = $participants > 0 ? ($count / $participants) * 100 : 0;
                            ?>
                                <div class="bar-item">
                                    <div class="bar-formation-label">Bloc <?php echo esc_html($formation->bloc); ?></div>
                                    <div class="bar-container">
                                        <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                    <span class="bar-formation-value">
                                        <?php echo round($percentage, 1); ?>%
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>                                                         
                </div>

                <!-- Liste des Coachs -->
                <div class="table-container">
                    <div class="table-headerr">
                        <h3>Région Casablanca Settat</h3>                        
                        <button class="menu-btn">⋯</button>
                    </div>
                    <?php
                        global $wpdb;

                        $total_participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra' AND role = 'participant'"
                        );

                        $total_formations = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}formations_lms"
                        );
                        
                        $total_telechargement = $wpdb->get_var(
                            "SELECT COUNT(DISTINCT fi.id) from {$wpdb->prefix}formation_inscriptions fi left join {$wpdb->prefix}lms_users u ON u.id = fi.user_id where u.region = 'rabat_sale_kenitra'"
                        );
                    ?>
                    <div class="dashboard-grid">
                        <div class="kpi-card coaches">
                            <h2>Total Coachs</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value">06</span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3092" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M35.3094 34.7733H35.3247" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3813 25.6658V22.6298C41.3813 21.8246 41.0615 21.0524 40.4921 20.4831C39.9228 19.9137 39.1506 19.5939 38.3454 19.5939H32.2735C31.4683 19.5939 30.6961 19.9137 30.1268 20.4831C29.5574 21.0524 29.2375 21.8246 29.2375 22.6298V25.6658" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 36.2909C45.9853 39.2646 40.707 40.8498 35.3097 40.8498C29.9125 40.8498 24.6341 39.2646 20.13 36.2909" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M47.4535 25.6653H23.1659C21.4892 25.6653 20.13 27.0245 20.13 28.7012V43.881C20.13 45.5577 21.4892 46.9169 23.1659 46.9169H47.4535C49.1302 46.9169 50.4894 45.5577 50.4894 43.881V28.7012C50.4894 27.0245 49.1302 25.6653 47.4535 25.6653Z" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card participants">
                            <h2>Total Participants</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_participants, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M45.9353 48.4349V45.3989C45.9353 43.7886 45.2956 42.2441 44.1569 41.1054C43.0182 39.9667 41.4738 39.327 39.8634 39.327H30.7556C29.1452 39.327 27.6008 39.9667 26.4621 41.1054C25.3234 42.2441 24.6837 43.7886 24.6837 45.3989V48.4349" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M35.3096 33.2553C38.663 33.2553 41.3814 30.5368 41.3814 27.1834C41.3814 23.83 38.663 21.1115 35.3096 21.1115C31.9561 21.1115 29.2377 23.83 29.2377 27.1834C29.2377 30.5368 31.9561 33.2553 35.3096 33.2553Z" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card formations">
                            <h2>Total formations</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_formations, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="71" viewBox="0 0 70 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="35.4696" r="34.6098" fill="white"/>
                                        <path d="M35.3093 49.1316V27.88" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3812 35.4697L44.4171 38.5056L50.489 32.4337" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 26.362V23.3261C50.4894 22.9235 50.3295 22.5374 50.0448 22.2527C49.7601 21.968 49.374 21.8081 48.9714 21.8081H41.3816C39.7712 21.8081 38.2268 22.4478 37.0881 23.5865C35.9494 24.7252 35.3097 26.2696 35.3097 27.88C35.3097 26.2696 34.67 24.7252 33.5313 23.5865C32.3926 22.4478 30.8481 21.8081 29.2378 21.8081H21.6479C21.2453 21.8081 20.8592 21.968 20.5745 22.2527C20.2899 22.5374 20.1299 22.9235 20.1299 23.3261V43.0597C20.1299 43.4623 20.2899 43.8484 20.5745 44.1331C20.8592 44.4178 21.2453 44.5777 21.6479 44.5777H30.7557C31.9635 44.5777 33.1218 45.0575 33.9759 45.9115C34.8299 46.7655 35.3097 47.9238 35.3097 49.1316C35.3097 47.9238 35.7895 46.7655 36.6435 45.9115C37.4975 45.0575 38.6558 44.5777 39.8636 44.5777H48.9714C49.374 44.5777 49.7601 44.4178 50.0448 44.1331C50.3295 43.8484 50.4894 43.4623 50.4894 43.0597V41.0864" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card telechargement">
                            <h2>Téléchargement</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_telechargement, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <g clip-path="url(#clip0_726_1436)"><path d="M49.0988 39.6699C48.4146 39.6699 47.8637 40.2209 47.8637 40.9051V46.8629H23.1363V40.9051C23.1363 40.2209 22.5854 39.6699 21.9012 39.6699C21.217 39.6699 20.666 40.2209 20.666 40.9051V48.098C20.666 48.7822 21.217 49.3332 21.9012 49.3332H49.0988C49.783 49.3332 50.334 48.7822 50.334 48.098V40.9051C50.334 40.2209 49.783 39.6699 49.0988 39.6699Z" fill="#5B74D7"/>
                                        <path d="M34.592 41.1365C35.2883 41.8388 36.142 41.4513 36.4024 41.1365L43.5045 33.4955C43.9708 32.9929 43.9405 32.2119 43.4379 31.7457C42.9354 31.2794 42.1543 31.3097 41.6942 31.8123L36.7354 37.1464V21.0894C36.7354 20.4052 36.1844 19.8542 35.5002 19.8542C34.8161 19.8542 34.2651 20.4052 34.2651 21.0894V37.1525L29.3063 31.8183C28.8401 31.3158 28.059 31.2916 27.5565 31.7517C27.054 32.2179 27.0297 32.999 27.4899 33.5015L34.592 41.1365V41.1365Z" fill="#5B74D7"/>
                                        </g><defs><clipPath id="clip0_726_1436"><rect width="31" height="31" fill="white" transform="translate(20 19)"/>
                                        </clipPath></defs>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-formation-section">
                        <h3>Nombre de participants par Blocs de formations</h3>
                        
                        <?php                        
                        // 1. Récupérer toutes les formations
                        $formations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}formations_lms ORDER BY id");
                        
                        if (empty($formations)) {
                            echo '<div class="error">Aucune formation trouvée.</div>';
                            return;
                        }   

                        $participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra'"
                        );                                            
                        
                        echo '<div class="chart-title">' . count($formations) . ' Formations </div>';
                        ?>
                        
                        <div class="bar-chart">
                            <?php foreach ($formations as $formation): 
                                // Compter les participants pour cette formation
                                $count = $wpdb->get_var($wpdb->prepare(
                                    "SELECT COUNT(*) FROM {$wpdb->prefix}formation_inscriptions fi
                                    INNER JOIN {$wpdb->prefix}lms_users u ON fi.user_id = u.id
                                    WHERE fi.formation_id = %d 
                                    AND fi.statut = 'en_attente'
                                    AND u.region = 'rabat_sale_kenitra'",
                                    $formation->id
                                ));
                                
                                // Calculer le pourcentage ABSOLU
                                $percentage = $participants > 0 ? ($count / $participants) * 100 : 0;
                            ?>
                                <div class="bar-item">
                                    <div class="bar-formation-label">Bloc <?php echo esc_html($formation->bloc); ?></div>
                                    <div class="bar-container">
                                        <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                    <span class="bar-formation-value">
                                        <?php echo round($percentage, 1); ?>%
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>  
                </div>
            </div> 
            <div class="tables-section m-top">
                <!-- Liste des Participants -->
                <div class="table-container">
                    <div class="table-headerr">
                        <h3>Région Fès Meknès</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    <?php
                        global $wpdb;

                        $total_participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra' AND role = 'participant'"
                        );

                        $total_formations = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}formations_lms"
                        );
                        
                        $total_telechargement = $wpdb->get_var(
                            "SELECT COUNT(DISTINCT fi.id) from {$wpdb->prefix}formation_inscriptions fi left join {$wpdb->prefix}lms_users u ON u.id = fi.user_id where u.region = 'rabat_sale_kenitra'"
                        );
                    ?>
                    <div class="dashboard-grid">
                        <div class="kpi-card coaches">
                            <h2>Total Coachs</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value">06</span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3092" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M35.3094 34.7733H35.3247" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3813 25.6658V22.6298C41.3813 21.8246 41.0615 21.0524 40.4921 20.4831C39.9228 19.9137 39.1506 19.5939 38.3454 19.5939H32.2735C31.4683 19.5939 30.6961 19.9137 30.1268 20.4831C29.5574 21.0524 29.2375 21.8246 29.2375 22.6298V25.6658" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 36.2909C45.9853 39.2646 40.707 40.8498 35.3097 40.8498C29.9125 40.8498 24.6341 39.2646 20.13 36.2909" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M47.4535 25.6653H23.1659C21.4892 25.6653 20.13 27.0245 20.13 28.7012V43.881C20.13 45.5577 21.4892 46.9169 23.1659 46.9169H47.4535C49.1302 46.9169 50.4894 45.5577 50.4894 43.881V28.7012C50.4894 27.0245 49.1302 25.6653 47.4535 25.6653Z" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card participants">
                            <h2>Total Participants</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_participants, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M45.9353 48.4349V45.3989C45.9353 43.7886 45.2956 42.2441 44.1569 41.1054C43.0182 39.9667 41.4738 39.327 39.8634 39.327H30.7556C29.1452 39.327 27.6008 39.9667 26.4621 41.1054C25.3234 42.2441 24.6837 43.7886 24.6837 45.3989V48.4349" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M35.3096 33.2553C38.663 33.2553 41.3814 30.5368 41.3814 27.1834C41.3814 23.83 38.663 21.1115 35.3096 21.1115C31.9561 21.1115 29.2377 23.83 29.2377 27.1834C29.2377 30.5368 31.9561 33.2553 35.3096 33.2553Z" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card formations">
                            <h2>Total formations</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_formations, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="71" viewBox="0 0 70 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="35.4696" r="34.6098" fill="white"/>
                                        <path d="M35.3093 49.1316V27.88" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3812 35.4697L44.4171 38.5056L50.489 32.4337" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 26.362V23.3261C50.4894 22.9235 50.3295 22.5374 50.0448 22.2527C49.7601 21.968 49.374 21.8081 48.9714 21.8081H41.3816C39.7712 21.8081 38.2268 22.4478 37.0881 23.5865C35.9494 24.7252 35.3097 26.2696 35.3097 27.88C35.3097 26.2696 34.67 24.7252 33.5313 23.5865C32.3926 22.4478 30.8481 21.8081 29.2378 21.8081H21.6479C21.2453 21.8081 20.8592 21.968 20.5745 22.2527C20.2899 22.5374 20.1299 22.9235 20.1299 23.3261V43.0597C20.1299 43.4623 20.2899 43.8484 20.5745 44.1331C20.8592 44.4178 21.2453 44.5777 21.6479 44.5777H30.7557C31.9635 44.5777 33.1218 45.0575 33.9759 45.9115C34.8299 46.7655 35.3097 47.9238 35.3097 49.1316C35.3097 47.9238 35.7895 46.7655 36.6435 45.9115C37.4975 45.0575 38.6558 44.5777 39.8636 44.5777H48.9714C49.374 44.5777 49.7601 44.4178 50.0448 44.1331C50.3295 43.8484 50.4894 43.4623 50.4894 43.0597V41.0864" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card telechargement">
                            <h2>Téléchargement</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_telechargement, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <g clip-path="url(#clip0_726_1436)"><path d="M49.0988 39.6699C48.4146 39.6699 47.8637 40.2209 47.8637 40.9051V46.8629H23.1363V40.9051C23.1363 40.2209 22.5854 39.6699 21.9012 39.6699C21.217 39.6699 20.666 40.2209 20.666 40.9051V48.098C20.666 48.7822 21.217 49.3332 21.9012 49.3332H49.0988C49.783 49.3332 50.334 48.7822 50.334 48.098V40.9051C50.334 40.2209 49.783 39.6699 49.0988 39.6699Z" fill="#5B74D7"/>
                                        <path d="M34.592 41.1365C35.2883 41.8388 36.142 41.4513 36.4024 41.1365L43.5045 33.4955C43.9708 32.9929 43.9405 32.2119 43.4379 31.7457C42.9354 31.2794 42.1543 31.3097 41.6942 31.8123L36.7354 37.1464V21.0894C36.7354 20.4052 36.1844 19.8542 35.5002 19.8542C34.8161 19.8542 34.2651 20.4052 34.2651 21.0894V37.1525L29.3063 31.8183C28.8401 31.3158 28.059 31.2916 27.5565 31.7517C27.054 32.2179 27.0297 32.999 27.4899 33.5015L34.592 41.1365V41.1365Z" fill="#5B74D7"/>
                                        </g><defs><clipPath id="clip0_726_1436"><rect width="31" height="31" fill="white" transform="translate(20 19)"/>
                                        </clipPath></defs>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-formation-section">
                        <h3>Nombre de participants par Blocs de formations</h3>
                        
                        <?php                        
                        // 1. Récupérer toutes les formations
                        $formations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}formations_lms ORDER BY id");
                        
                        if (empty($formations)) {
                            echo '<div class="error">Aucune formation trouvée.</div>';
                            return;
                        }   

                        $participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra'"
                        );                                            
                        
                        echo '<div class="chart-title">' . count($formations) . ' Formations </div>';
                        ?>
                        
                        <div class="bar-chart">
                            <?php foreach ($formations as $formation): 
                                // Compter les participants pour cette formation
                                $count = $wpdb->get_var($wpdb->prepare(
                                    "SELECT COUNT(*) FROM {$wpdb->prefix}formation_inscriptions fi
                                    INNER JOIN {$wpdb->prefix}lms_users u ON fi.user_id = u.id
                                    WHERE fi.formation_id = %d 
                                    AND fi.statut = 'en_attente'
                                    AND u.region = 'rabat_sale_kenitra'",
                                    $formation->id
                                ));
                                
                                // Calculer le pourcentage ABSOLU
                                $percentage = $participants > 0 ? ($count / $participants) * 100 : 0;
                            ?>
                                <div class="bar-item">
                                    <div class="bar-formation-label">Bloc <?php echo esc_html($formation->bloc); ?></div>
                                    <div class="bar-container">
                                        <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                    <span class="bar-formation-value">
                                        <?php echo round($percentage, 1); ?>%
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>                                                         
                </div>

                <!-- Liste des Coachs -->
                <div class="table-container">
                    <div class="table-headerr">
                        <h3>Région Beni Mellal Khénifra</h3>                        
                        <button class="menu-btn">⋯</button>
                    </div>
                    <?php
                        global $wpdb;

                        $total_participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra' AND role = 'participant'"
                        );

                        $total_formations = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}formations_lms"
                        );
                        
                        $total_telechargement = $wpdb->get_var(
                            "SELECT COUNT(DISTINCT fi.id) from {$wpdb->prefix}formation_inscriptions fi left join {$wpdb->prefix}lms_users u ON u.id = fi.user_id where u.region = 'rabat_sale_kenitra'"
                        );
                    ?>
                    <div class="dashboard-grid">
                        <div class="kpi-card coaches">
                            <h2>Total Coachs</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value">06</span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3092" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M35.3094 34.7733H35.3247" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3813 25.6658V22.6298C41.3813 21.8246 41.0615 21.0524 40.4921 20.4831C39.9228 19.9137 39.1506 19.5939 38.3454 19.5939H32.2735C31.4683 19.5939 30.6961 19.9137 30.1268 20.4831C29.5574 21.0524 29.2375 21.8246 29.2375 22.6298V25.6658" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 36.2909C45.9853 39.2646 40.707 40.8498 35.3097 40.8498C29.9125 40.8498 24.6341 39.2646 20.13 36.2909" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M47.4535 25.6653H23.1659C21.4892 25.6653 20.13 27.0245 20.13 28.7012V43.881C20.13 45.5577 21.4892 46.9169 23.1659 46.9169H47.4535C49.1302 46.9169 50.4894 45.5577 50.4894 43.881V28.7012C50.4894 27.0245 49.1302 25.6653 47.4535 25.6653Z" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card participants">
                            <h2>Total Participants</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_participants, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M45.9353 48.4349V45.3989C45.9353 43.7886 45.2956 42.2441 44.1569 41.1054C43.0182 39.9667 41.4738 39.327 39.8634 39.327H30.7556C29.1452 39.327 27.6008 39.9667 26.4621 41.1054C25.3234 42.2441 24.6837 43.7886 24.6837 45.3989V48.4349" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M35.3096 33.2553C38.663 33.2553 41.3814 30.5368 41.3814 27.1834C41.3814 23.83 38.663 21.1115 35.3096 21.1115C31.9561 21.1115 29.2377 23.83 29.2377 27.1834C29.2377 30.5368 31.9561 33.2553 35.3096 33.2553Z" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card formations">
                            <h2>Total formations</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_formations, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="71" viewBox="0 0 70 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="35.4696" r="34.6098" fill="white"/>
                                        <path d="M35.3093 49.1316V27.88" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3812 35.4697L44.4171 38.5056L50.489 32.4337" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 26.362V23.3261C50.4894 22.9235 50.3295 22.5374 50.0448 22.2527C49.7601 21.968 49.374 21.8081 48.9714 21.8081H41.3816C39.7712 21.8081 38.2268 22.4478 37.0881 23.5865C35.9494 24.7252 35.3097 26.2696 35.3097 27.88C35.3097 26.2696 34.67 24.7252 33.5313 23.5865C32.3926 22.4478 30.8481 21.8081 29.2378 21.8081H21.6479C21.2453 21.8081 20.8592 21.968 20.5745 22.2527C20.2899 22.5374 20.1299 22.9235 20.1299 23.3261V43.0597C20.1299 43.4623 20.2899 43.8484 20.5745 44.1331C20.8592 44.4178 21.2453 44.5777 21.6479 44.5777H30.7557C31.9635 44.5777 33.1218 45.0575 33.9759 45.9115C34.8299 46.7655 35.3097 47.9238 35.3097 49.1316C35.3097 47.9238 35.7895 46.7655 36.6435 45.9115C37.4975 45.0575 38.6558 44.5777 39.8636 44.5777H48.9714C49.374 44.5777 49.7601 44.4178 50.0448 44.1331C50.3295 43.8484 50.4894 43.4623 50.4894 43.0597V41.0864" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card telechargement">
                            <h2>Téléchargement</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_telechargement, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <g clip-path="url(#clip0_726_1436)"><path d="M49.0988 39.6699C48.4146 39.6699 47.8637 40.2209 47.8637 40.9051V46.8629H23.1363V40.9051C23.1363 40.2209 22.5854 39.6699 21.9012 39.6699C21.217 39.6699 20.666 40.2209 20.666 40.9051V48.098C20.666 48.7822 21.217 49.3332 21.9012 49.3332H49.0988C49.783 49.3332 50.334 48.7822 50.334 48.098V40.9051C50.334 40.2209 49.783 39.6699 49.0988 39.6699Z" fill="#5B74D7"/>
                                        <path d="M34.592 41.1365C35.2883 41.8388 36.142 41.4513 36.4024 41.1365L43.5045 33.4955C43.9708 32.9929 43.9405 32.2119 43.4379 31.7457C42.9354 31.2794 42.1543 31.3097 41.6942 31.8123L36.7354 37.1464V21.0894C36.7354 20.4052 36.1844 19.8542 35.5002 19.8542C34.8161 19.8542 34.2651 20.4052 34.2651 21.0894V37.1525L29.3063 31.8183C28.8401 31.3158 28.059 31.2916 27.5565 31.7517C27.054 32.2179 27.0297 32.999 27.4899 33.5015L34.592 41.1365V41.1365Z" fill="#5B74D7"/>
                                        </g><defs><clipPath id="clip0_726_1436"><rect width="31" height="31" fill="white" transform="translate(20 19)"/>
                                        </clipPath></defs>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-formation-section">
                        <h3>Nombre de participants par Blocs de formations</h3>
                        
                        <?php                        
                        // 1. Récupérer toutes les formations
                        $formations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}formations_lms ORDER BY id");
                        
                        if (empty($formations)) {
                            echo '<div class="error">Aucune formation trouvée.</div>';
                            return;
                        }   

                        $participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra'"
                        );                                            
                        
                        echo '<div class="chart-title">' . count($formations) . ' Formations </div>';
                        ?>
                        
                        <div class="bar-chart">
                            <?php foreach ($formations as $formation): 
                                // Compter les participants pour cette formation
                                $count = $wpdb->get_var($wpdb->prepare(
                                    "SELECT COUNT(*) FROM {$wpdb->prefix}formation_inscriptions fi
                                    INNER JOIN {$wpdb->prefix}lms_users u ON fi.user_id = u.id
                                    WHERE fi.formation_id = %d 
                                    AND fi.statut = 'en_attente'
                                    AND u.region = 'rabat_sale_kenitra'",
                                    $formation->id
                                ));
                                
                                // Calculer le pourcentage ABSOLU
                                $percentage = $participants > 0 ? ($count / $participants) * 100 : 0;
                            ?>
                                <div class="bar-item">
                                    <div class="bar-formation-label">Bloc <?php echo esc_html($formation->bloc); ?></div>
                                    <div class="bar-container">
                                        <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                    <span class="bar-formation-value">
                                        <?php echo round($percentage, 1); ?>%
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="tables-section m-top">
                <!-- Liste des Participants -->
                <div class="table-container">
                    <div class="table-headerr">
                        <h3>Région Marrakech Safi</h3>
                        <button class="menu-btn">⋯</button>
                    </div>
                    <?php
                        global $wpdb;

                        $total_participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra' AND role = 'participant'"
                        );

                        $total_formations = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}formations_lms"
                        );
                        
                        $total_telechargement = $wpdb->get_var(
                            "SELECT COUNT(DISTINCT fi.id) from {$wpdb->prefix}formation_inscriptions fi left join {$wpdb->prefix}lms_users u ON u.id = fi.user_id where u.region = 'rabat_sale_kenitra'"
                        );
                    ?>
                    <div class="dashboard-grid">
                        <div class="kpi-card coaches">
                            <h2>Total Coachs</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value">06</span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3092" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M35.3094 34.7733H35.3247" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3813 25.6658V22.6298C41.3813 21.8246 41.0615 21.0524 40.4921 20.4831C39.9228 19.9137 39.1506 19.5939 38.3454 19.5939H32.2735C31.4683 19.5939 30.6961 19.9137 30.1268 20.4831C29.5574 21.0524 29.2375 21.8246 29.2375 22.6298V25.6658" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 36.2909C45.9853 39.2646 40.707 40.8498 35.3097 40.8498C29.9125 40.8498 24.6341 39.2646 20.13 36.2909" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M47.4535 25.6653H23.1659C21.4892 25.6653 20.13 27.0245 20.13 28.7012V43.881C20.13 45.5577 21.4892 46.9169 23.1659 46.9169H47.4535C49.1302 46.9169 50.4894 45.5577 50.4894 43.881V28.7012C50.4894 27.0245 49.1302 25.6653 47.4535 25.6653Z" stroke="#FFCC33" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card participants">
                            <h2>Total Participants</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_participants, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <path d="M45.9353 48.4349V45.3989C45.9353 43.7886 45.2956 42.2441 44.1569 41.1054C43.0182 39.9667 41.4738 39.327 39.8634 39.327H30.7556C29.1452 39.327 27.6008 39.9667 26.4621 41.1054C25.3234 42.2441 24.6837 43.7886 24.6837 45.3989V48.4349" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M35.3096 33.2553C38.663 33.2553 41.3814 30.5368 41.3814 27.1834C41.3814 23.83 38.663 21.1115 35.3096 21.1115C31.9561 21.1115 29.2377 23.83 29.2377 27.1834C29.2377 30.5368 31.9561 33.2553 35.3096 33.2553Z" stroke="#975BD7" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card formations">
                            <h2>Total formations</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_formations, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="71" viewBox="0 0 70 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="35.4696" r="34.6098" fill="white"/>
                                        <path d="M35.3093 49.1316V27.88" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M41.3812 35.4697L44.4171 38.5056L50.489 32.4337" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M50.4894 26.362V23.3261C50.4894 22.9235 50.3295 22.5374 50.0448 22.2527C49.7601 21.968 49.374 21.8081 48.9714 21.8081H41.3816C39.7712 21.8081 38.2268 22.4478 37.0881 23.5865C35.9494 24.7252 35.3097 26.2696 35.3097 27.88C35.3097 26.2696 34.67 24.7252 33.5313 23.5865C32.3926 22.4478 30.8481 21.8081 29.2378 21.8081H21.6479C21.2453 21.8081 20.8592 21.968 20.5745 22.2527C20.2899 22.5374 20.1299 22.9235 20.1299 23.3261V43.0597C20.1299 43.4623 20.2899 43.8484 20.5745 44.1331C20.8592 44.4178 21.2453 44.5777 21.6479 44.5777H30.7557C31.9635 44.5777 33.1218 45.0575 33.9759 45.9115C34.8299 46.7655 35.3097 47.9238 35.3097 49.1316C35.3097 47.9238 35.7895 46.7655 36.6435 45.9115C37.4975 45.0575 38.6558 44.5777 39.8636 44.5777H48.9714C49.374 44.5777 49.7601 44.4178 50.0448 44.1331C50.3295 43.8484 50.4894 43.4623 50.4894 43.0597V41.0864" stroke="#EE4444" stroke-width="2.73235" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-card telechargement">
                            <h2>Téléchargement</h2>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="value"><?= str_pad($total_telechargement, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="icon">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.4" cx="35.3095" cy="34.7733" r="34.6098" fill="white"/>
                                        <g clip-path="url(#clip0_726_1436)"><path d="M49.0988 39.6699C48.4146 39.6699 47.8637 40.2209 47.8637 40.9051V46.8629H23.1363V40.9051C23.1363 40.2209 22.5854 39.6699 21.9012 39.6699C21.217 39.6699 20.666 40.2209 20.666 40.9051V48.098C20.666 48.7822 21.217 49.3332 21.9012 49.3332H49.0988C49.783 49.3332 50.334 48.7822 50.334 48.098V40.9051C50.334 40.2209 49.783 39.6699 49.0988 39.6699Z" fill="#5B74D7"/>
                                        <path d="M34.592 41.1365C35.2883 41.8388 36.142 41.4513 36.4024 41.1365L43.5045 33.4955C43.9708 32.9929 43.9405 32.2119 43.4379 31.7457C42.9354 31.2794 42.1543 31.3097 41.6942 31.8123L36.7354 37.1464V21.0894C36.7354 20.4052 36.1844 19.8542 35.5002 19.8542C34.8161 19.8542 34.2651 20.4052 34.2651 21.0894V37.1525L29.3063 31.8183C28.8401 31.3158 28.059 31.2916 27.5565 31.7517C27.054 32.2179 27.0297 32.999 27.4899 33.5015L34.592 41.1365V41.1365Z" fill="#5B74D7"/>
                                        </g><defs><clipPath id="clip0_726_1436"><rect width="31" height="31" fill="white" transform="translate(20 19)"/>
                                        </clipPath></defs>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-formation-section">
                        <h3>Nombre de participants par Blocs de formations</h3>
                        
                        <?php                        
                        // 1. Récupérer toutes les formations
                        $formations = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}formations_lms ORDER BY id");
                        
                        if (empty($formations)) {
                            echo '<div class="error">Aucune formation trouvée.</div>';
                            return;
                        }   

                        $participants = $wpdb->get_var(
                            "SELECT COUNT(*) FROM {$wpdb->prefix}lms_users  WHERE region = 'rabat_sale_kenitra'"
                        );                                            
                        
                        echo '<div class="chart-title">' . count($formations) . ' Formations </div>';
                        ?>
                        
                        <div class="bar-chart">
                            <?php foreach ($formations as $formation): 
                                // Compter les participants pour cette formation
                                $count = $wpdb->get_var($wpdb->prepare(
                                    "SELECT COUNT(*) FROM {$wpdb->prefix}formation_inscriptions fi
                                    INNER JOIN {$wpdb->prefix}lms_users u ON fi.user_id = u.id
                                    WHERE fi.formation_id = %d 
                                    AND fi.statut = 'en_attente'
                                    AND u.region = 'rabat_sale_kenitra'",
                                    $formation->id
                                ));
                                
                                // Calculer le pourcentage ABSOLU
                                $percentage = $participants > 0 ? ($count / $participants) * 100 : 0;
                            ?>
                                <div class="bar-item">
                                    <div class="bar-formation-label">Bloc <?php echo esc_html($formation->bloc); ?></div>
                                    <div class="bar-container">
                                        <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                                    </div>
                                    <span class="bar-formation-value">
                                        <?php echo round($percentage, 1); ?>%
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>                                                         
                </div>
            </div>
        </div>
    </div>
</div>
