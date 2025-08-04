// pedidos.js
document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Pedidos cargado');

    const dialogCompletar = document.getElementById('dialog-completar');
    const dialogCancelar = document.getElementById('dialog-cancelar');
    const dialogError = document.getElementById('dialog-error');
    const dialogDetails = document.getElementById('detailsModal');
    const btnCerrarCompletar = document.getElementById('btn-cerrar-completar');
    const btnCerrarCancelar = document.getElementById('btn-cerrar-cancelar');
    const btnCerrarError = document.getElementById('btn-cerrar-error');
    const btnConfirmarCompletar = document.getElementById('btn-confirmar-completar');
    const btnConfirmarCancelar = document.getElementById('btn-confirmar-cancelar');
    const mensajeError = document.getElementById('mensaje-error');

    let currentOrderId;
    let currentRow;

    function mostrarError(mensaje) {
        if (mensajeError && dialogError) {
            mensajeError.textContent = mensaje;
            dialogError.showModal();
        }
    }

    const completeButtons = document.querySelectorAll('.btn-completar');
    completeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentOrderId = this.getAttribute('data-id');
            currentRow = this.closest('tr');
            if (dialogCompletar) dialogCompletar.showModal();
        });
    });

    const detailsButtons = document.querySelectorAll('.btn-detalles');
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusCell = currentRow?.querySelector('.status-cell');
                    const actionCell = currentRow?.querySelector('td:last-child');

                    if (statusCell) statusCell.textContent = 'Completado';
                    if (actionCell) actionCell.innerHTML = `<button class="btn-detalles" data-id="${currentOrderId}">Ver Detalles</button>`;

                    if (dialogCompletar) dialogCompletar.close();
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

    if (btnCerrarCompletar && dialogCompletar) {
        btnCerrarCompletar.addEventListener('click', () => dialogCompletar.close());
    }

    const cancelButtons = document.querySelectorAll('.btn-cancelar');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentOrderId = this.getAttribute('data-id');
            currentRow = this.closest('tr');
            if (dialogCancelar) dialogCancelar.showModal();
        });
    });

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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusCell = currentRow?.querySelector('.status-cell');
                    const actionCell = currentRow?.querySelector('td:last-child');

                    if (statusCell) statusCell.textContent = 'Cancelado';
                    if (actionCell) actionCell.innerHTML = `<button class="btn-detalles" data-id="${currentOrderId}">Ver Detalles</button>`;

                    if (dialogCancelar) dialogCancelar.close();
                } else {
                    mostrarError(data.error || 'No se pudo cancelar el pedido.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema: ' + error.message);
            })
            .finally(() => {
                if (btnConfirmarCancelar) {
                    btnConfirmarCancelar.disabled = false;
                    btnConfirmarCancelar.textContent = 'Sí';
                }
            });
        });
    }

    if (btnCerrarCancelar && dialogCancelar) {
        btnCerrarCancelar.addEventListener('click', () => dialogCancelar.close());
    }

    if (btnCerrarError && dialogError) {
        btnCerrarError.addEventListener('click', () => dialogError.close());
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
        document.getElementById('detailsContent').innerHTML = html;
        document.getElementById('pdfLink').href = `/pedidos/${pedidoId}/pdf`;
        const dialogDetails = document.getElementById('detailsModal');
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