<?php

use App\Models\Message;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('allows a student to send a concern to staff and staff can reply', function () {
    $student = User::factory()->create(['role' => 'student']);
    $staff = User::factory()->create(['role' => 'staff']);

    actingAs($student)
        ->post('/messages', [
            'recipient_id' => $staff->id,
            'subject' => 'Need help with enrollment',
            'body' => 'I have a question regarding my enrollment status.',
        ]);

    $message = Message::query()->first();

    expect($message)->not->toBeNull();

    $response = actingAs($student)
        ->get('/messages/' . $message->id);

    $response->assertOk();

    expect($message)->not->toBeNull()
        ->and($message->sender_id)->toBe($student->id)
        ->and($message->recipient_id)->toBe($staff->id)
        ->and($message->subject)->toBe('Need help with enrollment');

    actingAs($staff)
        ->post('/messages/' . $message->id . '/reply', [
            'body' => 'We will review this and get back to you shortly.',
        ])
        ->assertRedirect('/messages/' . $message->id);

    expect(Message::query()->where('recipient_id', $student->id)->where('sender_id', $staff->id)->exists())->toBeTrue();

    actingAs($staff)
        ->delete('/messages/' . $message->id)
        ->assertRedirect('/messages');

    expect(Message::query()->where('conversation_id', $message->conversation_id)->exists())->toBeFalse();
});

it('prevents a student from sending a message to another student', function () {
    $student = User::factory()->create(['role' => 'student']);
    $otherStudent = User::factory()->create(['role' => 'student']);

    actingAs($student)
        ->post('/messages', [
            'recipient_id' => $otherStudent->id,
            'subject' => 'Not allowed',
            'body' => 'This should not be allowed.',
        ])
        ->assertForbidden();
});

it('allows a student to delete their conversation', function () {
    $student = User::factory()->create(['role' => 'student']);
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($student)->post('/messages', [
        'recipient_id' => $admin->id,
        'subject' => 'Delete this concern',
        'body' => 'This concern is no longer needed.',
    ]);

    $message = Message::query()->firstOrFail();

    actingAs($student)
        ->delete('/messages/' . $message->id)
        ->assertRedirect('/messages');

    expect(Message::query()->whereKey($message->id)->exists())->toBeFalse();
});
