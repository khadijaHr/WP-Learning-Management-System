<?php $user = cap_get_current_user(); ?>
<div class="container">
        <?php if (isset($_GET['updated'])): ?>
            <div class="success-message">
                Profil mis à jour avec succès!
            </div>
        <?php endif; ?>

        <h3 class="section-title">Informations personnelles</h3>

        <div class="container-section">
            <div class="info-box-coach-wrapper">
                <div class="info-box-coach">
                    <?php if ($user->image): ?>
                        <img src="<?php echo esc_url($user->image); ?>" alt="Photo de profil">
                    <?php else: ?>
                        <img src="<?php echo plugin_dir_url(dirname(__FILE__, 2)) . 'assets/images/default-avatar.jpg'; ?>" alt="Photo de profil">
                    <?php endif; ?>
                    <div>
                        <h3 class="name_coach"><?php echo esc_html($user->prenom). ' '. esc_html($user->nom);  ?></h3>
                        <small class="location">
                            <svg width="19" height="26" viewBox="0 0 19 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.16343 0.792969C4.10293 0.792969 0 4.8959 0 9.9564C0 11.9491 0.653277 13.7776 1.73914 15.275C1.75862 15.3109 1.76167 15.351 1.78382 15.3854L7.89277 24.5488C8.17607 24.9737 8.65334 25.2288 9.16343 25.2288C9.67353 25.2288 10.1508 24.9737 10.4341 24.5488L16.5431 15.3854C16.5656 15.351 16.5683 15.3109 16.5877 15.275C17.6736 13.7776 18.3269 11.9491 18.3269 9.9564C18.3269 4.8959 14.2239 0.792969 9.16343 0.792969ZM9.16343 13.0109C7.4766 13.0109 6.10896 11.6432 6.10896 9.9564C6.10896 8.26957 7.4766 6.90192 9.16343 6.90192C10.8503 6.90192 12.2179 8.26957 12.2179 9.9564C12.2179 11.6432 10.8503 13.0109 9.16343 13.0109Z" fill="#04477A"/>
                            </svg>       
                            <?php echo esc_html($user->ville_region).', Maroc'; ?>
                        </small>      
                    </div>      
                </div>
                
                <div class="info-detail-box m-top">                   
                    <div>
                        <h3><?php echo esc_html($user->prenom). ' '. esc_html($user->nom); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($user->description)); ?></p>
                        <div class="contact-info">
                            <small><?php echo esc_html($user->email); ?></small>
                            <small>                                         
                                <?php echo esc_html($user->ville_region).', Maroc'; ?>
                            </small>
                        </div>        
                    </div>      
                </div>        
            </div>
                   
            <div class="profile-coach-edit">             
                <form method="post" action="" enctype="multipart/form-data" id="profile-coach-update-form">
                    <?php wp_nonce_field('cap_profile_coach_action', 'cap_profile_coach_nonce'); ?>
                    
                    <div class="row">
                        <div class="form-group">
                            <label for="nom">Nom <span>*</span></label>
                            <input type="text" name="nom" id="nom" value="<?php echo esc_attr($user->nom); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom <span>*</span></label>
                            <input type="text" name="prenom" id="prenom" value="<?php echo esc_attr($user->prenom); ?>" required>
                        </div>
                    </div>                    

                    <div class="row">
                        <div class="form-group">
                            <label for="date_naissance">Date de naissance <span>*</span></label>
                            <input type="date" name="date_naissance" id="date_naissance" value="<?php echo esc_attr($user->date_naissance); ?>" required>
                        </div>
                                     
                        <div class="form-group">
                            <label for="nom">Email <span>*</span></label>
                            <input type="email" name="email" id="email" value="<?php echo esc_attr($user->email); ?>" required>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="form-group">
                            <label for="region">Région <span>*</span></label>
                            <select id="region" name="region" class="form-control" required>
                                <option value="">Sélectionnez une Région</option>
                                <option value="rabat_sale_kenitra" <?php selected($user->region, 'rabat_sale_kenitra'); ?>>Rabat, Salé, Kénitra</option>
                                <option value="casablanca_settat" <?php selected($user->region, 'casablanca_settat'); ?>>Casablanca, Settat</option>
                                <option value="fes_meknes" <?php selected($user->region, 'fes_meknes'); ?>>Fés, Meknès</option>
                                <option value="beni_mellal_khenifra" <?php selected($user->region, 'beni_mellal_khenifra'); ?>>Béni Mellal, Khenifra</option>
                                <option value="marrakech_safi">Marrakech Safi</option>
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="ville_region">Ville <span>*</span></label>
                            <input type="text" name="ville_region" id="ville_region" value="<?php echo esc_attr($user->ville_region); ?>" required>
                        </div>  
                    </div>
                            
                    <div class="form-group">
                        <label for="image">Photo de profil</label>
                        <?php if ($user->image): ?>
                            <div class="current-image">
                                <img src="<?php echo esc_url($user->image); ?>" width="100" style="display:block;margin-bottom:10px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" id="image" accept="image/*">
                        <p class="description">Formats supportés : JPG, PNG, GIF</p>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password">
                            <button type="button" class="generate-password" onclick="generatePassword()">Générer</button>
                            <span class="toggle-password-visibility" onclick="togglePasswordVisibility(this, 'password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <p class="description">Laisser vide pour ne pas changer le mot de passe</p>
                    </div>                                       
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="edit-btn">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
