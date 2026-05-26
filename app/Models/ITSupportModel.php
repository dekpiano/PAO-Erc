<?php

namespace App\Models;

use CodeIgniter\Model;

class ITSupportModel extends Model
{
    protected $table            = 'Tb_It_Support_Logs';
    protected $primaryKey       = 'its_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'its_ticket_code',
        'its_date',
        'its_task',
        'its_category',
        'its_location',
        'its_recorded_by',
        'its_user_id',
        'its_images'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'its_created_at';
    protected $updatedField  = 'its_updated_at';

    /**
     * เจนรหัสใบงานรันเลขอัตโนมัติในฟอร์แมต IT-YYYYMM-XXXX (อิงตามปี ค.ศ.)
     */
    public function generateTicketCode(): string
    {
        $prefix = 'IT-' . date('Ym') . '-';
        
        $lastLog = $this->where('its_ticket_code LIKE', $prefix . '%')
                        ->orderBy('its_id', 'DESC')
                        ->first();
        
        if ($lastLog && !empty($lastLog['its_ticket_code'])) {
            $parts = explode('-', $lastLog['its_ticket_code']);
            $lastSeq = (int) end($parts);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }
        
        return $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
