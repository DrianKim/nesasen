<div class="month-grid">
    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $hari)
        <div class="day-name">{{ $hari }}</div>
    @endforeach

    @foreach ($daysOfMonth as $day)
        <div class="day-cell
        {{ $day['tanggal']->isSameDay($selectedDate) ? 'active' : '' }}
        {{ $day['tanggal']->isToday() ? 'today' : '' }}
        {{ $day['tanggal']->month != $selectedDate->month ? 'other-month' : '' }}"
            onclick="changeDate('{{ $day['tanggal']->format('Y-m-d') }}')" data-month="{{ $day['tanggal']->month }}"
            data-current-month="{{ $selectedDate->month }}">
            {{ $day['tanggal']->format('d') }}
        </div>
    @endforeach
</div>
