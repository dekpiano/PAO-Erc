<?php

namespace App\Models;

use CodeIgniter\Model;

class ScienceWeekEvaluationModel extends Model
{
    protected $table            = 'Tb_ScienceWeek_Evaluations';
    protected $primaryKey       = 'eval_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'eval_name',
        'eval_school',
        'eval_province',
        'eval_phone',
        'eval_feedback',
        'eval_code',
        'eval_created_at'
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Generate a unique evaluation code for certificate reference
     */
    public function generateEvaluationCode()
    {
        do {
            $code = 'SW-EVAL-' . strtoupper(bin2hex(random_bytes(4)));
            $exists = $this->where('eval_code', $code)->countAllResults() > 0;
        } while ($exists);
        return $code;
    }
}
