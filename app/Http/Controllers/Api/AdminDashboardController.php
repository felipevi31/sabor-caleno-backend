<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order; // O la tabla/modelo donde guardas las ventas y pedidos
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function getMetrics(Request $request)
    {
        // Validar que el usuario sea administrador
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso restringido'], 403);
        }

        // Si aún no tienes tabla 'orders', toma valores dinámicos basados en la BD de usuarios
        $totalUsers = User::count();
        
        // Consultar pedidos reales (si la tabla Order existe en tu BD)
        $totalSales = class_exists(Order::class) ? Order::sum('total') : 0;
        $totalOrders = class_exists(Order::class) ? Order::count() : 0;
        
        // Obtener los últimos pedidos formato entendible
        $latestOrders = class_exists(Order::class) 
            ? Order::with('user')->latest()->take(10)->get()->map(function ($order) {
                return [
                    'id' => 'SC-' . $order->id,
                    'client' => $order->user->name ?? $order->customer_name ?? 'Cliente General',
                    'phone' => $order->phone ?? 'Sin teléfono',
                    'barrio' => $order->neighborhood ?? 'El Carmen de Viboral',
                    'items' => $order->details_summary ?? 'Pedido de pollo y acompañamientos',
                    'total' => (float) $order->total,
                    'status' => $order->status ?? 'Pendiente',
                    'time' => $order->created_at->diffForHumans(),
                ];
            })
            : [];

        return response()->json([
            'metrics' => [
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers,
            ],
            'orders' => $latestOrders
        ]);
    }
}   