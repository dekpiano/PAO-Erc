<?php

namespace App\Controllers\Forms;

use App\Controllers\BaseController;
use App\Models\Forms\FormModel;
use App\Models\Forms\FormFieldModel;
use App\Models\Forms\FormSubmissionModel;
use App\Models\Forms\FormAnswerModel;

class FormPublicController extends BaseController
{
    protected $formModel;
    protected $fieldModel;
    protected $subModel;
    protected $ansModel;

    public function __construct()
    {
        $this->formModel  = new FormModel();
        $this->fieldModel = new FormFieldModel();
        $this->subModel   = new FormSubmissionModel();
        $this->ansModel   = new FormAnswerModel();
    }

    public function index()
    {
        $forms = $this->formModel->where('form_status', 'active')
                                 ->orderBy('form_created_at', 'DESC')
                                 ->findAll();

        $data = [
            'title' => 'ระบบแบบสอบถามออนไลน์ | อบจ.นครสวรรค์',
            'forms' => $forms
        ];

        return view('forms/index', $data);
    }

    private function findForm($key)
    {
        if (empty($key)) return null;
        $form = $this->formModel->where('form_code', $key)->first();
        if (!$form && is_numeric($key)) {
            $form = $this->formModel->find($key);
        }
        return $form;
    }

    public function view($key)
    {
        $form = $this->findForm($key);
        if (!$form) {
            return view('forms/closed', [
                'title'   => 'ไม่พบแบบสอบถาม | อบจ.นครสวรรค์',
                'form'    => null,
                'message' => 'ไม่พบแบบสอบถามที่คุณต้องการเข้าถึง หรือลิงก์แบบสอบถามนี้อาจไม่ถูกต้อง'
            ]);
        }

        if ($form['form_status'] !== 'active') {
            return view('forms/closed', [
                'title'   => 'แบบสอบถามปิดรับคำตอบแล้ว | อบจ.นครสวรรค์',
                'form'    => $form,
                'message' => 'แบบสอบถามนี้ปิดรับฟังความคิดเห็นเรียบร้อยแล้ว ขอขอบพระคุณทุกท่านที่ร่วมตอบแบบสอบถาม'
            ]);
        }

        $fields = $this->fieldModel->where('field_form_id', $form['form_id'])->orderBy('field_sort_order', 'ASC')->findAll();

        $data = [
            'title'  => $form['form_title'] . ' | แบบสอบถามออนไลน์',
            'form'   => $form,
            'fields' => $fields
        ];

        return view('forms/view', $data);
    }

