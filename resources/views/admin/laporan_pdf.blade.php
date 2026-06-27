<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Bulanan</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f3f4f6; color: #333; }
        .text-left { text-align: left; }
        .footer { margin-top: 40px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">REKAP ABSENSI SISWA BULANAN</div>
        <div class="subtitle">Bulan: {{ $namaBulan }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%" class="text-left">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="13%">Hadir</th>
                <th width="13%">Terlambat</th>
                <th width="14%">Alpha</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($rekapSiswa as $siswa)
            <tr>
                <td>{{ $no++ }}</td>
                <td class="text-left">{{ $siswa['nama'] }}</td>
                <td>{{ $siswa['kelas'] }}</td>
                <td>{{ $siswa['hadir'] }}</td>
                <td>{{ $siswa['terlambat'] }}</td>
                <td>{{ $siswa['alpha'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 20px;">Belum ada data absensi untuk bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}</p>
        <br><br><br>
        <p>_____________________</p>
        <p>Admin SDN Cibodas</p>
    </div>

</body>
</html>
