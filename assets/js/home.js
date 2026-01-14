const logoutBtn = document.getElementById('logoutBtn');
const logoutForm = document.getElementById('logoutForm');

logoutBtn.addEventListener('click', () => {
    // Toggle the logout form visibility
    logoutForm.style.display = logoutForm.style.display === 'block' ? 'none' : 'block';
});

// Hide form if clicking outside
window.addEventListener('click', e => {
    if (!logoutForm.contains(e.target) && e.target !== logoutBtn) {
        logoutForm.style.display = 'none';
    }
});
