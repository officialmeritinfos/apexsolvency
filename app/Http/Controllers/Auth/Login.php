<?php

namespace App\Http\Controllers\Auth;

use App\Defaults\Regular;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerification;
use App\Jobs\SendTwoFactorMail;
use App\Models\GeneralSetting;
use App\Models\TwoFactor;
use App\Models\User;
use App\Notifications\EmailVerifyMail;
use App\Notifications\LoginMail;
use App\Notifications\TwoFactorMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
    use Regular;
    public function landingPage()
    {
        $web = GeneralSetting::find(1);
        $dataView = [
            'web'   =>  $web,
            'siteName'  =>  $web->name,
            'pageName'  =>  'Login Page'
        ];
        return view('auth.login',$dataView);
    }

    public function authenticate(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $validator = Validator::make($request->input(),[
            'email'=>['required','string','exists:users,username'],
            'password'=>['required']
        ],[],['email'=>'Username']);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();
        //check if the login checked out
        if(Auth::attempt(['username' => $request->email, 'password' => $request->password])){
            //is user active
            $user = Auth::user();
            if($user->status !=1){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Account is inactive');
            }

            //check if email is verified
            if ($user->emailVerified !=1){

                $user->notify(new EmailVerifyMail($user));
                return back()->with('success','Verification email sent. Please check both your spambox for the mail.');
            }
            //check if user has two factor authentication on
            switch ($user->twoWay){
                case 1:
                    //SendTwoFactorMail::dispatch($user);
                    $user->notify(new TwoFactorMail($user));
                    $dataUser = [
                        'twoWayPassed'=>2
                    ];
                    $message = "Please authenticate this login request from your email";
                    $url = route('login');
                    break;
                default:
                    //$user->notify(new LoginMail($user,$request));
                    $dataUser = [
                        'twoWayPassed'=>1
                    ];
                    $message = "Login successful.";
                    if ($user->is_admin ==1){
                        $url = route('admin.admin.dashboard');
                    }else {
                        $url = route('user.dashboard');
                    }
                    break;
            }
            User::where('id',$user->id)->update($dataUser);//update user
            return redirect($url)->with('info',$message);
        }
        return back()->with('error','Username and Password combination is wrong');
    }
    public function processTwoFactor($email,$hash,Request $request)
    {
        //verify login
        $user = User::where('username',$email)->firstOrFail();
        $exists = TwoFactor::where('user',$user->id)->where('token',$hash)->firstOrFail();

        if ($exists->expiration < time()){
            return redirect()->route('login')->with('error','Authentication failed due to timeout');
        }
        Auth::loginUsingId($user->id);
        //$user->notify(new LoginMail($user,$request));

        $user->twoWayPassed=1;
        $user->save();

        TwoFactor::where('user',$user->id)->delete();

        if ($user->is_admin ==1){
            $url = route('admin.admin.dashboard');
        }else {
            $url = route('user.dashboard');
        }

        return redirect($url)->with('success','Login successful.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->twoWayPassed=2;
        $user->save();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info','Logout was successful');
    }
}
