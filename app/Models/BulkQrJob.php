<?php

namespace App\Models;

use CodeIgniter\Model;

class BulkQrJob extends Model
{
    protected $table            = 'bulk_qr_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['job_name', 'total_files', 'processed_files', 'zip_file', 'status'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
} 