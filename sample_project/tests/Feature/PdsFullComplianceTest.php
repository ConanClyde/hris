<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsBackgroundInfo;
use App\Features\Pds\Models\PdsCscEligibility;
use App\Features\Pds\Models\PdsOtherInfo;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Pds\Models\PdsReference;
use App\Features\Pds\Models\PdsTraining;
use App\Features\Pds\Models\PdsVoluntaryWork;
use App\Features\Pds\Models\PdsWorkExperience;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdsFullComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected $employee;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'employee']);
        $this->employee = Employee::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_employee_can_save_csc_eligibility()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'csc',
            'csc' => [
                [
                    'license_name' => 'Professional Teacher',
                    'rating' => '85.50',
                    'date_of_examination' => '2015-09-27',
                    'place_of_examination' => 'Manila',
                    'license_no' => '1234567',
                    'date_of_validity' => '2018-09-27',
                ],
            ],
        ];

        $response = $this->post(route('employee.pds.store'), $data);
        $response->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $this->assertNotNull($pds);

        $eligibility = PdsCscEligibility::where('pds_id', $pds->id)->first();
        $this->assertNotNull($eligibility);
        $this->assertEquals('Professional Teacher', $eligibility->license_name);
        $this->assertEquals('2015-09-27', $eligibility->date_of_examination->format('Y-m-d'));
    }

    public function test_employee_can_save_work_experience()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'work',
            'work' => [
                [
                    'employed_from' => '2019-01-01',
                    'employed_to' => '2020-12-31',
                    'position_title' => 'Junior Developer',
                    'department' => 'IT Dept',
                    'salary' => '25000',
                    'salary_grade' => '12',
                    'appointment_status' => 'Permanent',
                    'is_government' => 'Y',
                ],
            ],
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $work = PdsWorkExperience::where('pds_id', $pds->id)->first();

        $this->assertEquals('Junior Developer', $work->position_title);
        $this->assertTrue($work->is_government);
        $this->assertEquals('2019-01-01', $work->employed_from->format('Y-m-d'));
    }

    public function test_employee_can_save_voluntary_work()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'voluntary',
            'voluntary' => [
                [
                    'org_name_address' => 'Red Cross',
                    'volunteer_from' => '2021-01-01',
                    'volunteer_to' => '2021-01-02',
                    'number_of_hours' => '16',
                    'nature_of_work' => 'Relief Ops',
                ],
            ],
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $vol = PdsVoluntaryWork::where('pds_id', $pds->id)->first();

        $this->assertEquals('Red Cross', $vol->org_name_address);
        $this->assertEquals(16, $vol->number_of_hours);
    }

    public function test_employee_can_save_training()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'training',
            'training' => [
                [
                    'title' => 'Laravel Advanced',
                    'training_from' => '2022-05-01',
                    'training_to' => '2022-05-05',
                    'number_of_hours' => '40',
                    'training_type' => 'Technical',
                    'sponsor' => 'Tech Corp',
                ],
            ],
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $training = PdsTraining::where('pds_id', $pds->id)->first();

        $this->assertEquals('Laravel Advanced', $training->title);
        $this->assertEquals('2022-05-01', $training->training_from->format('Y-m-d'));
    }

    public function test_employee_can_save_other_info()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'other',
            'other' => [
                [
                    'skills' => 'Programming',
                    'academic_distinctions' => 'Cum Laude',
                    'memberships' => 'PHP Users Group',
                ],
            ],
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $other = PdsOtherInfo::where('pds_id', $pds->id)->first();

        $this->assertEquals('Programming', $other->skills);
        $this->assertEquals('Cum Laude', $other->recognition);
        $this->assertEquals('PHP Users Group', $other->membership);
    }

    public function test_employee_can_save_references()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'references',
            'references' => [
                [
                    'reference_name' => 'John Boss',
                    'reference_address' => 'Makati City',
                    'reference_telno' => '123-4567',
                ],
            ],
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $ref = PdsReference::where('pds_id', $pds->id)->first();

        $this->assertEquals('John Boss', $ref->reference_name);
        $this->assertEquals('Makati City', $ref->reference_address);
    }

    public function test_employee_can_save_background_info()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'background',
            'q34a' => 'No',
            'q34b' => 'Yes',
            'q34_details' => 'Detailed explanation',
            'q35a' => 'No',
            'q35a_details' => 'Nothing',
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $bg = PdsBackgroundInfo::where('pds_id', $pds->id)->first();

        $this->assertEquals('Detailed explanation', $bg->details_34);
        $this->assertEquals('No', $bg->answers['q34a']);
        $this->assertEquals('Yes', $bg->answers['q34b']);
    }

    public function test_employee_can_save_government_ids()
    {
        $this->actingAs($this->user);

        $data = [
            'section' => 'govid',
            'govid_name' => 'Passport',
            'govid_no' => 'P1234567A',
            'govid_dateplace' => 'DFA Manila 2020-01-01',
        ];

        $this->post(route('employee.pds.store'), $data)->assertRedirect();

        $pds = Pds::where('employee_id', $this->employee->id)->first();
        $personal = PdsPersonal::where('pds_id', $pds->id)->first();

        $this->assertEquals('Passport', $personal->gov_id_type);
        $this->assertEquals('P1234567A', $personal->gov_id_no);
    }
}
