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

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!userSearch.contains(e.target) && !userSuggestions.contains(e.target)) {
                userSuggestions.innerHTML = '';
                userSuggestions.style.display = 'none';
            }
        });
    }

    // Botones de completar
    const completeButtons = document.querySelectorAll('.btn-completar');
    completeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentOrderId = this.getAttribute('data-id');
            currentRow = this.closest('tr');
            if (dialogCompletar) dialogCompletar.showModal();
        });
    });

    // Botones de cancelar
    const cancelButtons = document.querySelectorAll('.btn-cancelar');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentOrderId = this.getAttribute('data-id');
            currentRow = this.closest('tr');
            if (dialogCancelar) dialogCancelar.showModal();
        });
    });

    // Botones de detalles
    const detailsButtons = document.querySelectorAll('.btn-detalles, .mis-pedidos-btn-detalles');
    detailsButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const pedidoId = this.getAttribute('data-id');
            if (pedidoId) {
                showDetails(pedidoId);
            } else {
                mostrarError('ID del pedido no encontrado.');
            }
        });
    });

    // Botón de crear pedido
    const createButton = document.querySelector('.btn-crear-pedido');
    if (createButton) {
        createButton.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = '/pedidos/create';
        });
    }

    // Manejo de formularios de crear/editar pedidos
    const orderForms = document.querySelectorAll('#create-order-form, #edit-order-form');
    orderForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // botón de submit
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
                    // Si falla, reactivar botón
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
                // Si hay error, reactivar botón
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.id === 'create-order-form'
                        ? 'Crear Pedido'
                        : 'Guardar Cambios';
                }
            });
        });
    });

    // Agregar ítems en救助
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

    // Eliminar ítems
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-item') && document.querySelectorAll('#items-table tbody tr').length > 1) {
            e.target.closest('tr').remove();
        }
    });

    // Confirmar completar
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
                    const actionCell = currentRow?.querySelector('td:last-child');
                    if (statusCell) statusCell.textContent = 'Completado';
                    if (actionCell) actionCell.innerHTML = `<button class="btn-detalles" data-id="${currentOrderId}" aria-label="Ver detalles del pedido #${currentOrderId}">Ver Detalles</button>`;
                    if (dialogCompletar) dialogCompletar.close();
                    mostrarExito(data.success);
                } else {
                    mostrarError(data.error || 'No se pudo completar el pedido.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema: ' + error.message);
            })
            .finally(() => {
                if (btnConfirmarCompletar) {
                    btnConfirmarCompletar.disabled = false;
                    btnConfirmarCompletar.textContent = 'Sí';
                }
            });
        });
    }

    // Confirmar cancelar
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
                    const actionCell = currentRow?.querySelector('td:last-child');
                    if (statusCell) statusCell.textContent = 'Cancelado';
                    if (actionCell) actionCell.innerHTML = `<button class="btn-detalles" data-id="${currentOrderId}" aria-label="Ver detalles del pedido #${currentOrderId}">Ver Detalles</button>`;
                    if (dialogCancelar) dialogCancelar.close();
                    mostrarExito(data.success);
                } else {
                    mostrarError(data.error || 'No se pudo cancelar el pedido.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema al cancelar el pedido: ' + error.message);
            })
            .finally(() => {
                if (btnConfirmarCancelar) {
                    btnConfirmarCancelar.disabled = false;
                    btnConfirmarCancelar.textContent = 'Sí';
                }
            });
        });
    }

    // Cerrar modales
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