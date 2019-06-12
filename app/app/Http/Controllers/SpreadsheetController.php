<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpreadsheetRequest;
use App\SpreadsheetFile;
use Google_Service_Docs;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetController extends Controller
{


    function showUploadForm()
    {

        return view('uploadtabela', ['teste' => $this->testeLast()]);
    }

    function uploadSpreadsheet(SpreadsheetRequest $request)
    {
        $file = $request->file('tabela');
        $file_name = $file->store('spreadsheets');
        if ($file_name) {
            SpreadsheetFile::create([
                'file_name' => $file_name
            ]);
        }
    }

    function testeDrive()
    {

        $drive = $this->getDriveService();
        $id = env('GOOGLE_SPREADSHEET_ID');
        $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $file = $drive->files->get($id, ['alt' => 'media']);
        $extension = $file->getFileExtension();
        $req = $file->getBody();
        $name = date('mdYHis' . uniqid() . $extension);
        Storage::disk()->put('spreadsheets/' . $name, $req->getContents());
    }

    private function addSpreadSheetfile($file_name, $stream = null)
    {
        SpreadsheetFile::create([
            'file_name' => $file_name
        ]);

    }

    private function getDriveService()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        $client->addScope([Google_Service_Docs::DRIVE, \Google_Service_Docs::DOCUMENTS]);
        return new \Google_Service_Drive($client);
    }


    function testeLast()
    {
        $fileName = $this->getLastFile();
        $reader = IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($fileName);
        $last = $spreadsheet->getActiveSheet()->getCell('B5');
        return $last;
    }


    function downloadLast()
    {
        $fileName = $this->getLastFile();
        return response()->download($fileName, 'ultimaTabela.' . pathinfo($fileName, PATHINFO_EXTENSION));
    }

    /**
     * @return string|null
     */
    function getLastFile(): ?string
    {
        $prefix = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        $last = SpreadsheetFile::orderBy('created_at', 'desc')->limit(1)->first();
        return $last === null ? null : $prefix . $last->file_name;
    }
}
