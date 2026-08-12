<?php

namespace App\Models\Forms;

use CodeIgniter\Model;

class FormAnswerModel extends Model
{
    protected $table            = 'Tb_Form_Answers';
    protected $primaryKey       = 'ans_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ans_sub_id',
        'ans_field_id',
        'ans_value'
    ];

    protected $useTimestamps = false;
}
