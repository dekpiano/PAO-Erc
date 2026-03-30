<?php

namespace App\Models;

use CodeIgniter\Model;

class ScholarshipBookingModel extends Model
{
    protected $table      = 'Tb_Scholarship_Bookings';
    protected $primaryKey = 'bk_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'bk_slot_id',
        'bk_fullname',
        'bk_phone',
        'bk_id_card',
        'bk_school',
        'bk_grade',
        'bk_note',
        'bk_queue_number',
        'bk_status',
        'bk_created_at',
    ];

    protected $useTimestamps = false;

    /**
     * ดึงรายการจองพร้อมข้อมูลสล็อตและชื่อทุน
     */
    public function getBookingsWithDetails($scholarshipId = null, $date = null)
    {
        $builder = $this->db->table('Tb_Scholarship_Bookings as b')
            ->select('b.*, s.slot_date, s.slot_start_time, s.slot_end_time, sch.sch_title')
            ->join('Tb_Scholarship_Slots as s', 's.slot_id = b.bk_slot_id')
            ->join('Tb_Scholarships as sch', 'sch.sch_id = s.slot_scholarship_id')
            ->orderBy('s.slot_date', 'ASC')
            ->orderBy('s.slot_start_time', 'ASC')
            ->orderBy('b.bk_queue_number', 'ASC');

        if ($scholarshipId) {
            $builder->where('s.slot_scholarship_id', $scholarshipId);
        }

        if ($date) {
            $builder->where('s.slot_date', $date);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * นับจำนวนที่จองแล้วในสล็อตนี้ (ไม่นับ cancelled)
     */
    public function countBookedInSlot($slotId)
    {
        return $this->where('bk_slot_id', $slotId)
                    ->where('bk_status !=', 'cancelled')
                    ->countAllResults();
    }
}
