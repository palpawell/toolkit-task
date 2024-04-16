<?php

namespace Tests\Feature;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StatementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        Statement::factory(10)->create();

        $this->assertDatabaseCount('statements', 10);
    }

    public function test_client_can_create_ticket()
    {
        $response = $this
            ->postJson('/api/v1/statement/create', [
                'title' => 'Test title 11',
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('statements', [
            'title' => 'Test title 11',
        ]);
    }
}
