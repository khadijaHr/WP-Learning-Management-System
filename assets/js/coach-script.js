jQuery(document).ready(function($) {
    $('#CoachForm').on('submit', function(e) {
        e.preventDefault();
                
        if ($('#password').val() !== $('#confirm_password').val()) {
            alert('Les mots de passe ne correspondent pas');
            return;
        }

        var formData = new FormData(this);
        formData.append('action', 'coach_data');
        formData.append('security', coachData.nonce); 

        $.ajax({
            url: coachData.ajaxurl, 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Vérifiez si response est déjà un objet ou si besoin de le parser
                var res = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (res.success) {
                    // Affichez le message de succès correctement
                    alert(res.data.message || 'Coach enregistré avec succès');
                    $('#coachModal').hide();
                    
                    // Si vous avez besoin de rediriger ou rafraîchir
                    // window.location.reload();
                } else {
                    // Affichez le message d'erreur
                    alert(res.data || 'Erreur lors de l\'enregistrement');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Erreur lors de la communication avec le serveur: ' + error);
            }
        });
    });
          
});
