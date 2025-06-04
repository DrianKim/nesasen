<div class="month-grid">
    @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $hari)
        <div class="day-name">{{ $hari }}</div>
    @endforeach

    @foreach ($daysOfMonth as $day)
        <div class="day-cell {{ $day['tanggal']->isSameDay($selectedDate) ? 'active' : '' }}"
            onclick="changeDate('{{ $day['tanggal']->format('Y-m-d') }}')">
            {{ $day['tanggal']->format('d') }}
        </div>
    @endforeach
</div>
