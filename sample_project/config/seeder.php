<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Seeder Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control the default credentials and data used when
    | seeding the database. All sensitive values should be set via
    | environment variables in production environments.
    |
    */

    'admin' => [
        'name' => env('SEEDER_ADMIN_NAME', 'System Administrator'),
        'email' => env('SEEDER_ADMIN_EMAIL', 'admin@hris.local'),
        'password' => env('SEEDER_ADMIN_PASSWORD', 'admin123'),
        'role' => 'admin',
        'position' => env('SEEDER_ADMIN_POSITION', 'ICT Manager'),
        'division' => env('SEEDER_ADMIN_DIVISION', 'Chief of Hospital Offices Division'),
        'section' => env('SEEDER_ADMIN_SECTION', 'Information and Communications Technology Unit'),
    ],

    'hr_manager' => [
        'name' => env('SEEDER_HR_MANAGER_NAME', 'HR Manager'),
        'email' => env('SEEDER_HR_MANAGER_EMAIL', 'hr@hris.local'),
        'password' => env('SEEDER_HR_MANAGER_PASSWORD', 'hr123'),
        'role' => 'hr',
        'position' => env('SEEDER_HR_MANAGER_POSITION', 'Human Resource Management Officer III'),
        'division' => env('SEEDER_HR_MANAGER_DIVISION', 'Finance and Administrative Division'),
        'section' => env('SEEDER_HR_MANAGER_SECTION', 'Human Resource Management Section'),
    ],

    'hr_staff' => [
        'name' => env('SEEDER_HR_STAFF_NAME', 'HR Staff'),
        'email' => env('SEEDER_HR_STAFF_EMAIL', 'hrstaff@hris.local'),
        'password' => env('SEEDER_HR_STAFF_PASSWORD', 'hr123'),
        'role' => 'hr',
        'position' => env('SEEDER_HR_STAFF_POSITION', 'HR Specialist'),
        'division' => env('SEEDER_HR_STAFF_DIVISION', 'Finance and Administrative Division'),
        'section' => env('SEEDER_HR_STAFF_SECTION', 'Human Resource Management Section'),
    ],

    'employee' => [
        'name' => env('SEEDER_EMPLOYEE_NAME', 'Juan Dela Cruz'),
        'email' => env('SEEDER_EMPLOYEE_EMAIL', 'employee@hris.local'),
        'password' => env('SEEDER_EMPLOYEE_PASSWORD', 'employee123'),
        'role' => 'employee',
        'position' => env('SEEDER_EMPLOYEE_POSITION', 'Medical Officer III'),
        'division' => env('SEEDER_EMPLOYEE_DIVISION', 'Treatment and Rehabilitation Division'),
        'subdivision' => env('SEEDER_EMPLOYEE_SUBDIVISION', 'Non-Residential Treatment & Rehabilitation'),
        'section' => env('SEEDER_EMPLOYEE_SECTION', 'Medical Section'),
    ],

    'demo_users' => [
        'admin01' => [
            'name' => env('SEEDER_ADMIN01_NAME', 'IT Officer'),
            'email' => env('SEEDER_ADMIN01_EMAIL', 'admin01@hris.local'),
            'password' => env('SEEDER_DEMO_PASSWORD', 'password'),
            'role' => 'admin',
            'position' => env('SEEDER_ADMIN01_POSITION', 'ICT Officer I'),
            'division' => env('SEEDER_ADMIN01_DIVISION', 'Chief of Hospital Offices Division'),
            'section' => env('SEEDER_ADMIN01_SECTION', 'Information and Communications Technology Unit'),
        ],
        'hr01' => [
            'name' => env('SEEDER_HR01_NAME', 'HR Specialist'),
            'email' => env('SEEDER_HR01_EMAIL', 'hr01@hris.local'),
            'password' => env('SEEDER_DEMO_PASSWORD', 'password'),
            'role' => 'hr',
            'position' => env('SEEDER_HR01_POSITION', 'Human Resource Management Officer I'),
            'division' => env('SEEDER_HR01_DIVISION', 'Finance and Administrative Division'),
            'section' => env('SEEDER_HR01_SECTION', 'Human Resource Management Section'),
        ],
        'hr02' => [
            'name' => env('SEEDER_HR02_NAME', 'HR Assistant'),
            'email' => env('SEEDER_HR02_EMAIL', 'hr02@hris.local'),
            'password' => env('SEEDER_DEMO_PASSWORD', 'password'),
            'role' => 'hr',
            'position' => env('SEEDER_HR02_POSITION', 'Administrative Assistant'),
            'division' => env('SEEDER_HR02_DIVISION', 'Finance and Administrative Division'),
            'section' => env('SEEDER_HR02_SECTION', 'Human Resource Management Section'),
        ],
        'employee01' => [
            'name' => env('SEEDER_EMPLOYEE01_NAME', 'Maria Santos'),
            'email' => env('SEEDER_EMPLOYEE01_EMAIL', 'employee01@hris.local'),
            'password' => env('SEEDER_DEMO_PASSWORD', 'password'),
            'role' => 'employee',
            'position' => env('SEEDER_EMPLOYEE01_POSITION', 'Nurse II'),
            'division' => env('SEEDER_EMPLOYEE01_DIVISION', 'Treatment and Rehabilitation Division'),
            'subdivision' => env('SEEDER_EMPLOYEE01_SUBDIVISION', 'Non-Residential Treatment & Rehabilitation'),
            'section' => env('SEEDER_EMPLOYEE01_SECTION', 'Nursing Section'),
        ],
        'employee02' => [
            'name' => env('SEEDER_EMPLOYEE02_NAME', 'Pedro Garcia'),
            'email' => env('SEEDER_EMPLOYEE02_EMAIL', 'employee02@hris.local'),
            'password' => env('SEEDER_DEMO_PASSWORD', 'password'),
            'role' => 'employee',
            'position' => env('SEEDER_EMPLOYEE02_POSITION', 'Psychologist I'),
            'division' => env('SEEDER_EMPLOYEE02_DIVISION', 'Treatment and Rehabilitation Division'),
            'subdivision' => env('SEEDER_EMPLOYEE02_SUBDIVISION', 'Residential Treatment & Rehabilitation'),
            'section' => env('SEEDER_EMPLOYEE02_SECTION', 'Psychological Section'),
        ],
    ],

    'organizational_structure' => [
        'divisions' => [
            'Chief of Hospital Offices Division',
            'Treatment and Rehabilitation Division',
            'Finance and Administrative Division',
        ],
    ],
];
