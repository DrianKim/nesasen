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
        <p>Laporan Data Mapel</p>
    </div>
    <div class="info-section">
        <p><strong>Tanggal Ekspor:</strong> {{ $exported_at }}</p>
        <p><strong>Total Mapel:</strong> {{ $total_mapel }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 2%">No</th>
                <th class="text-center" style="width: 25%">Nama Mapel</th>
                <th class="text-center" style="width: 15%">Kode Mapel</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mapel as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_mapel }}</td>
                    <td class="text-center">{{ $item->kode_mapel }}</td>
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
