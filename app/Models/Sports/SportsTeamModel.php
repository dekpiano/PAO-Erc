<?php

namespace App\Models\Sports;

use CodeIgniter\Model;

class SportsTeamModel extends Model
{
    protected $table            = 'Tb_Sports_Teams';
    protected $primaryKey       = 'team_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'team_code',
        'category_id',
        'school_name',
        'team_name',
        'district',
        'province',
        'contact_name',
        'contact_phone',
        'contact_email',
        'contact_line_id',
        'status',          // pending, approved, rejected, cancelled
        'award_level',     // champion, runner_up_1, runner_up_2, runner_up_3, participation, none
        'admin_note',
        'token',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
