import './bootstrap.js';

document.addEventListener('DOMContentLoaded', function() {
    // Creacion
    const btnAgregar = document.getElementById('btn-agregar');
    const dialogAgregar = document.getElementById('dialog-agregar');
    const btnCerrar = document.getElementById('btn-cerrar');

    if (btnAgregar && dialogAgregar && btnCerrar) {
        btnAgregar.addEventListener('click', () => dialogAgregar.showModal());
        btnCerrar.addEventListener('click', () => dialogAgregar.close());

        const form = dialogAgregar.querySelector('form');
        if (form) {
            const guardarButton = form.querySelector('button[type="submit"]');
            if (guardarButton) {
                form.addEventListener('submit', function () {
                    guardarButton.disabled = true;
                    guardarButton.innerText = 'Creando...';
                });
            }
        }
    }

    // Edicion
    const dialogEditar = document.getElementById('dialog-editar');
    const btnCerrarEditar = document.getElementById('btn-cerrar-editar');
    const formEditar = document.getElementById('form-editar');
    const btnEditarTabla = document.querySelectorAll('.btn-editar-tabla');

    if (dialogEditar && formEditar) {
        btnEditarTabla.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const tamaño = this.getAttribute('data-tamaño');
                const precio = this.getAttribute('data-precio');
                const stock = this.getAttribute('data-stock');

                document.getElementById('edit-nombre')?.setAttribute('value', nombre);
                document.getElementById('edit-tamaño')?.setAttribute('value', tamaño);
                document.getElementById('edit-precio')?.setAttribute('value', precio);
                document.getElementById('edit-stock')?.setAttribute('value', stock);

                dialogEditar.showModal();

                formEditar.action = `/productos/${id}`;
            });
        });

        const guardarEditarButton = formEditar.querySelector('button[type="submit"]');
        if (guardarEditarButton) {
            formEditar.addEventListener('submit', function () {
                guardarEditarButton.disabled = true;
                guardarEditarButton.innerText = 'Editando...';
            });
        }

        if (btnCerrarEditar) {
            btnCerrarEditar.addEventListener('click', () => dialogEditar.close());
        }
    }

    // Eliminación
    const dialogEliminar = document.getElementById('dialog-eliminar');
    const btnCerrarEliminar = document.getElementById('btn-cerrar-eliminar');
    const btnConfirmarEliminar = document.getElementById('btn-confirmar-eliminar');
    const btnEliminarTabla = document.querySelectorAll('.btn-eliminar-tabla');

    let productoId;

    if (dialogEliminar && btnConfirmarEliminar) {
        btnEliminarTabla.forEach(button => {
            button.addEventListener('click', function () {
                const fila = this.closest('tr');
                const celdas = fila?.querySelectorAll('td');
                const nombre = celdas?.[1]?.innerText || 'nombre no encontrado';

                const mensajeEliminar = document.getElementById('mensaje-eliminar');
                if (mensajeEliminar) {
                    mensajeEliminar.innerText = `¿Estás seguro de que deseas eliminar el producto "${nombre}"?`;
                }

                dialogEliminar.showModal();
                productoId = this.getAttribute('data-id');
            });
        });

        btnConfirmarEliminar.addEventListener('click', function () {
            btnConfirmarEliminar.disabled = true;
            btnConfirmarEliminar.innerText = "Eliminando...";

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/productos/${productoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Error al eliminar el producto.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema con la eliminación. ' + error.message);
                });
        });

        if (btnCerrarEliminar) {
            btnCerrarEliminar.addEventListener('click', () => dialogEliminar.close());
        }
    }
});