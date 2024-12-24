<div class="card">
    <div class="card-header bg-{{ $widget->color }} text-white">
        <i class="{{ $widget->icon }}"></i> {{ $widget->name }}
    </div>
    <div class="card-body">
        <canvas id="chart-{{ $widget->id }}"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('chart-{{ $widget->id }}').getContext('2d');
        const chartData = @json($widget->data ?? []);

        new Chart(ctx, {
            type: 'bar', // Type de graphique
            data: {
                labels: chartData.map(item => item.label), // Labels pour les axes
                datasets: [{
                    label: '{{ $widget->label }}',
                    data: chartData.map(item => item.value), // Valeurs des données
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
