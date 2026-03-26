<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;


class Home extends Controller
{
    public function index()
    {


        $newsModel = new \App\Models\NewsModel();
        $data['title'] = 'กองการศึกษา ศาสนา และวัฒนธรรม - องค์การบริหารส่วนจังหวัดนครสวรรค์';
        
        // Fetch Top 6 Latest Published News
        $data['latest_news'] = $newsModel->where('news_status', 'published')
                                         ->orderBy('news_created_at', 'DESC')
                                         ->limit(6)
                                         ->findAll();
                                         
        return view('home/index', $data);
    }

    public function newsAll()
    {
        $newsModel = new \App\Models\NewsModel();
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');

        $query = $newsModel->where('news_status', 'published');

        if ($search) {
            $query->groupStart()
                  ->like('news_title', $search)
                  ->orLike('news_content', $search)
                  ->groupEnd();
        }

        if ($category) {
            $query->where('news_category', $category);
        }

        $data['title'] = "คลังข่าวสารและกิจกรรม | อบจ.นครสวรรค์";
        $data['news'] = $query->orderBy('news_created_at', 'DESC')->paginate(12);
        $data['pager'] = $newsModel->pager;
        $data['search'] = $search;
        $data['category_active'] = $category;

        return view('home/news_all', $data);
    }

    public function newsDetail($slug)
    {
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        
        $news = $newsModel->where('news_slug', $slug)
                          ->where('news_status', 'published')
                          ->first();
                          
        if (!$news) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Increment View Count
        $newsModel->update($news['news_id'], [
            'news_view_count' => $news['news_view_count'] + 1
        ]);

        $data['title'] = $news['news_title'] . " | อบจ.นครสวรรค์";
        $data['news'] = $news;
        $data['gallery'] = $galleryModel->where('gal_news_id', $news['news_id'])->findAll();
        
        return view('home/news_detail', $data);
    }

    public function personnel()
    {
        $model = new UserModel();
        // Fetch active staff, sorted by rank
        $users = $model->where('u_status', 'active')
                       ->orderBy('u_sort', 'ASC')
                       ->findAll();

        $data['title'] = 'ทำเนียบบุคลากร - กองการศึกษา องค์การบริหารส่วนจังหวัดนครสวรรค์';
        $data['personnel'] = [
            'director' => array_filter($users, fn($u) => $u['u_division'] == 'ผู้บริหาร'),
            'admin'    => array_filter($users, fn($u) => $u['u_division'] == 'ฝ่ายบริหาร'),
            'promotion'=> array_filter($users, fn($u) => $u['u_division'] == 'ฝ่ายส่งเสริม'),
            'other'    => array_filter($users, fn($u) => empty($u['u_division']) || !in_array($u['u_division'], ['ผู้บริหาร', 'ฝ่ายบริหาร', 'ฝ่ายส่งเสริม'])),
        ];

        return view('home/personnel', $data);
    }

    public function migrate()
    {
        $migrate = \Config\Services::migrations();
        try {
            $migrate->latest();
            return 'Migrations complete.';
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
