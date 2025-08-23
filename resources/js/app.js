import './bootstrap.js';
import './inventario.js';
import './pedidos.js';
import './registro.js';
import './resumen.js';
import './ubicacion.js';
import './usuarios.js';

document.addEventListener('DOMContentLoaded', () => {
  // Botón (puede ser el button o el span interno)
  const toggler =
    document.querySelector('.navbar-toggler') ||
    document.querySelector('#navbar-toggler');

  // Sidebar real (si hay wrapper + aside, toma el aside)
  const sidebar =
    document.querySelector('#dashboard .dashboard-sidebar') ||
    document.querySelector('.dashboard-sidebar');

  if (!toggler || !sidebar) return;

  const apply = (isOpen) => {
    // Muestra/oculta el panel
    sidebar.classList.toggle('active', isOpen);
    // Desplaza el contenido (CSS: body.sidebar-open .flex-1 { margin-left: 16rem; })
    document.body.classList.toggle('sidebar-open', isOpen);
    // Persistencia
    localStorage.setItem('dashboardState', isOpen ? 'visible' : 'hidden');
  };

  // Estado inicial desde localStorage (visible por defecto)
  const initialOpen = (localStorage.getItem('dashboardState') ?? 'visible') !== 'hidden';
  apply(initialOpen);

  // Toggle al click
  toggler.addEventListener('click', (e) => {
    e.preventDefault();
    const isOpen = sidebar.classList.contains('active');
    apply(!isOpen);
  });
});
