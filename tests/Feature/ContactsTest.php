<?php

use App\Models\Contacts;
use App\Models\User;

test('registrar un usuario', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Juan',
        'email' => 'juan@test.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Registrado exitosamente'])
        ->assertJsonStructure(['token', 'user']);
});

test('actualizar informacion del usuario', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/update', [
        'name' => 'Juan Actualizado',
        'email' => 'juan2@test.com',
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Usuario actualizado correctamente']);

    $user->refresh();
    expect($user->name)->toBe('Juan Actualizado');
    expect($user->email)->toBe('juan2@test.com');
});

test('no permitir registrar con correo duplicado', function () {
    User::factory()->create(['email' => 'juan@test.com']);

    $response = $this->postJson('/api/register', [
        'name' => 'Juan',
        'email' => 'juan@test.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $response->assertStatus(422);
});

test('crear un contacto', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/contacts', [
        'name' => 'Pedro',
        'phone_number' => '123456789',
    ]);

    $response->assertStatus(201)
        ->assertJson(['message' => 'Contacto creado correctamente']);
});

test('listar contactos del usuario', function () {
    $user = User::factory()->create();
    Contacts::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/contacts');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'contacts');
});

test('no permitir contacto con mismo telefono', function () {
    $user = User::factory()->create();
    Contacts::factory()->create([
        'user_id' => $user->id,
        'phone_number' => '123456789',
    ]);

    $response = $this->actingAs($user)->postJson('/api/contacts', [
        'name' => 'Pedro',
        'phone_number' => '123456789',
    ]);

    $response->assertStatus(422);
});

test('no permitir ver contactos de otro usuario', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Contacts::factory()->count(2)->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user1)->getJson('/api/contacts');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'contacts');
});
