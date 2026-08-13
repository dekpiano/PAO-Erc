<?php

namespace App\Models\Forms;

use CodeIgniter\Model;

class FormModel extends Model
{
    protected $table            = 'Tb_Forms';
    protected $primaryKey       = 'form_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'form_code',
        'form_title',
        'form_description',
        'form_status',
        'form_has_certificate',
        'form_is_shared',
        'form_shared_users',
        'form_cert_template',
        'form_cert_config',
        'form_created_by',
        'form_created_at'
    ];

    protected $useTimestamps = false;
}
