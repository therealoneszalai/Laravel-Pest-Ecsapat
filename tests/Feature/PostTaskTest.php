<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{actingAs, postJson};

uses(RefreshDatabase::class);

it('menti az új feladatot valid adatok esetén', function () {
    $user = User::factory()->create();

    $payload = [
        'title' => 'Megtanulni a Pest tesztelést',
        'description' => 'Már egész jól megy a dolog!',
        'status' => 'pending',
    ];

    actingAs($user)
        ->postJson('/api/tasks', $payload)
        ->assertStatus(201)
        ->assertJsonFragment($payload);

    $this->assertDatabaseHas('tasks', $payload);
});

// --- VALIDÁCIÓS TESZTEK ---
describe('PostTask validációs szabályok', function () {

    it('elbukik, ha nincs title megadva', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->postJson('/api/tasks', ['description' => 'Cím nélkül'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    });

    it('elbukik, ha a title túl rövid', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->postJson('/api/tasks', ['title' => 'Ab'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    });
});