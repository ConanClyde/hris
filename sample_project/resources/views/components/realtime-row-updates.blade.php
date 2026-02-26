<script>
/**
 * Granular Realtime Update Handler
 * Updates specific table rows in-place when receiving broadcast events
 */
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo === 'undefined') {
        console.log('Echo not loaded');
        return;
    }

    // Listen to leave status updates
    window.Echo.channel('leave.management')
        .listen('.leave.status.updated', function(e) {
            updateLeaveRow(e);
        });

    // Listen to training status updates  
    window.Echo.channel('training.management')
        .listen('.training.status.updated', function(e) {
            updateTrainingRow(e);
        });

    function updateLeaveRow(data) {
        // Find row by data attribute
        const rows = document.querySelectorAll('tr[data-leave-id]');
        rows.forEach(row => {
            if (row.dataset.leaveId == data.id) {
                // Update status badge
                const statusBadge = row.querySelector('.leave-status-badge');
                if (statusBadge) {
                    updateStatusBadge(statusBadge, data.status);
                }
                
                // Update other fields
                if (data.type) {
                    const typeCell = row.querySelector('.leave-type');
                    if (typeCell) typeCell.textContent = data.type;
                }
                
                if (data.dateFrom) {
                    const dateCell = row.querySelector('.leave-date');
                    if (dateCell) dateCell.textContent = formatDate(data.dateFrom);
                }
                
                if (data.totalDays) {
                    const daysCell = row.querySelector('.leave-days');
                    if (daysCell) daysCell.textContent = data.totalDays + ' day' + (data.totalDays > 1 ? 's' : '');
                }
                
                // Add highlight effect
                row.classList.add('bg-blue-50', 'dark:bg-blue-900/20');
                setTimeout(() => {
                    row.classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                }, 2000);
            }
        });
    }

    function updateTrainingRow(data) {
        const rows = document.querySelectorAll('tr[data-training-id]');
        rows.forEach(row => {
            if (row.dataset.trainingId == data.id) {
                // Update status badge
                const statusBadge = row.querySelector('.training-status-badge');
                if (statusBadge) {
                    updateStatusBadge(statusBadge, data.status);
                }
                
                // Update other fields
                if (data.title) {
                    const titleCell = row.querySelector('.training-title');
                    if (titleCell) titleCell.textContent = data.title;
                }
                
                if (data.dateFrom) {
                    const dateCell = row.querySelector('.training-date');
                    if (dateCell) dateCell.textContent = formatDate(data.dateFrom);
                }
                
                if (data.hours) {
                    const hoursCell = row.querySelector('.training-hours');
                    if (hoursCell) hoursCell.textContent = data.hours + ' hrs';
                }
                
                // Add highlight effect
                row.classList.add('bg-blue-50', 'dark:bg-blue-900/20');
                setTimeout(() => {
                    row.classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                }, 2000);
            }
        });
    }

    function updateStatusBadge(element, status) {
        // Remove all status classes
        element.classList.remove(
            'bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-900/30', 'dark:text-emerald-300',
            'bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-300',
            'bg-amber-100', 'text-amber-800', 'dark:bg-amber-900/30', 'dark:text-amber-300'
        );
        
        // Add appropriate classes based on status
        const statusLower = (status || '').toLowerCase();
        if (statusLower === 'approved') {
            element.classList.add('bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-900/30', 'dark:text-emerald-300');
        } else if (statusLower === 'rejected') {
            element.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-300');
        } else {
            element.classList.add('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900/30', 'dark:text-amber-300');
        }
        
        element.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : 'Pending';
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
});

// Alternative: Livewire-style DOM events for Alpine.js integration
document.addEventListener('leave-status-updated', function(e) {
    if (e.detail && typeof window.updateLeaveRow === 'function') {
        window.updateLeaveRow(e.detail);
    }
});

document.addEventListener('training-status-updated', function(e) {
    if (e.detail && typeof window.updateTrainingRow === 'function') {
        window.updateTrainingRow(e.detail);
    }
});
</script>
