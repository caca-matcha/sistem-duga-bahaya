<?php

use App\Models\Cell;
use App\Models\Map;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'she']);
    $this->actingAs($this->user);
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class); // Bypass CSRF
});

it('can create a pabrik map and ensures only one exists', function () {
    // Test creating the first Pabrik map
    $response = $this->postJson(route('she.maps.pabrik.store'), [
        'name' => 'Pabrik Utama',
        'rows' => 10,
        'cols' => 10,
        'created_by' => $this->user->id,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('she.maps.factory_index'));
    $response->assertSessionHas('success', 'Peta Risiko Pabrik berhasil dibuat.');
    $this->assertDatabaseHas('maps', ['name' => 'Pabrik Utama', 'type' => 'Pabrik']);

    // Test trying to create a second Pabrik map
    $response = $this->postJson(route('she.maps.pabrik.store'), [
        'name' => 'Pabrik Kedua',
        'rows' => 5,
        'cols' => 5,
        'created_by' => $this->user->id,
    ]);

    $response->assertSessionHasErrors(); // Should have errors due to existing Pabrik map
    $response->assertRedirect(route('she.maps.factory_index'));
    $response->assertSessionHas('error', 'Peta Risiko Pabrik sudah ada. Anda hanya dapat membuat satu Peta Risiko Pabrik.');
    $this->assertDatabaseMissing('maps', ['name' => 'Pabrik Kedua']);
});

it('stores a pabrik cell with aggregated risk from a gedung map', function () {
    // 1. Create a Gedung Map and some cells for it
    $gedungMap = Map::factory()->create(['type' => 'Gedung', 'rows' => 2, 'cols' => 2, 'created_by' => $this->user->id]);
    $gedungCells = [
        ['map_id' => $gedungMap->id, 'row_index' => 0, 'col_index' => 0, 'risk_score' => 5],
        ['map_id' => $gedungMap->id, 'row_index' => 0, 'col_index' => 1, 'risk_score' => 10],
        ['map_id' => $gedungMap->id, 'row_index' => 1, 'col_index' => 0, 'risk_score' => 15],
    ];
    foreach ($gedungCells as $cellData) {
        Cell::create($cellData);
    }
    // Expected average for gedungMap: (5 + 10 + 15) / 3 = 10

    // 2. Create a Pabrik Map
    $pabrikMap = Map::factory()->create(['type' => 'Pabrik', 'rows' => 1, 'cols' => 1, 'created_by' => $this->user->id]);

    // 3. Create a cell for the Pabrik Map, linking it to the Gedung Map
    $response = $this->postJson(route('she.api.cells.store'), [
        'map_id' => $pabrikMap->id,
        'row_index' => 0,
        'col_index' => 0,
        'gedung_map_id' => $gedungMap->id,
        'area_name' => 'Area Pabrik',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('cells', [
        'map_id' => $pabrikMap->id,
        'row_index' => 0,
        'col_index' => 0,
        'risk_score' => 10, // Aggregated average from gedungMap cells
        'metadata' => json_encode(['gedung_map_id' => $gedungMap->id]),
        'area_name' => 'Area Pabrik',
    ]);

    $pabrikCell = Cell::where('map_id', $pabrikMap->id)->first();
    expect($pabrikCell->metadata['gedung_map_id'])->toBe($gedungMap->id);
});

it('updates a pabrik cell with aggregated risk from a gedung map', function () {
    // 1. Create a Gedung Map and some cells for it
    $gedungMap = Map::factory()->create(['type' => 'Gedung', 'rows' => 2, 'cols' => 2, 'created_by' => $this->user->id]);
    $gedungCells = [
        ['map_id' => $gedungMap->id, 'row_index' => 0, 'col_index' => 0, 'risk_score' => 5],
        ['map_id' => $gedungMap->id, 'row_index' => 0, 'col_index' => 1, 'risk_score' => 10],
    ];
    foreach ($gedungCells as $cellData) {
        Cell::create($cellData);
    }
    // Expected average for gedungMap: (5 + 10) / 2 = 7.5 -> 8 (rounded)

    // 2. Create a Pabrik Map and an initial Pabrik cell
    $pabrikMap = Map::factory()->create(['type' => 'Pabrik', 'rows' => 1, 'cols' => 1, 'created_by' => $this->user->id]);
    $pabrikCell = Cell::factory()->create([
        'map_id' => $pabrikMap->id,
        'row_index' => 0,
        'col_index' => 0,
        'risk_score' => 0, // Initial score
        'metadata' => ['gedung_map_id' => null],
    ]);

    // 3. Update the Pabrik cell, linking it to the Gedung Map
    $response = $this->putJson(route('she.api.cells.update', $pabrikCell->id), [
        'gedung_map_id' => $gedungMap->id,
        'area_name' => 'Updated Area Pabrik',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('cells', [
        'id' => $pabrikCell->id,
        'map_id' => $pabrikMap->id,
        'row_index' => 0,
        'col_index' => 0,
        'risk_score' => 8, // Aggregated average from gedungMap cells
        'metadata' => json_encode(['gedung_map_id' => $gedungMap->id]),
        'area_name' => 'Updated Area Pabrik',
    ]);

    $updatedPabrikCell = Cell::find($pabrikCell->id);
    expect($updatedPabrikCell->metadata['gedung_map_id'])->toBe($gedungMap->id);
});

it('validates gedung_map_id for pabrik cell store', function () {
    $pabrikMap = Map::factory()->create(['type' => 'Pabrik', 'rows' => 1, 'cols' => 1, 'created_by' => $this->user->id]);

    // Invalid gedung_map_id
    $response = $this->postJson(route('she.api.cells.store'), [
        'map_id' => $pabrikMap->id,
        'row_index' => 0,
        'col_index' => 0,
        'gedung_map_id' => 9999, // Non-existent ID
    ]);

    $response->assertStatus(422); // Unprocessable Entity
    $response->assertJsonValidationErrors(['gedung_map_id']);
});

it('validates gedung_map_id for pabrik cell update', function () {
    $pabrikMap = Map::factory()->create(['type' => 'Pabrik', 'rows' => 1, 'cols' => 1, 'created_by' => $this->user->id]);
    $pabrikCell = Cell::factory()->create(['map_id' => $pabrikMap->id]);

    // Invalid gedung_map_id
    $response = $this->putJson(route('she.api.cells.update', $pabrikCell->id), [
        'gedung_map_id' => 9999, // Non-existent ID
    ]);

    $response->assertStatus(422); // Unprocessable Entity
    $response->assertJsonValidationErrors(['gedung_map_id']);
});
