<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\{actingAs, putJson};

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('módosítani tudja a saját feladatát', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'Régi cím']);

    $payload = [
        'title' => 'Frissített cím',
        'status' => 'done'
    ];

    actingAs($user)
        ->putJson("/api/tasks/{$task->id}", $payload)
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Frissített cím']);

    // Ellenőrizzük az adatbázisban is
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Frissített cím',
        'status' => 'done'
    ]);
});

it('nem tudja módosítani más felhasználó feladatát', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $taskOfUserB = Task::factory()->create(['user_id' => $userB->id]);

    actingAs($userA)
        ->putJson("/api/tasks/{$taskOfUserB->id}", ['title' => 'Hackelt cím'])
        ->assertStatus(403); // Tiltott hozzáférés!
});