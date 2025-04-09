<?php

namespace App\Observers;

use App\Models\Employee;
use App\Mail\EmployeeNotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        Mail::to($employee->email)->queue(new EmployeeNotificationMail($employee->toArray()));
        Log::info(MAIL_CREATE_QUEUE . ' ' . $employee->email);
    }

    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        Mail::to($employee->email)->queue(new EmployeeNotificationMail($employee->toArray()));
        Log::info(MAIL_UPDATE_QUEUE . ' ' . $employee->email);
    }

    /**
     * Handle the Employee "deleted" event.
     */
    public function deleted(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "restored" event.
     */
    public function restored(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "force deleted" event.
     */
    public function forceDeleted(Employee $employee): void
    {
        //
    }
}
