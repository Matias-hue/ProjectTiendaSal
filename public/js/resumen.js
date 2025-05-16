document.addEventListener('DOMContentLoaded', function () {
    // Gráfica de productos vendidos por día
    const ctx1 = document.getElementById('ventasPorDiaChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: window.chartLabels,
            datasets: [{
                label: 'Productos Vendidos',
                data: window.chartData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad Vendida'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Día del Mes'
                    }
                }
            }
        }
    });

    // Gráfica del producto más vendido
    const ctx2 = document.getElementById('productoMasVendidoChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: [window.productoMasVendido ? window.productoMasVendido.nombre : 'Sin datos', 'Otros'],
            datasets: [{
                data: [window.productoMasVendido ? window.productoMasVendido.total_vendido : 0, 1],
                backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(200, 200, 200, 0.3)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(200, 200, 200, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' unidades';
                        }
                    }
                }
            }
        }
    });

    // Gráfica de todos los productos vendidos
    const ctx3 = document.getElementById('todosProductosChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: window.productosLabels,
            datasets: [{
                label: 'Cantidad Vendida',
                data: window.productosData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad Vendida'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Producto'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});