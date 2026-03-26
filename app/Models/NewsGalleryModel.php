<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsGalleryModel extends Model
{
    protected $table      = 'Tb_News_Gallery';
    protected $primaryKey = 'gal_id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'gal_news_id', 
        'gal_image'
    ];

    protected $useTimestamps = false;
}
