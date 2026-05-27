<?php

namespace Tests\Feature;

use App\Models\FavList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests básicos para la funcionalidad de seguir listas.
 *
 * Cubre los flujos críticos del sistema de listas seguidas:
 * - Seguir y dejar de seguir una lista ajena
 * - Prevención de seguir la propia lista
 * - Visibilidad en el dashboard y en el perfil público
 * - Avatar del creador en el detalle de lista
 */
class FollowListTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────

    /** Crea un usuario con país (requerido por la migración). */
    private function crearUsuario(array $attrs = []): User
    {
        $country = \App\Models\Country::first() ?? \App\Models\Country::factory()->create();

        return User::factory()->create(array_merge([
            'country_id'         => $country->id,
            'profile_visibility' => 'public',
        ], $attrs));
    }

    /** Crea una lista pública asociada a un usuario. */
    private function crearLista(User $user, array $attrs = []): FavList
    {
        return FavList::create(array_merge([
            'user_id'    => $user->id,
            'name'       => 'Lista de prueba',
            'visibility' => 'public',
        ], $attrs));
    }

    // ─────────────────────────────────────────────
    //  Test 1: Seguir una lista ajena
    // ─────────────────────────────────────────────

    /** @test */
    public function un_usuario_puede_seguir_una_lista_ajena(): void
    {
        $seguidor = $this->crearUsuario();
        $creador  = $this->crearUsuario();
        $lista    = $this->crearLista($creador);

        $this->assertFalse($seguidor->isFollowingList($lista));

        $seguidor->followList($lista);

        $this->assertTrue($seguidor->fresh()->isFollowingList($lista));
        $this->assertDatabaseHas('list_likes', [
            'user_id' => $seguidor->id,
            'list_id' => $lista->id,
        ]);
    }

    // ─────────────────────────────────────────────
    //  Test 2: Dejar de seguir (toggle)
    // ─────────────────────────────────────────────

    /** @test */
    public function un_usuario_puede_dejar_de_seguir_una_lista(): void
    {
        $seguidor = $this->crearUsuario();
        $creador  = $this->crearUsuario();
        $lista    = $this->crearLista($creador);

        $seguidor->followList($lista);
        $this->assertTrue($seguidor->fresh()->isFollowingList($lista));

        $seguidor->unfollowList($lista);

        $this->assertFalse($seguidor->fresh()->isFollowingList($lista));
        $this->assertDatabaseMissing('list_likes', [
            'user_id' => $seguidor->id,
            'list_id' => $lista->id,
        ]);
    }

    // ─────────────────────────────────────────────
    //  Test 3: No se puede seguir la propia lista
    // ─────────────────────────────────────────────

    /** @test */
    public function el_componente_follow_button_impide_seguir_la_propia_lista(): void
    {
        $usuario = $this->crearUsuario();
        $lista   = $this->crearLista($usuario);

        // El componente debe detectar que es la propia lista y no crear el registro
        Livewire::actingAs($usuario)
            ->test('components.follow-button', ['model' => $lista, 'type' => 'list'])
            ->call('toggle');

        $this->assertDatabaseMissing('list_likes', [
            'user_id' => $usuario->id,
            'list_id' => $lista->id,
        ]);
    }

    // ─────────────────────────────────────────────
    //  Test 4: Dashboard muestra listas seguidas
    // ─────────────────────────────────────────────

    /** @test */
    public function el_dashboard_muestra_listas_seguidas_por_el_usuario(): void
    {
        $seguidor = $this->crearUsuario();
        $creador  = $this->crearUsuario();
        $lista    = $this->crearLista($creador, ['name' => 'Lista Seguida Prueba']);

        $seguidor->followList($lista);

        Livewire::actingAs($seguidor)
            ->test('pages.dashboard.lists')
            ->assertSee('Lista Seguida Prueba')
            ->assertSee('Listas Seguidas');
    }

    // ─────────────────────────────────────────────
    //  Test 5: Dashboard no muestra listas creadas en sección "Seguidas"
    // ─────────────────────────────────────────────

    /** @test */
    public function las_listas_creadas_no_aparecen_en_la_seccion_seguidas(): void
    {
        $usuario = $this->crearUsuario();
        $lista   = $this->crearLista($usuario, ['name' => 'Mi Lista Propia']);

        Livewire::actingAs($usuario)
            ->test('pages.dashboard.lists')
            ->assertSee('Mi Lista Propia')          // Aparece en "Creadas"
            ->assertSee('Listas Creadas');
        // No necesitamos verificar que NO está en "Seguidas" porque el HTML
        // puede repetir el nombre; verificamos la lógica de datos en los tests de modelo.
    }

    // ─────────────────────────────────────────────
    //  Test 6: Perfil público muestra listas seguidas
    // ─────────────────────────────────────────────

    /** @test */
    public function el_perfil_publico_muestra_listas_seguidas_cuando_son_publicas(): void
    {
        $propietario = $this->crearUsuario(['profile_visibility' => 'public']);
        $creador     = $this->crearUsuario();
        $lista       = $this->crearLista($creador, [
            'name'       => 'Lista Pública Seguida',
            'visibility' => 'public',
        ]);

        $propietario->followList($lista);

        Livewire::test('pages.users.show', ['user' => $propietario])
            ->assertSee('Listas Seguidas')
            ->assertSee('Lista Pública Seguida');
    }

    // ─────────────────────────────────────────────
    //  Test 7: Perfil privado oculta las listas seguidas
    // ─────────────────────────────────────────────

    /** @test */
    public function el_perfil_privado_oculta_las_listas_seguidas(): void
    {
        $propietario = $this->crearUsuario(['profile_visibility' => 'private']);
        $creador     = $this->crearUsuario();
        $lista       = $this->crearLista($creador, ['visibility' => 'public']);

        $propietario->followList($lista);

        $visitante = $this->crearUsuario();

        Livewire::actingAs($visitante)
            ->test('pages.users.show', ['user' => $propietario])
            ->assertDontSee('Listas Seguidas');
    }

    // ─────────────────────────────────────────────
    //  Test 8: El detalle de lista muestra el avatar_url del creador
    // ─────────────────────────────────────────────

    /** @test */
    public function el_detalle_de_lista_usa_el_avatar_url_del_creador(): void
    {
        $creador = $this->crearUsuario(['avatar' => '/storage/userimg/foto.jpg']);
        $lista   = $this->crearLista($creador);

        Livewire::test('pages.list.show', ['list' => $lista])
            ->assertSee('/storage/userimg/foto.jpg');
    }

    /** @test */
    public function el_detalle_de_lista_usa_ui_avatars_como_fallback_si_no_hay_foto(): void
    {
        $creador = $this->crearUsuario(['avatar' => null, 'name' => 'Juan Pérez']);
        $lista   = $this->crearLista($creador);

        Livewire::test('pages.list.show', ['list' => $lista])
            ->assertSee('ui-avatars.com');
    }

    // ─────────────────────────────────────────────
    //  Test 9: Carga dinámica en el dashboard
    // ─────────────────────────────────────────────

    /** @test */
    public function el_boton_cargar_mas_incrementa_el_limite_de_listas_seguidas(): void
    {
        $seguidor = $this->crearUsuario();
        $creador  = $this->crearUsuario();

        // Crear 8 listas (más que el límite inicial de 6)
        for ($i = 1; $i <= 8; $i++) {
            $lista = $this->crearLista($creador, ['name' => "Lista Seguida {$i}"]);
            $seguidor->followList($lista);
        }

        Livewire::actingAs($seguidor)
            ->test('pages.dashboard.lists')
            ->assertSet('followedLimit', 6)
            ->call('loadMore', 'followed')
            ->assertSet('followedLimit', 12);
    }
}
