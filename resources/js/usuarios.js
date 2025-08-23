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
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No se encontraron usuarios</td></tr>';
                return;
            }
            data.data.forEach(usuario => {
                const tr = document.createElement('tr');

                const tdId = document.createElement('td');
                tdId.textContent = usuario.id;
                tr.appendChild(tdId);

                const tdName = document.createElement('td');
                tdName.textContent = usuario.name || 'No disponible';
                tr.appendChild(tdName);

                const tdEmail = document.createElement('td');
                tdEmail.textContent = usuario.email || 'No disponible';
                tr.appendChild(tdEmail);

                const tdPhone = document.createElement('td');
                tdPhone.textContent = usuario.phone || 'No disponible';
                tr.appendChild(tdPhone);

                const tdAddress = document.createElement('td');
                tdAddress.textContent = usuario.address || 'No disponible';
                tr.appendChild(tdAddress);

                const tdRole = document.createElement('td');
                tdRole.textContent = usuario.role || 'Usuario';
                tr.appendChild(tdRole);

                const tdAcciones = document.createElement('td');
                tdAcciones.classList.add('action-cell');
                tdAcciones.innerHTML = `
                    <a href="/usuarios/${usuario.id}/edit" class="btn-editar" aria-label="Editar usuario ${usuario.name || 'No disponible'}">Editar</a>
                    <button class="btn-eliminar" data-user-id="${usuario.id}" data-user-name="${usuario.name || 'No disponible'}" aria-label="Eliminar usuario ${usuario.name || 'No disponible'}">Eliminar</button>
                `;
                tr.appendChild(tdAcciones);

                tbody.appendChild(tr);
            });

            paginationContainer.innerHTML = data.links || '';

            const newUrl = search ? `${usuariosIndexUrl}?search=${encodeURIComponent(search)}` : usuariosIndexUrl;
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Error fetching users:', error);
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Error al cargar usuarios</td></tr>';
        });
    }

    // Manejador global para los botones de eliminación
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-eliminar')) {
            e.preventDefault();
            const userId = e.target.getAttribute('data-user-id');
            const userName = e.target.getAttribute('data-user-name');

            console.log('Botón eliminar clickeado:', { userId, userName }); // Depuración

            let dialog = document.getElementById('delete-user-dialog');
            if (!dialog) {
                dialog = document.createElement('dialog');
                dialog.id = 'delete-user-dialog';
                document.body.appendChild(dialog);
                console.log('Diálogo creado'); // Depuración
            }
            dialog.innerHTML = `
                <div class="dialog-content">
                    <p id="mensaje-eliminar">¿Estás seguro de eliminar al usuario "${userName}"?</p>
                    <div class="dialog-actions">
                        <button class="btn-dialog-si">Sí</button>
                        <button class="btn-dialog-no">No</button>
                    </div>
                </div>
            `;

            try {
                dialog.showModal();
                console.log('Diálogo mostrado'); // Depuración
            } catch (error) {
                console.error('Error al mostrar el diálogo:', error);
            }

            dialog.querySelector('.btn-dialog-si').addEventListener('click', function () {
                console.log('Confirmado eliminación para usuario:', userId); // Depuración
                const form = document.createElement('form');
                form.action = `/usuarios/${userId}`;
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
                dialog.close();
            });

            dialog.querySelector('.btn-dialog-no').addEventListener('click', function () {
                console.log('Eliminación cancelada'); // Depuración
                dialog.close();
            });
        }
    });

    // Deshabilitar botón al enviar el formulario de creación
    const createForm = document.querySelector('.create-user-form');
    const submitButton = createForm?.querySelector('.btn-agregar');
    if (createForm && submitButton) {
        createForm.addEventListener('submit', function () {
            submitButton.disabled = true;
            submitButton.textContent = 'Creando...';
        });
    }
});