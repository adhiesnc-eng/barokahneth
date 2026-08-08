<?php
/**
 * ============================================================================
 * WEBHOOK BOT FONNTE - BAROKAH.NET
 * PT. Internet Sejahtera Barokah
 * ============================================================================
 */

header('Content-Type: application/json');

// Menerima data payload dari Fonnte
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => false, "message" => "No data payload received"]);
    exit;
}

// MASUKKAN TOKEN API FONNTE DEVICE ANDA DI SINI
$tokenFonnte = "p1NYTiLzm5KFnFAt9uNr";

// Variabel Input Fonnte
$sender   = isset($data['sender']) ? $data['sender'] : '081349923481';         // Nomor WA Pengirim
$message  = isset($data['message']) ? trim($data['message']) : 'terima kasih,...';   // Isi Pesan Masuk
$pesanLow = strtolower($message);

$reply = "";

/* =============================================================
   LOGIKA AUTO RESPONDER BOT WA
   ============================================================= */

if ($pesanLow == "menu" || $pesanLow == "help" || $pesanLow == "p" || $pesanLow == "bot") {
    $reply = "👋 *Selamat Datang di WhatsApp Bot BAROKAH.NET*\n" .
             "_PT. Internet Sejahtera Barokah_\n\n" .
             "Silakan ketik perintah kata kunci berikut:\n\n" .
             "1️⃣ *PAKET* : Cek daftar paket internet bulanan\n" .
             "2️⃣ *VOUCHER* : Cek harga voucher hotspot\n" .
             "3️⃣ *FORMAT* : Format pendaftaran via WA\n" .
             "4️⃣ *BAYAR* : Info pembayaran resmi (Privasi Rekening)\n" .
             "5️⃣ *ADMIN* : Bantuan langsung dari CS Manusia";

} else if ($pesanLow == "paket" || $pesanLow == "1") {
    $reply = "📦 *DAFTAR PAKET INTERNET BULANAN BAROKAH.NET*\n\n" .
             "🔴 *30 Mbps (Internet Only)*\n" .
             "• Rp 244.500/bln | Pasang: Rp 166.500\n\n" .
             "🔵 *30 Mbps (Internet + Phone)*\n" .
             "• Rp 310.800/bln | Pasang: Rp 55.000\n\n" .
             "🟢 *50 Mbps (1P Internet)*\n" .
             "• Rp 388.500/bln | *GRATIS PASANG*\n\n" .
             "🟡 *100 Mbps (High Speed)*\n" .
             "• Rp 471.750/bln | *GRATIS PASANG*\n\n" .
             "Ketik *FORMAT* untuk petunjuk daftar via WA.";

} else if ($pesanLow == "voucher" || $pesanLow == "2") {
    $reply = "📶 *DAFTAR HARGA VOUCHER HOTSPOT*\n\n" .
             "• *2 Jam* : Rp 2.000 (Up to 5 Mbps)\n" .
             "• *12 Jam* : Rp 5.000 (Up to 7 Mbps)\n" .
             "• *24 Jam* : Rp 8.000 (Up to 10 Mbps)\n" .
             "• *7 Hari* : Rp 35.000 (Up to 10 Mbps)\n\n" .
             "Ketik *BELI#NOMINAL* (Contoh: *BELI#12JAM*) untuk pemesanan.";

} else if ($pesanLow == "format" || $pesanLow == "3") {
    $reply = "📝 *FORMAT PENDAFTARAN MANUAL*\n\n" .
             "Salin dan isi format berikut:\n" .
             "`DAFTAR#Nama#AlamatLengkap#PaketPilihan`\n\n" .
             "Contoh:\n" .
             "`DAFTAR#Ahmad Hidayat#Jl Mawar No 12 RT 02/03#50 Mbps`";

} else if ($pesanLow == "bayar" || $pesanLow == "4") {
    // DISESUAIKAN DENGAN KEBIJAKAN PRIVASI REKENING TERPROTEKSI
    $reply = "🔒 *INFORMASI PEMBAYARAN BAROKAH.NET*\n" .
             "_PT. Internet Sejahtera Barokah_\n\n" .
             "Demi keamanan transaksi dan privasi, nomor rekening resmi hanya diberikan kepada pelanggan terdaftar.\n\n" .
             "Metode yang didukung:\n" .
             "1. Bank Transfer (BCA / Mandiri / BRI)\n" .
             "2. E-Wallet (DANA / OVO / GoPay)\n\n" .
             "Balas pesan ini dengan ketik *REK* untuk mendapatkan konfirmasi nomor rekening resmi langsung dari Admin Keuangan kami.";

} else if ($pesanLow == "rek") {
    $reply = "📩 *PERMINTAAN DETAIL REKENING RESMI*\n\n" .
             "Pesan Anda telah diteruskan ke Admin Keuangan PT. Internet Sejahtera Barokah.\n" .
             "Petugas kami akan mengirimkan nomor rekening resmi secara pribadi ke pesan ini dalam waktu singkat.";

} else if (strpos($pesanLow, 'daftar#') === 0) {
    $exp = explode('#', $message);
    $nama   = isset($exp[1]) ? trim($exp[1]) : '-';
    $alamat = isset($exp[2]) ? trim($exp[2]) : '-';
    $paket  = isset($exp[3]) ? trim($exp[3]) : '-';

    $reply = "✅ *PENDAFTARAN BERHASIL DITERIMA*\n\n" .
             "Terima kasih Bpk/Ibu *$nama*,\n" .
             "Pendaftaran paket *$paket* untuk lokasi *$alamat* telah masuk ke sistem BAROKAH.NET. Tim teknisi akan menghubungi Anda untuk survei lokasi.";

} else {
    if (strpos($message, 'FORMULIR PENDAFTARAN') !== false) {
        $reply = "Halo, pendaftaran dari website BAROKAH.NET telah berhasil diterima. Tim teknis akan memproses jadwal survei Anda secepatnya.";
    } else {
        $reply = "Halo! Terima kasih telah menghubungi BAROKAH.NET.\nKetik *MENU* untuk menampilkan daftar pilihan otomatis.";
    }
}

/* =============================================================
   MENGIRIM RESPON BALASAN KEMBALI VIA API FONNTE
   ============================================================= */

if (!empty($reply) && !empty($sender)) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target'  => $sender,
            'message' => $reply,
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $tokenFonnte"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    echo json_encode(["status" => true, "message" => "Balasan terkirim"]);
}
?>
