document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-registro');
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const search = e.target.value;
            window.location.href = '/registro?search=' + encodeURIComponent(search);
        }
    });
});