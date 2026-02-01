<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\{actingAs, getJson};

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('megjeleníti a saját feladatának részleteit', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Fontos Küldetés'
    ]);

    actingAs($user)
        ->getJson("/api/tasks/{$task->id}")
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Fontos Küldetés']);
});

it('megtagadja a hozzáférést más felhasználó feladatához', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $taskOfUserB = Task::factory()->create(['user_id' => $userB->id]);

    actingAs($userA)
        ->getJson("/api/tasks/{$taskOfUserB->id}")
        ->assertStatus(403);
});