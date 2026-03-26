<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('staff'));
        }
        return view('auth/login');
    }

    public function doLogin()
    {
        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->groupStart()
                      ->where('u_username', $username)
                      ->orWhere('u_email', $username)
                      ->groupEnd()
                      ->first();

        if ($user && password_verify($password ?? '', $user['u_password'])) {
            $sessionData = [
                'u_id'       => $user['u_id'],
                'u_username' => $user['u_username'],
                'u_fullname' => $user['u_fullname'],
                'u_role'     => $user['u_role'],
                'isLoggedIn' => true,
            ];
            session()->set($sessionData);
            
            // Redirect based on role
            if ($user['u_role'] === 'admin') {
                return redirect()->to(base_url('admin'));
            }
            return redirect()->to(base_url('staff'));
        } else {
            return redirect()->to(base_url('/auth/login'))->with('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/auth/login'));
    }

    public function googleLogin()
    {
        $credential = $this->request->getPost('credential');
        if (!$credential) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Token ไม่ถูกต้อง');
        }

        // Verify with Google via API call
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get("https://oauth2.googleapis.com/tokeninfo?id_token=" . $credential);
            $payload = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return redirect()->to(base_url('auth/login'))->with('error', 'การเชื่อมต่อกับ Google ล้มเหลว');
        }

        if (!$payload || isset($payload['error'])) {
            return redirect()->to(base_url('auth/login'))->with('error', 'การยืนยันตัวตนกับ Google ล้มเหลว');
        }

        // Validate Client ID (Audience)
        $client_id = "466841507075-cb297mr52ffija6jsif1b080mjus0k11.apps.googleusercontent.com";
        if ($payload['aud'] !== $client_id) {
             return redirect()->to(base_url('auth/login'))->with('error', 'Client ID ไม่ถูกต้อง');
        }

        $email = $payload['email'];
        $google_sub = $payload['sub'];

        $model = new UserModel();
        
        // 1. Find by Google Sub first
        $user = $model->where('u_google_sub', $google_sub)->first();
        
        // 2. If not found, find by Email then link it
        if (!$user) {
            $user = $model->where('u_email', $email)->first();
            if ($user) {
                $model->update($user['u_id'], ['u_google_sub' => $google_sub]);
            }
        }

        if ($user) {
            $sessionData = [
                'u_id'       => $user['u_id'],
                'u_username' => $user['u_username'],
                'u_fullname' => $user['u_fullname'],
                'u_role'     => $user['u_role'],
                'isLoggedIn' => true,
            ];
            session()->set($sessionData);
            
            if ($user['u_role'] === 'admin') {
                return redirect()->to(base_url('admin'));
            }
            return redirect()->to(base_url('staff'));
        } else {
            return redirect()->to(base_url('auth/login'))->with('error', "ไม่พบพนักงานที่เชื่อมต่อกับอีเมล $email ในระบบ โปรดแจ้งทางแอดมิน");
        }
    }
}
