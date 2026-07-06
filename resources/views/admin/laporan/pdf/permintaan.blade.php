<!DOCTYPE html>
<html>
<head>
    <title>Laporan Permintaan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0F766E; padding-bottom: 10px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 60px; height: 60px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; color: #0F766E; }
        .subtitle { font-size: 14px; margin: 5px 0; }
        .address { font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; color: #333; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .filter-info { font-size: 10px; margin-bottom: 10px; color: #555; }
    </style>
</head>
<body>
    @php
        $setting = \App\Models\Setting::first();
    @endphp

    <div class="header">
        @if($setting && $setting->logo)
            @php
                $path = storage_path('app/public/' . $setting->logo);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                if (file_exists($path)) {
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    echo '<img src="'.$base64.'" class="logo">';
                }
            @endphp
        @endif
        
        <h1 class="title">{{ $setting->nama_yayasan ?? 'Yayasan Masjid' }}</h1>
        <p class="subtitle">{{ $setting->nama_sistem ?? 'Sistem Inventaris' }}</p>
        <p class="address">{{ $setting->alamat ?? '' }} | Telp: {{ $setting->telepon ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <h2 class="text-center" style="margin-top:15px; font-size: 16px; margin-bottom: 5px;">LAPORAN PERMINTAAN BARANG</h2>
    
    <div class="filter-info">
        @if(isset($filter['start_date']) && isset($filter['end_date']))
            Periode: {{ date('d/m/Y', strtotime($filter['start_date'])) }} s/d {{ date('d/m/Y', strtotime($filter['end_date'])) }}
        @else
            Periode: Semua Waktu
        @endif
        <br>
        Status: {{ $filter['status'] ?? 'Semua Status' }}
        <span style="float: right;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">No. Permintaan</th>
                <th width="12%">Tanggal</th>
                <th width="20%">Koordinator & Wilayah</th>
                <th width="25%">Item Diminta</th>
                <th width="13%">Status</th>
                <th width="13%">Penyaluran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaans as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>PRM-{{ str_pad($item->id_permintaan, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                <td>
                    <strong>{{ $item->user->name ?? '-' }}</strong><br>
                    <span style="font-size: 9px; color: #555;">{{ $item->user->nama_mesjid ?? '-' }}</span>
                </td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($item->detail as $d)
                            <li>{{ $d->barang->nama_barang ?? '-' }} ({{ $d->jumlah }} {{ $d->barang->satuan->nama_satuan ?? '' }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $item->status }}</td>
                <td>
                    @if($item->distribusi)
                        Selesai disalurkan
                    @elseif($item->status == 'Disetujui')
                        Menunggu penyaluran
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
            @if($permintaans->isEmpty())
            <tr>
                <td colspan="7" class="text-center">Tidak ada riwayat permintaan pada periode ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 50px; border: none;">
        <tr>
            <td style="width: 50%; text-align: center; border: none; padding-top: 15px;">
                <p style="margin: 0;">Diketahui oleh :</p>
                <p style="margin: 0;">Ketua Harian YHA</p>
                <br><br><br><br><br>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">H. M. Saf'i Sitepu, S.Ag, SH, MH</p>
            </td>
            <td style="width: 50%; text-align: center; border: none;">
                <p style="margin: 0;">Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin: 0;">Dibuat oleh :</p>
                <p style="margin: 0;">Staff Umum dan Koord. PKM YHA</p>
                <br><br><br><br><br>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">Muhammad Saputra, ST</p>
            </td>
        </tr>
    </table>
</body>
</html>
