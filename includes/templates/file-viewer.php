<?php
/**
 * Template Name: File Viewer
 * Template Post Type: page
 */

// 1. Inclure votre header personnalisé
if (!defined('ABSPATH')) {
    exit; // Empêche l'accès direct
}

// 2. Récupérer l'ID du fichier
$file_id = isset($_GET['file_id']) ? intval($_GET['file_id']) : 0;

// 3. Vérifier le nonce pour la sécurité
if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'view_file_' . $file_id)) {
    wp_die('Lien invalide ou expiré');
}

// 4. Récupérer les infos du fichier
global $wpdb;
$file = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}formation_fichiers WHERE id = %d", 
    $file_id
));

if (!$file) {
    wp_die('Fichier introuvable');
}

// 5. Déterminer le type de fichier
$extension = strtolower(pathinfo($file->nom_fichier, PATHINFO_EXTENSION));
?>

<!-- 6. Inclure votre header personnalisé -->
<?php 
$header_path = locate_template('custom-header.php');
if (!$header_path && file_exists(plugin_dir_path(__FILE__) . 'templates/header.php')) {
    include plugin_dir_path(__FILE__) . 'templates/header.php';
} else {
    include $header_path;
}
?>

<div class="file-viewer-container">
    <div class="file-header">
        <h1><?php echo esc_html($file->nom_fichier); ?></h1>
        <div class="file-actions">
            <a href="<?php echo esc_url($file->chemin_fichier); ?>" download class="button">
                Télécharger
            </a>
        </div>
    </div>

    <div class="file-content">
        <?php if ($extension === 'pdf'): ?>
            <iframe src="<?php echo esc_url($file->chemin_fichier); ?>#toolbar=0" class="pdf-viewer"></iframe>
        
        <?php elseif (in_array($extension, ['doc', 'docx', 'ppt', 'pptx'])): ?>
            <div class="office-viewer">
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo urlencode($file->chemin_fichier); ?>" class="office-iframe"></iframe>
            </div>
        
        <?php else: ?>
            <div class="unsupported-file">
                <p>Ce type de fichier ne peut pas être prévisualisé.</p>
                <a href="<?php echo esc_url($file->chemin_fichier); ?>" download class="button">Télécharger</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// Inclure le footer si nécessaire
get_footer(); 
?>