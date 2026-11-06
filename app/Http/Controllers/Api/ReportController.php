<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Reporte de Ventas
    public function salesExcel(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $fileName = 'Ventas_Sabor_Caleno_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['ID Pedido', 'Fecha / Hora', 'Cliente', 'Teléfono', 'Barrio', 'Total (COP)', 'Estado']);

            $orders = class_exists(Order::class) ? Order::with('user')->latest()->get() : [];

            foreach ($orders as $order) {
                fputcsv($file, [
                    'SC-' . $order->id,
                    $order->created_at,
                    $order->user->name ?? $order->customer_name ?? 'Cliente General',
                    $order->phone ?? 'Sin teléfono',
                    $order->neighborhood ?? 'El Carmen de Viboral',
                    $order->total,
                    $order->status ?? 'Pendiente'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Reporte de Clientes
    public function usersExcel(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $fileName = 'Clientes_Sabor_Caleno_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['ID Usuario', 'Nombre Completo', 'Correo Electrónico', 'Fecha de Registro']);

            $users = User::all();

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}