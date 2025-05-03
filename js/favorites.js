document.addEventListener('DOMContentLoaded', function() {
    // Add click event listeners to all favorite buttons
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const itemId = this.dataset.itemId;
            const itemType = this.dataset.type;
            toggleFavorite(itemId, itemType, this);
        });
    });
});

function toggleFavorite(itemId, type, button) {
    fetch('ajax/toggle_favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&type=${type}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Toggle the active class on the button
            button.classList.toggle('active');
            
            // Update the icon and text
            const icon = button.querySelector('i');
            if (button.classList.contains('active')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                button.querySelector('span').textContent = 'إزالة من المفضلة';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                button.querySelector('span').textContent = 'إضافة إلى المفضلة';
            }

            // Show notification
            const message = button.classList.contains('active') ? 
                'تمت الإضافة إلى المفضلة بنجاح' : 
                'تمت الإزالة من المفضلة بنجاح';
            showNotification(message, 'success');
        } else {
            showNotification(data.message || 'عذراً، حدث خطأ. يرجى المحاولة مرة أخرى', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('عذراً، حدث خطأ في الاتصال بالخادم', 'error');
    });
}

function showNotification(message, type) {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Trigger fade in
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
} 