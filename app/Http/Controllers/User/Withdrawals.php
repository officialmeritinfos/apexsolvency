<?php

namespace App\Http\Controllers\User;

use App\Defaults\Regular;
use App\Http\Controllers\Controller;
use App\Jobs\SendInvestmentNotification;
use App\Models\Coin;
use App\Models\GeneralSetting;
use App\Models\Package;
use App\Models\User;
use App\Models\Withdrawal;
use App\Notifications\InvestmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Withdrawals extends Controller
{
    use Regular;
    public function landingPage()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $dataView = [
            'web'=>$web,
            'user'=>$user,
            'withdrawals'=>Withdrawal::where('user',$user->id)->paginate(15),
            'pageName'=>'Withdrawal Lists',
            'siteName'=>$web->name
        ];

        return view('user.withdrawals',$dataView);
    }
    public function newWithdrawal()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $dataView = [
            'web'=>$web,
            'user'=>$user,
            'pageName'=>'New Withdrawal',
            'siteName'=>$web->name,
            'coins'=>Coin::where('status',1)->get(),
        ];

        return view('user.new_withdrawal',$dataView);
    }

    public function processWithdrawal(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'amount'=>['required','numeric'],
            'asset'=>['required','alpha_dash'],
            'account'=>['required','numeric'],
            'wallet'=>['required','string']
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        if ($user->loan >0){
            return back()->with('error','You cannot make any withdrawal until you have cleared your loan of
            $'.number_format($user->loan,2).'. Please contact support for more details regarding this.');
        }

        //check if the user has the amount in balance
        switch ($input['account']){
            case 1:
                $balance = $user->profit;
                $newBalance = [
                    'profit'=>$balance- $input['amount']
                ];
                $source = "Profit";
                break;
            case 2:
                $balance = $user->refBal;
                $newBalance = [
                    'refBal'=>$balance- $input['amount']
                ];
                $source = "Referral";
                break;
            case 3:
                $balance = $user->bonus;
                $newBalance = [
                    'bonus'=>$balance- $input['amount']
                ];
                $source = "Bonus";
                break;
            default:
                $balance = $user->balance;
                $newBalance = [
                    'balance'=>$balance- $input['amount']
                ];
                $source = "Account";
                break;
        }
        if ( $user->isVerified!=1){
            return back()->with('error','You must verify your KYC first before requesting for withdrawal.');
        }

        if ($balance < $input['amount']){
            return back()->with('error','Insufficient balance in selected account.');
        }

        $ref = $this->generateId('withdrawals','reference',10);
        $data=[
            'user'=>$user->id,'reference'=>$ref,'amount'=>$input['amount'],'asset'=>$input['asset'],
            'details'=>$input['wallet'],'source'=>$source
        ];

        $withdrawal = Withdrawal::create($data);
        if (!empty($withdrawal)){
            User::where('id',$user->id)->update($newBalance);

            //check if admin exists
            $admin = User::where('is_admin',1)->first();
            $userMessage = "
                Your new withdrawal request of $<b>".$input['amount']." </b>
                has been received and will be processed soonest. Your Withdrawal reference Id is <b>".$ref."</b>
            ";
            //send mail to user
            //SendInvestmentNotification::dispatch($user,$userMessage,'New Withdrawal');
            $user->notify(new InvestmentMail($user,$userMessage,'New Withdrawal'));
            //send mail to Admin
            if (!empty($admin)){
                $adminMessage = "
                    A new withdrawal request of $<b>".$input['amount']."</b>
                    has been submitted by the investor <b>".$user->name."</b> with reference <b>".$ref."</b>
                ";
                //SendInvestmentNotification::dispatch($admin,$adminMessage,'New Withdrawal Request');
                $admin->notify(new InvestmentMail($admin,$adminMessage,'New Withdrawal Request'));
            }
            return redirect()->route('withdrawal.index')
                ->with('success','Withdrawal processing.');
        }
        return back()->with('error','Something went wrong');
    }
    public function cancel($id)
    {

    }
}
