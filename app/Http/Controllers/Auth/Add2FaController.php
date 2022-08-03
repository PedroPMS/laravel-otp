<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteActivationRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FALaravel\Google2FA;

class Add2FaController extends Controller
{
    protected string $redirectTo = RouteServiceProvider::HOME;

    protected function add2Fa(Request $request)
    {
        // Initialise the 2FA class
        $google2fa = app(Google2FA::class);
        $secretKey = $google2fa->generateSecretKey();

        // Save the registration data to the user session for just the next request
        $request->session()->flash('google2fa_secret', $secretKey);

        $user = auth()->user();
        // Generate the QR URL.
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );

        // Pass the QR barcode image to our view
        return view('google2fa.register', ['qrCodeUrl' => $qrCodeUrl, 'secret' => $secretKey]);
    }

    public function confirm2FaActivation(CompleteActivationRequest $request)
    {
        $google2fa = app(Google2FA::class);
        $key = session('google2fa_secret');
        $code = $request->input('code');
        $isValid = $google2fa->verifyKey($key, $code);

        if (!$isValid) {
            throw ValidationException::withMessages(['code' => 'Invalid code']);
        }

        $user = auth()->user();
        $user->google2fa_secret = session('google2fa_secret');
        $user->save();
        return redirect('home');
    }
}
