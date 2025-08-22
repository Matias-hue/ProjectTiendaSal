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

    if (!sidebar || !toggler || !dashboardContainer) {
        console.error('Uno o más elementos no se encontraron:', { sidebar, toggler, dashboardContainer });
        return;
    }

    const toggleSidebar = () => {
        const isActive = sidebar.classList.toggle('active');
        dashboardContainer.style.display = isActive ? 'block' : 'none';
        localStorage.setItem('dashboardState', isActive ? 'visible' : 'hidden');
    };

    toggler.addEventListener('click', toggleSidebar);

    const dashboardState = localStorage.getItem('dashboardState');
    if (dashboardState === 'hidden') {
        sidebar.classList.remove('active');
        dashboardContainer.style.display = 'none';
    } else {
        sidebar.classList.add('active');
        dashboardContainer.style.display = 'block';
    }
});