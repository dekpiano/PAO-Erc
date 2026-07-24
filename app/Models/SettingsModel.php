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

    // Helper to set setting value by key
    public function setVal($key, $value, $description = '')
    {
        $setting = $this->where('s_key', $key)->first();
        if ($setting) {
            return $this->update($setting['s_id'], ['s_value' => $value]);
        } else {
            return $this->insert([
                's_key'         => $key,
                's_value'       => $value,
                's_description' => $description
            ]);
        }
    }
}
