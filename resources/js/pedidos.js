document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Pedidos cargado');

    const dialogCompletar = document.getElementById('dialog-completar');
    const dialogCancelar = document.getElementById('dialog-cancelar');
    const dialogError = document.getElementById('dialog-error');
    const dialogSuccess = document.getElementById('dialog-success');
    const dialogDetails = document.getElementById('detailsModal');
    const misPedidosDialogDetails = document.getElementById('mis-pedidos-details-modal');
    const btnCerrarCompletar = document.getElementById('btn-cerrar-completar');
    const btnCerrarCancelar = document.getElementById('btn-cerrar-cancelar');
    const btnCerrarError = document.getElementById('btn-cerrar-error');
    const btnCerrarSuccess = document.getElementById('btn-cerrar-success');
    const btnConfirmarCompletar = document.getElementById('btn-confirmar-completar');
    const btnConfirmarCancelar = document.getElementById('btn-confirmar-cancelar');
    const mensajeError = document.getElementById('mensaje-error');
    const mensajeSuccess = document.getElementById('mensaje-success');
    const userSearch = document.getElementById('user_search');
    const userIdInput = document.getElementById('user_id');
    const userSuggestions = document.getElementById('user-suggestions');
    const orderSearch = document.getElementById('order-search');
    const orderSuggestions = document.getElementById('order-suggestions');

    let currentOrderId;
    let currentRow;

    function mostrarError(mensaje) {
        if (mensajeError && dialogError) {
            mensajeError.textContent = mensaje;
            dialogError.showModal();
        }
    }

    function mostrarExito(mensaje) {
        if (mensajeSuccess && dialogSuccess) {
            mensajeSuccess.textContent = mensaje;
            dialogSuccess.showModal();
        }
    }

    // Búsqueda de usuarios
    if (userSearch && userSuggestions) {
        let timeout = null;
        userSearch.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = userSearch.value.trim();
                if (query.length < 2) {
                    userSuggestions.innerHTML = '';
                    userSuggestions.style.display = 'none';
                    return;
                }
                fetch(`/usuarios?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    userSuggestions.innerHTML = '';
                    if (data.data.length === 0) {
                        userSuggestions.innerHTML = '<div class="suggestion-item">No se encontraron usuarios</div>';
                        userSuggestions.style.display = 'block';
                        return;
                    }
                    data.data.forEach(usuario => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = `${usuario.name || 'No disponible'} (${usuario.email})`;
                        div.dataset.userId = usuario.id;
                        div.addEventListener('click', () => {
                            userSearch.value = `${usuario.name || 'No disponible'} (${usuario.email})`;
                            userIdInput.value = usuario.id;
                            userSuggestions.innerHTML = '';
                            userSuggestions.style.display = 'none';
                        });
                        userSuggestions.appendChild(div);
                    });
                    userSuggestions.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                    userSuggestions.innerHTML = '<div class="suggestion-item">Error al buscar usuarios</div>';
                    userSuggestions.style.display = 'block';
                });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!userSearch.contains(e.target) && !userSuggestions.contains(e.target)) {
                userSuggestions.innerHTML = '';
                userSuggestions.style.display = 'none';
            }
        });
    }

    // Búsqueda de pedidos
    if (orderSearch && orderSuggestions) {
        let timeout = null;
        orderSearch.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = orderSearch.value.trim();
                if (query.length < 2) {
                    orderSuggestions.innerHTML = '';
                    orderSuggestions.style.display = 'none';
                    updateTable('/pedidos');
                    return;
                }
                fetch(`/pedidos?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    orderSuggestions.innerHTML = '';
                    if (data.data.length === 0) {
                        orderSuggestions.innerHTML = '<div class="suggestion-item">No se encontraron pedidos</div>';
                        orderSuggestions.style.display = 'block';
                        updateTable(`/pedidos?search=${encodeURIComponent(query)}`);
                        return;
                    }
                    data.data.forEach(pedido => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = `Pedido #${pedido.id} - ${pedido.user.name} (${pedido.user.email})`;
                        div.dataset.orderId = pedido.id;
                        div.addEventListener('click', () => {
                            orderSearch.value = `Pedido #${pedido.id} - ${pedido.user.name} (${pedido.user.email})`;
                            orderSuggestions.innerHTML = '';
                            orderSuggestions.style.display = 'none';
                            updateTable(`/pedidos?search=${encodeURIComponent(pedido.user.name)}`);
                        });
                        orderSuggestions.appendChild(div);
                    });
                    orderSuggestions.style.display = 'block';
                    updateTable(`/pedidos?search=${encodeURIComponent(query)}`);
                })
                .catch(error => {
                    console.error('Error fetching orders:', error);
                    orderSuggestions.innerHTML = '<div class="suggestion-item">Error al buscar pedidos</div>';
                    orderSuggestions.style.display = 'block';
                    mostrarError('Error al buscar pedidos: ' + error.message);
                });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!orderSearch.contains(e.target) && !orderSuggestions.contains(e.target)) {
                orderSuggestions.innerHTML = '';
                orderSuggestions.style.display = 'none';
            }
        });
    }

    // Manejo de paginación con AJAX
    function handlePagination(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        updateTable(url);
    }

    function updateTable(url) {
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
            const tableBody = document.querySelector('#pedidos-table tbody');
            const pagination = document.querySelector('.pagination');
            tableBody.innerHTML = '';
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="table-cell">No hay pedidos disponibles.</td></tr>';
                pagination.innerHTML = '';
                return;
            }
            data.data.forEach(pedido => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="table-cell">${pedido.id}</td>
                    <td class="table-cell">${pedido.user.name}</td>
                    <td class="table-cell">$${parseFloat(pedido.total).toFixed(2)}</td>
                    <td class="table-cell status-cell">${pedido.status.charAt(0).toUpperCase() + pedido.status.slice(1).toLowerCase()}</td>
                    <td class="table-cell action-cell">
                        ${pedido.status === 'Pendiente' ? `
                            <button class="btn-completar" data-id="${pedido.id}" aria-label="Marcar pedido #${pedido.id} como completado">Marcar como Completado</button>
                            <button class="btn-cancelar" data-id="${pedido.id}" aria-label="Cancelar pedido #${pedido.id}">Cancelar</button>
                            <a href="/pedidos/${pedido.id}/edit" class="btn-editar" aria-label="Editar pedido #${pedido.id}">Editar</a>
                        ` : ''}
                        <button class="btn-detalles" data-id="${pedido.id}" aria-label="Ver detalles del pedido #${pedido.id}">Ver Detalles</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            pagination.innerHTML = data.links;
            bindPaginationLinks();
            bindActionButtons();
        })
        .catch(error => {
            console.error('Error fetching orders:', error);
            mostrarError('Error al cargar los pedidos: ' + error.message);
        });
    }

    function bindPaginationLinks() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.removeEventListener('click', handlePagination);
            link.addEventListener('click', handlePagination);
        });
    }

    function bindActionButtons() {
        const completeButtons = document.querySelectorAll('.btn-completar');
        const cancelButtons = document.querySelectorAll('.btn-cancelar');
        const detailsButtons = document.querySelectorAll('.btn-detalles');

        completeButtons.forEach(button => {
            button.removeEventListener('click', handleComplete);
            button.addEventListener('click', handleComplete);
        });

        cancelButtons.forEach(button => {
            button.removeEventListener('click', handleCancel);
            button.addEventListener('click', handleCancel);
        });

        detailsButtons.forEach(button => {
            button.removeEventListener('click', handleDetails);
            button.addEventListener('click', handleDetails);
        });
    }

    function handleComplete(e) {
        e.preventDefault();
        currentOrderId = this.getAttribute('data-id');
        currentRow = this.closest('tr');
        if (dialogCompletar) dialogCompletar.showModal();
    }

    function handleCancel(e) {
        e.preventDefault();
        currentOrderId = this.getAttribute('data-id');
        currentRow = this.closest('tr');
        if (dialogCancelar) dialogCancelar.showModal();
    }

    function handleDetails(e) {
        e.preventDefault();
        const pedidoId = this.getAttribute('data-id');
        if (pedidoId) {
            showDetails(pedidoId);
        } else {
            mostrarError('ID del pedido no encontrado.');
        }
    }

    if (btnConfirmarCompletar) {
        btnConfirmarCompletar.addEventListener('click', function () {
            btnConfirmarCompletar.disabled = true;
            btnConfirmarCompletar.textContent = 'Procesando...';

            fetch(`/pedidos/${currentOrderId}/complete`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
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
                if (data.success) {
                    const statusCell = currentRow?.querySelector('.status-cell');
                    const actionCell = currentRow?.querySelector('.action-cell');
                    if (statusCell) statusCell.textContent = 'Completado';
                    if (actionCell) actionCell.innerHTML = `<button class="btn-detalles" data-id="${currentOrderId}" aria-label="Ver detalles del pedido #${currentOrderId}">Ver Detalles</button>`;
                    if (dialogCompletar) dialogCompletar.close();
                    mostrarExito(data.success);
                    bindActionButtons();
                } else {
                    mostrarError(data.error || 'No se pudo completar el pedido.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema: ' + error.message);
            })
            .finally(() => {
                btnConfirmarCompletar.disabled = false;
                btnConfirmarCompletar.textContent = 'Sí';
            });
        });
    }

    if (btnConfirmarCancelar) {
        btnConfirmarCancelar.addEventListener('click', function () {
            btnConfirmarCancelar.disabled = true;
            btnConfirmarCancelar.textContent = 'Procesando...';

            fetch(`/pedidos/${currentOrderId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
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
                if (data.success) {
                    const statusCell = currentRow?.querySelector('.status-cell');
                    const actionCell = currentRow?.querySelector('.action-cell');
                    if (statusCell) statusCell.textContent = 'Cancelado';
                    if (actionCell) actionCell.innerHTML = `<button class="btn-detalles" data-id="${currentOrderId}" aria-label="Ver detalles del pedido #${currentOrderId}">Ver Detalles</button>`;
                    if (dialogCancelar) dialogCancelar.close();
                    mostrarExito(data.success);
                    bindActionButtons();
                } else {
                    mostrarError(data.error || 'No se pudo cancelar el pedido.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema al cancelar el pedido: ' + error.message);
            })
            .finally(() => {
                btnConfirmarCancelar.disabled = false;
                btnConfirmarCancelar.textContent = 'Sí';
            });
        });
    }

    const detailsButtons = document.querySelectorAll('.btn-detalles, .mis-pedidos-btn-detalles');
    detailsButtons.forEach(button => {
        button.addEventListener('click', handleDetails);
    });

    const createButton = document.querySelector('.btn-crear-pedido');
    if (createButton) {
        createButton.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = '/pedidos/create';
        });
    }

    const orderForms = document.querySelectorAll('#create-order-form, #edit-order-form');
    orderForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = form.id === 'create-order-form'
                    ? 'Creando pedido...'
                    : 'Guardando cambios...';
            }

            if (!userIdInput.value) {
                mostrarError('Por favor, selecciona un usuario.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.id === 'create-order-form'
                        ? 'Crear Pedido'
                        : 'Guardar Cambios';
                }
                return;
            }

            const items = Array.from(form.querySelectorAll('.item-quantity')).map((input, index) => {
                const productSelect = form.querySelectorAll('.item-product')[index];
                const stock = parseInt(productSelect.selectedOptions[0].dataset.stock);
                const quantity = parseInt(input.value);
                if (quantity > stock) {
                    mostrarError(`La cantidad para ${productSelect.selectedOptions[0].text} excede el stock disponible (${stock}).`);
                    return null;
                }
                return {
                    product_id: productSelect.value,
                    quantity: quantity
                };
            });

            if (items.includes(null)) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.id === 'create-order-form'
                        ? 'Crear Pedido'
                        : 'Guardar Cambios';
                }
                return;
            }

            fetch(form.action, {
                method: form.querySelector('input[name="_method"]')?.value || form.method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: form.querySelector('#user_id').value,
                    items: items
                })
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
                if (data.success) {
                    mostrarExito(data.success);
                    setTimeout(() => window.location.href = '/pedidos', 1000);
                } else {
                    mostrarError(data.error || 'No se pudo procesar el pedido.');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = form.id === 'create-order-form'
                            ? 'Crear Pedido'
                            : 'Guardar Cambios';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema: ' + error.message);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.id === 'create-order-form'
                        ? 'Crear Pedido'
                        : 'Guardar Cambios';
                }
            });
        });
    });

    const addItemButton = document.getElementById('add-item');
    if (addItemButton) {
        addItemButton.addEventListener('click', function () {
            const tbody = document.querySelector('#items-table tbody');
            const index = tbody.children.length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="items[${index}][product_id]" class="item-product">
                        ${Array.from(document.querySelector('.item-product').options).map(option => 
                            `<option value="${option.value}" data-stock="${option.dataset.stock}">${option.text}</option>`
                        ).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][quantity]" value="1" min="1" class="item-quantity">
                </td>
                <td>
                    <button type="button" class="btn-remove-item">Eliminar</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-item') && document.querySelectorAll('#items-table tbody tr').length > 1) {
            e.target.closest('tr').remove();
        }
    });

    if (btnCerrarCompletar && dialogCompletar) {
        btnCerrarCompletar.addEventListener('click', () => dialogCompletar.close());
    }
    if (btnCerrarCancelar && dialogCancelar) {
        btnCerrarCancelar.addEventListener('click', () => dialogCancelar.close());
    }
    if (btnCerrarError && dialogError) {
        btnCerrarError.addEventListener('click', () => dialogError.close());
    }
    if (btnCerrarSuccess && dialogSuccess) {
        btnCerrarSuccess.addEventListener('click', () => dialogSuccess.close());
    }

    bindPaginationLinks();
});

function showDetails(pedidoId) {
    fetch(`/pedidos/${pedidoId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'text/html',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }
        return response.text();
    })
    .then(html => {
        const dialogDetails = document.getElementById('mis-pedidos-details-modal') || document.getElementById('detailsModal');
        const detailsContent = dialogDetails.querySelector('#mis-pedidos-details-content') || document.getElementById('detailsContent');
        const pdfLink = dialogDetails.querySelector('#mis-pedidos-pdf-link') || document.getElementById('pdfLink');
        detailsContent.innerHTML = html;
        pdfLink.href = `/pedidos/${pedidoId}/pdf`;
        if (dialogDetails) dialogDetails.showModal();
    })
    .catch(error => {
        console.error('Error al cargar detalles:', error);
        const mensajeError = document.getElementById('mensaje-error');
        const dialogError = document.getElementById('dialog-error');
        if (mensajeError && dialogError) {
            mensajeError.textContent = 'Error al cargar los detalles del pedido: ' + error.message;
            dialogError.showModal();
        }
    });
}