document.addEventListener('DOMContentLoaded', () => {    
    //togglePassword.addEventListener('click', function() {
        // Basculer entre type password et text
        // const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        // password.setAttribute('type', type);
        
        // // Changer l'icône œil (optionnel)
        // this.textContent = type === 'password' ? ' <span class="eye-icon toggle-password">&#128065;
        // </span>' : '<span class="eye-icon toggle-password">&#128064;</span>';
      
        // const input = document.querySelector('#password');
        // if (input.type === 'password') {
        //     input.type = 'text';
        //     this.textContent = '👁️‍🗨️'; 
        // } else {
        //     input.type = 'password';
        //     this.textContent = '👁️';
        // }
   // });
 
    // Gestion des boutons de téléchargement et de visualisation
    const dropdownButtons = document.querySelectorAll('.options-button');

    dropdownButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            // Prevent the click from propagating to the document and closing immediately
            event.stopPropagation();

            // Close any other open dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                if (openMenu !== button.nextElementSibling) { // Don't close the current one
                    openMenu.classList.remove('show');
                    openMenu.previousElementSibling.setAttribute('aria-expanded', 'false'); // Update ARIA
                }
            });

            // Toggle the 'show' class on the next sibling (the dropdown menu)
            const dropdownMenu = button.nextElementSibling;
            dropdownMenu.classList.toggle('show');
            // Update ARIA attribute for accessibility
            button.setAttribute('aria-expanded', dropdownMenu.classList.contains('show'));
        });
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.dropdown-container')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
                // Find the corresponding button and update its ARIA
                const button = menu.previousElementSibling;
                if (button && button.classList.contains('options-button')) {
                    button.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });

    // Optional: Handle clicks on dropdown items
    document.querySelectorAll('.dropdown-menu li a').forEach(item => {
        item.addEventListener('click', (event) => {
            // In a real application, the href attribute of the <a> tag will handle the download/view.
            // You might still want to do something like analytics here.
            const action = item.textContent;
            const cardTitle = item.closest('.file-card').querySelector('.file-title').textContent;
            console.log(`Action "${action}" triggered for file: "${cardTitle.trim().replace(/\.+$/, '')}"`);
            
            // Close the dropdown after an item is clicked
            item.closest('.dropdown-menu').classList.remove('show');
            const relatedButton = item.closest('.dropdown-container').querySelector('.options-button');
            if (relatedButton) {
                relatedButton.setAttribute('aria-expanded', 'false');
            }
        });
    });

    
});

document.addEventListener('DOMContentLoaded', function() {
    const monthYear = document.getElementById("monthYear");
    const daysContainer = document.getElementById("daysContainer");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    
    let currentDate = new Date();
    
    // Jours avec événements (exemple)
    const eventsData = {
        '2024-09-08': true,
        '2024-09-15': true,
        '2024-09-22': true
    };
    
    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const today = new Date();
        
        // Premier jour du mois et nombre de jours
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        
        // Affichage mois/année
        const options = { month: 'long', year: 'numeric' };
        monthYear.textContent = date.toLocaleDateString('fr-FR', options);
        
        // Vider les anciens jours
        daysContainer.innerHTML = "";
        
        // Ajouter les derniers jours du mois précédent
        for (let i = firstDay - 1; i >= 0; i--) {
            const dayCell = document.createElement("div");
            dayCell.className = "day-cell other-month";
            dayCell.textContent = daysInPrevMonth - i;
            daysContainer.appendChild(dayCell);
        }
        
        // Ajouter les jours du mois actuel
        for (let i = 1; i <= daysInMonth; i++) {
            const dayCell = document.createElement("div");
            dayCell.className = "day-cell";
            dayCell.textContent = i;
            
            // Vérifier si c'est aujourd'hui
            if (year === today.getFullYear() && 
                month === today.getMonth() && 
                i === today.getDate()) {
                dayCell.classList.add("today");
            }
            
            // Vérifier s'il y a un événement
            const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            if (eventsData[dateKey]) {
                dayCell.classList.add("has-event");
            }
            
            // Ajouter l'événement de clic
            dayCell.addEventListener('click', function() {
                // Retirer la sélection précédente
                document.querySelectorAll('.day-cell.selected').forEach(cell => {
                    cell.classList.remove('selected');
                });
                
                // Ajouter la sélection si ce n'est pas aujourd'hui
                if (!this.classList.contains('today') && !this.classList.contains('other-month')) {
                    this.classList.add('selected');
                }
            });
            
            daysContainer.appendChild(dayCell);
        }
        
        // Ajouter les premiers jours du mois suivant pour compléter la grille
        const totalCells = daysContainer.children.length;
        const remainingCells = 42 - totalCells; // 6 semaines * 7 jours
        
        for (let i = 1; i <= remainingCells && i <= 14; i++) {
            const dayCell = document.createElement("div");
            dayCell.className = "day-cell other-month";
            dayCell.textContent = i;
            daysContainer.appendChild(dayCell);
        }
    }
    
    // Événements de navigation
    prevBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });
    
    nextBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });
    
    // Initialisation
    renderCalendar(currentDate);

    
});


// Password generation and visibility toggle functionality
function generatePassword() {
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
    let password = "";
    
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    
    document.getElementById('password').value = password;
    document.getElementById('confirm_password').value = password;
}

function togglePasswordVisibility(iconElement, fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
        iconElement.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        field.type = "password";
        iconElement.innerHTML = '<i class="fas fa-eye"></i>';
    }
}