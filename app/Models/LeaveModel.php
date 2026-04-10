<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveModel extends Model
{
    protected $table            = 'Tb_Leave';
    protected $primaryKey       = 'leave_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'leave_user_id',
        'leave_type',
        'leave_reason',
        'leave_from_date',
        'leave_to_date',
        'leave_days',
        'leave_last_from_date',
        'leave_last_to_date',
        'leave_last_days',
        'leave_contact',
        'leave_status',
        'leave_approver_id',
        'leave_substitute_id'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'leave_created_at';
    protected $updatedField  = 'leave_updated_at';
}
