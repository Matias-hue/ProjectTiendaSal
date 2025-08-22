import './bootstrap.js';
import './inventario.js';
import './pedidos.js';
import './registro.js';
import './resumen.js';
import './ubicacion.js';
import './usuarios.js';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.dashboard-sidebar');
    const toggler = document.querySelector('.navbar-toggler');
    const dashboardContainer = document.querySelector('.dashboard-container');

    // Verificar si los elementos existen para evitar errores
    if (!sidebar || !toggler || !dashboardContainer) {
        console.error('Uno o más elementos no se encontraron:', { sidebar, toggler, dashboardContainer });
        return;
    }

    // Función para togglear el dashboard
    const toggleSidebar = () => {
        const isActive = sidebar.classList.toggle('active');
        dashboardContainer.style.display = isActive ? 'block' : 'none';
        localStorage.setItem('dashboardState', isActive ? 'visible' : 'hidden');
    };

    // Botón hamburguesa
    toggler.addEventListener('click', toggleSidebar);

    // Establecer el dashboard como visible por defecto al cargar la página
    sidebar.classList.add('active');
    dashboardContainer.style.display = 'block';
});