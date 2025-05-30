<?php

namespace App\Http\Controllers\User;

use App\Defaults\Regular;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\ManageAccount;
use App\Models\ManageAccountDuration;
use App\Models\Transfer;
use App\Models\User;
use App\Notifications\InvestmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Transfers extends Controller
{
    use Regular;
    //landing page
    public function landingPage()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $dataView =[
            'siteName' => $web->name,
            'pageName' => 'Transfer Funds',
            'user'     =>  $user,
            'web'       =>$web,
            'durations'=>ManageAccountDuration::get(),
            'transfers'  =>Transfer::where('sender',$user->id)->orWhere('recipient',$user->id)->get()
        ];

        return view('user.transfers',$dataView);
    }

    public function newTransfer(Request  $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'amount'=>['required','numeric'],
            'username'=>['required','string','exists:users,username'],
            'password'=>['required','current_password:web']
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $receiver = User::where('username',$input['username'])->first();

        if ($input['amount'] >$user->profit){
            return back()->with('error','insufficient balance');
        }

        if($user->canLoan!=1){
            return back()->with('error','Transfer is disabled. Please contact support.');
        }
        if ($user->username ==$input['username']){
            return back()->with('error','You cannot make transfer to yourself');
        }

        $receiver->profit=$receiver->profit+$input['amount'];
        $user->profit=$user->profit-$input['amount'];

        $transfer = Transfer::create([
            'sender'=>$user->id,
            'recipient'=>$receiver->id,
            'recipientHolder'=>'User → User',
            'reference'=>$this->generateId('transfers','reference',15),
            'status'=>1
        ]);
        if (!empty($transfer)){
            $user->save();
            $receiver->save();
            $usermessage = "Your account has been debited of $".$input['amount']." for a transfer with reference ".$transfer->reference."
                and credited to ".$input['username'].".
            ";

            $receivermessage = "Your account has been credited with $".$input['amount']." from ".$user->username."
            ";

            $user->notify(new InvestmentMail($user,$usermessage,'New Account Transfer'));
            $receiver->notify(new InvestmentMail($receiver,$receivermessage,'New Account Credit'));
            $admin = User::where('is_admin',1)->first();
            if (!empty($admin)){
                $adminMessage = "
                    A new transfer
                    has been made by the investor <b>".$user->name."</b> with reference <b>".$transfer->reference."</b> to the investor
                    ".$receiver->name." of the sum of $".$input['amount']."
                ";
                //SendInvestmentNotification::dispatch($admin,$adminMessage,'New Investment Initiation');
                $admin->notify(new InvestmentMail($admin,$adminMessage,'New  Account Transfer'));
            }
            return back()->with('success','Transfer successful');
        }
        return back()->with('error','Something went wrong');
    }
    public function bonusToProfit(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'gt:0']
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Invalid input. Please enter a valid amount.');
        }

        $amount = $request->input('amount');

        if ($amount > $user->bonus) {
            return back()->with('error', 'Insufficient bonus balance.');
        }

        // Update balances
        $user->bonus -= $amount;
        $user->profit += $amount;

        // Record transfer
        $transfer = Transfer::create([
            'sender' => $user->id,
            'recipient' => $user->id,
            'recipientHolder' => 'Bonus → Profit',
            'reference' => $this->generateId('transfers', 'reference', 15),
            'status' => 1,
            'amount' => $amount,
        ]);

        $user->save();

        // Notify user
        $message = "You moved $$amount from your <b>Bonus</b> to your <b>Profit</b> balance. (Ref: {$transfer->reference})";
        $user->notify(new InvestmentMail($user, $message, 'Internal Transfer'));

        return back()->with('success', 'Bonus successfully transferred to profit.');
    }

    public function refBalToProfit(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'gt:0']
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Invalid input. Please enter a valid amount.');
        }

        $amount = $request->input('amount');

        if ($amount > $user->refBal) {
            return back()->with('error', 'Insufficient referral balance.');
        }

        // Update balances
        $user->refBal -= $amount;
        $user->profit += $amount;

        // Record transfer
        $transfer = Transfer::create([
            'sender' => $user->id,
            'recipient' => $user->id,
            'recipientHolder' => 'RefBal → Profit',
            'reference' => $this->generateId('transfers', 'reference', 15),
            'status' => 1,
            'amount' => $amount,
        ]);

        $user->save();

        // Notify user
        $message = "You moved $$amount from your <b>Referral Balance</b> to your <b>Profit</b> balance. (Ref: {$transfer->reference})";
        $user->notify(new InvestmentMail($user, $message, 'Internal Transfer'));

        return back()->with('success', 'Referral balance successfully transferred to profit.');
    }

}
