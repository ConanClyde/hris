<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_update_persists_fields_to_session(): void
    {
        $response = $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->put(route('admin.profile.update'), [
            '_token' => csrf_token(),
            'first_name' => 'Jane',
            'middle_name' => 'Q',
            'surname' => 'Public',
            'name_extension' => 'III',
            'email' => 'jane@example.com',
        ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->assertSame('Jane', session('first_name'));
        $this->assertSame('Q', session('middle_name'));
        $this->assertSame('Public', session('surname'));
        $this->assertSame('III', session('name_extension'));
        $this->assertSame('jane@example.com', session('email'));
    }

    public function test_profile_update_uploads_avatar_to_public_disk_and_sets_session_avatar(): void
    {
        Storage::fake('public');

        $tmp = tmpfile();
        $png = "\x89PNG\r\n\x1a\n".
            "\x00\x00\x00\rIHDR".
            "\x00\x00\x00\x01\x00\x00\x00\x01\x08\x06\x00\x00\x00".
            "\x1f\x15\xc4\x89".
            "\x00\x00\x00\x0aIDATx\x9cc\x00\x01\x00\x00\x05\x00\x01".
            "\x0d\x0a\x2d\xb4".
            "\x00\x00\x00\x00IEND\xaeB`\x82";
        fwrite($tmp, $png);
        $meta = stream_get_meta_data($tmp);
        $file = new UploadedFile($meta['uri'], 'avatar.png', 'image/png', null, true);

        $response = $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->put(route('admin.profile.update'), [
            '_token' => csrf_token(),
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('admin.profile'));

        $avatarPath = session('avatar');
        $this->assertNotEmpty($avatarPath);
        Storage::disk('public')->assertExists($avatarPath);
    }

    public function test_profile_update_removes_avatar_from_public_disk_when_requested(): void
    {
        Storage::fake('public');

        $existingPath = 'avatars/existing.png';
        Storage::disk('public')->put($existingPath, 'x');

        $response = $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
            'avatar' => $existingPath,
        ])->put(route('admin.profile.update'), [
            '_token' => csrf_token(),
            'remove_avatar' => '1',
        ]);

        $response->assertRedirect(route('admin.profile'));
        Storage::disk('public')->assertMissing($existingPath);
        $this->assertNull(session('avatar'));
    }
}
