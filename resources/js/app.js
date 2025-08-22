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

    sidebar.classList.add('active');
    dashboardContainer.style.display = 'block';

    const inventarioBadge = document.querySelector('#inventario-badge');
    const pedidosBadge = document.querySelector('#pedidos-badge');
    const alertasBadge = document.querySelector('#alertas-badge');

    if (inventarioBadge && pedidosBadge && alertasBadge) {
        const updateBadges = () => {
            fetch('/alertas/total', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                inventarioBadge.textContent = data.lowStockCount > 0 ? data.lowStockCount : '';
                inventarioBadge.style.display = data.lowStockCount > 0 ? 'inline-block' : 'none';
                pedidosBadge.textContent = data.pendingOrders > 0 ? data.pendingOrders : '';
                pedidosBadge.style.display = data.pendingOrders > 0 ? 'inline-block' : 'none';
                alertasBadge.textContent = data.totalAlertas > 0 ? data.totalAlertas : '';
                alertasBadge.style.display = data.totalAlertas > 0 ? 'inline-block' : 'none';
            })
            .catch(error => console.error('Error al actualizar badges:', error));
        };

        updateBadges();
        setInterval(updateBadges, 10000);
    }
});