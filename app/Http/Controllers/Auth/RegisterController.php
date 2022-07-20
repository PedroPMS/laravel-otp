<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FALaravel\Google2FA;

class RegisterController extends Controller
{
    use RegistersUsers {
        register as registration;
    }

    protected string $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'google2fa_secret' => $data['google2fa_secret'],
        ]);
    }

    public function register(RegisterRequest $request)
    {
        // Initialise the 2FA class
        $google2fa = app(Google2FA::class);

        $request->merge(['google2fa_secret' => $google2fa->generateSecretKey()]);

        // Save the registration data to the user session for just the next request
        $request->session()->flash('registration_data', $request->all());

        // Generate the QR URL.
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $request->input('email'),
            $request->input('google2fa_secret')
        );

        // Pass the QR barcode image to our view
        return view('google2fa.register', ['qrCodeUrl' => $qrCodeUrl, 'secret' => $request->input('google2fa_secret')]);
    }

    public function completeRegistration(Request $request)
    {
        // add the session data back to the request input
        $request->merge(session('registration_data'));

        // Call the default laravel authentication
        return $this->registration($request);
    }
}
