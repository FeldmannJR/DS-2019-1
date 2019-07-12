@extends ('layouts.app')

@push('scripts')
@endpush
@section('content')
    <v-container fluid>
        <v-layout>

            @foreach($indicators as $indicator)
                <v-card class="pa-2" >
                    {{$indicator->name}}
                </v-card>
            @endforeach
        </v-layout>
    </v-container>
@endsection