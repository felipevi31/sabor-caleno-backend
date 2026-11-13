<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminDashboardAndReportsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que un usuario sin sesión (invitado) sea rechazado.
     */
    public function test_invitado_no_puede_acceder_a_metricas_ni_reportes(): void
    {
        $this->getJson('/api/admin/metrics')->assertStatus(401);
        $this->getJson('/api/admin/reports/sales-excel')->assertStatus(401);
        $this->getJson('/api/admin/reports/users-excel')->assertStatus(401);
    }

    /**
     * Verifica que un cliente normal (role != admin) reciba error 403.
     */
    public function test_cliente_normal_no_tiene_permisos_de_administrador(): void
    {
        $cliente = User::factory()->create([
            'role' => 'client',
        ]);

        Sanctum::actingAs($cliente);

        $this->getJson('/api/admin/metrics')->assertStatus(403);
        $this->getJson('/api/admin/reports/sales-excel')->assertStatus(403);
        $this->getJson('/api/admin/reports/users-excel')->assertStatus(403);
    }

    /**
     * Verifica que el administrador vea las métricas correctamente.
     */
    public function test_admin_puede_consultar_metricas_del_panel(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/metrics');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'metrics' => [
                         'total_sales',
                         'total_orders',
                         'total_users',
                     ],
                     'orders',
                 ]);
    }

    /**
     * Verifica que el administrador pueda descargar los reportes CSV de ventas y clientes.
     */
    public function test_admin_puede_descargar_reportes_csv(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Sanctum::actingAs($admin);

        // 1. Probar reporte de ventas
        $salesResponse = $this->get('/api/admin/reports/sales-excel');
        $salesResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', $salesResponse->headers->get('Content-Type'));

        // 2. Probar reporte de clientes
        $usersResponse = $this->get('/api/admin/reports/users-excel');
        $usersResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', $usersResponse->headers->get('Content-Type'));
    }
}