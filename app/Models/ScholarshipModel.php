<?php

namespace App\Models;

use CodeIgniter\Model;

class ScholarshipModel extends Model
{
    protected $table      = 'Tb_Scholarships';
    protected $primaryKey = 'sch_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'sch_title', 
        'sch_slug', 
        'sch_content', 
        'sch_amount', 
        'sch_deadline', 
        'sch_status', 
        'sch_cover',
        'sch_attachment',
        'sch_allowed_grades', // 👈 เพิ่มใหม่เพื่อเปิด/ปิดระดับชั้น
        'sch_created_by',
        'sch_created_at',
        'sch_updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'sch_created_at';
    protected $updatedField  = 'sch_updated_at';

    // Helper to generate slug
    public function generateSlug($title)
    {
        $slug = mb_strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        // Handle Thai characters in slug if possible, otherwise just append unique id
        if (empty($slug)) {
            $slug = 'scholarship';
        }
        return $slug . '-' . uniqid();
    }
}
