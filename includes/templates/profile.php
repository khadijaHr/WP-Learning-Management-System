<?php $user = cap_get_current_user(); ?>
<div class="container">
        <?php if (isset($_GET['updated'])): ?>
            <div class="success-message">
                Profil mis à jour avec succès!
            </div>
        <?php endif; ?>

        <h3 class="section-title">Informations personnelles</h3>

        <div class="container-section">
            <div class="info-box">
                <?php if ($user->image): ?>
                    <img src="<?php echo esc_url($user->image); ?>" alt="Photo de profil">
                <?php else: ?>
                    <img src="<?php echo plugin_dir_url(dirname(__FILE__, 2)) . 'assets/images/default-avatar.jpg'; ?>" alt="Photo de profil">
                <?php endif; ?>
                <div>
                    <h3 class="name_participant"><?php echo esc_html($user->prenom) . ' ' . esc_html($user->nom); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($user->description)); ?></p>
                    <div class="contact-info">
                        <small><strong>Email :</strong> <?php echo esc_html($user->email); ?></small>
                        <small class="location">
                            <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 0C2.23875 0 0 2.18278 0 4.875C0 5.93511 0.356458 6.90788 0.948958 7.70453C0.959583 7.72363 0.96125 7.74495 0.973333 7.76323L4.30667 12.6382C4.46125 12.8643 4.72167 13 5 13C5.27833 13 5.53875 12.8643 5.69333 12.6382L9.02667 7.76323C9.03896 7.74495 9.04042 7.72363 9.05104 7.70453C9.64354 6.90788 10 5.93511 10 4.875C10 2.18278 7.76125 0 5 0ZM5 6.5C4.07958 6.5 3.33333 5.77241 3.33333 4.875C3.33333 3.97759 4.07958 3.25 5 3.25C5.92042 3.25 6.66667 3.97759 6.66667 4.875C6.66667 5.77241 5.92042 6.5 5 6.5Z" fill="#04477A"/>
                            </svg>            
                            <?php echo esc_html($user->ville_region).', Maroc'; ?>
                        </small>
                    </div>        
                </div>      
            </div>
        
            <div class="profile-edit">
                <div class="profile-image-wrapper">
                    <?php if ($user->image): ?>
                        <img src="<?php echo esc_url($user->image); ?>" class="profile-avatar">
                    <?php else: ?>
                        <img src="<?php echo plugin_dir_url(dirname(__FILE__, 2)) . 'assets/images/default-avatar.jpg'; ?>" alt="Photo de profil">
                    <?php endif; ?>
                    <label for="profile-image-upload" class="camera-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="10" fill="white"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.33203 4.85363C7.33203 4.4694 7.61951 4.1579 7.97446 4.1579H12.2034C12.5583 4.1579 12.8458 4.4694 12.8458 4.85363C12.8458 5.23786 12.5583 5.54937 12.2034 5.54937H7.97446C7.61951 5.54937 7.33203 5.23786 7.33203 4.85363ZM8.56992 15.6812H11.6079C13.8326 15.6812 14.9449 15.6812 15.7248 15.1786C16.0341 14.9631 16.2976 14.6834 16.4989 14.3557C17.0726 13.6043 17.0726 12.5572 17.0726 10.4631C17.0726 8.36905 17.0726 7.32261 16.4983 6.57058C16.2973 6.24303 16.034 5.96335 15.7248 5.74789C14.9449 5.24526 13.8326 5.24526 11.6079 5.24526H8.56992C6.34522 5.24526 5.23291 5.24526 4.453 5.74789C4.14393 5.96336 3.88067 6.24305 3.67963 6.57058C3.10535 7.32261 3.10535 8.36905 3.10535 10.4631V10.4631C3.10535 12.5572 3.10535 13.6037 3.67963 14.3557C3.88067 14.6834 4.14393 14.9631 4.453 15.1786C5.23291 15.6812 6.34522 15.6812 8.56992 15.6812ZM7.21611 10.4631C7.21611 8.91858 8.51436 7.66764 10.1152 7.66764C11.7161 7.66764 13.0143 8.91858 13.0143 10.4631C13.0143 12.0077 11.7156 13.2586 10.1152 13.2586C8.51436 13.2586 7.21611 12.0063 7.21611 10.4631ZM8.3759 10.4631C8.3759 9.53639 9.15513 8.78639 10.1152 8.78639C11.0753 8.78639 11.8545 9.53639 11.8545 10.4631C11.8545 11.3899 11.0753 12.1399 10.1152 12.1399C9.15513 12.1399 8.3759 11.3899 8.3759 10.4631ZM14.3668 7.66764C14.0468 7.66764 13.7874 7.91789 13.7874 8.22789C13.7874 8.53789 14.0468 8.78814 14.3668 8.78814H14.7539C15.0739 8.78814 15.3333 8.53789 15.3333 8.22789C15.3333 7.91789 15.0739 7.66764 14.7539 7.66764H14.3668Z" fill="#92ADC2"/>
                        </svg>
                    </label>
                </div>

                <h4><?php echo esc_html($user->role); ?></h4>
                <p class="role"><?php echo ($user->role === 'Coach' ? 'Communication' : 'Participant'); ?></p>
            
                <form method="post" action="" enctype="multipart/form-data" id="profile-update-form">
                    <?php wp_nonce_field('cap_profile_action', 'cap_profile_nonce'); ?>
                    
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
                            <label for="nom">Email <span>*</span></label>
                            <input type="email" name="email" id="email" value="<?php echo esc_attr($user->email); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_naissance">Date de naissance <span>*</span></label>
                            <input type="date" name="date_naissance" id="date_naissance" value="<?php echo esc_attr($user->date_naissance); ?>" required>
                        </div>
                    </div>                    

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
                            <button type="button" class="generate-password" onclick="generatePassword()">Générer</button>
                            <span class="toggle-password-visibility" onclick="togglePasswordVisibility(this, 'password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div> 
                                        
                    <div class="row">
                        <div class="form-group">
                            <label for="region">Région <span>*</span></label>
                            <select name="region" id="region" class="form-control" required>
                                <option value="">Sélectionnez une Région</option>
                                <option value="rabat_sale_kenitra" <?php selected($user->region, 'rabat_sale_kenitra'); ?>>Rabat, Salé, Kénitra</option>
                                <option value="casablanca_settat" <?php selected($user->region, 'casablanca_settat'); ?>>Casablanca, Settat</option>
                                <option value="fes_meknes" <?php selected($user->region, 'fes_meknes'); ?>>Fés, Meknès</option>
                                <option value="beni_mellal_khenifra" <?php selected($user->region, 'beni_mellal_khenifra'); ?>>Béni Mellal, Khenifra</option>
                                <option value="marrakech_safi" <?php selected($user->region, 'marrakech_safi'); ?>>Marrakech Safi</option>
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
                                <label>
                                    <input type="checkbox" name="delete_image" value="1"> Supprimer l'image actuelle
                                </label>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" id="image" accept="image/*">
                        <p class="description">Formats supportés : JPG, PNG, GIF</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"><?php echo htmlspecialchars($user->description); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="edit-btn">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
