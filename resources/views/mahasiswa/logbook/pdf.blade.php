<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logbook Magang - {{ $mahasiswa->nama }}</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
            size: a4 portrait;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #1a202c;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1E40AF;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            color: #1E40AF;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 3px 0 0 0;
            color: #2d3748;
        }
        .header p {
            font-size: 8.5pt;
            margin: 2px 0 0 0;
            color: #718096;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .meta-table td.label {
            width: 25%;
            font-weight: bold;
            color: #4a5568;
        }
        .meta-table td.colon {
            width: 2%;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            text-align: left;
        }
        .content-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 6px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 7.5pt;
            font-weight: bold;
            border-radius: 4px;
            background-color: #e2e8f0;
            color: #334155;
        }
        .badge-disetujui {
            background-color: #dcfce7;
            color: #15803d;
        }
        .signatures {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>
    <!-- Header / Kop Surat Resmi -->
    <div class="header">
        <h1>Pemerintah Provinsi Jawa Timur</h1>
        <h2>Dinas Komunikasi dan Informatika</h2>
        <p>Jl. Ahmad Yani No. 242-244, Gayungan, Surabaya, Jawa Timur 60235 | Telp: (031) 8294608</p>
        <p style="margin-top: 6px; font-weight: bold; font-size: 10pt; color: #1E40AF;">
            LAPORAN LOGBOOK KEGIATAN MAGANG (SIMANG)
        </p>
    </div>

    <!-- Student Metadata -->
    <table class="meta-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="colon">:</td>
            <td><strong>{{ $mahasiswa->nama }}</strong></td>
            <td class="label">NIM</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Perguruan Tinggi</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->asal_universitas }}</td>
            <td class="label">Program Studi</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->program_studi }}</td>
        </tr>
        <tr>
            <td class="label">Bidang Penempatan</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->bidang->nama_bidang ?? '-' }}</td>
            <td class="label">Periode Laporan</td>
            <td class="colon">:</td>
            <td>{{ ucfirst($filter) }} (Dicetak: {{ now()->format('d/m/Y') }})</td>
        </tr>
    </table>

    <!-- Logbook Records Table -->
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 25%;">Aktivitas Utama</th>
                <th style="width: 35%;">Deskripsi Pekerjaan</th>
                <th style="width: 9%; text-align: center;">Durasi</th>
                <th style="width: 12%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logbooks as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                    <td><strong>{{ $item->aktivitas_utama }}</strong></td>
                    <td>{{ $item->deskripsi_kegiatan }}</td>
                    <td style="text-align: center;">{{ $item->durasi_jam }} Jam</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $item->status_logbook === 'disetujui' ? 'badge-disetujui' : '' }}">
                            {{ ucfirst($item->status_logbook) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px; color: #94a3b8;">
                        Tidak ada catatan logbook untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="4" style="text-align: right; padding-right: 10px;">Total Jam Kerja:</td>
                <td style="text-align: center;">{{ $totalJam }} Jam</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Official Signatures -->
    <table class="signatures">
        <tr>
            <td>
                Surabaya, {{ now()->translatedFormat('d F Y') }}<br>
                Mahasiswa Magang,
                <div class="signature-space"></div>
                <strong>{{ $mahasiswa->nama }}</strong><br>
                NIM. {{ $mahasiswa->nim ?? '-' }}
            </td>
            <td>
                Mengetahui,<br>
                Pembimbing Lapangan Diskominfo Jatim,
                <div class="signature-space"></div>
                <strong>{{ $mahasiswa->bidang && $mahasiswa->bidang->mentors->first() ? $mahasiswa->bidang->mentors->first()->nama : 'Pembimbing Magang' }}</strong><br>
                NIP. {{ $mahasiswa->bidang && $mahasiswa->bidang->mentors->first() ? $mahasiswa->bidang->mentors->first()->nip : '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
