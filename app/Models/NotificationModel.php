<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table      = 'Tb_Notifications';
    protected $primaryKey = 'not_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    protected $allowedFields = [
        'not_user_id',
        'not_title',
        'not_message',
        'not_link',
        'not_is_read'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'not_created_at';
    protected $updatedField  = 'not_updated_at';

    public function getUnreadCount($userId)
    {
        return $this->where('not_user_id', $userId)
                    ->where('not_is_read', 0)
                    ->countAllResults();
    }
}
