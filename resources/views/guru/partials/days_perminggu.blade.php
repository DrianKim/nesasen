@foreach ($daysOfWeek as $day)
    <div class="day-item-week
    {{ $day['tanggal']->isSameDay($selectedDate) ? 'active' : '' }}
    {{ $day['tanggal']->isToday() ? 'today' : '' }}"
        onclick="changeDate('{{ $day['tanggal']->format('Y-m-d') }}')">
        <div class="day-name-week">{{ $day['nama_hari'] }}</div>
        <div class="day-number-week">{{ $day['tanggal']->format('d') }}</div>
    </div>
@endforeach
