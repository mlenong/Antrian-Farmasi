<?php

namespace App\Models;

use CodeIgniter\Model;

class EksekutifModel extends Model
{
    protected $table = 'trn_kpo_eksekutif';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id', 'tanggal', 'nomor', 'no_reg', 'nama', 'sts_racikan', 'antrian', 'antrian_aktif', 'process_at', 'end_at', 'call_at'
    ];
}
