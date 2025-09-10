document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Registros cargado');

    const searchInput = document.getElementById('search-registro');
    const tableBody = document.querySelector('.table tbody');
    const pagination = document.querySelector('.pagination');

    function updateTable(url = '/registro') {
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar los registros');
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = data.data.length === 0
                ? '<tr><td colspan="5" class="empty">No hay registros disponibles.</td></tr>'
                : data.data.map(log => `
                    <tr>
                        <td>${log.id}</td>
                        <td>${log.user ? log.user.name : 'N/A'}</td>
                        <td>${log.action.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</td>
                        <td>${log.description}</td>
                        <td>${new Date(log.created_at).toLocaleString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    </tr>
                `).join('');
            pagination.innerHTML = data.links || '';
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    updateTable(link.href);
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = '<tr><td colspan="5" class="empty">Error al cargar los registros.</td></tr>';
        });
    }

    searchInput?.addEventListener('input', () => {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            const search = searchInput.value.trim();
            updateTable(search ? `/registro?search=${encodeURIComponent(search)}` : '/registro');
        }, 300);
    });

    updateTable();
});