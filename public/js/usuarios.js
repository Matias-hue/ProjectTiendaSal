document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Usuarios cargado');

    const searchInput = document.getElementById('search');
    const tbody = document.getElementById('usuarios-table-body');
    const paginationContainer = document.querySelector('.pagination');
    const usuariosIndexUrl = '/usuarios';
    const form = searchInput?.form;

    let timeout = null;
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = searchInput.value.trim();
                fetchUsuarios(query);
            }, 300);
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            fetchUsuarios(query);
        });
    }

    if (paginationContainer) {
        paginationContainer.addEventListener('click', function (e) {
            e.preventDefault();
            const target = e.target.closest('.page-link');
            if (target && !target.parentElement.classList.contains('disabled')) {
                const url = target.getAttribute('href');
                fetchUsuarios(null, url);
            }
        });
    }

    function fetchUsuarios(search = '', url = null) {
        const fetchUrl = url || `${usuariosIndexUrl}?search=${encodeURIComponent(search)}`;

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';
            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No se encontraron usuarios</td></tr>';
                return;
            }
            data.data.forEach(usuario => {
                const tr = document.createElement('tr');

                const tdId = document.createElement('td');
                tdId.textContent = usuario.id;
                tr.appendChild(tdId);

                const tdName = document.createElement('td');
                tdName.textContent = usuario.name;
                tr.appendChild(tdName);

                const tdRole = document.createElement('td');
                tdRole.textContent = usuario.role || 'Usuario';
                tr.appendChild(tdRole);

                const tdAcciones = document.createElement('td');
                tdAcciones.innerHTML = `<a href="/usuarios/${usuario.id}/edit" class="btn-editar" aria-label="Editar usuario ${usuario.name}">Editar</a>`;
                tr.appendChild(tdAcciones);

                tbody.appendChild(tr);
            });

            paginationContainer.innerHTML = data.links || '';

            const newUrl = search ? `${usuariosIndexUrl}?search=${encodeURIComponent(search)}` : usuariosIndexUrl;
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Error fetching users:', error);
            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Error al cargar usuarios</td></tr>';
        });
    }
});