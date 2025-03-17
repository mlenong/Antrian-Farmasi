<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konter Farmasi BPJS</title>
    <script>
    const baseURL = "<?= base_url() ?>";
    </script>
    <script src="https://cdn.socket.io/4.5.1/socket.io.min.js"></script>
    <link rel="stylesheet" href="assets/bpjs/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bpjs/css/style_bpjs.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="assets/bpjs/js/index_bpjs.js"></script>

</head>

<body>
    <div id="message-box"></div>
    <div id="loading">Loading...</div>
    <div class="container card">
        <div id="headoff" class="row"></div>
        <div class="row">
            <div class="col-12 text-center mt-2">
                <h3>Pemanggilan Antrian Farmasi BPJS</h3>
            </div>
        </div>

        <div class="row mt-1">
            <!-- Panel Data Proses -->
            <div class="col-md-4">
                <div class="panel-heading">DATA PROSES RACIKAN</div>
                <div class="table-container">
                    <table id="list2" class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Antrian</th>
                                <th>No Reg</th>
                                <th>Call</th>
                                <th>Done</th>
                                <th>Selesai</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Panel Menu Panggil -->
            <div class="col-md-4 text-center">
                <div class="panel-heading">MENU PANGGIL</div>
                <div class="queue-display mt-3" id="antrianSekarang">END</div>
                <button id="nextAntrianBtn" onclick="nextAntrian()" class="btn btn-warning btn-call">CALL</button>
                <button id="recallAntrianBtn" onclick="recallAntrian()" class="btn btn-call"
                    style="display: none;">RECALL</button>
                <button id="doneAntrianBtn" onclick="doneAntrian()" class="btn btn-call"
                    style="display: none;">DONE</button>
            </div>

            <!-- Panel Data Pemanggilan -->
            <div class="col-md-4">
                <div class="panel-heading">DATA PEMANGGILAN</div>
                <div class="table-container">
                    <table id="list1" class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Antrian</th>
                                <th>No Reg</th>
                                <th>Call</th>
                                <th>Process</th>
                                <th>Done</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="col-12 text-center mt-2">
            <h3>Process Station</h3>
        </div>
        <div class="col-md-12">
            <div class="panel-heading">DATA ANTRIAN OBAT RACIKAN</div>
            <div class="table-container">
                <table id="list3" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Antrian</th>
                            <th>No Reg</th>
                            <th>Call</th>
                            <th>Proses</th>
                            <th>Done</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/bpjs/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTables for both tables

            $('#list3').DataTable({
                paging: false,
                searching: true,
                info: false,
                columnDefs: [{ orderable: false, targets: [1, 2, 3, 4] }]
            });

            $('#list2').DataTable({
                paging: false,
                searching: true,
                info: false,
                columnDefs: [{ orderable: false, targets: [1, 2, 3, 4] }]
            });

            $('#list1').DataTable({
                paging: false,
                searching: true,
                info: false,
                columnDefs: [{ orderable: false, targets: [1, 2, 3, 4] }]
            });
        });
    </script>
</body>

</html>