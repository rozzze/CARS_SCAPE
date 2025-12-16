<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Sale;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SalesReports extends Component
{
    public $start_date;
    public $end_date;
    public $customer_id; // Changed from user_id to customer_id or keep both
    public $user_id;    // Keep user_id as secondary filter if needed, but per request prioritize customer
    public $vehicle_id;
    public $search;     // New Search term

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function getSalesProperty()
    {
        return Sale::query()
            ->with(['user', 'vehicle', 'customer'])
            ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date))
            ->when($this->user_id, fn($q) => $q->where('user_id', $this->user_id))
            ->when($this->vehicle_id, fn($q) => $q->where('vehicle_id', $this->vehicle_id))
            ->when($this->customer_id, fn($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->search, function($q) {
                $q->whereHas('customer', function($subQ) {
                    $subQ->where('nombres_completos', 'like', '%' . $this->search . '%')
                         ->orWhere('numero_documento', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('vehicle', function($subQ) {
                    $subQ->where('marca', 'like', '%' . $this->search . '%')
                         ->orWhere('modelo', 'like', '%' . $this->search . '%');
                })
                ->orWhere('numero_boleta', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();
    }

    public function generatePdf()
    {
        $sales = $this->sales;
        
        $pdf = Pdf::loadView('reports.sales-pdf', [
            'sales' => $sales,
            'filters' => [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'user' => $this->user_id ? User::find($this->user_id)->name : 'Todos',
                'customer' => $this->customer_id ? Customer::find($this->customer_id)->nombres_completos : 'Todos',
                'vehicle' => $this->vehicle_id ? Vehicle::find($this->vehicle_id)->marca . ' ' . Vehicle::find($this->vehicle_id)->modelo : 'Todos',
                'search' => $this->search,
            ]
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reporte_ventas_' . now()->format('Y_m_d_H_i') . '.pdf');
    }

    public function render()
    {
        return view('livewire.reports.sales-reports', [
            'users' => User::role('Vendedor')->get(), // Only show Salespeople
            'vehicles' => Vehicle::all(),
            'customers' => Customer::all(), 
            'sales_preview' => $this->sales
        ]);
    }
}
