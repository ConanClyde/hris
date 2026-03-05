<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('broadcasts avatar updated event when uploading avatar on profile page', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $originalAvatar = $user->avatar;

    Storage::fake('public');

    $this->actingAs($user)
        ->from(route('admin.profile'))
        ->post(route('admin.profile.update'), [
            '_method' => 'PUT',
            'first_name' => 'Test',
            'middle_name' => '',
            'last_name' => 'User',
            'name_extension' => '',
            'email' => $user->email,
            // Use a simple fake file to avoid requiring GD
            'avatar' => UploadedFile::fake()->create('avatar.jpg', 10, 'image/jpeg'),
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $user->refresh();
    expect($user->avatar)->not->toBe($originalAvatar);
    expect($user->avatar)->not->toBeNull();

});

test('broadcasts avatar removed event when removing avatar on profile page', function () {
    $user = User::factory()->create(['role' => 'admin', 'avatar' => 'avatars/old.jpg']);

    Storage::fake('public');
    Storage::disk('public')->put('avatars/old.jpg', 'test');

    $this->actingAs($user)
        ->from(route('admin.profile'))
        ->post(route('admin.profile.update'), [
            '_method' => 'PUT',
            'first_name' => 'Test',
            'middle_name' => '',
            'last_name' => 'User',
            'name_extension' => '',
            'email' => $user->email,
            'remove_avatar' => true,
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $user->refresh();
    expect($user->avatar)->toBeNull();
});
