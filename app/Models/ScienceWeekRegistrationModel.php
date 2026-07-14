<?php

namespace App\Models;

use CodeIgniter\Model;

class ScienceWeekRegistrationModel extends Model
{
    protected $table            = 'Tb_ScienceWeek_Registrations';
    protected $primaryKey       = 'reg_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reg_year',
        'reg_code',
        'reg_competition_type',
        'reg_level',
        'reg_school_name',
        'reg_school_province',
        'reg_team_name',
        'reg_members',
        'reg_advisors',
        'reg_contact_phone',
        'reg_contact_email',
        'reg_status',
        'reg_custom_fields',
        'reg_score',
        'reg_rank',
        'reg_checkin_status',
        'reg_checkin_time',
        'reg_updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'reg_created_at';
    protected $updatedField  = 'reg_updated_at';

    /**
     * Generate dynamic registration code like SCI-2026-00001
     */
    public function generateRegistrationCode(): string
    {
        $prefix = 'SCI-' . date('Y') . '-';
        
        $lastReg = $this->where('reg_code LIKE', $prefix . '%')
                        ->orderBy('reg_id', 'DESC')
                        ->first();
        
        if ($lastReg && !empty($lastReg['reg_code'])) {
            $parts = explode('-', $lastReg['reg_code']);
            $lastSeq = (int) end($parts);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }
        
        return $prefix . str_pad($nextSeq, 5, '0', STR_PAD_LEFT);
    }
}
