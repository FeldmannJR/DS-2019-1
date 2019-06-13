<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\SpreadsheetRequest;
use App\Setting;
use App\SpreadsheetFile;
use App\User;
use Google_Service_Docs;
use Google_Service_Drive;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetController extends Controller
{


    /**
     * SpreadsheetController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:' . UserRole::Admin);
    }


    private function checkUserToken($user)
    {
        if ($user->google_token == null) {
            return false;
        }
        return true;
    }


    function index(Request $req)
    {


        dd($this->getSelectedMeta());
        $user = $req->user();

        if (!$this->checkUserToken($user)) {
            return redirect(action('SpreadsheetController@googleLogin'));
        }


        $client = $this->getGoogleClient();
        $client->setAccessToken($user->google_token);
        $client_id = $client->getClientId();
        $browser_developer_key = 'AIzaSyDVxAwmIgsap8-vJvAREOyX5bbbjL0gBBE';
        $token = $client->getAccessToken()['access_token'];
        $app_id = '90617204341';
        $scope = 'https://www.googleapis.com/auth/drive.file';
        $redirect_url = action('SpreadsheetController@pickFile');

        $metadata = $this->getSelectedMeta();

        return view('planilhas.choosefile', compact('scope', 'client_id', 'browser_developer_key', 'token', 'redirect_url', 'app_id', 'metadata'));
    }

    public function fileExists(User $user, $fileId)
    {
        if ($this->checkUserToken($user)) {
            $client = $this->getGoogleClient();
            $client->setAccessToken($user->google_token);
            $drive = new Google_Service_Drive($client);
            try {

                $f = $drive->files->get($fileId);
                if ($f) {
                    return true;
                }
            } catch (\Google_Service_Exception $e) {
                return false;
            }
        }
        return false;
    }


    public function getSelectedMeta()
    {
        if ($this->isSelectedValid()) {
            $fileId = $this->getFileId();
            $drive = $this->getSelectedDrive();
            return $drive->files->get($fileId);
        }
    }

    public function getFileId()
    {
        return Setting::get('fileId');
    }

    public function getSelectedUser()
    {
        $user_id = Setting::get('planilha_user');
        $user = User::find($user_id);
        if ($user !== null) {
            return $user;
        }
    }

    public function isSelectedValid()
    {
        $user_id = Setting::get('planilha_user');
        $fileId = $this->getFileId();

        if ($fileId !== null && $user_id !== null) {
            $user = User::find($user_id);
            if ($user !== null) {
                if ($user->google_token !== null && $this->checkUserToken($user)) {
                    return true;
                }
            }
        }
        return false;
    }

    public
    function getSelectedDrive()
    {
        $user = $this->getSelectedUser();
        $client = $this->getGoogleClient();
        $client->setAccessToken($user->google_token);
        return new Google_Service_Drive($client);
    }

    public
    function downloadSelected(Request $req)
    {
        $user = $req->user();
        if ($this->checkUserToken($user)) {
            $fileId = $this->getFileId();
            $drive = $this->getSelectedDrive();
            $meta = $drive->files->get($fileId);
            $media = $drive->files->get($fileId, ['alt' => 'media']);


            $this->saveSpreadsheet($media->getBody()->getContents(), $meta);
        }
        return null;
    }


    public
    function pickFile(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'fileId' => 'required|min:10|max:100'
        ]);
        $user = $req->user();
        $validator->after(function ($v) use ($user) {
            if (!$this->fileExists($user, $v->validated()['fileId'])) {
                $v->errors()->add('fileId', 'Arquivo não encontrado!');
            }
        });
        if ($validator->fails()) {
            return \redirect('planilhas')
                ->withErrors($validator)
                ->withInput();
        }
        $input = $validator->validated();
        Setting::set('planilha_user', $user->id);
        Setting::set('fileId', $input['fileId']);


    }

    function googleLogin()
    {
        $client = $this->getGoogleClient();
        $url = $client->createAuthUrl();
        echo "redirecting to $url";
        return \redirect()->away($url);

    }

    function googleCallback(Request $request)
    {
        if (!$request->has('code')) {
            echo "Invalid";
            return;
        }
        $client = $this->getGoogleClient();
        $code = $request->input('code');
        $client->fetchAccessTokenWithAuthCode($code);
        // Conseguiu pegar o token

        $token = $client->getAccessToken();
        if ($token) {
            dump($code);
            dump($token);
            $user = $request->user();
            $user->google_code = $code;
            $user->google_token = $token;
            $user->save();
        } else {
            return "Error";
        }
    }


    private
    function getGoogleClient()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(base_path(env('GOOGLE_CLIENT_CREDENTIALS')));
        $client->addScope(Google_Service_Drive::DRIVE_FILE);
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(action('SpreadsheetController@googleCallback'));
        $client->setIncludeGrantedScopes(true);
        $client->setAccessType('offline');

        return $client;
    }


    private
    function saveSpreadsheet($stream, $metadata)
    {
        $ext = pathinfo($metadata->getName(), PATHINFO_EXTENSION);
        # Gera um nome com a hora atual + id unico
        $name = date('d.m.Y-H:i:s-') . uniqid() . '.' . $ext;
        # Salva no storage
        if (Storage::disk()->put('spreadsheets/' . $name, $stream)) {
            SpreadsheetFile::create([
                'file_name' => $name
            ]);
            echo "Salvou com o nome $name";
        }

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


    // Não é mais usado
    private
    function getDriveService()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        $client->addScope([Google_Service_Docs::DRIVE]);
        return new \Google_Service_Drive($client);
    }

    // Não é mais usado
    function downloadPlanilha()
    {
        $drive = $this->getDriveService();
        $id = env('GOOGLE_SPREADSHEET_ID');
        # Pega Metadata do arquivo, nome e etc
        $metadata = $drive->files->get($id);

        # Pega a stream do arquivo
        $file = $drive->files->get($id, ['alt' => 'media']);
        $contents = $file->getBody()->getContents();

        $this->saveSpreadsheet($contents, $metadata);
    }


}
