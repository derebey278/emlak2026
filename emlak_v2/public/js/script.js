/**
 * Emlak Yönetim Sistemi - Main JavaScript
 */

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Application initialized');
    initializeEventListeners();
});

/**
 * Initialize event listeners
 */
function initializeEventListeners() {
    // Toggle favorites
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', toggleFavorite);
    });
    
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
}

/**
 * Toggle favorite listing
 */
function toggleFavorite(e) {
    e.preventDefault();
    const listingId = this.dataset.listingId;
    const btn = this;
    
    fetch('api/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ listing_id: listingId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.classList.toggle('favorited');
        }
    })
    .catch(error => console.error('Error:', error));
}

/**
 * Format price
 */
function formatPrice(price) {
    return '₺ ' + parseFloat(price).toLocaleString('tr-TR', {minimumFractionDigits: 2});
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.insertBefore(alertDiv, document.querySelector('main'));
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

/**
 * Load listings
 */
function loadListings(filters = {}) {
    const params = new URLSearchParams(filters);
    
    fetch(`api/listings?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayListings(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

/**
 * Display listings
 */
function displayListings(listings) {
    const container = document.getElementById('listings-container');
    
    if (!container) return;
    
    container.innerHTML = '';
    
    if (listings.length === 0) {
        container.innerHTML = '<div class="alert alert-info">İlan bulunamadı</div>';
        return;
    }
    
    listings.forEach(listing => {
        const card = createListingCard(listing);
        container.appendChild(card);
    });
}

/**
 * Create listing card element
 */
function createListingCard(listing) {
    const card = document.createElement('div');
    card.className = 'col-md-6 col-lg-4 mb-4';
    
    const imageUrl = listing.image || 'placeholder.jpg';
    const isFavorited = listing.is_favorited ? 'favorited' : '';
    
    card.innerHTML = `
        <div class="listing-card">
            <div class="position-relative">
                <img src="${imageUrl}" alt="${listing.title}" class="listing-image">
                <span class="listing-badge">${listing.purpose === 'rent' ? 'KİRALIK' : 'SATIŞLIK'}</span>
                <button class="favorite-btn ${isFavorited}" data-listing-id="${listing.id}" title="Favoriye Ekle">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            
            <div class="p-3">
                <h5 class="card-title truncate">${listing.title}</h5>
                <p class="listing-price">${formatPrice(listing.price)}</p>
                
                <div class="listing-details">
                    <span><i class="fas fa-map-marker-alt"></i> ${listing.city}</span>
                    <span><i class="fas fa-home"></i> ${listing.rooms || 'N/A'} Oda</span>
                    <span><i class="fas fa-ruler"></i> ${listing.area || 'N/A'} m²</span>
                </div>
                
                <a href="listing/${listing.id}" class="btn btn-primary btn-sm mt-3 w-100">
                    Detayları Gör
                </a>
            </div>
        </div>
    `;
    
    return card;
}

/**
 * Validate form
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const formData = new FormData(form);
    const errors = [];
    
    // Check required fields
    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            errors.push(`${field.name} alanı zorunludur`);
        }
    });
    
    if (errors.length > 0) {
        errors.forEach(error => showNotification(error, 'danger'));
        return false;
    }
    
    return true;
}

/**
 * Submit form via AJAX
 */
function submitFormAjax(formId, endpoint) {
    if (!validateForm(formId)) return false;
    
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    
    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'İşlem başarılı', 'success');
            if (data.redirect) {
                setTimeout(() => window.location.href = data.redirect, 1000);
            }
        } else {
            showNotification(data.message || 'Hata oluştu', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('İşlem sırasında hata oluştu', 'danger');
    });
    
    return false;
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('tr-TR', options);
}

/**
 * Calculate days until date
 */
function daysUntil(dateString) {
    const today = new Date();
    const target = new Date(dateString);
    const difference = target - today;
    return Math.ceil(difference / (1000 * 60 * 60 * 24));
}