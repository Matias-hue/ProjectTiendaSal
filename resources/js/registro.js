document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Registros cargado');

    const searchInput = document.getElementById('search-registro');
    const tableBody = document.querySelector('.table tbody');
    const pagination = document.querySelector('.pagination');
    let timeout;

    function updateTable(url = '/registro') {
        console.log('Fetching URL:', url);
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error HTTP: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = '';
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="empty">No hay registros disponibles.</td></tr>';
                pagination.innerHTML = '';
                return;
            }
            data.data.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${log.id}</td>
                    <td>${log.user ? log.user.name : 'N/A'}</td>
                    <td>${log.action.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</td>
                    <td>${log.description}</td>
                    <td>${new Date(log.created_at).toLocaleString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                `;
                tableBody.appendChild(row);
            });
            pagination.innerHTML = data.links;
            bindPaginationLinks();
        })
        .catch(error => {
            console.error('Error fetching logs:', error);
            tableBody.innerHTML = '<tr><td colspan="5" class="empty">Error al cargar los registros.</td></tr>';
        });
    }

    function bindPaginationLinks() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                updateTable(url);
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const search = this.value.trim();
                const url = search ? `/registro?search=${encodeURIComponent(search)}` : '/registro';
                updateTable(url);
            }, 300);
        });
    }

    updateTable();
});