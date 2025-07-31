document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Inventario cargado');

    // Agregar producto
    const btnAgregar = document.getElementById('btn-agregar');
    const dialogAgregar = document.getElementById('dialog-agregar');
    const btnCerrar = document.getElementById('btn-cerrar');

    if (btnAgregar && dialogAgregar && btnCerrar) {
        btnAgregar.addEventListener('click', () => dialogAgregar.showModal());
        btnCerrar.addEventListener('click', () => dialogAgregar.close());

        const form = dialogAgregar.querySelector('form');
        const guardarButton = form?.querySelector('button[type="submit"]');
        form?.addEventListener('submit', () => {
            if (guardarButton) {
                guardarButton.disabled = true;
                guardarButton.innerText = 'Creando...';
            }
        });
    }

    // Editar producto
    const dialogEditar = document.getElementById('dialog-editar');
    const btnCerrarEditar = document.getElementById('btn-cerrar-editar');
    const formEditar = document.getElementById('form-editar');

    if (dialogEditar && formEditar) {
        document.querySelectorAll('.btn-editar-tabla').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                document.getElementById('edit-nombre').value = button.dataset.nombre;
                document.getElementById('edit-tamaño').value = button.dataset.tamaño;
                document.getElementById('edit-precio').value = button.dataset.precio;
                document.getElementById('edit-stock').value = button.dataset.stock;

                formEditar.action = `/productos/${id}`;
                dialogEditar.showModal();
            });
        });

        const guardarEditarButton = formEditar.querySelector('button[type="submit"]');
        formEditar.addEventListener('submit', () => {
            if (guardarEditarButton) {
                guardarEditarButton.disabled = true;
                guardarEditarButton.innerText = 'Editando...';
            }
        });

        btnCerrarEditar?.addEventListener('click', () => dialogEditar.close());
    }

    // Eliminar producto
    const dialogEliminar = document.getElementById('dialog-eliminar');
    const btnCerrarEliminar = document.getElementById('btn-cerrar-eliminar');
    const btnConfirmarEliminar = document.getElementById('btn-confirmar-eliminar');
    let productoId;

    if (dialogEliminar && btnConfirmarEliminar) {
        document.querySelectorAll('.btn-eliminar-tabla').forEach(button => {
            button.addEventListener('click', () => {
                productoId = button.dataset.id;
                const nombre = button.dataset.nombre || 'producto';

                const mensajeEliminar = document.getElementById('mensaje-eliminar');
                if (mensajeEliminar) {
                    mensajeEliminar.innerText = `¿Estás seguro de que deseas eliminar el producto "${nombre}"?`;
                }

                dialogEliminar.showModal();
            });
        });

        btnConfirmarEliminar.addEventListener('click', () => {
            btnConfirmarEliminar.disabled = true;
            btnConfirmarEliminar.innerText = "Eliminando...";

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/productos/${productoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        console.error('Error al eliminar:', response);
                        alert('Error al eliminar el producto.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al eliminar el producto: ' + error.message);
                });
        });

        btnCerrarEliminar?.addEventListener('click', () => dialogEliminar.close());
    }
});