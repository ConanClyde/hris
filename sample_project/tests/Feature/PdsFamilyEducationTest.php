<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsChild;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdsFamilyEducationTest extends TestCase
{
    use RefreshDatabase;

    protected $employee;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user and employee
        $this->user = User::factory()->create(['role' => 'employee']);
        $this->employee = Employee::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_employee_can_save_family_background()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'c1_family',
            'spouse_name' => 'Jane Doe', // Mapped to spouse_surname temporarily
            'spouse_occupation' => 'Engineer',
            'father_surname' => 'Doe',
            'father_first_name' => 'John',
            'mother_maiden_surname' => 'Smith',
            'mother_maiden_first_name' => 'Mary',
        ];

        $response = $this->post(route('employee.pds.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pds_families', [
            'spouse_surname' => 'Jane Doe',
            'spouse_occupation' => 'Engineer',
            'father_surname' => 'Doe',
            'mother_maiden_surname' => 'Smith',
        ]);
    }

    public function test_employee_can_save_children()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'c1_family',
            'children' => [
                ['name' => 'Child 1', 'dob' => '2010-01-01'],
                ['name' => 'Child 2', 'dob' => '2012-05-05'],
            ],
        ];

        $response = $this->post(route('employee.pds.store'), $data);

        $response->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $this->assertNotNull($pds);

        $this->assertDatabaseHas('pds_children', [
            'pds_id' => $pds->id,
            'name' => 'Child 1',
        ]);

        $child1 = PdsChild::where('pds_id', $pds->id)->where('name', 'Child 1')->first();
        $this->assertEquals('2010-01-01', $child1->dob->format('Y-m-d'));

        $this->assertDatabaseHas('pds_children', [
            'pds_id' => $pds->id,
            'name' => 'Child 2',
        ]);

        $child2 = PdsChild::where('pds_id', $pds->id)->where('name', 'Child 2')->first();
        $this->assertEquals('2012-05-05', $child2->dob->format('Y-m-d'));

        $this->assertEquals(2, $pds->children()->count());
    }

    public function test_employee_can_update_children()
    {
        $this->actingAs($this->user);

        // Initial save
        $this->post(route('employee.pds.store'), [
            'section' => 'c1_family',
            'children' => [
                ['name' => 'Old Child', 'dob' => '2010-01-01'],
            ],
        ]);

        // Update (remove Old Child, add New Child)
        $this->post(route('employee.pds.store'), [
            'section' => 'c1_family',
            'children' => [
                ['name' => 'New Child', 'dob' => '2015-01-01'],
            ],
        ]);

        $this->assertDatabaseMissing('pds_children', ['name' => 'Old Child']);
        $this->assertDatabaseHas('pds_children', ['name' => 'New Child']);
    }

    public function test_employee_can_save_education()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'c1_education',
            'education' => [
                'elementary' => [
                    'school' => 'Elementary School',
                    'year_graduated' => '2000',
                ],
                'college' => [
                    'school' => 'University',
                    'degree_course' => 'BS CS',
                    'year_graduated' => '2008',
                ],
            ],
        ];

        $response = $this->post(route('employee.pds.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pds = Pds::where('employee_id', $this->employee->id)->first();

        $this->assertDatabaseHas('pds_education', [
            'pds_id' => $pds->id,
            'level' => 'Elementary',
            'school_name' => 'Elementary School',
        ]);

        $this->assertDatabaseHas('pds_education', [
            'pds_id' => $pds->id,
            'level' => 'College',
            'school_name' => 'University',
            'degree_course' => 'BS CS',
        ]);
    }
}
