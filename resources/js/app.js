import './bootstrap.js';
import './inventario.js';
import './pedidos.js';
import './registro.js';
import './resumen.js';
import './ubicacion.js';
import './usuarios.js';

document.addEventListener('DOMContentLoaded', function() {
    // Creacion
    const btnAgregar = document.getElementById('btn-agregar');
    const dialogAgregar = document.getElementById('dialog-agregar');
    const btnCerrar = document.getElementById('btn-cerrar');
    const dialogEditar = document.getElementById('dialog-editar');
    const btnCerrarEditar = document.getElementById('btn-cerrar-editar');
    const dialogEliminar = document.getElementById('dialog-eliminar');
    const btnCerrarEliminar = document.getElementById('btn-cerrar-eliminar');
    const btnConfirmarEliminar = document.getElementById('btn-confirmar-eliminar');

    // Abrir y Cerrar Creacion
    if(btnAgregar && dialogAgregar && btnCerrar) {
        btnAgregar.addEventListener('click', function() {
            dialogAgregar.showModal();
        });

        btnCerrar.addEventListener('click', function() {
            dialogAgregar.close();
        });
    }


    // Texto "Creando..."
    const form = dialogAgregar.querySelector('form');
    const guardarButton = form.querySelector('button[type="submit"]');

    if (form && guardarButton) {
        form.addEventListener('submit', function () {
            guardarButton.disabled = true;
            guardarButton.innerText= 'Creando...';
        });
    }

    console.log(dialogAgregar); // Verifica si dialogAgregar es null
    console.log(form); // Verifica si form es null
    

    // Edicion
    const btnEditarTabla = document.querySelectorAll('.btn-editar-tabla');

    btnEditarTabla.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const tamaño = this.getAttribute('data-tamaño');
            const precio = this.getAttribute('data-precio');
            const stock = this.getAttribute('data-stock');

            // Rellenar campos
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-tamaño').value = tamaño;
            document.getElementById('edit-precio').value = precio;
            document.getElementById('edit-stock').value = stock;

            dialogEditar.showModal();

            const formEditar = document.getElementById('form-editar');
            formEditar.action = `/productos/${id}`;
        });
    });

    // Cerrar Edicion
    if(btnCerrarEditar) {
        btnCerrarEditar.addEventListener('click', function() {
            dialogEditar.close();
        });
    }

    // Texto "Editando..."
    const formEditar = document.getElementById('form-editar');
    const guardarEditarButton = formEditar.querySelector('button[type="submit"]');

    if (formEditar && guardarEditarButton) {
        formEditar.addEventListener('submit', function () {
            guardarEditarButton.disabled = true;
            guardarEditarButton.innerText= 'Editando...';
        });
    }

    // Eliminacion
    const btnEliminarTabla = document.querySelectorAll('.btn-eliminar-tabla');
    let productoId;

    btnEliminarTabla.forEach(button => {
        button.addEventListener('click', function() {
            const fila = this.closest('tr');
            const celdas = fila.querySelectorAll('td');
            console.log(celdas); // Para validar qué celdas se obtienen
            const nombre = celdas[1]?.innerText || 'nombre no encontrado';
            document.getElementById('mensaje-eliminar').innerText = `¿Estás seguro de que deseas eliminar el producto "${nombre}"?`;
            dialogEliminar.showModal();
            productoId = this.getAttribute('data-id');
        });
    });

    // Confirmar Eliminacion
    btnConfirmarEliminar.addEventListener('click', function() {
        btnConfirmarEliminar.disabled = true;
        btnConfirmarEliminar.innerText = "Eliminando...";

        fetch(`/productos/${productoId}`, { 
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            alert('Hubo un problema con la eliminación.' + error.message);
        });
    });

    // Cerrar Eliminacion
    if (btnCerrarEliminar) {
        btnCerrarEliminar.addEventListener('click', function() {
            dialogEliminar.close();
        });
    };

});