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
    const toggleDashboard = document.querySelector('.btn-toggle-dashboard');

    // Verificar si los elementos existen para evitar errores
    if (!sidebar || !toggler || !toggleDashboard) {
        console.error('Uno o más elementos no se encontraron:', { sidebar, toggler, toggleDashboard });
        return;
    }

    // Función para togglear el dashboard
    const toggleSidebar = () => {
        const isActive = sidebar.classList.toggle('active');
        localStorage.setItem('dashboardState', isActive ? 'visible' : 'hidden');
        toggleDashboard.textContent = isActive ? 'Ocultar Dashboard' : 'Mostrar Dashboard';
    };

    // Botón hamburguesa
    toggler.addEventListener('click', toggleSidebar);

    // Botón dentro del dashboard
    toggleDashboard.addEventListener('click', toggleSidebar);

    // Cargar estado del dashboard desde localStorage
    const dashboardState = localStorage.getItem('dashboardState');
    if (dashboardState === 'visible') {
        sidebar.classList.add('active');
        toggleDashboard.textContent = 'Ocultar Dashboard';
    } else {
        sidebar.classList.remove('active');
        toggleDashboard.textContent = 'Mostrar Dashboard';
    }
});