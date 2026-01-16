<div class="liste_participants">
    <div class="header_mf">
        <h1>Liste des Participants</h1>       
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
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead class="table-header">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
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
    
    <!-- Popup d'affichage des détails -->
    <div class="modal" id="participantDetailsModal" style="display: none;">
        <div class="modal-content-user">
            <div class="modal-header">
                <h2 class="modal-title">Détails de Participant</h2>                
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
    // Données récupérées via PHP
    const users = <?php echo get_all_participants_data(); ?>;

    // Variables de pagination
    let currentPage = 1;
    let itemsPerPage = 4;
    let filteredUsers = [...users];

    // Éléments DOM
    const tableBody = document.getElementById('table-body');
    const pageInfo = document.getElementById('page-info');
    const itemsPerPageSelect = document.getElementById('items-per-page');
    const paginationControls = document.getElementById('pagination-controls');
    const firstPageBtn = document.getElementById('first-page');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const lastPageBtn = document.getElementById('last-page');
    const searchInput = document.querySelector('.search-input');

    // Fonction pour générer les lignes du tableau
    function renderTableRows() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedUsers = filteredUsers.slice(startIndex, endIndex);

        tableBody.innerHTML = '';

        paginatedUsers.forEach(user => {
            const row = document.createElement('tr');                
                row.innerHTML = `
                    <td><span data-id="${user.id}"></span>#${user.id}</td>
                    <td>${user.nom} ${user.prenom}</td>                    
                    <td>${user.ville_region}</td>                                       
                    <td>${user.telephone}</td>
                    <td>${user.email}</td>
                    <td>${user.date}</td>
                    <td>${user.statut == 1 ? "Activé" : "Désactivé"}</td>
                    <td><button class="btn-action">Afficher</button></td>
                `;
                tableBody.appendChild(row);
                });
                // Mettre à jour les informations de pagination
        updatePaginationInfo();
    }

    // Fonction pour mettre à jour les informations de pagination
    function updatePaginationInfo() {
        const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);

        // Activer/désactiver les boutons de navigation
        firstPageBtn.disabled = currentPage === 1;
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
        lastPageBtn.disabled = currentPage === totalPages || totalPages === 0;

        // Générer les boutons de page
        generatePageButtons(totalPages);
    }

    // Fonction pour générer les boutons de page
    function generatePageButtons(totalPages) {
        // Réinitialiser les boutons de pagination
        paginationControls.innerHTML = '';

        // Boutons "First" et "Prev"
        paginationControls.appendChild(firstPageBtn);
        paginationControls.appendChild(prevPageBtn);

        const maxVisibleButtons = 5;
        let startPage, endPage;

        if (totalPages <= maxVisibleButtons) {
            startPage = 1;
            endPage = totalPages;
        } else {
            if (currentPage <= Math.ceil(maxVisibleButtons / 2)) {
                startPage = 1;
                endPage = maxVisibleButtons;
            } else if (currentPage >= totalPages - Math.floor(maxVisibleButtons / 2)) {
                startPage = totalPages - maxVisibleButtons + 1;
                endPage = totalPages;
            } else {
                startPage = currentPage - Math.floor(maxVisibleButtons / 2);
                endPage = currentPage + Math.floor(maxVisibleButtons / 2);
            }
        }

        if (startPage > 1) {
            const firstPage = createPageButton(1);
            paginationControls.appendChild(firstPage);
            if (startPage > 2) {
                const dots = document.createElement('span');
                dots.className = 'page-btn';
                dots.textContent = '...';
                paginationControls.appendChild(dots);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const btn = createPageButton(i, i === currentPage);
            paginationControls.appendChild(btn);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dots = document.createElement('span');
                dots.className = 'page-btn';
                dots.textContent = '...';
                paginationControls.appendChild(dots);
            }
            const lastPage = createPageButton(totalPages);
            paginationControls.appendChild(lastPage);
        }

        // Boutons "Next" et "Last"
        paginationControls.appendChild(nextPageBtn);
        paginationControls.appendChild(lastPageBtn);
    }

    // Fonction utilitaire pour créer un bouton de page
    function createPageButton(pageNumber, isActive = false) {
        const btn = document.createElement('button');
        btn.className = 'page-btn' + (isActive ? ' active' : '');
        btn.dataset.page = pageNumber;
        btn.textContent = pageNumber;
        btn.addEventListener('click', () => {
            currentPage = pageNumber;
            renderTableRows();
        });
        return btn;
    }

    // Fonction pour ajouter les écouteurs d'événements aux boutons de pagination
    function addPaginationEventListeners() {
        document.querySelectorAll('.page-btn').forEach(btn => {
            if (btn.textContent && !isNaN(btn.textContent)) {
                btn.addEventListener('click', () => {
                    currentPage = parseInt(btn.dataset.page);
                    renderTableRows();
                });
            }
        });

        // Boutons de navigation
        document.getElementById('first-page')?.addEventListener('click', () => {
            currentPage = 1;
            renderTableRows();
        });

        document.getElementById('prev-page')?.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTableRows();
            }
        });

        document.getElementById('next-page')?.addEventListener('click', () => {
            const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTableRows();
            }
        });

        document.getElementById('last-page')?.addEventListener('click', () => {
            const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
            currentPage = totalPages;
            renderTableRows();
        });
    }

    // Fonctionnalité de recherche
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        if (searchTerm === '') {
            filteredUsers = [...users];
        } else {
            filteredUsers = users.filter(user =>
                user.nom.toLowerCase().includes(searchTerm) ||
                user.prenom.toLowerCase().includes(searchTerm) ||
                user.telephone.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.date.toLowerCase().includes(searchTerm)  ||
                user.ville_region.toLowerCase().includes(searchTerm)
            );
        }

        currentPage = 1; // Réinitialiser à la première page après une recherche
        renderTableRows();
    });

    // Changement du nombre d'éléments par page
    itemsPerPageSelect.addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1; // Réinitialiser à la première page
        renderTableRows();
    });

    // Fonction de filtrage selon la recherche
    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();

        filteredUsers = users.filter(user => {
            return (
                user.nom.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.ville_region.toLowerCase().includes(searchTerm)
            );
        });

        currentPage = 1; // Revenir à la première page après recherche
        renderTableRows();
    });

    jQuery(document).ready(function($) {
        // 1. Gestion de la popup d'affichage
        $(document).on('click', '.btn-action', function() {
            const participantId = $(this).closest('tr').find('[data-id]').data('id');
            showParticipantDetails(participantId);
        });

        function showParticipantDetails(participantId) {
            $('#participantDetailsModal').show();
            $('#participantDetailsContent').html('<div class="loader">Chargement...</div>');

            $.ajax({
                url: participantRegistration.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_participant_details',
                    participant_id: participantId,
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

        $('#closeDetailsModal, #participantDetailsModal').on('click', function(e) {
            if (e.target === this || e.target.id === 'closeDetailsModal') {
                $('#participantDetailsModal').hide();
            }
        });

        $(document).on('keyup', function(e) {
            if (e.key === "Escape") {
                $('#participantDetailsModal').hide();
                $('.dropdown-menu').removeClass('show');
            }
        });

        // 2. Gestion du menu déroulant Export
        $('#exportDropdown').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.dropdown-menu').toggleClass('show');
        });

        $(document).on('click', function() {
            $('.dropdown-menu').removeClass('show');
        });                 
        
        function exportToExcel() {
            console.log('Export Excel...');
        }
        
        function exportToPDF() {
            console.log('Export PDF...');
        }
    });
        
    // Initialisation
    renderTableRows();
    addPaginationEventListeners();
</script>