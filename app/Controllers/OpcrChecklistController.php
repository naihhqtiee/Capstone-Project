<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;


class OpcrChecklistController extends Controller
{
    private $uploadPath;

    public function __construct()
    {
        $this->uploadPath = WRITEPATH . 'uploads/';
    }

    public function index()
    {
        // Get all Excel files in uploads
        $files = glob($this->uploadPath . '*.{xlsx,xls}', GLOB_BRACE);
        $fileList = [];

        foreach ($files as $file) {
            $fileList[] = basename($file);
        }

        return view('staff/opcr_checklist', ['files' => $fileList]);
    }

    public function import()
    {
        $file = $this->request->getFile('opcr_file');

        if ($file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getClientName();
            $targetPath = $this->uploadPath . $fileName;

            if (file_exists($targetPath)) {
                $fileName = time() . '_' . $fileName;
            }

            $file->move($this->uploadPath, $fileName);

            return redirect()->to(base_url('staff/opcr-checklist'))
                ->with('success', 'File uploaded: ' . $fileName);
        }

        return redirect()->back()->with('error', 'File upload failed.');
    }

public function view($filename)
{
    $filename = urldecode($filename);
    $filePath = $this->uploadPath . $filename;

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File not found.');
    }

    // Load spreadsheet
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    return view('staff/opcr_checklist_view', [
        'filename' => $filename,
        'rows' => $rows
    ]);
}
public function embed($filename)
{
    $filename = urldecode($filename);
    $filePath = base_url('writable/uploads/' . $filename);

    return view('staff/opcr_checklist_embed', ['filePath' => $filePath, 'filename' => $filename]);
}



    public function download($filename)
    {
        $filename = urldecode($filename);
        $filePath = $this->uploadPath . $filename;

        if (file_exists($filePath)) {
            return $this->response->download($filePath, null)->setFileName($filename);
        }

        return redirect()->back()->with('error', 'File not found.');
    }

    public function export()
    {
        $filePath = $this->uploadPath . 'opcr_checklist.xlsx';

        if (file_exists($filePath)) {
            return $this->response->download($filePath, null)
                ->setFileName('opcr_checklist.xlsx');
        }

        return redirect()->back()->with('error', 'No file found to export.');
    }

    
}
