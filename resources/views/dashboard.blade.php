<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div 
                class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
                x-data="{
                    stats: @js($stats),
                    chartData: @js($chartData),
                    chartType: @js($chartType),
                    chartTitle: @js($chartTitle),
                    
                    getIcon(name) {
                        const icons = {
                            'currency-dollar': '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.768 0-1.536.219-2.121.659L9 15.182z\' />',
                            'truck': '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12\' />',
                            'users': '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M18 18.72a9.094 9.094 0 00-3.17-2.135 15.11 15.11 0 00-1.042-.315l-3.095-.732a18.75 18.75 0 00-3.182.004l-3.095.732a15.11 15.11 0 00-1.042.315A9.094 9.094 0 003 18.72m15 0a2.25 2.25 0 002.25-2.25v-1.5a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 15v1.5a2.25 2.25 0 002.25 2.25m15 0H3.75m14.25 0a2.25 2.25 0 01-2.25-2.25v-1.5a2.25 2.25 0 012.25-2.25h.008c.621 0 1.125.504 1.125 1.125v1.5a2.25 2.25 0 01-2.25 2.25h-.008zM7.5 12h3M7.5 15h3M5.25 12h.008v.008H5.25V12z\' />',
                            'check-circle': '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' />',
                            'receipt-percent': '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z\' />',
                            'clock': '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\' />',
                        };
                        return icons[name] || '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z\' />';
                    },
                    
                    initChart() {
                        let existingChart = Chart.getChart('myDashboardChart');
                        if (existingChart) {
                            existingChart.destroy();
                        }

                        const ctx = document.getElementById('myDashboardChart').getContext('2d');
                        new Chart(ctx, {
                            type: this.chartType,
                            data: this.chartData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#334155',
                                            font: { size: 14 }
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: this.chartTitle,
                                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#334155',
                                        font: { size: 18 }
                                    }
                                },
                                scales: {
                                    y: {
                                        display: this.chartType === 'bar' || this.chartType === 'line', 
                                        ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' },
                                        grid: { color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)' }
                                    },
                                    x: {
                                        display: this.chartType === 'bar' || this.chartType === 'line',
                                        ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' },
                                        grid: { display: false }
                                    }
                                }
                            }
                        });
                    }
                }"
                x-init="initChart()"
                @dark-mode-toggled.window="initChart()"
            >
                
                {{-- WIDGETS DE ESTADÍSTICAS --}}
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <template x-for="stat in stats" :key="stat.label">
                        <div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        fill="none" viewBox="0 0 24 24" 
                                        stroke-width="2" 
                                        stroke="currentColor" 
                                        class="w-6 h-6 text-indigo-600 dark:text-indigo-300"
                                        x-html="getIcon(stat.icon)"
                                    ></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400" x-text="stat.label"></p>
                                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100" x-text="stat.value"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- WIDGET DEL GRÁFICO --}}
                @if(auth()->user()->hasRole('Administrador'))
                    <livewire:dashboard.admin-sales-chart />
                @else
                    {{-- GRÁFICO ESTÁTICO (Para otros roles) --}}
                    <div 
                        class="relative h-full min-h-[400px] flex-1 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm"
                        x-data="{
                            chartInstance: null,
                            staticData: @js($chartData),
                            staticTitle: @js($chartTitle),
                            staticType: @js($chartType),
                            initStaticChart() {
                                if (this.chartInstance) this.chartInstance.destroy();
                                const ctx = document.getElementById('myDashboardChart').getContext('2d');
                                this.chartInstance = new Chart(ctx, {
                                    type: this.staticType,
                                    data: this.staticData,
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { position: 'bottom' },
                                            title: { display: true, text: this.staticTitle, font: { size: 18 } }
                                        }
                                    }
                                });
                            }
                        }"
                        x-init="initStaticChart()"
                        @dark-mode-toggled.window="initStaticChart()"
                    >
                        <canvas id="myDashboardChart"></canvas>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts.app>