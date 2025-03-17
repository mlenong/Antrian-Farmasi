<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian Eksekutif</title>
    <script>
    const baseURL = "<?= base_url() ?>";
    </script>
    <!-- Bootstrap CSS -->
    <link href="assets/bpjs/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS style -->
    <link rel="stylesheet" href="assets/bpjs/css/style_display.css">
    <!-- Socket.io -->
    <script src="assets/socket/socket.io.min.js"></script>
</head>

<body>
    <h1 class="header">INFORMASI ANTRIAN FARMASI EKSEKUTIF</h1>
    <div class="container-fluid">
        <div class="proses">
            <div class="header">Proses</div>
            <div id="queue-process-list" class="queue-container">
                <div class="queue-list" id="queue-process-items">
                    <!-- Antrian proses akan dimasukkan di sini -->
                </div>
            </div>
        </div>
        <div class="antrian">
            <div>
                <div class="header">Racikan</div>
                <div id="queue-call-list" class="queue-item">
                </div>
            </div>
            <div>
                <div class="header">Non-Racikan</div>
                <div id="queue-nonracikan-list" class="queue-item">
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="running-text">
            <span>ASSALAMUALAIKUM WARAHMATULAHI WABARAKATUH, MOHON
                MENUNGGU DENGAN SABAR, ANDA AKAN SEGERA KAMI LAYANI, TERIMA
                KASIH</span>
        </div>
    </div>

    <script src="assets/eksekutif/js/display_ekse.js"></script>
</body>

</html>