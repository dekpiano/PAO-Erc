<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table      = 'Tb_Settings';
    protected $primaryKey = 's_id';

    protected $allowedFields = [
        's_key', 
        's_value', 
        's_description'
    ];

    // Helper to get setting value by key
    public function getVal($key)
    {
        $setting = $this->where('s_key', $key)->first();
        return $setting ? $setting['s_value'] : null;
    }
}
