<?php

namespace App\Models;

use CodeIgniter\Model;

class AntrianModel extends Model
{
    protected $table = 'trn_kpo_rajal';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id', 'tanggal', 'nomor', 'no_reg', 'nama', 'sts_racikan', 'antrian', 'antrian_aktif', 'process_at', 'end_at', 'call_at'
    ];
}
