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

                        <form action="{{route('complete-activation')}}" method="post">
                            Type your code: <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                                <input type="text" id="code" name="code" class="form-control" placeholder="Enter code" value="{{ old('code') }}">
                                <span class="text-danger">{{ $errors->first('code') }}</span>
                            </div>
                            <input type="submit" value="check">
                        </form>

{{--                        @if ($valid)--}}
{{--                            <div style="color: green; font-weight: 800;">VALID</div>--}}
{{--                        @else--}}
{{--                            <div style="color: red; font-weight: 800;">INVALID</div>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
