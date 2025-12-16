<div 
    class="relative h-full min-h-[450px] flex-1 flex flex-col overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm"
    x-data="{
        chartInstance: null,
        initChart(data) {
            if (this.chartInstance) {
                this.chartInstance.destroy();
            }
            const ctx = document.getElementById('adminDynamicChart').getContext('2d');
            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Rendimiento de Ventas', font: { size: 16 } }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    }"
    x-init="initChart(@js($chartData))"
    @chart-updated.window="initChart($event.detail.data)"
>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">Métricas de Ventas</h3>
        
        <div class="w-64">
            <label for="seller_select" class="sr-only">Filtrar por Vendedor</label>
            <select 
                id="seller_select"
                wire:model.live="selectedUserId"
                class="block w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 bg-white dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"
            >
                <option value="all">Todos los Vendedores</option>
                @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="relative flex-grow w-full h-[350px]">
        <canvas id="adminDynamicChart"></canvas>
    </div>
</div>
