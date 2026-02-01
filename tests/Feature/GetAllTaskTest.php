<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\{actingAs, getJson};

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('kilistázza a bejelentkezett felhasználó összes feladatát', function () {
    $user = User::factory()->create();
    
    // Létrehozunk 3 saját feladatot
    Task::factory()->count(3)->create(['user_id' => $user->id]);
    
    // Létrehozunk 2 "idegen" feladatot is a rendszerben
    Task::factory()->count(2)->create();

    actingAs($user)
        ->getJson('/api/tasks')
        ->assertStatus(200)
        ->assertJsonCount(3); // Csak a saját 3 taskját láthatja
});