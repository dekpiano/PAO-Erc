<?php

namespace App\Models\Sports;

use CodeIgniter\Model;

class SportsCertificateModel extends Model
{
    protected $table            = 'Tb_Sports_Certificates';
    protected $primaryKey       = 'cert_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'target_type',       // all, athlete, coach
        'award_level',       // all, champion, 1st_runner_up, 2nd_runner_up, 3rd_runner_up, participant
        'cert_title',
        'cert_template',
        'cert_config',       // JSON coordinates & styles
        'cert_prefix',       // e.g. PAO-SP-2569/
        'signatory_name_1',
        'signatory_pos_1',
        'signatory_img_1',
        'signatory_name_2',
        'signatory_pos_2',
        'signatory_img_2',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
