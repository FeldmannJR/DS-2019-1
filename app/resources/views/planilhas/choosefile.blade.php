@extends('layouts.app')

@push('scripts')

    <script type="text/javascript">

        // The Browser API key obtained from the Google API Console.
        // Replace with your own Browser API key, or your own key.
        var developerKey = '{{$browser_developer_key}}';

        // The Client ID obtained from the Google API Console. Replace with your own Client ID.
        var clientId = "{{$client_id}}"

        // Scope to use to access user's Drive items.
        var scope = ['{{$scope}}'];

        var pickerApiLoaded = false;
        var oauthToken = "{{$token}}";
        var appId = '{{$app_id}}';

        // Use the API Loader script to load google.picker and gapi.auth.
        function onApiLoad() {
            gapi.load('auth2', onAuthApiLoad);
            gapi.load('picker', onPickerApiLoad);
        }

        function onAuthApiLoad() {
            var authBtn = document.getElementById('auth');
            authBtn.disabled = false;
            authBtn.addEventListener('click', function () {
                gapi.auth2.init({client_id: clientId}).then(function (googleAuth) {
                    googleAuth.signIn({scope: scope}).then(function (result) {
                        handleAuthResult(result.getAuthResponse());
                    })
                })
            });
        }

        function onPickerApiLoad() {
            pickerApiLoaded = true;
        }

        function handleAuthResult(authResult) {
            if (authResult && !authResult.error) {
                oauthToken = authResult.access_token;
                createPicker();
            }
        }

        // Create and render a Picker object for picking user Photos.
        function createPicker() {
            if (pickerApiLoaded && oauthToken) {
                var view = new google.picker.DocsView(google.picker.ViewId.SPREADSHEETS);
                var picker = new google.picker.PickerBuilder()
                    .addView(view)
                    .setOAuthToken(oauthToken)
                    .setDeveloperKey(developerKey)
                    .setCallback(pickerCallback)
                    .setAppId(appId)
                    .build();
                picker.setVisible(true);
            }
        }

        // A simple callback implementation.
        function pickerCallback(data) {
            var fileId = 'nothing';
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                var doc = data[google.picker.Response.DOCUMENTS][0];
                fileId = doc[google.picker.Document.ID];
                document.getElementById('choose_file_id').value = fileId;
                document.getElementById('choose_file').submit();
                return;
            }
        }
    </script>
@endpush
@section('content')
    @error('fileId')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{$message}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @enderror

    @if($metadata !== null)


    @endif
    <!-- The Google API Loader script. -->
    <button type='button' onclick="createPicker();" class="btn btn-primary btn-lg">Selecionar Planilha</button>
    <script type="text/javascript" src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
    <form id="choose_file" action="{{ action('SpreadsheetController@pickFile') }}" method="POST"
          style="display: none;">
        @csrf
        <input id="choose_file_id" name="fileId" type="hidden" value=""/>
    </form>
@endsection