<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara - {{ $beritaAcara->nomor_ba }}</title>

    <style>
        /* SETTING HALAMAN UTAMA */
        @page {
            margin: 1cm 2cm; /* Atas Bawah 1cm, Kanan Kiri 2cm */
        }

        body {
            font-family: Helvetica, Arial, sans-serif; /* Font aman */
            font-size: 11pt; /* Sesuai request */
            line-height: 1.5; /* Sesuai request */
            color: #000;
        }

        /* RESET UMUM */
        table { border-collapse: collapse; width: 100%; }
        td { vertical-align: top; }
        
        /* HEADER DENGAN TABEL (ANTI-BLANK) */
        .header-table {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda lebih formal */
            margin-bottom: 15pt;
            padding-bottom: 5pt;
        }
        .header-logo-col {
            width: 20%; /* Kolom Kiri untuk Logo */
            vertical-align: middle;
        }
        .header-text-col {
            width: 60%; /* Kolom Tengah untuk Judul */
            text-align: center;
            vertical-align: middle;
        }
        .header-empty-col {
            width: 20%; /* Kolom Kanan kosong penyeimbang agar judul persis tengah */
        }

        .header-title { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .header-subtitle { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .header-note { font-size: 9pt; font-style: italic; margin-top: 2pt; }

        /* UTILS */
        .bold { font-weight: bold; }
        
        /* PARAGRAF */
        p {
            margin: 0 0 10pt 0;
            text-align: justify;
            text-indent: 1cm; /* Menjorok ke dalam */
        }
        p.no-indent { text-indent: 0; }

        /* TABEL DATA NASABAH */
        table.data-table { margin-bottom: 10pt; }
        table.data-table td { padding: 2pt 0; }
        table.data-table td.label { width: 130pt; } 
        table.data-table td.colon { width: 15pt; text-align: center; }
        table.data-table td.value { font-weight: bold; }

        /* TABEL LIST HASIL */
        table.list-table td.num { width: 25pt; padding-left: 10pt; }

        /* TANDA TANGAN (LAYOUT TABEL MURNI) */
        .signature-table {
            margin-top: 30pt;
            page-break-inside: avoid; 
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            padding: 0 10pt;
        }
        
        /* Wrapper Tanda Tangan (Tanpa Flexbox) */
        .sig-block { margin-bottom: 5pt; }
        .sig-role { margin-bottom: 10pt; }
        .sig-img-container {
            height: 70px; 
            display: block;
            margin-bottom: 0;
        }
        .sig-img {
            max-height: 400px;
            max-width: 200px;
            display: inline-block;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 2pt;
        }

        /* FOOTER */
        .footer-note {
            font-size: 9pt;
            font-style: italic;
            margin-top: 20pt;
        }
        .date-line {
            text-align: right;
            margin-bottom: 10pt;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td class="header-logo-col">
                <img src="{{ public_path('images/bjbsekuritas.jpg') }}" style="height: 55px; width: auto;" alt="Logo">
            </td>
            <br>
        </br>
    <br>
    </br>   
              <br>
        </br>
    <br>
    </br> 
      <td class="header-text-col">
              <br>
        </br>
    <br>
    </br> 
              <br>
        </br>
    <br>
    </br> 
              <br>
        </br>
    <br>
    </br> 
  
                <div class="header-title">BERITA ACARA</div>
                <div class="header-subtitle">PENGECEKAN CALON NASABAH/NASABAH</div>
                <div class="header-note">Informasi bersifat rahasia, hanya untuk keperluan internal bjb Sekuritas.</div>
            </td>
            <td class="header-empty-col"></td>
        </tr>
    </table>
    

    <!-- PARAGRAF SESUAI PERMINTAAN -->
    <p>
        Pada hari ini, tanggal <span class="bold">{{ $beritaAcara->tanggal_ba->format('d') }} {{ $beritaAcara->tanggal_ba->locale('id')->translatedFormat('F') }} {{ $beritaAcara->tanggal_ba->format('Y') }}</span>, telah dilakukan pencarian profil Calon Nasabah/Nasabah terhadap database Nasabah dan database Watch List bjb Sekuritas dengan menggunakan Aplikasi BOFIS dan aplikasi PPATK dalam rangka melaksanakan penerapan Program Anti Pencucian Uang dan Pencegahan Pendanaan Terorisme dan Pencegahan Pendanaan Proliferasi Senjata Pemusnah Massal (APU-PPT dan PPSPM) bjb Sekuritas. Adapun pencarian dimaksud dilakukan terhadap profil sebagai berikut:
    </p>

    <table class="data-table">
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td class="value">{{ $beritaAcara->nasabah->nama }}</td>
        </tr>
        <tr>
            <td class="label">KTP</td>
            <td class="colon">:</td>
            <td class="value">{{ $beritaAcara->nasabah->ktp }}</td>
        </tr>
        <tr>
            <td class="label">NPWP</td>
            <td class="colon">:</td>
            <td class="value">{{ $beritaAcara->nasabah->npwp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td class="colon">:</td>
            <td class="value">{{ $beritaAcara->nasabah->tanggal_lahir ? $beritaAcara->nasabah->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Negara</td>
            <td class="colon">:</td>
            <td class="value">{{ $beritaAcara->nasabah->negara ?? 'INDONESIA' }}</td>
        </tr>
    </table>

   <p>
        Setelah dilakukan pengecekan terhadap hasil pencarian, maka atas kesamaan/kemiripan atas profil tersebut dinyatakan bahwa:
    </p>

    <table class="list-table">
        <tr>
            <td class="num">1.</td>
            <td><span class="bold">{{ $beritaAcara->getWatchlistResult() }}*</span> kecocokan data dengan profil yang terdapat pada Database Watch List bjb Sekuritas.</td>
        </tr>
        <tr>
            <td class="num">2.</td>
            <td><span class="bold">{{ $beritaAcara->getExistingResult() }}*</span> kecocokan data dengan profil yang terdapat pada Database Nasabah Existing bjb Sekuritas.</td>
        </tr>
    </table>
<br>
    <p>
        Demikian Berita Acara ini dibuat, untuk dipergunakan sebagaimana mestinya.
    </p>
    
     <div class="date-line">
        Bandung, {{ $beritaAcara->tanggal_ba->locale('id')->translatedFormat('d F Y') }}
    </div>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="sig-block">
                    <div>Mengetahui,</div>
                    <div class="sig-role">{{ $beritaAcara->approvedBy->jabatan ?? 'Group Head Sales & Marketing' }}</div>

                    <div class="sig-img-container">
                        @php
                            $pathAppr = $beritaAcara->approvedBy->ttd_path ?? '';
                            $fullPathAppr = storage_path('app/' . $pathAppr);
                        @endphp

                        @if($pathAppr && file_exists($fullPathAppr))
                            <img src="{{ $fullPathAppr }}" class="sig-img" alt="TTD">
                        @else
                            <div style="height: 65px;"></div>
                        @endif
                    </div>

                    <div class="sig-name">{{ $beritaAcara->approvedBy->name ?? '(..........................)' }}</div>
                </div>
            </td>

            <td>
                <div class="sig-block">
                    <div>Dibuat oleh,</div>
                    <div class="sig-role">{{ $beritaAcara->creator->jabatan ?? 'Customer Service' }}</div>

                    <div class="sig-img-container">
                        @php
                            $pathCreator = $beritaAcara->creator->ttd_path ?? '';
                            $fullPathCreator = storage_path('app/' . $pathCreator);
                        @endphp

                        @if($pathCreator && file_exists($fullPathCreator))
                            <img src="{{ $fullPathCreator }}" class="sig-img" alt="TTD">
                        @else
                            <div style="height: 65px;"></div>
                        @endif
                    </div>

                    <div class="sig-name">{{ $beritaAcara->creator->name ?? '(..........................)' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">*coret yang tidak perlu</div>

</body>
</html>