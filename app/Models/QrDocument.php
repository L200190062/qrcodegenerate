<?php

namespace App\Models;

use CodeIgniter\Model;

class QrDocument extends Model
{
    protected $table            = 'qr_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['job_id', 'title', 'nim', 'description', 'pdf_file', 'qr_code'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}