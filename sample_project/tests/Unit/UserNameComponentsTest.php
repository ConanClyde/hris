<?php

namespace Tests\Unit;

use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserNameComponentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that setting name automatically parses into components
     */
    public function test_name_mutator_parses_components(): void
    {
        $user = new User;
        $user->name = 'Juan Dela Cruz Jr.';

        $this->assertEquals('Juan', $user->first_name);
        $this->assertNull($user->middle_name);
        $this->assertEquals('Dela Cruz', $user->last_name);
        $this->assertEquals('Jr.', $user->name_extension);
    }

    /**
     * Test parsing name with middle name
     */
    public function test_name_parsing_with_middle_name(): void
    {
        $user = new User;
        $user->name = 'Maria Santos Reyes';

        $this->assertEquals('Maria', $user->first_name);
        $this->assertEquals('Santos', $user->middle_name);
        $this->assertEquals('Reyes', $user->last_name);
        $this->assertNull($user->name_extension);
    }

    /**
     * Test parsing compound surname
     */
    public function test_name_parsing_with_compound_surname(): void
    {
        $user = new User;
        $user->name = 'Juan De Leon Jr.';

        $this->assertEquals('Juan', $user->first_name);
        $this->assertNull($user->middle_name);
        $this->assertEquals('De Leon', $user->last_name);
        $this->assertEquals('Jr.', $user->name_extension);
    }

    /**
     * Test full_name accessor constructs correct full name
     */
    public function test_full_name_accessor(): void
    {
        $user = User::factory()->make([
            'first_name' => 'Juan',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'name_extension' => 'Jr.',
        ]);

        $this->assertEquals('Juan S. Dela Cruz Jr.', $user->full_name);
    }

    /**
     * Test display_name accessor fallback chain
     */
    public function test_display_name_accessor_with_components(): void
    {
        $user = User::factory()->make([
            'first_name' => 'Maria',
            'last_name' => 'Reyes',
            'name' => 'Legacy Name',
        ]);

        // Should prefer component fields
        $this->assertEquals('Maria Reyes', $user->display_name);
    }

    /**
     * Test display_name falls back to legacy name
     */
    public function test_display_name_accessor_fallback_to_legacy(): void
    {
        $user = User::factory()->make([
            'first_name' => null,
            'last_name' => null,
            'name' => 'Legacy Full Name',
        ]);

        $this->assertEquals('Legacy Full Name', $user->display_name);
    }

    /**
     * Test display_name falls back to 'User' when nothing available
     */
    public function test_display_name_accessor_fallback_to_default(): void
    {
        $user = new User;

        $this->assertEquals('User', $user->display_name);
    }

    /**
     * Test fillable includes new name component fields
     */
    public function test_fillable_includes_name_components(): void
    {
        $user = new User;
        $fillable = $user->getFillable();

        $this->assertContains('first_name', $fillable);
        $this->assertContains('middle_name', $fillable);
        $this->assertContains('last_name', $fillable);
        $this->assertContains('name_extension', $fillable);
    }

    /**
     * Test appends includes computed name attributes
     */
    public function test_appends_includes_computed_names(): void
    {
        $user = new User;
        $appends = $user->getAppends();

        $this->assertContains('full_name', $appends);
        $this->assertContains('display_name', $appends);
    }

    /**
     * Test database migration creates new columns
     */
    public function test_database_has_name_component_columns(): void
    {
        $this->artisan('migrate');

        $this->assertTrue(\Schema::hasColumn('users', 'first_name'));
        $this->assertTrue(\Schema::hasColumn('users', 'middle_name'));
        $this->assertTrue(\Schema::hasColumn('users', 'last_name'));
        $this->assertTrue(\Schema::hasColumn('users', 'name_extension'));
    }

    /**
     * Test full name with only first name
     */
    public function test_full_name_with_only_first_name(): void
    {
        $user = User::factory()->make([
            'first_name' => 'Juan',
            'middle_name' => null,
            'last_name' => null,
            'name_extension' => null,
        ]);

        $this->assertEquals('Juan', $user->full_name);
    }

    /**
     * Test full name with extension only
     */
    public function test_full_name_with_extension_only(): void
    {
        $user = User::factory()->make([
            'first_name' => 'Juan',
            'middle_name' => null,
            'last_name' => 'Dela Cruz',
            'name_extension' => 'Jr.',
        ]);

        $this->assertStringContainsString('Jr.', $user->full_name);
    }
}
