<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvestmentNotification;
use App\Models\CardApplication;
use App\Models\GeneralSetting;
use App\Models\LoanApplication;
use App\Models\MembershipApplication;
use App\Models\Package;
use App\Models\User;
use App\Notifications\InvestmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Notification;

class Investors extends Controller
{
    public function landingPage()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $dataView =[
            'siteName' => $web->name,
            'pageName' => 'Investors',
            'user'     =>  $user,
            'web'=>$web,
            'investors'=>User::where('status',1)->get()
        ];

        return view('admin.investors',$dataView);
    }

    public function inactive()
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $dataView =[
            'siteName' => $web->name,
            'pageName' => 'Investors',
            'user'     =>  $user,
            'web'=>$web,
            'investors'=>User::where('status','!=',1)->get()
        ];

        return view('admin.investors',$dataView);
    }

    public function details($id)
    {
        $web = GeneralSetting::find(1);
        $user = Auth::user();

        $dataView =[
            'siteName' => $web->name,
            'pageName' => 'Investor Details',
            'user'     =>  $user,
            'web'=>$web,
            'investor'=>User::where('id',$id)->first(),
            'promos'=>Notification::where('user',$id)->get(),
            'cards'=>CardApplication::where('user',$id)->paginate(15,'*','cards'),
            'loans'=>LoanApplication::where('user',$id)->paginate(15,'*','loans'),
            'memberships'=>MembershipApplication::where('user',$id)->paginate('15','*','memberships'),
        ];

        return view('admin.investor_detail',$dataView);
    }

    public function activateTwoWay($id)
    {
        $data =[
            'twoWay'=>1
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function deactivateTwoWay($id)
    {
        $data =[
            'twoWay'=>2
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function activateLoan($id)
    {
        $data =[
            'canLoan'=>1
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function deactivateLoan($id)
    {
        $data =[
            'canLoan'=>2
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function verifyEmail($id)
    {
        $data =[
            'emailVerified'=>1
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function unVerifyEmail($id)
    {
        $data =[
            'emailVerified'=>2
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function activateUser($id)
    {
        $data =[
            'status'=>1
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function deactivateUser($id)
    {
        $data =[
            'status'=>2
        ];
        User::where('id',$id)->update($data);

        return redirect('admin/investors') ->with('success','Successful');
    }

    public function addFund(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'balance'=>$investor->balance+$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);
        if ($update){
            //send mail to investor
            $userMessage = "
                Your Account balance has been credited with $<b>" . $input['amount'] . " .
            ";
            //SendInvestmentNotification::dispatch($investor, $userMessage, 'Balance Topup');
            $investor->notify(new InvestmentMail($investor, $userMessage, 'Balance Topup'));
        }
        return back()->with('success','Balance added');
    }
    public function addProfit(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'profit'=>$investor->profit+$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);
        if ($update){
            //send mail to investor
            $userMessage = "
                Your Profit balance has been credited with $<b>" . $input['amount'] . " .
            ";
            //SendInvestmentNotification::dispatch($investor, $userMessage, 'Profit Topup');
            $investor->notify(new InvestmentMail($investor, $userMessage, 'Profit Topup'));
        }
        return back()->with('success','Profit added');
    }
    public function addRef(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'refBal'=>$investor->refBal+$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);
        if ($update){
            //send mail to investor
            $userMessage = "
                Your Referral balance has been credited with $<b>" . $input['amount'] . " .
            ";
            //SendInvestmentNotification::dispatch($investor, $userMessage, 'Referral Topup');
            $investor->notify(new InvestmentMail($investor, $userMessage, 'Referral Topup'));

        }
        return back()->with('success','Referral Balance added');
    }
    public function addWith(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'withdrawals'=>$investor->withdrawals+$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);
        if ($update){
            //send mail to investor
            $userMessage = "
                Your Withdrawal request of $<b>" . $input['amount'] . "</b> has been processed
                and sent to your wallet Address. Your transaction hash is <b>".Str::random(200)."</b>
            ";
            //SendInvestmentNotification::dispatch($investor, $userMessage, 'Withdrawal Approved');
            $investor->notify(new InvestmentMail($investor, $userMessage, 'Withdrawal Approved'));

        }
        return back()->with('success','Withdrawal added');
    }

    public function subFund(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'balance'=>$investor->balance-$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);

        return back()->with('success','Balance subtracted');
    }
    public function subProfit(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'profit'=>$investor->profit-$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);

        return back()->with('success','Profit subtracted');
    }
    public function subRef(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'refBal'=>$investor->refBal-$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);

        return back()->with('success','Referral Balance subtracted');
    }
    public function subWith(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'withdrawals'=>$investor->withdrawals-$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);

        return back()->with('success','Withdrawal subtracted');
    }
    public function addLoan(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'bonus'=>$investor->bonus+$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);
        if ($update){
            //send mail to investor
            $userMessage = "
                You have been credited with $<b>" . $input['amount'] . "</b> as bonus
            ";
            //SendInvestmentNotification::dispatch($investor, $userMessage, 'Withdrawal Approved');
            $investor->notify(new InvestmentMail($investor, $userMessage, 'Credit Notification - Bonus'));

        }

        return back()->with('success','Bonus added');
    }

    public function subLoan(Request $request)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();
        $validator = Validator::make($request->input(),[
            'id'=>['required','numeric'],
            'amount'=>['required','numeric'],
        ]);

        if ($validator->fails()){
            return back()->with('errors',$validator->errors());
        }
        $input = $validator->validated();

        $investor = User::where('id',$input['id'])->first();

        $data = [
            'bonus'=>$investor->bonus-$input['amount']
        ];

        $update = User::where('id',$input['id'])->update($data);

        return back()->with('success','Bonus subtracted');
    }

    public function loginUser($id)
    {
        $web = GeneralSetting::where('id',1)->first();
        $user = Auth::user();

        $investor = User::where('id',$id)->first();

        Auth::logout();

        Auth::login($investor);

        return redirect(route('user.dashboard')) ->with('success','Login Successful');

    }
    public function verifyKYC($id)
    {
        $data =[
            'isVerified'=>1
        ];
        $investor = User::where('id',$id)->first();

        $userMessage = "
                Your KYC submission has been approved, and account fully activated.
            ";
        //SendInvestmentNotification::dispatch($investor, $userMessage, 'Balance Topup');
        $investor->notify(new InvestmentMail($investor, $userMessage, 'KYC Verified'));

        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function unverifyKYC($id)
    {
        $data =[
            'isVerified'=>3
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function activateReinvestment($id)
    {
        $data =[
            'canCompound'=>1
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }
    public function deactivateReinvestment($id)
    {
        $data =[
            'canCompound'=>2
        ];
        User::where('id',$id)->update($data);

        return back()->with('success','Successful');
    }

    public function approveMembership($id)
    {

        $membership = MembershipApplication::where('id',$id)->first();

        $user = User::where('id',$membership->user)->first();

        $user->update([
            'name' => $membership->name,
            'country' => $membership->country,
        ]);
        $membership->status=1;
        $membership->save();

        return back()->with('success','Membership Application Approved.');
    }
    public function rejectMembership($id)
    {
        $membership = MembershipApplication::where('id',$id)->first();

        $membership->status=3;
        $membership->save();

        return back()->with('success','Membership Application cancelled.');
    }
    public function approveLoan($id)
    {

        $loan = LoanApplication::where('id',$id)->first();

        $user = User::where('id',$loan->user)->first();

        $user->update([
            'loan' => bcadd($user->loan,$loan->amount),
        ]);
        $loan->status=1;
        $loan->save();

        return back()->with('success','Loan Application Approved.');
    }
    public function rejectLoan($id)
    {
        $loan = LoanApplication::where('id',$id)->first();

        $loan->status=3;
        $loan->save();

        return back()->with('success','Loan Application cancelled.');
    }
    public function approveCard($id)
    {

        $card = CardApplication::where('id',$id)->first();
        $card->status=1;
        $card->save();

        return back()->with('success','Card Application Approved.');
    }
    public function rejectCard($id)
    {
        $card = CardApplication::where('id',$id)->first();

        $card->status=3;
        $card->save();

        return back()->with('success','Card Application cancelled.');
    }
}
