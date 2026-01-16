<?php
/* Template Name: Interface Coach */
?>

<div class="liste_participants">
    <div class="header_mf">
        <h1>Liste des Coachs</h1>
    </div>
    <div class="section_files">
        <div class="header-list">
            <div class="search-container"> 
                <input type="text" class="search-input" placeholder="Recherche...">
                <span class="search-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.0206 2.05941C9.27458 -0.68647 4.80516 -0.68647 2.0591 2.05941C-0.686366 4.80588 -0.686366 9.27441 2.0591 12.0209C4.50454 14.4656 8.31319 14.7275 11.0575 12.8185C11.1152 13.0917 11.2474 13.3525 11.4599 13.565L15.459 17.5638C16.0418 18.1454 16.9835 18.1454 17.5634 17.5638C18.1455 16.9817 18.1455 16.04 17.5634 15.4596L13.5642 11.4596C13.3529 11.2488 13.0916 11.1161 12.8183 11.0584C14.7286 8.31368 14.4667 4.50588 12.0206 2.05941ZM10.758 10.7584C8.70786 12.8084 5.37128 12.8084 3.3217 10.7584C1.27272 8.70833 1.27272 5.37256 3.3217 3.32253C5.37128 1.27309 8.70786 1.27309 10.758 3.32253C12.8082 5.37256 12.8082 8.70833 10.758 10.7584Z" fill="#303030"/>
                    </svg>
                </span>
            </div>

            <div class="controls">
                <div class="dropdown" style="display: none">
                    <button class="btn btn-outline dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.7411 0.244651L16.7916 5.55651C16.9252 5.69746 17 5.88472 17 6.08004L17 15.3315C17 17.8626 14.9553 20 12.5337 20H4.57002C2.00686 20 0 17.9492 0 15.3315L0 12.8085C0 12.3916 0.334975 12.0534 0.747713 12.0534C1.16045 12.0534 1.49543 12.3916 1.49543 12.8085L1.49543 15.3315C1.49543 17.1316 2.81639 18.4898 4.57002 18.4898H12.5337C14.1447 18.4898 15.5046 17.043 15.5046 15.3315V6.38309L11.6873 2.36798V3.68487C11.6873 4.6957 12.5018 5.52026 13.5027 5.52228C13.9154 5.52328 14.2494 5.86157 14.2484 6.27939C14.2474 6.69519 13.9134 7.03247 13.5007 7.03247H13.4997C11.6763 7.02844 10.1918 5.52731 10.1918 3.68487V1.52228H4.57002C2.90312 1.52228 1.49543 2.89554 1.49543 4.52152L1.49543 8.70375C1.49543 9.12056 1.16045 9.45885 0.747713 9.45885C0.334975 9.45885 0 9.12056 0 8.70375L0 4.52152C0 2.07702 2.0926 0.0120816 4.57002 0.0120816H10.8817C10.8913 0.0111603 10.9 0.00855304 10.9086 0.00599539C10.9188 0.00296283 10.9287 0 10.9395 0C10.9512 0 10.9616 0.00312576 10.972 0.00624229C10.9803 0.00871902 10.9885 0.0111899 10.9974 0.0120816H11.2017C11.4051 0.0120816 11.5995 0.0956456 11.7411 0.244651ZM7.36821 7.49247C7.36821 7.07565 7.70319 6.73737 8.11593 6.73737C8.52866 6.73737 8.86364 7.07565 8.86364 7.49247V12.2828L10.4159 10.7072C10.707 10.4112 11.1806 10.4112 11.4737 10.7052C11.7658 10.9991 11.7668 11.4774 11.4756 11.7734L8.64531 14.6437V14.6447C8.50972 14.7807 8.32229 14.8652 8.11593 14.8652C7.90856 14.8652 7.72213 14.7807 7.58654 14.6447V14.6437L4.7562 11.7734C4.46509 11.4774 4.46609 10.9991 4.7582 10.7052C4.90475 10.5592 5.09517 10.4857 5.28658 10.4857C5.47799 10.4857 5.66941 10.5592 5.81596 10.7072L7.36821 12.2828L7.36821 7.49247Z" fill="#04477A"/>
                        </svg>
                        Exporter
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <button type="button" class="dropdown-item export-btn" data-format="csv">CSV</button>
                        <button type="button" class="dropdown-item export-btn" data-format="excel">Excel</button>
                    </div>
                </div>
                <button class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" fill="#04477A"/>
                        <path d="M9 5.66667V12.3333M12.3333 9H5.66667M17 9C17 4.58333 13.4167 1 9 1C4.58333 1 1 4.58333 1 9C1 13.4167 4.58333 17 9 17C13.4167 17 17 13.4167 17 9Z" stroke="#F7F9FF" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    Inscription
                </button>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead class="table-header">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Région</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                <span id="page-info">Page</span>
                <select class="pagination-select" id="items-per-page">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            <div class="pagination-controls" id="pagination-controls">
                <button class="page-btn" id="first-page" disabled>‹‹</button>
                <button class="page-btn" id="prev-page" disabled>‹</button>
                <button class="page-btn active" data-page="1">1</button>
                <button class="page-btn" id="next-page">›</button>
                <button class="page-btn" id="last-page">››</button>
            </div>
        </div>
    </div>

    <!-- Popup d'inscription -->
    <div class="modal" id="coachModal" style="display: none;">
        <div class="modal-content-user">
            <div class="modal-header">
                <h2 class="modal-title">Nouveau Coach</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="CoachForm" class="CoachForm" method="POST" enctype="multipart/form-data">
                <!-- Section Photo -->
                <div class="form-section" id="photoSection">
                    <div>
                        <label for="photo">Ajouter une photo</label>
                        <div class="file-upload">
                            <svg width="48" height="49" viewBox="0 0 48 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32 32.5L24 24.5L16 32.5" stroke="blcoach_image_idack" stroke-opacity="0.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M24 24.5V42.5" stroke="black" stroke-opacity="0.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M40.7809 37.279C42.7316 36.2155 44.2726 34.5328 45.1606 32.4962C46.0487 30.4597 46.2333 28.1855 45.6853 26.0324C45.1373 23.8793 43.8879 21.97 42.1342 20.6059C40.3806 19.2418 38.2226 18.5005 36.0009 18.499H33.4809C32.8755 16.1575 31.7472 13.9837 30.1808 12.141C28.6144 10.2983 26.6506 8.83469 24.4371 7.86021C22.2236 6.88572 19.818 6.42571 17.4011 6.51476C14.9843 6.6038 12.619 7.23959 10.4833 8.37432C8.34747 9.50905 6.49672 11.1132 5.07014 13.0662C3.64356 15.0191 2.67828 17.2701 2.24686 19.6498C1.81544 22.0295 1.92911 24.4761 2.57932 26.8055C3.22954 29.135 4.39938 31.2867 6.0009 33.099" stroke="black" stroke-opacity="0.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M32 32.5L24 24.5L16 32.5" stroke="black" stroke-opacity="0.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <label for="photo" class="file-upload-label">
                                <span>SÉLECTIONNER UN FICHIER</span>
                                <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">
                            </label>
                        </div>
                    </div>
                    <div>
                        <div class="form-row1">
                            <div class="form-group">
                                <label for="nom">Nom <span>*</span></label>
                                <input type="text" id="nom" name="nom" placeholder="Nom" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom <span>*</span></label>
                                <input type="text" id="prenom" name="prenom" placeholder="Prenom" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Nom et localisation -->
                <div class="form-section">
                    <div class="row">
                        <div class="form-group">
                            <label for="region">Région <span>*</span></label>
                            <select id="region" name="region" class="form-control" required>
                                <option value="">Sélectionnez une Région</option>
                                <option value="rabat_sale_kenitra">Rabat, Salé, Kénitra</option>
                                <option value="casablanca_settat">Casablanca, Settat</option>
                                <option value="fes_meknes">Fés, Meknès</option>
                                <option value="beni_mellal_khenifra">Béni Mellal, Khenifra</option>
                                <option value="marrakech_safi">Marrakech Safi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ville_region">Ville <span>*</span></label>
                            <input type="text" id="ville_region" name="ville_region" placeholder="Ville" required>
                        </div>
                    </div>
                </div>
                <!-- Section Informations de base -->
                <div class="form-section">
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance  <span>*</span></label>
                        <input type="date" id="date_naissance" name="date_naissance" required>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="email">Email <span>*</span></label>
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone <span>*</span></label>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="adresse">Adresse domicile </label>
                            <textarea id="adresse" name="adresse" placeholder="Adresse domicile..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="genre">Genre <span>*</span></label>
                            <select id="genre" name="genre" class="form-control" required>
                                <option value="">Sélectionnez le genre</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="cin">CIN <span>*</span></label>
                            <input type="text" id="cin" name="cin"  placeholder="CIN" required>
                        </div>

                        <div class="form-group">
                            <label for="specialite">Spécialité <span>*</span></label>
                            <input type="text" id="specialite" name="specialite" placeholder="Spécialité" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group password-field">
                            <label for="password">Mot de passe <span>*</span></label>
                            <div class="password-input-container">
                                <input type="password" id="password" name="password" required>
                                <button type="button" class="generate-password" onclick="generatePassword()">Générer</button>
                                <span class="toggle-password-visibility" onclick="togglePasswordVisibility(this, 'password')">
                                   <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group password-field">
                            <label for="confirm_password">Confirmer le mot de passe <span>*</span></label>
                            <div class="password-input-container">
                                <input type="password" id="confirm_password" name="confirm_password" required>
                                <span class="toggle-password-visibility" onclick="togglePasswordVisibility(this, 'confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Description..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="cancelInscription">Annuler</button>
                    <button type="submit" class="btn btn-add submit-btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup d'affichage des détails -->
    <div class="modal" id="coachDetailsModal" style="display: none;">
        <div class="modal-content-user">
            <div class="modal-header">
                <h2 class="modal-title">Détails de Coach</h2>    
                <button class="close-btn" id="closeDetailsModal">&times;</button>
            </div>
            <div class="modal-body" id="participantDetailsContent">
                <!-- Contenu chargé dynamiquement -->
                <div class="loader">Chargement...</div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Données récupérées via PHP
    const users = <?php echo get_custom_coachs_data(); ?>;

    // Variables de pagination
    let currentPage = 1;
    let itemsPerPage = 4;
    let filteredUsers = [...users];

    // Éléments DOM
    const tableBody = document.getElementById('table-body');
    const pageInfo = document.getElementById('page-info');
    const itemsPerPageSelect = document.getElementById('items-per-page');
    const paginationControls = document.getElementById('pagination-controls');
    const searchInput = document.querySelector('.search-input');
    const coachModal = document.getElementById('coachModal');
    const CoachForm = document.getElementById('CoachForm');

    // Initialisation
    renderTableRows();
    setupEventListeners();

    function renderTableRows() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedUsers = filteredUsers.slice(startIndex, endIndex);

        tableBody.innerHTML = '';

        paginatedUsers.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><span data-id="${user.id}"></span>#${user.id}</td>
                <td>${user.nom}</td>
                <td>${user.prenom}</td>
                <td>${user.region ? user.region.replace(/_/g, ' ')
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                    .join(' ') 
                    : ''}
                </td>
                <td>${user.telephone}</td>
                <td>${user.email}</td>
                <td>${user.date}</td>
                <td class="action-buttons">
                    <button class="btn-action">Afficher</button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        updatePaginationInfo();
    }

    function setupEventListeners() {
        // Pagination
        itemsPerPageSelect.addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            renderTableRows();
        });

        // Recherche
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filteredUsers = users.filter(user =>
                user.nom.toLowerCase().includes(searchTerm) ||
                user.prenom.toLowerCase().includes(searchTerm) ||
                user.telephone.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.date.toLowerCase().includes(searchTerm)
            );
            currentPage = 1;
            renderTableRows();
        });

        
        // Écouteur pour les boutons Modifier
        document.addEventListener('click', function(e) {
            if (e.target.closest('.modifier')) {
                const button = e.target.closest('.modifier');
                const userId = button.dataset.id;
                editCoach(userId);
            }
        });
       
        function validateFile(input) {
            if (input.files.length > 0 && input.files[0].size === 0) {
                alert("Fichier invalide ou vide !");
                input.value = ''; // Reset the input
                return false;
            }
            return true;
        }
        // Fonction utilitaire pour définir les valeurs des champs
        function setFormValue(fieldName, value) {
            const element = document.querySelector(`#CoachForm [name="${fieldName}"]`);
            if (!element) return;

            // Vérifie si l'élément est un <select>
            if (element.tagName === 'SELECT') {
                // Vérifie si une option avec la valeur existe déjà
                const optionExists = Array.from(element.options).some(option => option.value === value);

                if (optionExists) {
                    element.value = value;
                } else if (value) {
                    // Si l'option n'existe pas, on l'ajoute dynamiquement (optionnel)
                    const newOption = new Option(value, value, true, true);
                    element.add(newOption);
                } else {
                    element.value = '';
                }
            } else {
                // Pour les champs <input>, <textarea>, etc.
                element.value = value || '';
            }
        }


        // 1. Ajoutez l'écouteur d'événement pour les boutons supprimer
        document.addEventListener('click', function(e) {
            if (e.target.closest('.supprimer')) {
                const button = e.target.closest('.supprimer');
                const userId = button.dataset.id;
                deleteCoach(userId);
            }
        });


        // Fonction utilitaire pour afficher des notifications
        function showNotification(message, type = 'success') {
            // Vous pouvez remplacer ceci par votre système de notification préféré
            alert(`${type.toUpperCase()}: ${message}`);
        }

        // Modal d'inscription/modification
        document.querySelector('.btn-primary').addEventListener('click', () => {
            coachModal.style.display = 'flex';
            CoachForm.reset();
            delete coachModal.dataset.editingUserId;
            document.querySelector('#coachModal h2').textContent = 'Nouveau Coach';
        });

        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('cancelInscription').addEventListener('click', closeModal);
                
        coachModal.addEventListener('click', function(e) {
            if (e.target === coachModal) {
                closeModal();
            }
        });
        
    }

    function closeModal() {
        coachModal.style.display = 'none';
        
        // Réinitialiser les deux formulaires
        document.getElementById('CoachForm').reset();
        
        // Supprimer les données d'édition
        delete document.getElementById('CoachForm').dataset.editingUserId;
        // Réinitialiser les aperçus d'image
        document.querySelectorAll('#coach_image_preview').forEach(img => {
            img.src = '';
            img.style.display = 'none';
        });
    }
    
    function updatePaginationInfo() {
        const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
        const pageInfoText = filteredUsers.length > 0
            ? `Page ${currentPage} sur ${totalPages}`
            : 'Aucun résultat';

        document.getElementById('page-info').textContent = pageInfoText;

        // Activer/désactiver les boutons de navigation
        document.getElementById('first-page').disabled = currentPage === 1;
        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = currentPage === totalPages || totalPages === 0;
        document.getElementById('last-page').disabled = currentPage === totalPages || totalPages === 0;

        // Générer les boutons de page
        generatePageButtons(totalPages);
    }

    function generatePageButtons(totalPages) {
        paginationControls.innerHTML = '';

        // Boutons de base
        const firstPageBtn = document.createElement('button');
        firstPageBtn.className = 'page-btn';
        firstPageBtn.id = 'first-page';
        firstPageBtn.innerHTML = '&laquo;&laquo;';
        firstPageBtn.disabled = currentPage === 1;
        firstPageBtn.addEventListener('click', () => {
            currentPage = 1;
            renderTableRows();
        });

        const prevPageBtn = document.createElement('button');
        prevPageBtn.className = 'page-btn';
        prevPageBtn.id = 'prev-page';
        prevPageBtn.innerHTML = '&laquo;';
        prevPageBtn.disabled = currentPage === 1;
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTableRows();
            }
        });

        paginationControls.appendChild(firstPageBtn);
        paginationControls.appendChild(prevPageBtn);

        // Boutons numérotés
        const maxVisibleButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

        if (endPage - startPage + 1 < maxVisibleButtons) {
            startPage = Math.max(1, endPage - maxVisibleButtons + 1);
        }

        if (startPage > 1) {
            const firstNumBtn = createPageButton(1);
            paginationControls.appendChild(firstNumBtn);

            if (startPage > 2) {
                const dots = document.createElement('span');
                dots.className = 'page-dots';
                dots.textContent = '...';
                paginationControls.appendChild(dots);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const btn = createPageButton(i);
            paginationControls.appendChild(btn);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dots = document.createElement('span');
                dots.className = 'page-dots';
                dots.textContent = '...';
                paginationControls.appendChild(dots);
            }

            const lastNumBtn = createPageButton(totalPages);
            paginationControls.appendChild(lastNumBtn);
        }

        // Boutons suivants
        const nextPageBtn = document.createElement('button');
        nextPageBtn.className = 'page-btn';
        nextPageBtn.id = 'next-page';
        nextPageBtn.innerHTML = '&raquo;';
        nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTableRows();
            }
        });

        const lastPageBtn = document.createElement('button');
        lastPageBtn.className = 'page-btn';
        lastPageBtn.id = 'last-page';
        lastPageBtn.innerHTML = '&raquo;&raquo;';
        lastPageBtn.disabled = currentPage === totalPages || totalPages === 0;
        lastPageBtn.addEventListener('click', () => {
            currentPage = totalPages;
            renderTableRows();
        });

        paginationControls.appendChild(nextPageBtn);
        paginationControls.appendChild(lastPageBtn);
    }

    function createPageButton(pageNumber) {
        const btn = document.createElement('button');
        btn.className = `page-btn ${pageNumber === currentPage ? 'active' : ''}`;
        btn.textContent = pageNumber;
        btn.addEventListener('click', () => {
            currentPage = pageNumber;
            renderTableRows();
        });
        return btn;
    }
});
</script>

<script>
// Initialisation des variables globales
window.ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
window.coachData = {
    nonce: '<?php echo wp_create_nonce("coach_ajax_nonce"); ?>'
};


jQuery(document).ready(function($) {
    // 1. Gestion de la popup d'affichage
    $(document).on('click', '.btn-action', function() {
        const coachId = $(this).closest('tr').find('[data-id]').data('id');
        showParticipantDetails(coachId);
    });

    function showParticipantDetails(coachId) {
        $('#coachDetailsModal').show();
        $('#participantDetailsContent').html('<div class="loader">Chargement...</div>');

        $.ajax({
            url: participantRegistration.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_participant_details',
                participant_id: coachId,
                security: participantRegistration.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#participantDetailsContent').html(response.data.html);
                } else {
                    $('#participantDetailsContent').html('<div class="error">Erreur: ' + response.data + '</div>');
                }
            },
            error: function() {
                $('#participantDetailsContent').html('<div class="error">Erreur lors du chargement</div>');
            }
        });
    }

    $('#closeDetailsModal, #coachDetailsModal').on('click', function(e) {
        if (e.target === this || e.target.id === 'closeDetailsModal') {
            $('#coachDetailsModal').hide();
        }
    });
   
});

</script>
