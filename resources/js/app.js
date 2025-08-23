import './bootstrap.js';
import './inventario.js';
import './pedidos.js';
import './registro.js';
import './resumen.js';
import './ubicacion.js';
import './usuarios.js';

document.addEventListener('DOMContentLoaded', () => {
  const toggler =
    document.querySelector('.navbar-toggler') ||
    document.querySelector('#navbar-toggler');

  const sidebar =
    document.querySelector('#dashboard .dashboard-sidebar') ||
    document.querySelector('.dashboard-sidebar');

    const alertasBadgeHamburguesa = document.getElementById('alertas-badge-hamburguesa')

    if (!toggler || !sidebar || !alertasBadgeHamburguesa) return;

    const apply = (isOpen) => {

        sidebar.classList.toggle('active', isOpen);

        document.body.classList.toggle('sidebar-open', isOpen);

        alertasBadgeHamburguesa.style.display = isOpen ? 'none' : 'inlineblock';

        localStorage.setItem('dashboardState', isOpen ? 'visible' : 'hidden');
    };

    const initialOpen = (localStorage.getItem('dashboardState') ?? 'visible') !== 'hidden';
    apply(initialOpen);

    toggler.addEventListener('click', (e) => {
        e.preventDefault();
        const isOpen = sidebar.classList.contains('active');
        alertasBadgeHamburguesa.style.display = 'inline-block';
        apply(!isOpen);
    });
});
