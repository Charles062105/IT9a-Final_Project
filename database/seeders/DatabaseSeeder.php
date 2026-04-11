<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'System Administrator', 'email' => 'admin@hrms.com',
            'password' => Hash::make('password'), 'role' => 'admin', 'status' => 'active',
        ]);

        $data = [
            ['name'=>'Maria Santos',   'email'=>'maria@hrms.com',  'eid'=>'EMP-001','fn'=>'Maria',  'ln'=>'Santos',   'g'=>'Female','dept'=>'Engineering',    'pos'=>'Senior Developer',    'sal'=>75000],
            ['name'=>'Juan dela Cruz', 'email'=>'juan@hrms.com',   'eid'=>'EMP-002','fn'=>'Juan',   'ln'=>'dela Cruz','g'=>'Male',  'dept'=>'Finance',         'pos'=>'Accountant',          'sal'=>55000],
            ['name'=>'Ana Reyes',      'email'=>'ana@hrms.com',    'eid'=>'EMP-003','fn'=>'Ana',    'ln'=>'Reyes',    'g'=>'Female','dept'=>'Human Resources', 'pos'=>'HR Coordinator',      'sal'=>48000],
            ['name'=>'Carlos Mendoza', 'email'=>'carlos@hrms.com', 'eid'=>'EMP-004','fn'=>'Carlos', 'ln'=>'Mendoza',  'g'=>'Male',  'dept'=>'Marketing',       'pos'=>'Marketing Manager',   'sal'=>65000],
            ['name'=>'Rosa Garcia',    'email'=>'rosa@hrms.com',   'eid'=>'EMP-005','fn'=>'Rosa',   'ln'=>'Garcia',   'g'=>'Female','dept'=>'Sales',           'pos'=>'Sales Representative','sal'=>40000],
        ];

        foreach ($data as $d) {
            $user = User::create([
                'name' => $d['name'], 'email' => $d['email'],
                'password' => Hash::make('password'), 'role' => 'employee', 'status' => 'active',
            ]);

            $emp = Employee::create([
                'user_id' => $user->id, 'employee_id' => $d['eid'],
                'first_name' => $d['fn'], 'last_name' => $d['ln'], 'gender' => $d['g'],
                'birth_date' => '1993-06-15', 'address' => 'Metro Manila, Philippines',
                'phone' => '0917' . rand(1000000, 9999999),
                'emergency_contact' => 'Family Contact', 'emergency_phone' => '0918' . rand(1000000, 9999999),
                'civil_status' => 'Single', 'department' => $d['dept'], 'position' => $d['pos'],
                'hire_date' => '2021-03-01', 'salary' => $d['sal'], 'status' => 'active',
                'sss_number' => '12-' . rand(1000000, 9999999) . '-' . rand(0, 9),
                'philhealth_number' => rand(10, 99) . '-' . rand(100000000, 999999999) . '-' . rand(0, 9),
                'pagibig_number' => rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999),
            ]);

            // 7 days attendance
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                if ($date->isWeekend()) continue;
                $isAbsent = ($i === 3 && $d['eid'] === 'EMP-001');
                $isLate   = (!$isAbsent && $i === 1 && $d['eid'] === 'EMP-002');
                Attendance::create([
                    'employee_id' => $emp->id, 'date' => $date->toDateString(),
                    'time_in'     => $isAbsent ? null : $date->copy()->setHour($isLate ? 9 : 8)->setMinute(rand(0, 15)),
                    'time_out'    => $isAbsent ? null : $date->copy()->setHour(17)->setMinute(rand(0, 30)),
                    'status'      => $isAbsent ? 'absent' : ($isLate ? 'late' : 'present'),
                ]);
            }

            // Sample leaves
            Leave::create(['employee_id'=>$emp->id,'type'=>'Vacation','start_date'=>now()->addDays(7)->toDateString(),'end_date'=>now()->addDays(11)->toDateString(),'reason'=>'Annual family vacation','status'=>'pending']);
            Leave::create(['employee_id'=>$emp->id,'type'=>'Sick','start_date'=>now()->subDays(20)->toDateString(),'end_date'=>now()->subDays(19)->toDateString(),'reason'=>'Fever and flu','status'=>'approved']);

            // Payroll
            $ded = round($d['sal'] * 0.1, 2);
            Payroll::create([
                'employee_id' => $emp->id, 'period_month' => now()->month, 'period_year' => now()->year,
                'basic_salary' => $d['sal'],
                'sss_deduction' => round($d['sal'] * 0.045, 2),
                'philhealth_deduction' => round($d['sal'] * 0.025, 2),
                'pagibig_deduction' => 100,
                'tax_deduction' => round($d['sal'] * 0.03, 2),
                'total_deductions' => $ded, 'net_pay' => $d['sal'] - $ded, 'status' => 'released',
            ]);

            // Maria has a violation
            if ($d['eid'] === 'EMP-001') {
                Violation::create([
                    'employee_id' => $emp->id, 'type' => 'Absence', 'offense_number' => 1,
                    'description' => 'Unexcused absence on ' . now()->subDays(3)->format('F d, Y'),
                    'action_taken' => 'Verbal Warning',
                    'issued_by' => $admin->id, 'date_issued' => now()->subDays(3)->toDateString(),
                ]);
                HrmsNotification::create(['user_id'=>$user->id,'title'=>'Violation Notice — Verbal Warning','message'=>'You received a Verbal Warning for your absence on '.now()->subDays(3)->format('M d, Y').'. Please maintain regular attendance.','type'=>'warning','read'=>false]);
            }

            HrmsNotification::create(['user_id'=>$user->id,'title'=>'Welcome to HRMS!','message'=>"Welcome {$d['fn']}! Your account is active. Please complete your profile and review your employment details.","type"=>'success','read'=>false]);
        }

        // Pending user (awaiting approval)
        User::create([
            'name' => 'Pending Applicant', 'email' => 'pending@hrms.com',
            'password' => Hash::make('password'), 'role' => 'employee', 'status' => 'pending',
        ]);

        // Notify admin about pending account
        HrmsNotification::create(['user_id'=>$admin->id,'title'=>'New Account Request','message'=>'Pending Applicant (pending@hrms.com) has submitted an account registration request.','type'=>'info','read'=>false]);

        $this->command->info('✓ Seeded! Admin: admin@hrms.com / Employee: maria@hrms.com / password: password');
    }
}
