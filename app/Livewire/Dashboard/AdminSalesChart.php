<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class AdminSalesChart extends Component
{
    public $selectedUserId = 'all';
    public $chartData = [];

    public function mount()
    {
        $this->updateChart();
    }

    public function updatedSelectedUserId()
    {
        $this->updateChart();
        $this->dispatch('chart-updated', data: $this->chartData);
    }

    public function updateChart()
    {
        $query = Sale::query();

        if ($this->selectedUserId && $this->selectedUserId !== 'all') {
            $query->where('user_id', $this->selectedUserId);
        }

        // Get sales for the last 6 months
        $sales = $query->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(monto_total) as total_sales')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Prepare labels (User friendly month names)
        $labels = $sales->map(function($sale) {
             return \Carbon\Carbon::createFromFormat('Y-m', $sale->month)->format('M Y');
        })->toArray();

        $data = $sales->pluck('total_sales')->toArray();

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Monto de Ventas ($)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.5)', // Indigo
                    'borderColor' => 'rgba(99, 102, 241, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true
                ]
            ]
        ];
    }

    public function render()
    {
        // Only get users with role 'Vendedor'
        $sellers = User::role('Vendedor')->get();

        return view('livewire.dashboard.admin-sales-chart', [
            'sellers' => $sellers
        ]);
    }
}
