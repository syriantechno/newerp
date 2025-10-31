@php
    // map status to symbols
    $map = [
        'present'        => '✅',
        'half_day'       => '⭐',
        'late'           => '🕓',
        'absent'         => '❌',
        'holiday'        => '📅',
        'day_off'        => '💤',
        'leave'          => '✈️',
        'not_registered' => '🚫',
    ];

    $daysCount = count($daysMeta);
@endphp

<div class="table-responsive">
    <table class="table table-bordered align-items-center mb-0">
        <thead class="bg-light">
        <tr>
            <th style="min-width:220px;">Employee</th>
            @foreach($daysMeta as $dm)
                <th class="text-center" style="min-width:34px;">
                    <div class="d-flex flex-column align-items-center">
                        <span class="text-xs">{{ $dm['d'] }}</span>
                        <small class="text-secondary">{{ $dm['dow_short'] }}</small>
                    </div>
                </th>
            @endforeach
            <th class="text-center">Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse($employees as $emp)
            @php
                $byDay = array_fill(1, $daysCount, 'not_registered');
                $totalPresent = 0;

                $empRecs = $records[$emp->id] ?? collect();
                foreach ($empRecs as $rec) {
                    $d = (int)\Carbon\Carbon::parse($rec->date)->format('j');
                    $byDay[$d] = $rec->status;
                    if (in_array($rec->status, ['present','late'])) {
                        $totalPresent++;
                    } elseif ($rec->status === 'half_day') {
                        $totalPresent += 0.5;
                    }
                }
            @endphp
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/team-1.jpg') }}" class="avatar avatar-sm me-2" alt="photo">
                        <div>
                            <div class="text-sm fw-bold">{{ $emp->name }}</div>
                            <div class="text-xs text-secondary">{{ $emp->email }}</div>
                        </div>
                    </div>
                </td>
                @for($d=1;$d<=$daysCount;$d++)
                    <td class="text-center">{!! $map[$byDay[$d]] !!}</td>
                @endfor
                <td class="text-center">
                    {{ rtrim(rtrim(number_format($totalPresent,1,'.',''), '0'), '.') }} / {{ $daysCount }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $daysCount + 2 }}" class="text-center text-muted py-4">
                    No employees found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
