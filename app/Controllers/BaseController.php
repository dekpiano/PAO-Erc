<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        $this->helpers = array_merge($this->helpers ?? [], ['form', 'url', 'html']);

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        
        // --- ส่วนรัน Migration อัตโนมัติ (รันผ่านโค้ด) ---
        try {
            $migrations = \Config\Services::migrations();
            $migrations->latest(); 
        } catch (\Throwable $e) {
            log_message('error', 'Auto Migration Error: ' . $e->getMessage());
        }
        // ------------------------------------------

        if (session()->get('isLoggedIn')) {
            $notModel = new \App\Models\NotificationModel();
            $this->viewData['unread_count'] = $notModel->getUnreadCount(session()->get('u_id'));
        }
    }

    /**
     * ส่งการแจ้งเตือน (ในระบบ + อีเมล)
     */
    protected function notify($userId, $title, $message, $link = null, $sendEmail = true)
    {
        try {
            $notModel = new \App\Models\NotificationModel();
            $notModel->insert([
                'not_user_id' => $userId,
                'not_title'   => $title,
                'not_message' => $message,
                'not_link'    => $link,
                'not_is_read' => 0
            ]);

            if ($sendEmail) {
                // ตรวจสอบว่าเป็น localhost หรือไม่ ถ้าใช่ ไม่ต้องส่งอีเมล
                $isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) || strpos(base_url(), 'localhost') !== false;
                
                if (!$isLocal) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->find($userId);
                    if ($user && !empty($user['u_email'])) {
                        $email = \Config\Services::email();
                        $email->setTo($user['u_email']);
                        $email->setSubject($title);
                        
                        $body = "คุณได้รับการแจ้งเตือนใหม่:\n\n" . $message;
                        if ($link) {
                            $body .= "\n\nคลิกเพื่อดูรายละเอียด: " . base_url($link);
                        }
                        
                        $email->setMessage($body);
                        $email->send();
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Notification Error: ' . $e->getMessage());
        }
    }

    /**
     * ส่งการแจ้งเตือนให้แอดมินทุกคน
     */
    protected function notifyAdmins($title, $message, $link = null)
    {
        $userModel = new \App\Models\UserModel();
        // ค้นหาคนที่มี role เป็น superadmin หรือ admin
        $admins = $userModel->whereIn('u_role', ['superadmin', 'admin', 'news', 'personnel', 'summary', 'settings'])
                            ->orLike('u_role', 'superadmin')
                            ->orLike('u_role', 'admin')
                            ->findAll();
        
        foreach ($admins as $admin) {
            $this->notify($admin['u_id'], $title, $message, $link, true);
        }
    }
}