    public function submit($key)
    {
        $form = $this->findForm($key);
        if (!$form || $form['form_status'] !== 'active') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'แบบสอบถามปิดรับคำตอบแล้ว']);
        }

        $formId = $form['form_id'];
        $fields = $this->fieldModel->where('field_form_id', $formId)->findAll();
        $answers = $this->request->getPost('answers') ?: [];

        // Validate required fields
        foreach ($fields as $f) {
            if ($f['field_is_required'] == 1) {
                $val = $answers[$f['field_id']] ?? null;
                if (is_array($val)) {
                    $val = implode(', ', $val);
                }
                if (is_null($val) || trim($val) === '') {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => "กรุณาตอบคำถาม: {$f['field_label']}"
                    ]);
                }
            }
        }

        $certCode = ($form['form_has_certificate'] == 1) ? $this->subModel->generateCertCode() : null;

        $subId = $this->subModel->insert([
            'sub_form_id'         => $formId,
            'sub_responder_name'  => null,
            'sub_responder_email' => null,
            'sub_cert_code'       => $certCode,
            'sub_submitted_at'    => date('Y-m-d H:i:s')
        ]);

        foreach ($fields as $f) {
            $val = $answers[$f['field_id']] ?? null;
            if (is_array($val)) {
                $val = implode(', ', $val);
            }
            $this->ansModel->insert([
                'ans_sub_id'   => $subId,
                'ans_field_id' => $f['field_id'],
                'ans_value'    => $val
            ]);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'ส่งแบบสอบถามสำเร็จ!',
            'redirect' => base_url("forms/success/{$subId}")
        ]);
    }

    public function success($subId)
    {
        $sub = $this->subModel->find($subId);
        if (!$sub) {
            return view('forms/closed', [
                'title'   => 'ไม่พบข้อมูลการส่ง | อบจ.นครสวรรค์',
                'form'    => null,
                'message' => 'ไม่พบข้อมูลประวัติการทำแบบสอบถามที่คุณต้องการดู'
            ]);
        }

        $form = $this->formModel->find($sub['sub_form_id']);

        $data = [
            'title' => 'ส่งแบบสอบถามเรียบร้อยแล้ว | อบจ.นครสวรรค์',
            'sub'   => $sub,
            'form'  => $form
        ];

        return view('forms/success', $data);
    }

    public function claimCertificate($subId)
    {
        $sub = $this->subModel->find($subId);
        if (!$sub) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลการส่งแบบสอบถาม']);
        }

        $form = $this->formModel->find($sub['sub_form_id']);
        if (!$form || $form['form_has_certificate'] != 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'แบบสอบถามนี้ไม่มีเกียรติบัตร']);
        }

        $responderName = trim($this->request->getPost('sub_responder_name') ?? '');

        if (empty($responderName)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกชื่อ-นามสกุลสำหรับออกเกียรติบัตร']);
        }

        $this->subModel->update($subId, [
            'sub_responder_name' => $responderName
        ]);

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'ออกเกียรติบัตรเรียบร้อยแล้ว!',
            'cert_url' => base_url("forms/certificate/{$subId}")
        ]);
    }

    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    private function formatThaiName($name)
    {
        if (empty($name) || !is_string($name)) return $name;
        $name = trim($name);
        $pattern = '/^(เด็กชาย|เด็กหญิง|นาย|นางสาว|นาง|ด\.ช\.|ด\.ญ\.|ดร\.|ผศ\.|รศ\.|ครู|อาจารย์|ว่าที่ร\.ต\.|ว่าที่ ร\.ต\.|ว่าที่ร้อยตรี)\s+/u';
        return preg_replace($pattern, '$1', $name);
    }

    private function getFieldText($field, $form, $sub, $config, $answersMap = [], $allFieldKeys = [], &$visited = [])
    {
        if (in_array($field, $visited)) return '';
        $visited[] = $field;

        $text = '';
        if ($field === 'name') {
            $nameVal = $sub['sub_responder_name'] ?? '';
            if (empty($nameVal) && !empty($answersMap)) {
                $dbFields = $this->fieldModel->where('field_form_id', $form['form_id'])->findAll();
                foreach ($dbFields as $df) {
                    $labelLower = mb_strtolower($df['field_label']);
                    if (!empty($answersMap[$df['field_id']]) && (mb_strpos($labelLower, 'ชื่อ') !== false || mb_strpos($labelLower, 'name') !== false)) {
                        $nameVal = $answersMap[$df['field_id']];
                        break;
                    }
                }
                if (empty($nameVal)) {
                    foreach ($dbFields as $df) {
                        if (!empty($answersMap[$df['field_id']]) && $df['field_type'] === 'text') {
                            $nameVal = $answersMap[$df['field_id']];
                            break;
                        }
                    }
                }
            }
            $text = $this->formatThaiName($nameVal ?: 'ผู้ตอบแบบสอบถาม');
        } elseif ($field === 'text') {
            $text = "ได้ผ่านการทำแบบสอบถามเรื่อง \"{$form['form_title']}\"";
        } elseif ($field === 'date') {
            $submittedAt = strtotime(($sub['sub_submitted_at'] ?? null) ?: date('Y-m-d'));
            $thaiDay = date('j', $submittedAt);
            $thaiMonths = [1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'];
            $thaiMonth = $thaiMonths[(int)date('n', $submittedAt)];
            $thaiYear = date('Y', $submittedAt) + 543;
            $text = "ให้ไว้ ณ วันที่ {$thaiDay} {$thaiMonth} พ.ศ. {$thaiYear}";
        } elseif ($field === 'code') {
            $text = "เลขที่: " . (($sub['sub_cert_code'] ?? null) ?: 'CERT-2026-DEMO');
        } elseif (strpos($field, 'field_') === 0) {
            $fieldId = (int) str_replace('field_', '', $field);
            if (isset($answersMap[$fieldId]) && $answersMap[$fieldId] !== '') {
                $text = $answersMap[$fieldId];
            } elseif (($sub['sub_id'] ?? '') === 'demo') {
                $fObj = $this->fieldModel->find($fieldId);
                $text = $fObj['field_label'] ?? ('คำถามที่ ' . $fieldId);
            } else {
                $text = '';
            }
        }

        // ดูว่าฟิลด์นี้เลือกดึงฟิลด์ไหนมาต่อท้าย
        $appendKey = $config["parent_{$field}"] ?? 'none';
        if ($appendKey !== 'none') {
            $text .= ' ' . $this->getFieldText($appendKey, $form, $sub, $config, $answersMap, $allFieldKeys, $visited);
        }

        return $text;
    }

    public function generateCertBinary($sub, $form)
    {
        $formId = $form['form_id'] ?? 0;
        $dbFields = $this->fieldModel->where('field_form_id', $formId)->orderBy('field_sort_order', 'ASC')->findAll();

        $answersMap = [];
        if (($sub['sub_id'] ?? '') !== 'demo') {
            $ansRows = $this->ansModel->where('ans_sub_id', $sub['sub_id'])->findAll();
            foreach ($ansRows as $ar) {
                $answersMap[$ar['ans_field_id']] = $ar['ans_value'];
            }
        }

        $fieldsKeys = ['name', 'date', 'code', 'text'];
        if (!empty($dbFields)) {
            foreach ($dbFields as $df) {
                $fieldsKeys[] = 'field_' . $df['field_id'];
            }
        }

        $configJson = $form['form_cert_config'] ?? '';
        $config = !empty($configJson) ? json_decode($configJson, true) : [];

        $bgImagePath = $config['bg_image'] ?? ($form['form_cert_template'] ?? '');
        $fullBgPath = FCPATH . $bgImagePath;

        if (empty($bgImagePath) || !file_exists($fullBgPath)) {
            // Create A4 Landscape default image (1920x1357)
            $image = imagecreatetruecolor(1920, 1357);
            $bgColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $bgColor);

            $borderColor = imagecolorallocate($image, 99, 102, 241);
            imagesetthickness($image, 8);
            imagerectangle($image, 40, 40, 1880, 1317, $borderColor);
        } else {
            $image = null;
            $info = @getimagesize($fullBgPath);
            $mime = $info['mime'] ?? '';
            if ($mime === 'image/png') {
                $image = @imagecreatefrompng($fullBgPath);
            } elseif ($mime === 'image/jpeg') {
                $image = @imagecreatefromjpeg($fullBgPath);
            } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
                $image = @imagecreatefromwebp($fullBgPath);
            }

            if (!$image) {
                // Fallback to default A4 Landscape image if format is not supported directly by GD
                $image = imagecreatetruecolor(1920, 1357);
                $bgColor = imagecolorallocate($image, 255, 255, 255);
                imagefill($image, 0, 0, $bgColor);
                $borderColor = imagecolorallocate($image, 99, 102, 241);
                imagesetthickness($image, 8);
                imagerectangle($image, 40, 40, 1880, 1317, $borderColor);
            }
        }

        $fontPath = FCPATH . 'assets/fonts/Niramit-Bold.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = FCPATH . 'assets/fonts/Sarabun-Bold.ttf';
        }

        $getCertVal = function($param, $fKey, $default, $sortIdx = 0) use ($config) {
            $exactKey = "{$param}_{$fKey}";
            if (isset($config[$exactKey])) return $config[$exactKey];
            if (strpos($fKey, 'field_') === 0 && $sortIdx > 0 && is_array($config)) {
                $dynamicKeys = [];
                foreach ($config as $ck => $cv) {
                    if (strpos($ck, "{$param}_field_") === 0) {
                        $dynamicKeys[] = $ck;
                    }
                }
                if (isset($dynamicKeys[$sortIdx - 1])) {
                    return $config[$dynamicKeys[$sortIdx - 1]];
                }
            }
            return $default;
        };

        // สร้าง set ของฟิลด์ที่ถูกดึงไปต่อท้ายฟิลด์อื่น → ข้ามการแสดงแยก
        $consumedFields = [];
        $fieldSortCounter = 0;
        foreach ($fieldsKeys as $f) {
            if (strpos($f, 'field_') === 0) $fieldSortCounter++;
            $pv = $getCertVal('parent', $f, 'none', $fieldSortCounter);
            if ($pv !== 'none') $consumedFields[] = $pv;
        }

        $fieldSortCounter = 0;
        foreach ($fieldsKeys as $field) {
            if (strpos($field, 'field_') === 0) $fieldSortCounter++;

            $isEnabled = (int)$getCertVal('enabled', $field, 0, $fieldSortCounter) === 1;

            if (!$isEnabled || in_array($field, $consumedFields)) continue;

            $text = $this->getFieldText($field, $form, $sub, $config, $answersMap, $fieldsKeys);

            $x = (int) $getCertVal('x', $field, 960, $fieldSortCounter);
            $y = (int) $getCertVal('y', $field, 500, $fieldSortCounter);
            $fontSize = (int) $getCertVal('size', $field, 32, $fieldSortCounter);
            $align = $getCertVal('align', $field, 'center', $fieldSortCounter);
            $hexColor = $getCertVal('color', $field, '#000000', $fieldSortCounter);
            $rgb = $this->hexToRgb($hexColor);
            $colorAlloc = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);

            $weight = $getCertVal('weight', $field, 'bold', $fieldSortCounter);
            $currentFontPath = ($weight === 'regular')
                ? FCPATH . 'assets/fonts/Niramit-Regular.ttf'
                : FCPATH . 'assets/fonts/Niramit-Bold.ttf';
            if (!file_exists($currentFontPath)) {
                $currentFontPath = $fontPath;
            }

            if (!file_exists($currentFontPath)) {
                // Fallback GD builtin string
                imagestring($image, 5, $x - 100, $y, $text, $colorAlloc);
                continue;
            }

            $bbox = imagettfbbox($fontSize, 0, $currentFontPath, $text);

            if ($align === 'center') {
                $drawX = $x - ($bbox[2] + $bbox[0]) / 2;
            } elseif ($align === 'right') {
                $drawX = $x - $bbox[2];
            } else {
                $drawX = $x - $bbox[0];
            }

            $drawY = $y - ($bbox[7] + $bbox[1]) / 2;

            if ($weight === 'extrabold') {
                for ($dx = -1; $dx <= 1; $dx++) {
                    for ($dy = -1; $dy <= 1; $dy++) {
                        if ($dx !== 0 || $dy !== 0) {
                            imagettftext($image, (int) $fontSize, 0, (int) ($drawX + $dx), (int) ($drawY + $dy), $colorAlloc, $currentFontPath, $text);
                        }
                    }
                }
            } elseif ($weight === 'ultrabold') {
                for ($dx = -2; $dx <= 2; $dx++) {
                    for ($dy = -2; $dy <= 2; $dy++) {
                        if ($dx !== 0 || $dy !== 0) {
                            imagettftext($image, (int) $fontSize, 0, (int) ($drawX + $dx), (int) ($drawY + $dy), $colorAlloc, $currentFontPath, $text);
                        }
                    }
                }
            }

            imagettftext($image, (int) $fontSize, 0, (int) $drawX, (int) $drawY, $colorAlloc, $currentFontPath, $text);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return $imageData;
    }

    public function downloadCert($subId)
    {
        if ($subId === 'demo') {
            $sub = [
                'sub_id'             => 'demo',
                'sub_responder_name' => $this->request->getGet('name') ?: 'นายสมชาย รักดี',
                'sub_responder_email'=> 'demo@example.com',
                'sub_cert_code'      => 'CERT-2026-DEMO',
                'sub_submitted_at'   => date('Y-m-d H:i:s')
            ];
            $formId = $this->request->getGet('form_id');
            $form = $this->formModel->find($formId);
            if (!$form) {
                $form = [
                    'form_id'              => 0,
                    'form_title'           => 'แบบสอบถามสาธิตตัวอย่าง',
                    'form_has_certificate' => 1,
                    'form_cert_template'   => ''
                ];
            }
        } else {
            $sub = $this->subModel->find($subId);
            if (!$sub) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูล');

            $form = $this->formModel->find($sub['sub_form_id']);
            if (!$form || $form['form_has_certificate'] != 1) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('แบบสอบถามนี้ไม่มีเกียรติบัตร');
            }
        }

        $imageData = $this->generateCertBinary($sub, $form);

        $certCode = $sub['sub_cert_code'] ?: 'CERT-DEMO';
        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setHeader('Content-Disposition', 'inline; filename="certificate_' . $certCode . '.png"')
            ->setBody($imageData);
    }
}
