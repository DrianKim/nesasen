<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    @page {
        margin: 20px;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
    }

    .header h1 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .header p {
        margin: 5px 0;
        color: #666;
    }

    .info-section {
        margin-bottom: 20px;
    }

    .info-section p {
        margin: 5px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
        font-weight: bold;
        tex-align: center;
    }

    .text-center {
        text-align: center;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #666;
    }
</style>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Laporan Data KelasKu</p>
    </div>
    <div class="info-section">
        <p><strong>Tanggal Ekspor:</strong> {{ $exported_at }}</p>
        <p><strong>Total KelasKu:</strong> {{ $total_data }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 2%">No</th>
                <th class="text-center" style="width: 30%">KelasKu</th>
                <th class="text-center" style="width: 15%">Kelas</th>
                <th class="text-center" style="width: 30%">Guru</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelasKu as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->mataPelajaran->nama_mapel }}</td>
                    <td class="text-center">{{ $item->kelas->tingkat }} {{ $item->kelas->jurusan->kode_jurusan }} {{ $item->kelas->no_kelas }}</td>
                    <td>{{ $item->guru->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $exported_at_time }}</p>
        <p>SMKN 1 Subang</p>
    </div>
</body>

</html>
