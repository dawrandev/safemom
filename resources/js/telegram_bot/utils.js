/**
 * Format date to readable string
 * @param {Date} date - Date to format
 * @returns {string} Formatted date string
 */
export function formatDate(date = new Date()) {
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    return date.toLocaleDateString('id-ID', options);
}

/**
 * Format time to HH:MM:SS
 * @param {number} seconds - Time in seconds
 * @returns {string} Formatted time string
 */
export function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;

    return [
        hours.toString().padStart(2, '0'),
        minutes.toString().padStart(2, '0'),
        secs.toString().padStart(2, '0')
    ].join(':');
}

/**
 * Get greeting based on time of day
 * @returns {string} Greeting message
 */
export function getGreeting() {
    const hour = new Date().getHours();

    if (hour < 12) {
        return 'Selamat Pagi';
    } else if (hour < 15) {
        return 'Selamat Siang';
    } else if (hour < 18) {
        return 'Selamat Sore';
    } else {
        return 'Selamat Malam';
    }
}

/**
 * Debounce function to limit execution rate
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} Debounced function
 */
export function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Show loading state
 * @param {HTMLElement} element - Element to show loading state
 * @param {boolean} isLoading - Loading state
 */
export function setLoadingState(element, isLoading) {
    if (isLoading) {
        element.classList.add('opacity-50', 'pointer-events-none');
    } else {
        element.classList.remove('opacity-50', 'pointer-events-none');
    }
}
