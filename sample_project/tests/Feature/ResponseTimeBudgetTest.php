<?php

namespace Tests\Feature;

use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseTimeBudgetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Budget thresholds in milliseconds.
     */
    protected const BUDGETS = [
        'login_page' => 500,
        'api_me' => 300,
        'admin_dashboard' => 800,
        'employee_dashboard' => 800,
        'admin_users' => 800,
        'admin_performance' => 800,
        'admin_activity_logs' => 1200,
        'admin_calendar' => 1000,
    ];

    protected function setUp(): void
    {
        parent::setUp();
        // Warm up the application to avoid cold start penalty in first test
        $this->get('/');
    }

    /**
     * Create an admin user and return session headers.
     */
    protected function asAdmin(): self
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        return $this->actingAs($user)->withSession([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
    }

    /**
     * Create an employee user and return session headers.
     */
    protected function asEmployee(): self
    {
        $user = User::factory()->create([
            'role' => 'employee',
            'is_active' => true,
        ]);

        return $this->actingAs($user)->withSession([
            'user_id' => $user->id,
            'role' => 'employee',
        ]);
    }

    protected function measure(callable $callback): float
    {
        $start = microtime(true);
        $callback();
        $end = microtime(true);

        return ($end - $start) * 1000; // ms
    }

    public function test_login_page_renders_within_budget(): void
    {
        // 1. Warm up
        $this->get(route('login'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('login'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['login_page'],
            $duration,
            "Login page took {$duration}ms (budget: ".self::BUDGETS['login_page'].'ms)'
        );
    }

    public function test_api_me_responds_within_budget(): void
    {
        $this->asEmployee();

        // 1. Warm up
        $this->getJson(route('api.me'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->getJson(route('api.me'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['api_me'],
            $duration,
            "API Me took {$duration}ms (budget: ".self::BUDGETS['api_me'].'ms)'
        );
    }

    public function test_admin_dashboard_loads_within_budget(): void
    {
        $this->asAdmin();

        // 1. Warm up
        $this->get(route('admin.dashboard'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('admin.dashboard'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['admin_dashboard'],
            $duration,
            "Admin Dashboard took {$duration}ms (budget: ".self::BUDGETS['admin_dashboard'].'ms)'
        );
    }

    public function test_employee_dashboard_loads_within_budget(): void
    {
        $this->asEmployee();

        // 1. Warm up
        $this->get(route('employee.dashboard'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('employee.dashboard'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['employee_dashboard'],
            $duration,
            "Employee Dashboard took {$duration}ms (budget: ".self::BUDGETS['employee_dashboard'].'ms)'
        );
    }

    public function test_admin_users_loads_within_budget(): void
    {
        $this->asAdmin();

        // Seed some data to make it realistic
        User::factory()->count(10)->create();

        // 1. Warm up
        $this->get(route('admin.users'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('admin.users'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['admin_users'],
            $duration,
            "Admin Users took {$duration}ms (budget: ".self::BUDGETS['admin_users'].'ms)'
        );
    }

    public function test_admin_performance_page_loads_within_budget(): void
    {
        $this->asAdmin();

        // 1. Warm up
        $this->get(route('admin.performance.index'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('admin.performance.index'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['admin_performance'],
            $duration,
            "Admin Performance took {$duration}ms (budget: ".self::BUDGETS['admin_performance'].'ms)'
        );
    }

    public function test_admin_activity_logs_load_within_budget(): void
    {
        $this->asAdmin();

        // 1. Warm up
        $this->get(route('admin.activity-logs.index'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('admin.activity-logs.index'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['admin_activity_logs'],
            $duration,
            "Admin Activity Logs took {$duration}ms (budget: ".self::BUDGETS['admin_activity_logs'].'ms)'
        );
    }

    public function test_admin_calendar_loads_within_budget(): void
    {
        $this->asAdmin();

        // 1. Warm up
        $this->get(route('admin.calendar'));

        // 2. Measure
        $duration = $this->measure(function () {
            $this->get(route('admin.calendar'))->assertStatus(200);
        });

        $this->assertLessThan(
            self::BUDGETS['admin_calendar'],
            $duration,
            "Admin Calendar took {$duration}ms (budget: ".self::BUDGETS['admin_calendar'].'ms)'
        );
    }
}
