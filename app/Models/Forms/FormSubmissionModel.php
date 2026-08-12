<?php

namespace App\Models\Forms;

use CodeIgniter\Model;

class FormSubmissionModel extends Model
{
    protected $table            = 'Tb_Form_Submissions';
    protected $primaryKey       = 'sub_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sub_form_id',
        'sub_responder_name',
        'sub_responder_email',
        'sub_cert_code',
        'sub_submitted_at'
    ];

    protected $useTimestamps = false;

    public function generateCertCode()
    {
        do {
            $code = 'CERT-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
            $exists = $this->where('sub_cert_code', $code)->countAllResults() > 0;
        } while ($exists);
        return $code;
    }
}
