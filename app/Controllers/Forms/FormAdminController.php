<?php

namespace App\Controllers\Forms;

use App\Controllers\BaseController;
use App\Models\Forms\FormModel;
use App\Models\Forms\FormFieldModel;
use App\Models\Forms\FormSubmissionModel;
use App\Models\Forms\FormAnswerModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FormAdminController extends BaseController
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

        $this->initTables();
    }

    private function initTables()
    {
        $db = \Config\Database::connect();
        
        if (!$db->tableExists('Tb_Forms')) {
            $db->query("CREATE TABLE Tb_Forms (
                form_id INT AUTO_INCREMENT PRIMARY KEY,
                form_title VARCHAR(255) NOT NULL,
                form_description TEXT NULL,
                form_status VARCHAR(50) NOT NULL DEFAULT 'active',
                form_has_certificate TINYINT(1) NOT NULL DEFAULT 0,
                form_cert_template VARCHAR(255) NULL,
                form_cert_config TEXT NULL,
                form_created_by INT NULL,
                form_created_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        if (!$db->tableExists('Tb_Form_Fields')) {
            $db->query("CREATE TABLE Tb_Form_Fields (
                field_id INT AUTO_INCREMENT PRIMARY KEY,
                field_form_id INT NOT NULL,
                field_label TEXT NOT NULL,
                field_type VARCHAR(50) NOT NULL,
                field_options TEXT NULL,
                field_is_required TINYINT(1) NOT NULL DEFAULT 0,
                field_sort_order INT NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        if (!$db->tableExists('Tb_Form_Submissions')) {
            $db->query("CREATE TABLE Tb_Form_Submissions (
                sub_id INT AUTO_INCREMENT PRIMARY KEY,
                sub_form_id INT NOT NULL,
                sub_responder_name VARCHAR(255) NULL,
                sub_responder_email VARCHAR(255) NULL,
                sub_cert_code VARCHAR(100) NULL,
                sub_submitted_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        if (!$db->tableExists('Tb_Form_Answers')) {
            $db->query("CREATE TABLE Tb_Form_Answers (
                ans_id INT AUTO_INCREMENT PRIMARY KEY,
                ans_sub_id INT NOT NULL,
                ans_field_id INT NOT NULL,
                ans_value TEXT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    private function checkAccess()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }
        return true;
    }

    public function index()
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $userId = session()->get('u_id');

        $builder = $this->formModel->orderBy('form_created_at', 'DESC');
        if ($userId) {
            $builder->groupStart()
                    ->where('form_created_by', $userId)
                    ->orWhere('form_created_by', null)
                    ->groupEnd();
        }
        $forms = $builder->findAll();

        if ($userId && !empty($forms)) {
            foreach ($forms as &$f) {
                if (empty($f['form_created_by'])) {
                    $this->formModel->update($f['form_id'], ['form_created_by' => $userId]);
                    $f['form_created_by'] = $userId;
                }
                $f['response_count'] = $this->subModel->where('sub_form_id', $f['form_id'])->countAllResults();
            }
        } else {
            foreach ($forms as &$f) {
                $f['response_count'] = $this->subModel->where('sub_form_id', $f['form_id'])->countAllResults();
            }
        }

        $data = [
            'title'    => 'ระบบแบบสอบถาม & เกียรติบัตรออนไลน์',
            'fullname' => session()->get('u_fullname'),
            'forms'    => $forms
        ];

        return view('forms/admin/index', $data);
    }

    public function store()
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $title = $this->request->getPost('form_title');
        if (empty($title)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อแบบสอบถาม']);
        }

        $formId = $this->formModel->insert([
            'form_title'           => $title,
            'form_description'     => $this->request->getPost('form_description'),
            'form_status'          => 'active',
            'form_has_certificate' => (int) $this->request->getPost('form_has_certificate'),
            'form_created_by'      => session()->get('u_id'),
            'form_created_at'      => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'สร้างแบบสอบถามเรียบร้อยแล้ว',
            'redirect' => base_url("staff/forms/builder/{$formId}")
        ]);
    }

    private function verifyOwnership($form)
    {
        $userId = session()->get('u_id');
        if ($userId && !empty($form['form_created_by']) && $form['form_created_by'] != $userId) {
            return false;
        }
        return true;
    }

    public function edit($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if (!$form || !$this->verifyOwnership($form)) {
            return redirect()->to(base_url('staff/forms'))->with('error', 'ไม่พบแบบสอบถามหรือคุณไม่มีสิทธิ์');
        }

        $data = [
            'title'    => " ตั้งค่าทั่วไป: {$form['form_title']}",
            'fullname' => session()->get('u_fullname'),
            'form'     => $form
        ];

        return view('forms/admin/edit', $data);
    }

    public function builder($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if (!$form || !$this->verifyOwnership($form)) {
            return redirect()->to(base_url('staff/forms'))->with('error', 'ไม่พบแบบสอบถามหรือคุณไม่มีสิทธิ์');
        }

        $fields = $this->fieldModel->where('field_form_id', $formId)->orderBy('field_sort_order', 'ASC')->findAll();
        $certConfig = !empty($form['form_cert_config']) ? json_decode($form['form_cert_config'], true) : [];

        $data = [
            'title'       => " Form Builder: {$form['form_title']}",
            'fullname'    => session()->get('u_fullname'),
            'form'        => $form,
            'fields'      => $fields,
            'cert_config' => $certConfig
        ];

        return view('forms/admin/builder', $data);
    }

    public function certificate($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if (!$form || !$this->verifyOwnership($form)) {
            return redirect()->to(base_url('staff/forms'))->with('error', 'ไม่พบแบบสอบถามหรือคุณไม่มีสิทธิ์');
        }

        $fields = $this->fieldModel->where('field_form_id', $formId)->orderBy('field_sort_order', 'ASC')->findAll();
        $certConfig = !empty($form['form_cert_config']) ? json_decode($form['form_cert_config'], true) : [];

        $data = [
            'title'       => " ตั้งค่าเกียรติบัตร: {$form['form_title']}",
            'fullname'    => session()->get('u_fullname'),
            'form'        => $form,
            'fields'      => $fields,
            'cert_config' => $certConfig
        ];

        return view('forms/admin/certificate', $data);
    }

    public function uploadCertChunk()
    {
        $fileId = $this->request->getPost('file_id');
        $chunkIndex = (int) $this->request->getPost('chunk_index');
        $totalChunks = (int) $this->request->getPost('total_chunks');
        $originalFilename = $this->request->getPost('filename');
        $file = $this->request->getFile('chunk');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ชิ้นส่วนไฟล์ไม่ถูกต้อง']);
        }

        $tempDir = WRITEPATH . 'uploads/temp_chunks/' . $fileId . '/';
        if (!is_dir($tempDir)) mkdir($tempDir, 0777, true);

        $file->move($tempDir, 'chunk_' . $chunkIndex);

        if ($chunkIndex + 1 === $totalChunks) {
            $ext = pathinfo($originalFilename, PATHINFO_EXTENSION);
            $newName = 'cert_bg_' . uniqid() . '.' . strtolower($ext);
            $targetDir = FCPATH . 'uploads/forms/certificates/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $out = fopen($targetDir . $newName, 'wb');
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFile = $tempDir . 'chunk_' . $i;
                if (file_exists($chunkFile)) {
                    $in = fopen($chunkFile, 'rb');
                    stream_copy_to_stream($in, $out);
                    fclose($in);
                }
            }
            fclose($out);

            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);

            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'อัปโหลดภาพเรียบร้อยแล้ว',
                'filename' => 'uploads/forms/certificates/' . $newName
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'uploading',
            'message' => 'ได้รับ Chunk ' . ($chunkIndex + 1) . '/' . $totalChunks
        ]);
    }

    public function saveGeneralSettings($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $form = $this->formModel->find($formId);
        if (!$form) return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลแบบสอบถาม']);

        $updateData = [
            'form_title'           => $this->request->getPost('form_title'),
            'form_description'     => $this->request->getPost('form_description'),
            'form_status'          => $this->request->getPost('form_status') ?? 'active',
            'form_has_certificate' => (int) $this->request->getPost('form_has_certificate'),
        ];

        $this->formModel->update($formId, $updateData);

        return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกข้อมูลทั่วไปเรียบร้อยแล้ว']);
    }

    public function saveSettings($formId)
    {
        return $this->saveGeneralSettings($formId);
    }

    public function saveCertSettings($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $form = $this->formModel->find($formId);
        if (!$form) return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลแบบสอบถาม']);

        $updateData = [
            'form_has_certificate' => (int) $this->request->getPost('form_has_certificate'),
        ];

        // Cert template from direct file or chunked upload
        $bgUploaded = $this->request->getPost('bg_image_uploaded');
        if ($bgUploaded) {
            $updateData['form_cert_template'] = $bgUploaded;
        } else {
            $certFile = $this->request->getFile('form_cert_template');
            if ($certFile && $certFile->isValid() && !$certFile->hasMoved()) {
                $targetDir = FCPATH . 'uploads/forms/certificates/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $newName = $certFile->getRandomName();
                $certFile->move($targetDir, $newName);
                $updateData['form_cert_template'] = 'uploads/forms/certificates/' . $newName;
            }
        }

        $existingConfig = !empty($form['form_cert_config']) ? json_decode($form['form_cert_config'], true) : [];
        if (!is_array($existingConfig)) $existingConfig = [];

        $dbFields = $this->fieldModel->where('field_form_id', $formId)->orderBy('field_sort_order', 'ASC')->findAll();
        $certFields = ['name', 'date', 'code', 'text'];
        if (!empty($dbFields)) {
            foreach ($dbFields as $df) {
                $certFields[] = 'field_' . $df['field_id'];
            }
        }

        $certConfig = $existingConfig;
        if (isset($updateData['form_cert_template'])) {
            $certConfig['bg_image'] = $updateData['form_cert_template'];
        }

        foreach ($certFields as $fieldKey) {
            $certConfig["enabled_{$fieldKey}"] = $this->request->getPost("enabled_{$fieldKey}") ? 1 : 0;
            $certConfig["parent_{$fieldKey}"]  = $this->request->getPost("parent_{$fieldKey}") ?? 'none';
            $certConfig["x_{$fieldKey}"]       = (int) ($this->request->getPost("x_{$fieldKey}") ?? ($existingConfig["x_{$fieldKey}"] ?? 960));
            $certConfig["y_{$fieldKey}"]       = (int) ($this->request->getPost("y_{$fieldKey}") ?? ($existingConfig["y_{$fieldKey}"] ?? 500));
            $certConfig["size_{$fieldKey}"]    = (int) ($this->request->getPost("size_{$fieldKey}") ?? ($existingConfig["size_{$fieldKey}"] ?? 32));
            $certConfig["align_{$fieldKey}"]   = $this->request->getPost("align_{$fieldKey}") ?? ($existingConfig["align_{$fieldKey}"] ?? 'center');
            $certConfig["weight_{$fieldKey}"]  = $this->request->getPost("weight_{$fieldKey}") ?? ($existingConfig["weight_{$fieldKey}"] ?? 'bold');
            $certConfig["color_{$fieldKey}"]   = $this->request->getPost("color_{$fieldKey}") ?? ($existingConfig["color_{$fieldKey}"] ?? '#000000');
        }

        $updateData['form_cert_config'] = json_encode($certConfig, JSON_UNESCAPED_UNICODE);

        $this->formModel->update($formId, $updateData);

        return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกการตั้งค่าเกียรติบัตรเรียบร้อยแล้ว']);
    }

    public function saveFields($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $fieldsJson = $this->request->getPost('fields');
        $fields = json_decode($fieldsJson, true);

        if (!is_array($fields)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'รูปแบบข้อมูลข้อคำถามไม่ถูกต้อง']);
        }

        $existingFields = $this->fieldModel->where('field_form_id', $formId)->findAll();
        $existingIds = array_column($existingFields, 'field_id');

        $keptIds = [];
        $savedFieldsResult = [];

        foreach ($fields as $idx => $f) {
            $fieldId = !empty($f['field_id']) ? (int) $f['field_id'] : null;
            $opts = !empty($f['options']) ? (is_array($f['options']) || is_object($f['options']) ? json_encode($f['options'], JSON_UNESCAPED_UNICODE) : $f['options']) : null;
            
            $data = [
                'field_form_id'     => $formId,
                'field_label'       => $f['label'] ?? 'คำถามที่ไม่มีหัวข้อ',
                'field_type'        => $f['type'] ?? 'text',
                'field_options'     => $opts,
                'field_is_required' => !empty($f['is_required']) ? 1 : 0,
                'field_sort_order'  => $idx + 1
            ];

            if ($fieldId && in_array($fieldId, $existingIds)) {
                $this->fieldModel->update($fieldId, $data);
                $keptIds[] = $fieldId;
                $savedFieldsResult[] = array_merge(['field_id' => $fieldId], $data);
            } else {
                $newId = $this->fieldModel->insert($data);
                $keptIds[] = $newId;
                $savedFieldsResult[] = array_merge(['field_id' => $newId], $data);
            }
        }

        $toDelete = array_diff($existingIds, $keptIds);
        if (!empty($toDelete)) {
            $this->fieldModel->whereIn('field_id', $toDelete)->delete();
        }

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'บันทึกชุดคำถามเรียบร้อยแล้ว',
            'saved_fields' => $savedFieldsResult
        ]);
    }

    public function delete($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if ($form && $this->verifyOwnership($form)) {
            // 1. Delete associated certificate template file from disk
            if (!empty($form['form_cert_template'])) {
                $certPath = FCPATH . $form['form_cert_template'];
                if (file_exists($certPath) && is_file($certPath)) {
                    @unlink($certPath);
                }
            }

            // 2. Check form_cert_config for any bg_image file
            if (!empty($form['form_cert_config'])) {
                $certConfig = json_decode($form['form_cert_config'], true);
                if (!empty($certConfig['bg_image'])) {
                    $bgPath = FCPATH . $certConfig['bg_image'];
                    if (file_exists($bgPath) && is_file($bgPath)) {
                        @unlink($bgPath);
                    }
                }
            }

            // 3. Delete database records (Answers, Submissions, Fields, Form)
            $subs = $this->subModel->where('sub_form_id', $formId)->findAll();
            foreach ($subs as $s) {
                $this->ansModel->where('ans_sub_id', $s['sub_id'])->delete();
            }
            $this->subModel->where('sub_form_id', $formId)->delete();
            $this->fieldModel->where('field_form_id', $formId)->delete();
            $this->formModel->delete($formId);
        }

        return redirect()->to(base_url('staff/forms'))->with('success', 'ลบแบบสอบถามและไฟล์เกียรติบัตรเรียบร้อยแล้ว');
    }

    public function responses($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if (!$form || !$this->verifyOwnership($form)) return redirect()->to(base_url('staff/forms'))->with('error', 'ไม่พบแบบสอบถามหรือคุณไม่มีสิทธิ์');

        $fields = $this->fieldModel->where('field_form_id', $formId)->orderBy('field_sort_order', 'ASC')->findAll();
        $submissions = $this->subModel->where('sub_form_id', $formId)->orderBy('sub_submitted_at', 'DESC')->findAll();

        foreach ($submissions as &$sub) {
            $answers = $this->ansModel->where('ans_sub_id', $sub['sub_id'])->findAll();
            $ansMap = [];
            foreach ($answers as $a) {
                $ansMap[$a['ans_field_id']] = $a['ans_value'];
            }
            $sub['answers'] = $ansMap;
        }

        $data = [
            'title'       => "ผลการตอบแบบสอบถาม: {$form['form_title']}",
            'fullname'    => session()->get('u_fullname'),
            'form'        => $form,
            'fields'      => $fields,
            'submissions' => $submissions
        ];

        return view('forms/admin/responses', $data);
    }

    public function clearResponses($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if (!$form) return redirect()->to(base_url('staff/forms'))->with('error', 'ไม่พบแบบสอบถาม');

        $submissions = $this->subModel->where('sub_form_id', $formId)->findAll();
        $subIds = array_column($submissions, 'sub_id');

        if (!empty($subIds)) {
            $this->ansModel->whereIn('ans_sub_id', $subIds)->delete();
            $this->subModel->where('sub_form_id', $formId)->delete();
        }

        return redirect()->to(base_url("staff/forms/responses/{$formId}"))->with('success', 'ลบข้อมูลการตอบทั้งหมดเรียบร้อยแล้ว');
    }

    public function exportExcel($formId)
    {
        $chk = $this->checkAccess();
        if ($chk !== true) return $chk;

        $form = $this->formModel->find($formId);
        if (!$form) return redirect()->to(base_url('staff/forms'));

        $fields = $this->fieldModel->where('field_form_id', $formId)->orderBy('field_sort_order', 'ASC')->findAll();
        $submissions = $this->subModel->where('sub_form_id', $formId)->orderBy('sub_submitted_at', 'ASC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Responses');

        $headers = ['ลำดับ', 'วันเวลาที่ตอบ', 'ชื่อ-นามสกุล', 'อีเมล', 'รหัสเกียรติบัตร'];
        foreach ($fields as $f) {
            $headers[] = $f['field_label'];
        }

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        $row = 2;
        foreach ($submissions as $idx => $sub) {
            $answers = $this->ansModel->where('ans_sub_id', $sub['sub_id'])->findAll();
            $ansMap = [];
            foreach ($answers as $a) {
                $ansMap[$a['ans_field_id']] = $a['ans_value'];
            }

            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValue('B' . $row, $sub['sub_submitted_at']);
            $sheet->setCellValue('C' . $row, $sub['sub_responder_name']);
            $sheet->setCellValue('D' . $row, $sub['sub_responder_email']);
            $sheet->setCellValue('E' . $row, $sub['sub_cert_code']);

            $colIdx = 5;
            foreach ($fields as $f) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
                $sheet->setCellValue($colLetter . $row, $ansMap[$f['field_id']] ?? '');
                $colIdx++;
            }
            $row++;
        }

        $filename = 'form_responses_' . $formId . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
