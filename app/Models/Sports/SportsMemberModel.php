<?php

namespace App\Models\Sports;

use CodeIgniter\Model;

class SportsMemberModel extends Model
{
    protected $table            = 'Tb_Sports_Members';
    protected $primaryKey       = 'member_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'team_id',
        'comp_year',
        'category_id',
        'member_type',      // athlete, coach, manager, assistant
        'prefix',
        'first_name',
        'last_name',
        'id_card',
        'birth_date',
        'age',
        'gender',
        'jersey_number',
        'position',
        'photo_path',
        'doc_id_card_path',
        'doc_student_card_path',
        'doc_medical_path',
        'award_level',      // champion, 1st_runner_up, 2nd_runner_up, 3rd_runner_up, participant, none
        'cert_number',
        'cert_issue_date',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
