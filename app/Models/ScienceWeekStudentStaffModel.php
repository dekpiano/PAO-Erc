<?php

namespace App\Models;

use CodeIgniter\Model;

class ScienceWeekStudentStaffModel extends Model
{
    protected $table            = 'Tb_ScienceWeek_StudentStaff';
    protected $primaryKey       = 'staff_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'staff_year',
        'staff_competition_type',
        'staff_prefix',
        'staff_firstname',
        'staff_lastname',
        'staff_class',
        'staff_created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'staff_created_at';
    protected $updatedField  = 'staff_updated_at';
}
