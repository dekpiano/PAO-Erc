<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table      = 'Tb_News';
    protected $primaryKey = 'news_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'news_title', 
        'news_slug', 
        'news_content', 
        'news_category', 
        'news_cover', 
        'news_view_count', 
        'news_status', 
        'news_created_by',
        'news_created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'news_created_at';
    protected $updatedField  = 'news_updated_at';

    // Helper to generate slug
    public function generateSlug($title)
    {
        $slug = mb_strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        return $slug . '-' . uniqid();
    }
}
