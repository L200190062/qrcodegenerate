<?php

namespace App\Models;

use CodeIgniter\Model;

class UMhsDetail extends Model
{
    protected $DBGroup = 'dbo';
    protected $table = 'UMhsDetail';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "FID",
        "FNIM",
        "FNAMA",
        "FTMPLAHIR",
        "FTGLLAHIR",
        "FKDPROP",
        "FKDKAB",
        "FTHMASUK",
        "FALAMAT",
        "FTELEPON",
        "FPA",
        "JN_LAMIN",
        "AG_CMHS",
        "WN_CMHS",
        "AS_CMHS",
        "FIBU",
        "FNKTP",
        "PH_ORWALI",
        "NAMA_OT",
        "PEKERJ_OT",
        "ALAMAT_OT",
        "NPS_PMB",
        "STAMHS",
        "PERX",
        "TG_LULUS",
        "TG_MASUK",
        "BATAS_ST",
        "THSMT",
        "REKENING",
        "NO_IJZ",
        "TELP_ASAL2",
        "TELP_AYAH2",
        "TELP_WALI2",
        "JSK",
        "FKDP2",
        "FKDK2",
        "AS_PRO",
        "AS_KAB",
        "AL_PRO",
        "AL_KAB",
        "FJSkripsi",
        "FPredikatLulus",
        "FNoTranskrip",
        "FSW",
        "FIJZ",
        "FIJZSLA",
        "NOIJZ1",
        "FTHLL",
        "FIPK",
        "NA_CMHS",
        "AL_AYAH",
        "StShMhs",
        "BeaSiswa",
        "PenddOT",
        "GajiOT",
        "WNeg",
        "FTHLL1",
        "MSTHN",
        "NOSKL",
        "NomorSKL",
        "TglSKL",
        "UsSKL",
        "EMAIL",
        "FNIRM",
        "FNIRL",
        "BISTU",
        "PEKSB",
        "NMPEK",
        "PTPEK",
        "PSPEK",
        "KT_AKTIF",
        "FLLN",
        "KT_CUTI",
        "TG_WISUDA",
        "FMhsID",
        "TIPE_MHS",
        "KDKABNAS",
        "KDPROVNAS",
        "TGLREG",
        "KDSMUNAS",
        "fdl",
        "Eligible"
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function findByNIM($nim)
    {
        return $this->where('FNIM', $nim)->first();
    }
}
