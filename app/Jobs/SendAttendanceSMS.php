<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\SemaphoreService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAttendanceSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $student;
    protected $status;
    protected $schedule;
    protected $attendance;

    /**
     * Create a new job instance.
     */
    public function __construct($student, $status, $schedule, $attendance)
    {
        $this->student = $student;
        $this->status = $status;
        $this->schedule = $schedule;
        $this->attendance = $attendance;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $semaphore = new SemaphoreService();
        $parents = $this->student->parents()->whereNotNull('phone')->get();

        if ($parents->isEmpty()) {
            Log::warning("No parent phone numbers found for student ID {$this->student->id}");
            return;
        }

        // Format time and attendance details
        $timeIn = $this->attendance->time_in
            ? Carbon::parse($this->attendance->time_in)->format('h:i A')
            : 'N/A';

        $timeOut = $this->attendance->time_out
            ? Carbon::parse($this->attendance->time_out)->format('h:i A')
            : 'N/A';

        $formattedDate = Carbon::parse($this->attendance->date)->format('M d, Y');

        $class = $this->attendance->class;
        if (!$class) {
            Log::warning("No class found for attendance ID {$this->attendance->id}");
            return;
        }

        $currentClass = "{$class->formatted_grade_level} - {$class->section}";
        $subject = $this->schedule->subject_name ?? 'your child’s class';
        $studentFullName = "{$this->student->student_fName} {$this->student->student_lName}";

        // Loop through parents and send by-status message
        foreach ($parents as $parent) {
            // Sanitize and format phone number
            $phone = preg_replace('/[^0-9]/', '', $parent->phone);
            if (str_starts_with($phone, '0')) {
                $phone = '+63' . substr($phone, 1);
            } elseif (str_starts_with($phone, '63')) {
                $phone = '+' . $phone;
            } elseif (!str_starts_with($phone, '+63')) {
                $phone = '+63' . $phone;
            }

            // Match controller message format by attendance status
            switch ($this->status) {
                case 'present':
                    $message = "Hello {$parent->firstName}! Your child {$studentFullName} was marked as PRESENT today ({$formattedDate}) in {$subject}. Time In: {$timeIn}, Expected Time Out: {$timeOut}. - {$currentClass}";
                    break;

                case 'late':
                    $message = "Hello {$parent->firstName}! Your child {$studentFullName} was marked as LATE today ({$formattedDate}) in {$subject}. Time In: {$timeIn}, Expected Time Out: {$timeOut}. - {$currentClass}";
                    break;

                case 'absent':
                    $message = "Hello {$parent->firstName}! Your child {$studentFullName} was marked as ABSENT today ({$formattedDate}) in {$subject}. - {$currentClass}";
                    break;

                case 'excused':
                    $message = "Hello {$parent->firstName}! Your child {$studentFullName} was marked as EXCUSED today ({$formattedDate}) in {$subject}. - {$currentClass}";
                    break;

                default:
                    $message = "Hello {$parent->firstName}! Your child {$studentFullName} was marked as '{$this->status}' today ({$formattedDate}) in {$subject}. Time In: {$timeIn}, Expected Time Out: {$timeOut}. - {$currentClass}";
                    break;
            }

            try {
                $semaphore->sendSMS($phone, $message);
                Log::info(" SMS sent to {$parent->firstName} ({$phone}) for student {$studentFullName} | Status: {$this->status}");
            } catch (\Exception $e) {
                Log::error(" Failed to send SMS to {$phone}: " . $e->getMessage());
            }
        }
    }
}
