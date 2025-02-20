// Fonction pour afficher une notification
function showNotification(type, message) {
    const notificationContainer = document.getElementById('notification-container');

    const notification = document.createElement('div');
    notification.classList.add('notification', type);  // Ajouter les classes en fonction du type (success, error, etc.)

    notification.innerHTML = `
        <span class="notification-message">${message}</span>
        <button class="close-button" onclick="closeNotification(this)">×</button>
    `;

    notificationContainer.appendChild(notification);

    // Fermer la notification après 5 secondes
    setTimeout(() => {
        closeNotification(notification);
    }, 5000);
}

// Fonction pour fermer la notification
function closeNotification(notification) {
    notification.remove();
}