@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Set up Google Authenticator</div>

                    <div class="panel-body" style="text-align: center;">
                        <p>Set up your two-factor authentication by scanning the barcode below. Alternatively, you can use the code {{ $secret }}</p>
                        <div>
                            {{ QrCode::size(300)->generate($qrCodeUrl) }}
                        </div>
                        <p>You must set up your Google Authenticator app before continuing.</p>
                        <div>
                            <a href="{{route('complete-activation')}}"><button class="btn-primary">Enable Two-Factor Authentication</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
