@php
    use Carbon\Carbon;

    // Get total days of the selected month
    $daysInMonth = Carbon::create($year, $month)->daysInMonth;

    // Group attendances by date for easier lookup
    $attendanceByDate = $attendances->groupBy('date');
@endphp

<div class="table-responsive">
    <table class="table table-hover table-bordered table-sm text-center align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="min-width: 150px;">Date</th>
                <th style="min-width: 80px;">Status</th>
                <th style="min-width: 120px;">Time In</th>
                <th style="min-width: 120px;">Time Out</th>
                <th style="min-width: 200px;">Subject</th>
                <th style="min-width: 200px;">Teacher</th>
            </tr>
        </thead>

        <tbody>
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = Carbon::create($year, $month, $day)->format('Y-m-d');
                    $records = $attendanceByDate->get($date, collect());
                @endphp

                {{-- ///// Date row ///// --}}
                <tr>
                    <td class="fw-bold text-primary text-start">
                        {{ Carbon::create($year, $month, $day)->format('M d, Y (D)') }}
                    </td>

                    @if ($records->isNotEmpty())
                        <td colspan="5" class="p-0">
                            <table class="table table-bordered table-sm mb-0 align-middle text-center">
                                <tbody>
                                    @foreach ($records as $record)
                                        @php
                                            // ///// Symbol Mapping /////
                                            $symbol = match (strtolower($record->status)) {
                                                'present' => '✓',
                                                'absent' => 'X',
                                                'late' => 'L',
                                                'excused' => 'E',
                                                default => '-',
                                            };

                                            // ///// Colors /////
                                            $symbolColor = match ($symbol) {
                                                '✓' => 'text-success',
                                                'X' => 'text-danger',
                                                'L' => 'text-warning',
                                                'E' => 'text-primary',
                                                default => 'text-muted',
                                            };

                                            // ///// Tooltip Title /////
                                            $tooltipTitle = match ($symbol) {
                                                '✓' => 'Present',
                                                'X' => 'Absent',
                                                'L' => 'Late',
                                                'E' => 'Excused',
                                                default => 'No record',
                                            };

                                            $subject = $record->subject->name ?? 'N/A';
                                            $teacher = $record->teacher->full_name ?? 'N/A';
                                            $timeIn = $record->time_in
                                                ? Carbon::parse($record->time_in)->format('g:i A')
                                                : '-';
                                            $timeOut = $record->time_out
                                                ? Carbon::parse($record->time_out)->format('g:i A')
                                                : '-';
                                        @endphp

                                        <tr>
                                            <td class="{{ $symbolColor }} fw-bold" data-bs-toggle="tooltip"
                                                title="{{ $tooltipTitle }} | {{ $subject }}">
                                                {{ $symbol }}
                                            </td>
                                            <td>{{ $timeIn }}</td>
                                            <td>{{ $timeOut }}</td>
                                            <td>{{ $subject }}</td>
                                            <td>{{ $teacher }}</td>
                                            <td>{{ data_get($attendance, $day, '—') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    @else
                        <td colspan="5" class="text-muted">No attendance record</td>
                    @endif
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<script>
    // Initialize Bootstrap tooltips inside modal
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>
