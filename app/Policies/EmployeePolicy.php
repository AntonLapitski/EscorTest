<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function before(Employee $employee, $ability)
    {
        if ($employee->isEmployee()) {
            return true;
        }
    }

    public function create(Employee $employee)
    {
        return true;
    }

    public function view(Employee $employee)
    {
        return true;
    }

    public function delete(Employee $employee)
    {
        return true;
    }

    public function update(Employee $employee)
    {
        return true;
    }

    public function getRecords(Employee $employee)
    {
        return true;
    }
}
