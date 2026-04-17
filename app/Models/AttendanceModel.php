<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table      = 'Tb_Attendance';
    protected $primaryKey = 'atd_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Use the atd_ prefix for all fields
    protected $allowedFields = [
        'atd_user_id', 
        'atd_type', 
        'atd_status',
        'atd_timestamp', 
        'atd_date',
        'atd_ip', 
        'atd_location', 
        'atd_note'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'atd_created_at';
    protected $updatedField  = 'atd_updated_at';
}
