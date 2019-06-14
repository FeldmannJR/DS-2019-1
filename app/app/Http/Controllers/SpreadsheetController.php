<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\GoogleAccount;
use App\Http\Requests\SpreadsheetRequest;
use App\Setting;
use App\SpreadsheetFile;
use App\User;
use Google_Client;
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


    /**
     * Verifica se o usuario tem uma conta do google linkada
     * @param User $user user to verify
     * @return bool if user has account linked
     */
    private function hasAccountLinked($user)
    {
        $acc = $user->googleAccount;
        if ($acc === null) {
            return false;
        }
        return true;
    }

    /**
     * Se foi revogado o token da conta do google do $user, irá remover a conta do google
     * @param User $user user to check token
     * @return bool if user token still valid
     */
    private function checkUserGoogleAccount($user)
    {
        if ($this->hasAccountLinked($user)) {
            if (!$this->isGoogleAccountValid($user->googleAccount)) {
                $user->googleAccount->delete();
                $user->google_account = null;
                $user->save();
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Verifica se o arquivo selecionado existe na conta do usuario
     * @param GoogleAccount $googleAccount
     * @param $fileId string drive file id
     * @return bool if file exists
     */
    public function fileExists($googleAccount, $fileId)
    {
        if ($this->isGoogleAccountValid($googleAccount)) {
            $client = $this->getGoogleClientWithAccount($googleAccount);
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

    /**
     * Verifica se não foi revogado o token
     * @param GoogleAccount $googleAccount
     * @return bool
     */
    public function isGoogleAccountValid($googleAccount)
    {
        if ($googleAccount === null) return false;
        $client = $this->getGoogleClientWithAccount($googleAccount);
        if ($client === null) {
            return false;
        }
        return true;
    }

    /**
     * Se o arquivo selecionado é valido
     * @return bool
     */
    public function isSelectedValid()
    {
        return $this->isGoogleAccountValid($this->getSelectedGoogleAccount());
    }

    /**
     * Get the current selected file meta
     * @return \Google_Service_Drive_DriveFile
     */
    public function getSelectedFileMetadata()
    {
        if ($this->isSelectedValid()) {
            $acc = $this->getSelectedGoogleAccount();
            $drive = $this->getSelectedDrive();
            return $drive->files->get($acc->file_id);
        }
        return null;
    }

    /**
     * Get selected google account
     * @return GoogleAccount|null
     */
    public function getSelectedGoogleAccount()
    {
        $id = Setting::get('google_account');
        if ($id === null) return null;
        return GoogleAccount::find($id);
    }

    /**
     * Get the selected drive
     * @return Google_Service_Drive
     */
    public function getSelectedDrive()
    {
        $acc = $this->getSelectedGoogleAccount();
        $client = $this->getGoogleClientWithAccount($acc);
        return new Google_Service_Drive($client);
    }

    /**
     * Pagina inicial
     * @param Request $req
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index(Request $req)
    {

        $user = $req->user();
        $user_google = $user->googleAccount;
        // Checa se a conta do google do cara ta linkada ainda
        $this->checkUserGoogleAccount($user);

        $metadata = $this->getSelectedFileMetadata();
        $file_google = $this->getSelectedGoogleAccount();
        $last = SpreadsheetFile::getLast();

        if ($this->hasAccountLinked($user)) {
            $client = $this->getGoogleClientWithAccount($user_google);
            $client_id = $client->getClientId();

            $browser_developer_key = env('GOOGLE_API_KEY');
            $app_id = env('GOOGLE_APP_ID');

            $token = $client->getAccessToken()['access_token'];

            $scope = 'https://www.googleapis.com/auth/drive.file';
            $redirect_url = action('SpreadsheetController@pickFile');
            return view('planilhas.index', compact('scope', 'client_id', 'browser_developer_key', 'token', 'redirect_url', 'app_id', 'metadata', 'file_google', 'user_google', 'last'));
        } else {
            return view('planilhas.index', compact('metadata', 'last', 'file_google'));

        }
    }

    /**
     * Chamado quando o usuario escolhe o arquivo no picker
     */
    public function pickFile(Request $req)
    {
        $user = $req->user();
        if (!$this->checkUserGoogleAccount($user)) {
            return \redirect('planilhas')->with('error', 'Conta do google não está mais linkada!');
        }
        $validator = Validator::make($req->all(), [
            'fileId' => 'required|min:10|max:100'
        ]);
        $validator->after(function ($v) use ($user) {
            if (!$this->fileExists($user->googleAccount, $v->validated()['fileId'])) {
                $v->errors()->add('fileId', 'Arquivo não encontrado!');
            }
        });
        if ($validator->fails()) {
            return \redirect('planilhas')
                ->withErrors($validator)
                ->withInput();
        }
        $input = $validator->validated();
        $user->googleAccount->file_id = $input['fileId'];
        $user->googleAccount->save();
        Setting::set('google_account', $user->googleAccount->id);

        return \redirect('planilhas')->with('success', 'Selecionado arquivo com sucesso!');

    }


    /**
     * Função baixa o arquivo doo google drive e guarda no storage e banco de dados
     * @param bool $redirect se vai redirecionar o pedido para outra pagina ou retornar booleano
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function downloadFromDrive($redirect = false)
    {
        if ($this->isSelectedValid()) {
            $drive = $this->getSelectedDrive();
            $fileId = $this->getSelectedGoogleAccount()->file_id;
            $ext = null;
            try {
                $meta = $drive->files->get($fileId);
                if ($meta->getMimeType() == "application/vnd.google-apps.spreadsheet") {
                    $media = $drive->files->export($fileId, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', ['alt' => 'media']);
                    $ext = 'xlsx';
                } else {
                    $media = $drive->files->get($fileId, ['alt' => 'media']);
                }
            } catch (\Google_Service_Exception $e) {
                if ($redirect) {
                    return \redirect(action('SpreadsheetController@index'))->with('error', "Error Google: " . $e->getMessage());
                } else {
                    return 'Error Google ' . $e->getMessage();
                }
            }
            $name = $this->saveSpreadsheet($media->getBody()->getContents(), $meta, $ext);

            if ($name) {
                if ($redirect) {
                    return \redirect(action('SpreadsheetController@index'))->with('success', "Arquivo baixado para o disco com o nome $name");
                } else {
                    return true;
                }
            }
        }
        if ($redirect) {
            return \redirect(action('SpreadsheetController@index'))->with('error', "Não consegui baixar o arquivo!");
        } else {
            return false;
        }
    }

    /**
     * Alias para baixar o arquivo do google drive para o storage e redirecionar o usuario
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function downloadFromDriveWithRedirects()
    {
        return $this->downloadFromDrive(true);
    }


    /**
     * Redireciona o usuario para a pagina de login do google caso não tenha conta linkada
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function googleLogin(Request $request)
    {
        $user = $request->user();
        if ($this->hasAccountLinked($user)) {
            return \redirect(action('SpreadsheetController@index'));
        }
        $client = $this->getGoogleClient();
        $url = $client->createAuthUrl();
        return \redirect()->away($url);

    }

    /**
     * Deslinka a conta do usuario com a do google revocando o token, e redireciona o usuario para o index
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function googleLogout(Request $request)
    {
        $user = $request->user();
        if (!$this->hasAccountLinked($user)) {
            return \redirect(action('SpreadsheetController@index'));
        }

        $user->googleAccount->delete();
        $user->google_account = null;
        $user->save();
        return \redirect(action('SpreadsheetController@index'));
    }


    /*
     * Esta pagina é chamada pelo google após a autenticação
     * com a input 'code', sendo o código para pedir o token
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string|void
     */
    public function googleCallback(Request $request)
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
        $refresh_token = $client->getRefreshToken();
        $user = $request->user();

        if ($token) {
            $oauth2 = new \Google_Service_Oauth2($client);
            $info = $oauth2->userinfo->get();
            $id = $info->getId();

            $count = GoogleAccount::where('google_id', $id)->count();
            $isCurrent = false;
            if ($count > 0) {
                if ($this->hasAccountLinked($request->user())) {
                    $google = $request->user()->googleAccount;
                    if ($google->google_id === $id) {
                        $isCurrent = true;
                    }
                }
                if (!$isCurrent) {
                    return redirect(action('SpreadsheetController@index'))->with('error', 'Está conta já está linkada com outro usuario!');
                }
            }
            if ($this->hasAccountLinked($request->user())) {
                if ($google->google_id !== $id) {
                    return redirect(action('SpreadsheetController@index'))->with('error', 'Você já tem uma conta linkada e não é selecionada!');
                }
            }

            $google = new GoogleAccount(['refresh_token' => $refresh_token, 'token' => $token, 'google_id' => $id, 'picture' => $info->getPicture(), 'email' => $info->getEmail()]);
            if ($isCurrent) {
                /**
                 * @var $account GoogleAccount
                 */
                $account = $user->googleAccount;
                $account->refresh_token = $google->refresh_token;
                $account->token = $google->token;
                $account->email = $google->email;
                $account->picture = $google->picture;
                $account->save();
            } else {

                $google->save();
                $user->googleAccount()->associate($google);
                $user->save();
            }
            return \redirect(action('SpreadsheetController@index'));

        } else {
            return "Error";
        }
    }

    /**
     * Retorna uma instacia do google client já autenticado com a $googleAccount
     * @param GoogleAccount $googleAccount
     * @return Google_Client|null
     */
    private function getGoogleClientWithAccount($googleAccount)
    {
        // Instancia um novo cliente
        $client = $this->getGoogleClient();
        if ($googleAccount !== null) {
            // Seta o token que tava no banco
            $client->setAccessToken($googleAccount->token);
            // Se o token expirou ele puxa outro com o refresh token
            if ($client->isAccessTokenExpired()) {
                $token = $client->refreshToken($googleAccount->refresh_token);
                if ($token) {
                    $googleAccount->token = $token;
                    $googleAccount->save();
                } else {
                    return null;
                }
            }
        }
        return $client;
    }


    /**
     * Retorna o google client basico sem autenticação
     * @return Google_Client
     * @throws \Google_Exception
     */
    private function getGoogleClient()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(base_path(env('GOOGLE_CLIENT_CREDENTIALS')));
        $client->addScope([Google_Service_Drive::DRIVE_FILE, \Google_Service_Oauth2::USERINFO_EMAIL]);
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(action('SpreadsheetController@googleCallback'));
        $client->setIncludeGrantedScopes(true);
        $client->setAccessType('offline');

        return $client;
    }

    /**
     * Salva a tabela no disco e inseri no banco de dados
     * @param $stream
     * @param \Google_Service_Drive_DriveFile $metadata
     * @param string|null $ext
     * @return string|null
     */
    private function saveSpreadsheet($stream, \Google_Service_Drive_DriveFile $metadata, string $ext = null)
    {
        if ($ext === null)
            $ext = pathinfo($metadata->getName(), PATHINFO_EXTENSION);
        # Gera um nome com a hora atual + id unico
        $name = date('d.m.Y-H:i:s-') . uniqid() . '.' . $ext;
        $path = "spreadsheets/$name";
        # Salva no storage
        if (Storage::disk()->put($path, $stream)) {
            // Salva no banco
            SpreadsheetFile::create([
                'file_name' => $name,
                'full_path' => $path,
                'original_file_name' => $metadata->getName(),
                'file_size' => Storage::disk()->size($path)
            ]);
            return $name;
        }
        return null;
    }


    /**
     * Baixa no navegador a ultima tabela que foi baixada pelo servidor
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function downloadLast()
    {
        $fileName = SpreadsheetFile::getLastPath();
        if ($fileName === null) {
            return \redirect(action('SpreadsheetController@index'))->with('error', 'Nenhuma planilha localizada no servidor!');
        }
        return response()->download($fileName, 'ultimaTabela.' . pathinfo($fileName, PATHINFO_EXTENSION));
    }


}
