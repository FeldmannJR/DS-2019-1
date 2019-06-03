<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpreadsheetRequest;
use App\SpreadsheetFile;
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
