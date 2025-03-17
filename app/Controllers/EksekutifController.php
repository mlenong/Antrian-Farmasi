<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EksekutifModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Controller;

class EksekutifController extends Controller
{
    protected $eksekutifModel;
    protected $tanggalHariIni;

    public function __construct()
    {
        $this->eksekutifModel = new EksekutifModel();
        $this->tanggalHariIni = date('Y-m-d');
    }

    public function index()
    {
        return view('antrian/index-ekse');
    }

    public function displayEkse()
    {
        return view('antrian/display-ekse');
    }

    public function getAntrianHariIniEkse()
    {
        $kolom = ['id', 'nomor', 'antrian', 'tanggal', 'no_reg', 'nama', 'antrian_aktif', 'call_at', 'process_at', 'sts_racikan'];
        $data = $this->eksekutifModel
            ->select($kolom)
            ->where('tanggal', $this->tanggalHariIni)
            ->where('sts_racikan', 'T')
            ->where('end_at', null)
            ->orderBy('antrian', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getAntrianRacikanEkse()
    {
        $kolom = ['id', 'nomor', 'antrian', 'tanggal', 'no_reg', 'nama', 'antrian_aktif', 'sts_racikan', 'end_at'];
        $data = $this->eksekutifModel
            ->select($kolom)
            ->where('tanggal', $this->tanggalHariIni)
            ->where('sts_racikan', 'Y')
            ->where('process_at is NOT NULL')
            ->where('end_at', null)
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getProsesRacikanEkse()
    {
        $kolom = ['id', 'nomor', 'antrian', 'tanggal', 'no_reg', 'nama', 'antrian_aktif', 'sts_racikan', 'end_at'];
        $data = $this->eksekutifModel
            ->select($kolom)
            ->where('tanggal', $this->tanggalHariIni)
            ->where('sts_racikan', 'Y')
            ->where('process_at', null)
            ->where('end_at', null)
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getAntrianSekarangEkse()
    {
        $kolom = ['id', 'antrian'];
        $data = $this->eksekutifModel
            ->select($kolom)
            ->where('tanggal', $this->tanggalHariIni)
            ->where('antrian_aktif', 'T')
            ->where('sts_racikan', 'T')
            ->where('call_at', null)
            ->where('end_at', null)
            ->orderBy('antrian', 'asc')
            ->first();

        if ($data) {
            return $this->response->setJSON($data);
        }

        return $this->response->setJSON(['error' => '-END-'], 404);
    }

    public function getAntrianAktifEkse()
    {
        $kolom = ['id', 'antrian', 'antrian_aktif'];
        $data = $this->eksekutifModel
            ->select($kolom)
            ->where('tanggal', $this->tanggalHariIni)
            ->where('antrian_aktif', 'Y')
            ->first();

        if ($data) {
            return $this->response->setJSON($data);
        }

        return $this->response->setJSON(['error' => '-END-'], 404);
    }

    public function updateProsesEkse($id)
    {
        $updateData = [
            'sts_racikan' => 'Y',
            // 'antrian_aktif' => 'Y',
            'process_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->eksekutifModel->update($id, $updateData)) {
            $antrian = $this->eksekutifModel->find($id);
            $this->sendToWebSocket('/process-ekse', [
                'id' => $id,
                'nama' => $antrian['nama'],
                'antrian' => $antrian['antrian']
            ]);
            return $this->response->setJSON(['message' => 'Proses racikan berhasil diupdate']);
        }

        return $this->response->setJSON(['error' => 'Gagal memperbarui proses'], 500);
    }

    public function callAntrianEkse($id)
    {
        $updateData = [
            'antrian_aktif' => 'Y',
            'call_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->eksekutifModel->update($id, $updateData)) {
            $antrian = $this->eksekutifModel->find($id);
            $this->sendToWebSocket('/call-ekse', [
                'id' => $id,
                'nama' => $antrian['nama'],
                'antrian' => $antrian['antrian'],
                'sts_racikan' => $antrian['sts_racikan'],
                'status' => 'call'
            ]);
            return $this->response->setJSON(['message' => "Nomor antrian {$antrian['antrian']} dipanggil."]);
        }

        return $this->response->setJSON(['error' => 'Gagal memanggil antrian'], 500);
    }

    public function nextAntrianEkse()
    {

        $antrian = $this->eksekutifModel
            ->where('tanggal', $this->tanggalHariIni)
            ->where('antrian_aktif', 'T')
            ->where('call_at', null)
            ->where('end_at', null)
            ->where('sts_racikan', 'T')
            ->orderBy('antrian', 'ASC')
            ->first();

        if (!$antrian) {
            return $this->response->setJSON(['error' => 'Tidak ada antrian tersedia'], 404);
        }


        $updateData = [
            'antrian_aktif' => 'Y',
            'call_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->eksekutifModel->update($antrian['id'], $updateData)) {
            $this->sendToWebSocket('/call-ekse', [
                'id' => $antrian['id'],
                'nama' => $antrian['nama'],
                'antrian' => $antrian['antrian'],
                'sts_racikan' => $antrian['sts_racikan'],
                'status' => 'call'
            ]);
            return $this->response->setJSON(['message' => "Antrian {$antrian['antrian']} dipanggil.", 'antrian' => $antrian]);
        }

        return $this->response->setJSON(['error' => 'Gagal memperbarui antrian'], 500);
    }


    public function recallAntrianEkse()
    {
        $antrian = $this->eksekutifModel
            ->where('tanggal', $this->tanggalHariIni)
            // ->where('sts_racikan', 'T')
            ->where('antrian_aktif', 'Y')
            ->where('end_at', null)
            ->orderBy('antrian', 'DESC')
            ->first();

        if (!$antrian) {
            return $this->response->setJSON(['error' => 'Tidak ada antrian aktif'], 404);
        }

        // Panggil antrian yang aktif
        $this->sendToWebSocket('/call-ekse', [
            'id' => $antrian['id'],
            'nama' => $antrian['nama'],
            'antrian' => $antrian['antrian'],
            'sts_racikan' => $antrian['sts_racikan'],
            'status' => 'recall'
        ]);

        return $this->response->setJSON(['message' => "Antrian {$antrian['antrian']} dipanggil kembali.", 'antrian' => $antrian]);
    }

    public function doneAntrianEkse()
    {
        $antrian = $this->eksekutifModel
            ->where('antrian_aktif', 'Y')
            ->where('end_at', null)
            ->orderBy('antrian', 'desc')
            ->first();

        if ($antrian) {

            $updateData = [
                'antrian_aktif' => 'T',
                'end_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->eksekutifModel->update($antrian['id'], $updateData)) {
                $this->sendToWebSocket('/done-ekse', ['id' => $antrian['id']]);
                return $this->response->setJSON(['message' => "Antrian ID {$antrian['id']} selesai."]);
            } else {
                return $this->response->setJSON(['error' => 'Gagal memperbarui status antrian'], 500);
            }
        } else {
            return $this->response->setJSON(['error' => 'Tidak ada antrian aktif yang dapat diselesaikan'], 404);
        }
    }


    public function doneListEkse($id)
    {
        $antrian = $this->eksekutifModel->find($id);

        if (!$antrian || !is_null($antrian['end_at'])) {
            return $this->response->setJSON(['error' => 'Antrian tidak ditemukan atau sudah selesai'], 404);
        }

        $updateData = [
            // 'sts_racikan' => 'T',
            'antrian_aktif' => 'T',
            'end_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->eksekutifModel->update($id, $updateData);
            $this->sendToWebSocket('/done-ekse', ['id' => $id]);
            return $this->response->setJSON(['message' => "Antrian ID {$id} selesai."]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Gagal memperbarui status antrian'], 500);
        }
    }

    private function sendToWebSocket($endpoint, $data)
    {
        $wsServer = 'http://172.16.15.25:3000';
        $ch = curl_init($wsServer . $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            log_message('error', "Failed to send data to WebSocket server: $response");
        }
    }
}
