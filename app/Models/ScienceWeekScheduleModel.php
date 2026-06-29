<?php

namespace App\Models;

use CodeIgniter\Model;

class ScienceWeekScheduleModel extends Model
{
    protected $table            = 'Tb_ScienceWeek_Schedules';
    protected $primaryKey       = 'sch_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sch_year',
        'sch_date',
        'sch_title',
        'sch_description',
        'sch_color'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'sch_created_at';
    protected $updatedField  = 'sch_updated_at';
}
