document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript de Usuarios cargado');

    // Busqueda en tiempo real
    const searchInput = document.getElementById('search');
    const tbody = document.getElementById('usuarios-table-body');
    const usuariosIndexUrl = '/usuarios';

    if (searchInput) {
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = searchInput.value.trim();
                fetch(`${usuariosIndexUrl}?search=` + encodeURIComponent(query), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">No se encontraron usuarios</td></tr>';
                        return;
                    }
                    data.forEach(usuario => {
                        const tr = document.createElement('tr');

                        const tdId = document.createElement('td');
                        tdId.textContent = usuario.id;
                        tr.appendChild(tdId);

                        const tdName = document.createElement('td');
                        tdName.textContent = usuario.name;
                        tr.appendChild(tdName);

                        const tdRole = document.createElement('td');
                        tdRole.textContent = usuario.role ? usuario.role : 'Usuario';
                        tr.appendChild(tdRole);

                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error fetching users: ', error);
                });
            }, 300);
        });
    }
});