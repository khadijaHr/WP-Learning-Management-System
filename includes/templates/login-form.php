<div class="login-container">
    <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
            <?php echo esc_html($_GET['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">
            <?php 
            $success_message = esc_html($_GET['success']);
            // Afficher le message de succès avec des sauts de ligne
            echo nl2br($success_message);
            ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de connexion (par défaut) -->
    <div id="login-form" class="form-wrapper">
        <h2>Connexion</h2>
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="" name="loginform" id="monplugin-login-form">
            <?php wp_nonce_field('cap_login_action', 'cap_login_nonce'); ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" 
                    placeholder="Entrer votre email" 
                    value="<?php echo isset($_SESSION['login_email']) ? esc_attr($_SESSION['login_email']) : ''; ?>" 
                    required>
                <?php unset($_SESSION['login_email']); ?>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" name="password" id="password" placeholder="Entrer votre mot de passe" required style="padding-right: 35px; width: 100%;"/>
                    <span class="eye-icon toggle-password" data-target="password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"><i class="fas fa-eye"></i></span>
                </div>        
            </div>
            
            <div class="options">        
                <a href="#" class="forgot-password-link">Mot de passe oublié ?</a>
            </div>

            <div class="form-group">
                <button type="submit" name="submit">Se connecter</button>
            </div>
        </form>
    </div>

    <!-- Formulaire de réinitialisation -->
    <div id="reset-form" class="form-wrapper" style="display:none;">
        <h2>Réinitialisation du mot de passe</h2>
        
        <?php if (isset($_SESSION['reset_link'])): ?>
            <div class="reset-link-container">
                <h3>Votre lien de réinitialisation :</h3>
                <div class="reset-link">
                    <a href="<?php echo esc_url($_SESSION['reset_link']); ?>">
                        <?php echo esc_url($_SESSION['reset_link']); ?>
                    </a>
                </div>
                <p>Copiez ce lien ou cliquez dessus pour continuer.</p>
            </div>
            <?php unset($_SESSION['reset_link']); ?>
        <?php else: ?>
            <form method="post" action="" name="resetform" id="monplugin-reset-form">
                <?php wp_nonce_field('cap_reset_action', 'cap_reset_nonce'); ?>

                <div class="form-group">
                    <label for="reset_email">Email</label>
                    <input type="email" name="reset_email" id="reset_email" placeholder="Entrer votre email" required>
                </div>
                
                <div class="options">        
                    <a href="#" class="back-to-login">Retour à la connexion</a>
                </div>

                <div class="form-group">
                    <button type="submit" name="reset_submit">Générer le lien</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
jQuery(document).ready(function($) {
    // Gestion du formulaire de connexion
    const togglePassword = document.querySelector('.toggle-password');
    //const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function() {
        const input = document.querySelector('#password');
        if (input.type === 'password') {
            input.type = 'text';
            this.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Icône œil barré quand le mot de passe est visible
        } else {
            input.type = 'password';
            this.innerHTML = '<i class="fas fa-eye"></i>'; // Icône œil normal quand le mot de passe est masqué
        }
    });

    // Basculer entre les formulaires
    $('.forgot-password-link').click(function(e) {
        e.preventDefault();
        $('#login-form').hide();
        $('#reset-form').show();
    });
    
    // Toggle between login and reset forms
    $('.forgot-password-link').click(function(e) {
        e.preventDefault();
        $('#login-form').hide();
        $('#reset-form').show();
    });
    
    $('.back-to-login').click(function(e) {
        e.preventDefault();
        $('#reset-form').hide();
        $('#login-form').show();
    });
});
</script>
