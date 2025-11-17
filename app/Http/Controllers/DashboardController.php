<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Vehicle;
use App\Models\Shipment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Variables por defecto
        $stats = [];
        $chartData = [];
        $chartTitle = 'Dashboard';
        $chartType = 'bar';

        // LÓGICA PARA EL ADMINISTRADOR
        if ($user->hasRole('Administrador')) {
            $stats = [
                ['label' => 'Total de Ventas', 'value' => Sale::count(), 'icon' => 'currency-dollar'],
                ['label' => 'Vehículos Disponibles', 'value' => Vehicle::where('estado', 'Disponible')->count(), 'icon' => 'truck'],
                ['label' => 'Total de Usuarios', 'value' => User::count(), 'icon' => 'users'],
            ];
            
            $sales = Sale::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(monto_total) as total_sales')
                )
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            $chartTitle = 'Ventas Totales por Mes';
            $chartType = 'bar';
            $chartData = [
                'labels' => $sales->pluck('month')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Monto Vendido ($)',
                        'data' => $sales->pluck('total_sales')->toArray(),
                        'backgroundColor' => 'rgba(79, 70, 229, 0.7)',
                        'borderColor' => 'rgba(79, 70, 229, 1)',
                        'borderWidth' => 2,
                        'borderRadius' => 8,
                    ],
                ],
            ];
        } 
        
        // LÓGICA PARA EL JEFE DE ALMACÉN
        elseif ($user->hasRole('Jefe de Almacén')) {
            $stats = [
                ['label' => 'Vehículos Disponibles', 'value' => Vehicle::where('estado', 'Disponible')->count(), 'icon' => 'check-circle'],
                ['label' => 'Vehículos Vendidos', 'value' => Vehicle::where('estado', 'Vendido')->count(), 'icon' => 'receipt-percent'],
                ['label' => 'Vehículos en Traslado', 'value' => Vehicle::where('estado', 'En Traslado')->count(), 'icon' => 'truck'],
            ];
            
            $inventory = Vehicle::select('estado', DB::raw('count(*) as count'))
                ->groupBy('estado')
                ->get();
                
            $chartTitle = 'Composición del Inventario';
            $chartType = 'doughnut';
            $chartData = [
                'labels' => $inventory->pluck('estado')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Vehículos',
                        'data' => $inventory->pluck('count')->toArray(),
                        'backgroundColor' => [
                            'rgba(34, 197, 94, 0.7)', // Disponible (Verde)
                            'rgba(239, 68, 68, 0.7)', // Vendido (Rojo)
                            'rgba(234, 179, 8, 0.7)', // En Traslado (Amarillo)
                        ],
                    ],
                ],
            ];
        }
        
        // LÓGICA PARA EL VENDEDOR
        elseif ($user->hasRole('Vendedor')) {
            $mySales = Sale::where('user_id', $user->id);
            $myShipments = Shipment::whereHas('sale', fn($q) => $q->where('user_id', $user->id));

            $stats = [
                ['label' => 'Mis Ventas (Este Mes)', 'value' => (clone $mySales)->whereMonth('created_at', now()->month)->count(), 'icon' => 'receipt-percent'],
                ['label' => 'Monto Vendido (Este Mes)', 'value' => '$' . number_format((clone $mySales)->whereMonth('created_at', now()->month)->sum('monto_total'), 2), 'icon' => 'currency-dollar'],
                ['label' => 'Mis Traslados Pendientes', 'value' => (clone $myShipments)->where('estado', 'Pendiente')->count(), 'icon' => 'clock'],
            ];
            
            $sales = (clone $mySales)->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            $chartTitle = 'Mis Ventas (Últimos 30 Días)';
            $chartType = 'line';
            $chartData = [
                'labels' => $sales->pluck('date')->toArray(),
                'datasets' => [
                    [
                        'label' => 'N° de Ventas',
                        'data' => $sales->pluck('count')->toArray(),
                        'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                        'borderColor' => 'rgba(79, 70, 229, 1)',
                        'borderWidth' => 2,
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
            ];
        }
        
        // LÓGICA PARA EL ENCARGADO DE TRASLADO
        elseif ($user->hasRole('Encargado de Traslado')) {
            $stats = [
                ['label' => 'Traslados Pendientes', 'value' => Shipment::where('estado', 'Pendiente')->count(), 'icon' => 'clock'],
                ['label' => 'Traslados en Tránsito', 'value' => Shipment::where('estado', 'En Tránsito')->count(), 'icon' => 'truck'],
                ['label' => 'Traslados Entregados', 'value' => Shipment::where('estado', 'Entregado')->count(), 'icon' => 'check-circle'],
            ];
            
            $shipments = Shipment::select('estado', DB::raw('count(*) as count'))
                ->groupBy('estado')
                ->get();
                
            $chartTitle = 'Estado de Todos los Traslados';
            $chartType = 'pie';
            $chartData = [
                'labels' => $shipments->pluck('estado')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Traslados',
                        'data' => $shipments->pluck('count')->toArray(),
                        'backgroundColor' => [
                            'rgba(234, 179, 8, 0.7)', // Pendiente (Amarillo)
                            'rgba(59, 130, 246, 0.7)', // En Tránsito (Azul)
                            'rgba(34, 197, 94, 0.7)', // Entregado (Verde)
                        ],
                    ],
                ],
            ];
        }

        // Retornar la vista con los datos
        return view('dashboard', compact('stats', 'chartData', 'chartTitle', 'chartType'));
    }
}