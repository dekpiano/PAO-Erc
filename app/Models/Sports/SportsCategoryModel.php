<?php

namespace App\Models\Sports;

use CodeIgniter\Model;

class SportsCategoryModel extends Model
{
    protected $table            = 'Tb_Sports_Categories';
    protected $primaryKey       = 'category_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sport_name',
        'category_name',
        'category_gender',
        'category_type',
        'age_min',
        'age_max',
        'min_players',
        'max_players',
        'min_coaches',
        'max_coaches',
        'max_teams',
        'reg_start_date',
        'reg_end_date',
        'comp_year',
        'rules_file',
        'rules_detail',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
