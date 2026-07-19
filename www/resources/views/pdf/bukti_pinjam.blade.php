<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .info-table .label { font-weight: bold; width: 150px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .items-table th { background: #f5f5f5; font-weight: bold; }
        .footer { margin-top: 40px; width: 100%; }
        .signature-box { width: 45%; float: left; text-align: center; }
        .signature-box.right { float: right; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bukti Peminjaman Alat Laboratorium</h1>
        <p>Lab Kimia Terpadu Universitas IPWIJA</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">ID Peminjaman</td>
            <td>: REQ-{{ str_pad($borowing->id_borrowing, 5, '0', STR_PAD_LEFT) }}</td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ now()->format('d M Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Nama Peminjam</td>
            <td>: {{ $borowing->mahasiswa?->nama_lengkap ?? '-' }}</td>
            <td class="label">Status</td>
            <td>: <strong>{{ strtoupper($borowing->status) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td>: {{ $borowing->mahasiswa?->program_studi ?? '-' }}</td>
            <td class="label">Tgl Pengajuan</td>
            <td>: {{ $borowing->tgl_pengajuan?->format('d M Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Keperluan</td>
            <td colspan="3">: {{ $borowing->keperluan }}</td>
        </tr>
        <tr>
            <td class="label">Rencana Pinjam</td>
            <td>: {{ $borowing->tgl_rencana_pinjam?->format('d M Y') ?? '-' }}</td>
            <td class="label">Rencana Kembali</td>
            <td>: {{ $borowing->tgl_rencana_kembali?->format('d M Y') ?? '-' }}</td>
        </tr>
    </table>

    <h3 style="font-size: 14px; margin-bottom: 10px;">Daftar Alat</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Alat</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borowing->borrowingItems as $index => $item)
            <tr>
                <td style="text-align: center; width: 40px;">{{ $index + 1 }}</td>
                <td style="width: 150px;">{{ $item->tool?->kode_alat ?? '-' }}</td>
                <td>{{ $item->tool?->nama_alat ?? '-' }}</td>
                <td style="text-align: center; width: 80px;">{{ $item->jumlah_unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Petugas Laboratorium</p>
            <div class="signature-space"></div>
            <p>( ........................................ )</p>
        </div>
        <div class="signature-box right">
            <p>Peminjam</p>
            <div class="signature-space"></div>
            <p>( {{ $borowing->mahasiswa?->nama_lengkap ?? '......................................' }} )</p>
        </div>
    </div>
</body>
</html>
