<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
     public function ProviderRedirect($provider){
        if($provider === 'google')
        {
            return Socialite::driver('google')->redirect();
        }else{
            return 'Todavia no esta funcional';
        }
        
    }
    public function ProviderCallback($provider){
        if($provider === 'google')
        {
             try{
                $googleUser = Socialite::driver('google')->stateless()->user();
                $user = \App\Models\User::updateOrCreate([
                    'email' => $googleUser->email,
                    ], [
                        'name' => $googleUser->name,
                        'google_id' => $googleUser->id,
                        'profile_photo_path' => $googleUser->avatar,
                    ]);
                    if($user->roles()->count() == 0){
                        $user->assignRole('CLIENTE');
                    }
                    Auth::login($user,true);
                    request()->session()->regenerate();
                    return redirect('/dashboard');
            }catch (\Exception $e) {
                        return redirect('/login')->with('error', 'Error: ' . $e->getMessage());
            }
        }else{
            return 'Todavia no esta funcional';
        }
       
    }
}
