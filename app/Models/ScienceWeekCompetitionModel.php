<?php

namespace App\Models;

use CodeIgniter\Model;

class ScienceWeekCompetitionModel extends Model
{
    protected $table            = 'Tb_ScienceWeek_Competitions';
    protected $primaryKey       = 'comp_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'comp_year',
        'comp_name',
        'comp_icon',
        'comp_level',
        'comp_level_limits',
        'comp_description',
        'comp_rule_file',
        'comp_rule_link',
        'comp_group_link',
        'comp_group_qr',
        'comp_color',
        'comp_custom_fields',
        'comp_limit',
        'comp_member_limit',
        'comp_status',
        'comp_open_time',
        'comp_close_time'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'comp_created_at';
    protected $updatedField  = 'comp_updated_at';
}
