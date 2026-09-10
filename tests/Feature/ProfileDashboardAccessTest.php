<?php

use App\Models\User;

it('allows faculty to edit their own profile information', function () {
    $faculty = User::factory()->create([
        'role' => 'faculty',
        'name' => 'Faculty Person',
        'email' => 'faculty@example.test',
    ]);

    $response = $this->actingAs($faculty)->get(route('profile.edit', $faculty));

    $response->assertOk();
});

it('keeps password blank when optional password is left empty during profile update', function () {
    $faculty = User::factory()->create([
        'role' => 'faculty',
        'name' => 'Old Faculty Name',
        'email' => 'old-faculty@example.test',
        'phone' => '09123456789',
    ]);

    $response = $this->actingAs($faculty)->put(route('profile.update', $faculty), [
        'name' => 'Updated Faculty Name',
        'email' => 'updated-faculty@example.test',
        'phone' => '09987654321',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $faculty->refresh();

    expect($faculty->name)->toBe('Updated Faculty Name')
        ->and($faculty->email)->toBe('updated-faculty@example.test')
        ->and($faculty->phone)->toBe('09987654321');
});

it('shows the external quick access links on the admin and faculty dashboards', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.test']);
    $faculty = User::factory()->create(['role' => 'faculty', 'email' => 'faculty@example.test']);

    $adminResponse = $this->actingAs($admin)->get(route('admin'));
    $adminResponse->assertOk()->assertSee('Saint Francis of Assisi College Main Site');

    $facultyResponse = $this->actingAs($faculty)->get(route('staff'));
    $facultyResponse->assertOk()->assertSee('Saint Francis of Assisi College Main Site');
});
