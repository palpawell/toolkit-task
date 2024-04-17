<?php

namespace Tests\Feature;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StatementTest extends TestCase
{
    use RefreshDatabase;

    private Authenticatable $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    public function test_client_can_create_statement()
    {
        Statement::factory(10)->create(['user_id' => $this->user->id]);
        $this->assertDatabaseCount('statements', 10);

        $response = $this
            ->postJson('/api/v1/statement/create', [
                'title' => 'Test title 11',
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('statements', [
            'title' => 'Test title 11',
        ]);
    }

    public function test_client_can_create_statement_with_file()
    {
        Statement::factory(10)->create(['user_id' => $this->user->id]);
        $this->assertDatabaseCount('statements', 10);

        Storage::fake('public');

        $fileName = 'document.pdf';
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this
            ->post('/api/v1/statement/create', [
                    'title' => 'Statement with file',
                    'file' => $file,
                ]
            );

        $response->assertOk();

        $this->assertDatabaseHas('statements', [
            'title' => 'Statement with file',
            'file' => $fileName,
        ]);

        Storage::disk('public')->assertExists("uploads/$fileName");
    }

    public function test_client_can_list_statements()
    {
        Statement::factory(10)->create(['user_id' => $this->user->id]);
        $this->assertDatabaseCount('statements', 10);

        $response = $this
            ->getJson('/api/v1/statement');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('models.data')
            );

        $responseData = $response->json();
        $this->assertTrue(count($responseData['models']['data']) > 0);
    }

    public function test_client_can_delete_statement()
    {
        $models = Statement::factory(10)->create(['user_id' => $this->user->id]);
        $this->assertDatabaseCount('statements', 10);

        $deleteID = $models[0]->id;

        $response = $this
            ->delete("/api/v1/statement?id=$deleteID");

        $response->assertOk();

        $this->assertDatabaseMissing('statements', [
            'id' => $deleteID,
        ]);
    }
}
