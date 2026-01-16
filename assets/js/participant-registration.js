jQuery(document).ready(function($) {
    $('#inscriptionForm').on('submit', function(e) {
        e.preventDefault();
        
        if ($('#password').val() !== $('#confirm_password').val()) {
            alert('Les mots de passe ne correspondent pas');
            return;
        }

        var formData = new FormData(this);
        formData.append('action', 'register_participant');
        formData.append('security', participantRegistration.nonce); 

        $.ajax({
            url: participantRegistration.ajaxurl, 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    $('#inscriptionModal').hide();
                    window.location.reload();
                } else {
                    alert(response.data);
                }
            },
            error: function() {
                alert('Erreur lors de la communication avec le serveur');
            }
        });
    });

    $(document).on('click', '.export-btn', function() {
        const format = $(this).data('format');
        console.log('Export cliqué:', format); // Debug visible
        
        switch(format) {
            case 'csv':
                exportToCSV();
                break;
            case 'excel':
                exportToExcel();
                break;
            case 'pdf':
                exportToPDF();
                break;
            case 'print':
                window.print();
                break;
        }
        
        // Fermer le dropdown
        $(this).closest('.dropdown-menu').removeClass('show');
    });    
   
    /** */
    function exportToCSV() {
        console.log('Début de l\'export CSV...');
        console.log('Données pluginData:', pluginData); // Vérifiez que l'objet existe
        
        // Vérification que pluginData est disponible
        if (typeof pluginData === 'undefined') {
            console.error('pluginData non défini');
            alert('Erreur de configuration');
            return;
        }
    
        $.ajax({
            url: pluginData.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_participants_for_export',
                security: pluginData.nonce // Utilisation du nonce localisé
            },
            success: function(response) {
                if (response.success) {
                    const csvData = convertToCSV(response.data);
                    downloadCSV(csvData, 'participants_' + new Date().toISOString().slice(0, 10) + '.csv');
                } else {
                    console.error('Erreur serveur:', response);
                    alert('Erreur: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX complète:', {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                    readyState: xhr.readyState,
                    statusText: xhr.statusText
                });
                alert('Erreur technique (voir console)');
            }
        });
    }

    // Fonction de conversion JSON vers CSV
    function exportToCSV() {
        console.group('Export Debug');
        
        try {
            // 1. Validation des prérequis
            if (typeof pluginData === 'undefined') {
                throw new Error('Plugin configuration missing');
            }

            console.log('AJAX URL:', pluginData.ajaxurl);
            console.log('Nonce:', pluginData.nonce);

            // 2. Configuration AJAX
            $.ajax({
                url: pluginData.ajaxurl,
                type: 'POST',
                dataType: 'json',
                timeout: 30000,
                data: {
                    action: 'get_participants_for_export',
                    security: pluginData.nonce
                },
                success: function(response, status, xhr) {
                    console.log('Server Response:', response);
                    
                    if (!response) {
                        throw new Error('Empty server response');
                    }

                    if (response.success) {
                        const csvData = convertToCSV(response.data);
                        downloadCSV(csvData, 'participants_' + new Date().toISOString().slice(0,10) + '.csv');
                    } else {
                        throw new Error(response.data || 'Unknown server error');
                    }
                },
                error: function(xhr, status, error) {
                    let msg = `AJAX Error (${xhr.status}): `;
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        msg += response.message || error;
                    } catch (e) {
                        msg += error;
                    }
                    
                    throw new Error(msg);
                }
            });
            
        } catch (error) {
            console.error('Export Failed:', error);
            alert('Export Error: ' + error.message);
            
        } finally {
            console.groupEnd();
        }
    }

    // Fonction de téléchargement
    function downloadCSV(csvData, filename) {
        const blob = new Blob(["\uFEFF" + csvData], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});