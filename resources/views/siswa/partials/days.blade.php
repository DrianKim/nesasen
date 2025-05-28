@foreach ($daysOfWeek as $day)
    <div class="day-item {{ $day['tanggal']->isSameDay($selectedDate) ? 'active' : '' }}"
        onclick="changeDate('{{ $day['tanggal']->format('Y-m-d') }}')">
        <div class="day-name">{{ $day['nama_hari'] }}</div>
        <div class="day-number">{{ $day['tanggal']->format('d') }}</div>
    </div>
@endforeach
