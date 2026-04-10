<?php

namespace App\Models;

use CodeIgniter\Model;

class PositionModel extends Model
{
    protected $table            = 'Tb_Positions';
    protected $primaryKey       = 'pos_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pos_name', 
        'pos_type', 
        'pos_level', 
        'pos_is_head'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'pos_created_at';
    protected $updatedField  = 'pos_updated_at';
}
