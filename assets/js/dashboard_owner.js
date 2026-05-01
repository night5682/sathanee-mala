document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stockPieChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['อาหาร', 'เครื่องดื่ม', 'อื่นๆ'],
                datasets: [{
                    data: [300, 150, 100], 
                    backgroundColor: ['#4f46e5', '#a855f7', '#6366f1']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});