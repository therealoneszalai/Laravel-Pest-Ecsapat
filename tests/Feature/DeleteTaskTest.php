<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\{actingAs, deleteJson};

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('törölni tudja a saját feladatát', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->deleteJson("/api/tasks/{$task->id}")
        ->assertStatus(200);

    // Ellenőrizzük, hogy tényleg eltűnt-e
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('nem tudja törölni más felhasználó feladatát', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $taskOfUserB = Task::factory()->create(['user_id' => $userB->id]);

    actingAs($userA)
        ->deleteJson("/api/tasks/{$taskOfUserB->id}")
        ->assertStatus(403);

    // Ellenőrizzük, hogy az adat még mindig megvan
    $this->assertDatabaseHas('tasks', ['id' => $taskOfUserB->id]);
});