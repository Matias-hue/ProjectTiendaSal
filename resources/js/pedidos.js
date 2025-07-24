document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Pedidos cargado');

    // Modales
    const dialogCompletar = document.getElementById('dialog-completar');
    const dialogCancelar = document.getElementById('dialog-cancelar');
    const dialogError = document.getElementById('dialog-error');
    const btnCerrarCompletar = document.getElementById('btn-cerrar-completar');
    const btnCerrarCancelar = document.getElementById('btn-cerrar-cancelar');
    const btnCerrarError = document.getElementById('btn-cerrar-error');
    const btnConfirmarCompletar = document.getElementById('btn-confirmar-completar');
    const btnConfirmarCancelar = document.getElementById('btn-confirmar-cancelar');
    const mensajeError = document.getElementById('mensaje-error');

    let currentOrderId;
    let currentRow;

    // Función para mostrar errores en el modal
    function mostrarError(mensaje) {
        mensajeError.textContent = mensaje;
        dialogError.showModal();
    }

    // Marcar como completado
    const completeButtons = document.querySelectorAll('.btn-completar');
    completeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentOrderId = this.getAttribute('data-id');
            currentRow = this.closest('tr');
            dialogCompletar.showModal();
        });
    });

    // Confirmar completar
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
                    // Actualizar la celda de estado
                    const statusCell = currentRow.querySelector('.status-cell');
                    statusCell.textContent = 'Completado';

                    // Eliminar botones de acción
                    const actionCell = currentRow.querySelector('td:last-child');
                    actionCell.innerHTML = '';

                    dialogCompletar.close();
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

    // Cerrar modal completar
    btnCerrarCompletar.addEventListener('click', function () {
        dialogCompletar.close();
    });

    // Cancelar pedido
    const cancelButtons = document.querySelectorAll('.btn-cancelar');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentOrderId = this.getAttribute('data-id');
            currentRow = this.closest('tr');
            dialogCancelar.showModal();
        });
    });

    // Confirmar cancelar
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
                    // Actualizar la celda de estado
                    const statusCell = currentRow.querySelector('.status-cell');
                    statusCell.textContent = 'Cancelado';

                    // Eliminar botones de acción
                    const actionCell = currentRow.querySelector('td:last-child');
                    actionCell.innerHTML = '';

                    dialogCancelar.close();
                } else {
                    mostrarError(data.error || 'No se pudo cancelar el pedido.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Hubo un problema: ' + error.message);
            })
            .finally(() => {
                btnConfirmarCancelar.disabled = false;
                btnConfirmarCancelar.textContent = 'Sí';
            });
    });

    // Cerrar modal cancelar
    btnCerrarCancelar.addEventListener('click', function () {
        dialogCancelar.close();
    });

    // Cerrar modal error
    btnCerrarError.addEventListener('click', function () {
        dialogError.close();
    });
});