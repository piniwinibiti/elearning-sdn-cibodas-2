<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi Laporan Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Rekapitulasi Absensi & Nilai Siswa</h2>
        <p>Tanggal Cetak: {{ date('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa / NIS</th>
                <th>Kelas</th>
                <th class="text-center">Total Kehadiran</th>
                <th class="text-center">Tugas Yg Dikerjakan</th>
                <th class="text-center">Rata-rata Nilai Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanData as $index => $data)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $data['siswa']->user->nama_lengkap }}</strong><br>
                    <small>NIS: {{ $data['siswa']->nis }}</small>
                </td>
                <td class="text-center">{{ $data['siswa']->id_kelas }}</td>
                <td class="text-center">{{ $data['hadir'] }} Hari</td>
                <td class="text-center">{{ $data['tugas_terkumpul'] }} / {{ $data['total_tugas'] }}</td>
                <td class="text-center">
                    <strong>{{ $data['rata_rata_tugas'] }}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
