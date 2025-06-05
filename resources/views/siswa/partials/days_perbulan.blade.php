<div class="month-grid">
    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $hari)
        <div class="day-name">{{ $hari }}</div>
    @endforeach

    @foreach ($daysOfMonth as $day)
        @php
            $isOtherMonth = $day['tanggal']->month != $selectedDate->month;
        @endphp
        <div class="day-cell
    {{ $day['tanggal']->isSameDay($selectedDate) ? 'active' : '' }}
    {{ $day['tanggal']->isToday() ? 'today' : '' }}
    {{ $isOtherMonth ? 'other-month disabled' : '' }}"
            @if (!$isOtherMonth) onclick="changeDate('{{ $day['tanggal']->format('Y-m-d') }}')" @endif
            data-month="{{ $day['tanggal']->month }}" data-current-month="{{ $selectedDate->month }}">
            {{ $day['tanggal']->format('d') }}
        </div>
    @endforeach
</div>
