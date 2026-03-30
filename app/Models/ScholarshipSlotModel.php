<?php

namespace App\Models;

use CodeIgniter\Model;

class ScholarshipSlotModel extends Model
{
    protected $table      = 'Tb_Scholarship_Slots';
    protected $primaryKey = 'slot_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'slot_scholarship_id',
        'slot_date',
        'slot_start_time',
        'slot_end_time',
        'slot_max',
        'slot_is_active',
        'slot_created_at',
    ];

    protected $useTimestamps = false;

    /**
     * ดึงสล็อตพร้อมจำนวนที่ถูกจองไปแล้ว
     */
    public function getSlotsWithBookingCount($scholarshipId, $date = null)
    {
        $builder = $this->db->table('Tb_Scholarship_Slots as s')
            ->select('s.*, COUNT(b.bk_id) as booked_count')
            ->join('Tb_Scholarship_Bookings as b', 'b.bk_slot_id = s.slot_id AND b.bk_status != "cancelled"', 'left')
            ->where('s.slot_scholarship_id', $scholarshipId)
            ->where('s.slot_is_active', 1)
            ->groupBy('s.slot_id')
            ->orderBy('s.slot_date', 'ASC')
            ->orderBy('s.slot_start_time', 'ASC');

        if ($date) {
            $builder->where('s.slot_date', $date);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * ดึงวันที่ทั้งหมดที่มีสล็อตเปิดรับ
     */
    public function getAvailableDates($scholarshipId)
    {
        return $this->db->table('Tb_Scholarship_Slots')
            ->select('slot_date')
            ->where('slot_scholarship_id', $scholarshipId)
            ->where('slot_is_active', 1)
            ->where('slot_date >=', date('Y-m-d'))
            ->groupBy('slot_date')
            ->orderBy('slot_date', 'ASC')
            ->get()
            ->getResultArray();
    }
}
