<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas Premium</title>
    <style>
        /* Base & Reset */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #475569; /* Slate 600 */
            line-height: 1.4;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Utils */
        .w-full { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .text-xs { font-size: 10px; }
        .text-sm { font-size: 12px; }
        .text-xl { font-size: 20px; }
        
        /* Layout Components */
        .container {
            padding: 20px;
        }

        /* Header */
        .header-bg {
            background-color: #f8fafc; /* Slate 50 */
            padding: 30px;
            border-bottom: 2px solid #e2e8f0; /* Slate 200 */
        }
        
        .brand-title {
            color: #1e293b; /* Slate 800 */
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            color: #6366f1; /* Indigo 500 */
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        /* Report Meta Info */
        .meta-grid {
            margin-top: 20px;
            width: 100%;
        }
        .meta-box {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .meta-label {
            color: #94a3b8; /* Slate 400 */
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }
        .meta-value {
            color: #334155; /* Slate 700 */
            font-weight: 600;
            font-size: 11px;
        }

        /* Table Design */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
        }
        
        th {
            background-color: #f1f5f9; /* Slate 100 */
            color: #475569; /* Slate 600 */
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            padding: 12px 10px;
            border-bottom: 2px solid #cbd5e1; /* Slate 300 */
            text-align: left;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0; /* Slate 200 */
            color: #334155; /* Slate 700 */
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f8fafc; /* Slate 50 */
        }
        
        /* Highlight specific columns */
        .col-boleta { font-family: monospace; color: #6366f1; font-weight: bold; }
        .col-monto { font-weight: bold; color: #0f172a; }

        /* Totals Section */
        .totals-section {
            margin-top: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0;
            page-break-inside: avoid;
        }
        .totals-table td {
            border: none;
            padding: 10px 20px;
        }
        .total-label {
            text-align: right; 
            font-weight: 600; 
            color: #64748b;
        }
        .total-amount {
            text-align: right; 
            font-weight: 800; 
            color: #1e293b; 
            font-size: 16px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            margin-top: 30px;
            background-color: #fff;
        }
        .footer-text {
            color: #94a3b8;
            font-size: 10px;
        }
        
        /* Decorative Elements */
        .accent-bar {
            height: 4px;
            background: linear-gradient(to right, #6366f1, #a855f7, #ec4899); /* Indigo, Purple, Pink */
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Decorative Top Bar -->
    <div class="accent-bar"></div>

    <!-- Header Section -->
    <div class="header-bg">
        <table class="w-full">
            <tr>
                <td style="border:none; padding:0;">
                    <div class="brand-title">CARS SCAPE</div>
                    <div class="brand-subtitle">Gestión Automotriz Premium</div>
                </td>
                <td style="border:none; padding:0; text-align:right;">
                    <div class="brand-title" style="font-size: 16px; color: #64748b;">Reporte de Ventas</div>
                    <div class="brand-subtitle" style="color: #94a3b8;">Generado el: {{ now()->format('d/m/Y h:i A') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        <!-- Filters / Meta Info -->
        <div class="meta-box">
            <table class="w-full">
                <tr>
                    <td style="border:none; padding:0; width: 33%;">
                        <span class="meta-label">Rango de Fechas</span>
                        <span class="meta-value">
                            {{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }}
                        </span>
                    </td>
                    <td style="border:none; padding:0; width: 33%;">
                        <span class="meta-label">Generado Por</span>
                        <span class="meta-value">{{ auth()->user()->name }}</span>
                    </td>
                    <td style="border:none; padding:0; width: 33%;">
                        <span class="meta-label">Filtros Aplicados</span>
                        <div class="meta-value">
                            @if($filters['user'] !== 'Todos') Vendedor: {{ $filters['user'] }}<br> @endif
                            @if($filters['vehicle'] !== 'Todos') Vehículo: {{ $filters['vehicle'] }}<br> @endif
                            @if(!empty($filters['search'])) Búsqueda: "{{ $filters['search'] }}" @endif
                            @if($filters['user'] === 'Todos' && $filters['vehicle'] === 'Todos' && empty($filters['search']))
                                Ninguno (Reporte General)
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Sales Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Fecha</th>
                    <th style="width: 15%;">Boleta</th>
                    <th style="width: 25%;">Cliente</th>
                    <th style="width: 18%;">Vendedor</th>
                    <th style="width: 18%;">Vehículo</th>
                    <th style="width: 12%; text-align: right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                        <td class="col-boleta">{{ $sale->numero_boleta }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ $sale->customer->nombres_completos ?? 'N/A' }}</div>
                            <div style="font-size: 9px; color: #94a3b8;">{{ $sale->customer->numero_documento ?? '' }}</div>
                        </td>
                        <td>{{ $sale->user->name ?? 'N/A' }}</td>
                        <td>
                            {{ $sale->vehicle->marca ?? '' }} 
                            <span style="color: #64748b;">{{ $sale->vehicle->modelo ?? '' }}</span>
                        </td>
                        <td class="text-right col-monto">${{ number_format($sale->monto_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Footer -->
        <div class="totals-section">
            <table class="totals-table w-full">
                <tr>
                    <td class="total-label" style="width: 70%;">TOTAL VENTAS:</td>
                    <td class="total-amount">${{ number_format($sales->sum('monto_total'), 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Signature / Notes Area (Empty for professional look) -->
        <div style="margin-top: 40px; border-top: 1px dashed #cbd5e1; padding-top: 10px;">
            <p style="text-align: center; color: #94a3b8; font-size: 10px;">
                Este documento es un reporte generado por el sistema y no requiere firma manual.
            </p>
        </div>
    </div>

    <!-- Page Footer -->
    <div class="footer">
        <div class="footer-text">
            &copy; {{ date('Y') }} Cars Scape System. Reservados todos los derechos.
        </div>
        <!-- Little dots like welcome page -->
        <div style="text-align: center; margin-top: 5px;">
            <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#c7d2fe; margin:0 2px;"></span>
            <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#e9d5ff; margin:0 2px;"></span>
            <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#fbcfe8; margin:0 2px;"></span>
        </div>
    </div>

</body>
</html>
