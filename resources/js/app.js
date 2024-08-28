import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: JSON.parse(document.getElementById('categoryLabels').textContent),
            datasets: [{
                label: 'Expenses by Category',
                data: JSON.parse(document.getElementById('categoryData').textContent),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
