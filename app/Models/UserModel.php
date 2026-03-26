<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'Tb_Users';
    protected $primaryKey = 'u_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    protected $allowedFields = [
        'u_username', 
        'u_email',
        'u_google_sub',
        'u_password', 
        'u_fullname', 
        'u_prefix',
        'u_position',
        'u_level',
        'u_division',
        'u_photo',
        'u_phone',
        'u_sort',
        'u_status',
        'u_role'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'u_created_at';
    protected $updatedField  = 'u_updated_at';
}
