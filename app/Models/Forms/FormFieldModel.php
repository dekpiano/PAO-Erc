<?php

namespace App\Models\Forms;

use CodeIgniter\Model;

class FormFieldModel extends Model
{
    protected $table            = 'Tb_Form_Fields';
    protected $primaryKey       = 'field_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'field_form_id',
        'field_label',
        'field_type',
        'field_options',
        'field_is_required',
        'field_sort_order'
    ];

    protected $useTimestamps = false;
}
