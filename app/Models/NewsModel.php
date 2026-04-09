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

    // Helper to generate slug supporting Thai characters
    public function generateSlug($title)
    {
        // Replace spaces with - and remove special chars but keep Thai (including vowels/marks)
        $slug = mb_strtolower(trim($title));
        // \p{L} is for letters, \p{M} is for marks (Thai vowels/tone marks), \p{N} is for numbers
        $slug = preg_replace('/[^\p{L}\p{M}\p{N}\s-]/u', '', $slug); 
        $slug = preg_replace('/\s+/', '-', $slug); 
        $slug = preg_replace('/-+/', '-', $slug); 
        
        return trim($slug, '-') . '-' . uniqid();
    }
}
