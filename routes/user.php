<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\User\Dashboard;
use App\Http\Controllers\User\Deposits;
use App\Http\Controllers\User\Investments;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\ManagedAccounts;
use App\Http\Controllers\User\MembershipController;
use App\Http\Controllers\User\Referrals;
use App\Http\Controllers\User\Settings;
use App\Http\Controllers\User\Transfers;
use App\Http\Controllers\User\Withdrawals;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user routes for your web.
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
| To access this endpoint, the prefix 'account' has to be added.
| You can change this in the RouteServiceProvider file
|
*/


Route::get('dashboard',[Dashboard::class,'landingPage'])->name('user.dashboard');
Route::get('dashboard/promo/{id}/enroll',[Dashboard::class,'enrollInPromo'])->name('user.enrollPromo');

/*================ DEPOSIT ROUTE ====================*/
Route::get('deposits',[Deposits::class,'landingPage'])->name('deposit.index');
Route::get('new_deposit',[Deposits::class,'newDeposit'])->name('new_deposit');
Route::post('process_deposit',[Deposits::class,'processDeposit'])->name('deposit.new');
Route::get('deposits/{id}/details',[Deposits::class,'depositDetails'])->name('deposit_detail');
Route::get('deposits/{id}/cancel',[Deposits::class,'cancel'])->name('deposit.cancel');
/*================ INVESTMENT ROUTE ====================*/
Route::get('investments',[Investments::class,'landingPage'])->name('investment.index');
Route::get('new_investment',[Investments::class,'newInvestment'])->name('new_investment');
Route::post('process_investment',[Investments::class,'processInvestment'])->name('investment.new');
Route::get('investments/{id}/details',[Investments::class,'investmentDetails'])->name('invest_detail');
Route::get('investments/{id}/cancel',[Investments::class,'cancel'])->name('invest.cancel');
/*================ WITHDRAWAL ROUTE ====================*/
Route::get('withdrawals',[Withdrawals::class,'landingPage'])->name('withdrawal.index');
Route::get('new_withdrawals',[Withdrawals::class,'newWithdrawal'])->name('new_withdrawal');
Route::post('process_withdrawal',[Withdrawals::class,'processWithdrawal'])->name('withdraw.new');
Route::get('withdrawals/{id}/cancel',[Withdrawals::class,'cancel'])->name('withdraw.cancel');
/*================ SETTINGS ROUTE ====================*/
Route::get('settings',[Settings::class,'landingPage'])->name('setting.index');
Route::post('update-settings',[Settings::class,'processSetting'])->name('settings.update');
Route::post('update-password',[Settings::class,'processPassword'])->name('password.update');
Route::post('update-photo',[Settings::class,'processPhoto'])->name('photo.update');
Route::get('kyc',[Settings::class,'kyc'])->name('setting.kyc');
Route::post('update-kyc',[Settings::class,'submitKyc'])->name('kyc.update');
/*================ REFERRAL ROUTE ====================*/
Route::get('referral',[Referrals::class,'landingPage'])->name('referral.index');
/*================ CHART ROUTE ====================*/

Route::get('/earnings/chart/{userId}', function ($userId) {
    $earnings = \App\Models\InvestmentReturn::where('user', $userId)
        ->select('created_at', 'amount')
        ->get();

    $data = $earnings->map(function ($earning) {
        return [
            strtotime($earning->created_at) * 1000, // Convert to milliseconds for ApexCharts
            $earning->amount,
        ];
    });
    return response()->json($data);
})->name('earnings.chart');

Route::get('/withdrawal/chart/{userId}', function ($userId) {
    $earnings = Withdrawal::where('user', $userId)->where('status','!=',3)
        ->select('created_at', 'amount')
        ->get();

    $data = $earnings->map(function ($earning) {
        return [
            strtotime($earning->created_at) * 1000, // Convert to milliseconds for ApexCharts
            $earning->amount,
        ];
    });
    return response()->json($data);
})->name('withdrawals.chart');

Route::get('/investments/chart/{userId}', function ($userId) {
    $earnings = \App\Models\Investment::where('user', $userId)->where('status','!=',3)->where('status','!=',2)
        ->select('created_at', 'amount')
        ->get();

    $data = $earnings->map(function ($earning) {
        return [
            strtotime($earning->created_at) * 1000, // Convert to milliseconds for ApexCharts
            $earning->amount,
        ];
    });
    return response()->json($data);
})->name('investments.chart');
/*================ MANAGED ACCOUNT ROUTE ====================*/
Route::get('subtrade',[ManagedAccounts::class,'landingPage'])->name('subtrade.index');
Route::post('subtrade/new',[ManagedAccounts::class,'subscribeNew'])->name('subtrade.new');
Route::get('subtrade/{id}/delete',[ManagedAccounts::class,'delete'])->name('subtrade.delete');
/*================ TRANSFERS ROUTE ====================*/
Route::get('transfer',[Transfers::class,'landingPage'])->name('transfer.index');
Route::post('transfer/new',[Transfers::class,'newTransfer'])->name('transfer.new');

// Internal Transfers
Route::post('transfer/bonus-to-profit', [Transfers::class, 'bonusToProfit'])->name('transfer.bonus-to-profit');
Route::post('transfer/refbal-to-profit', [Transfers::class, 'refBalToProfit'])->name('transfer.refbal-to-profit');

Route::get('logout',[Login::class,'logout']);


/*================ CARD ROUTE ====================*/
Route::get('cards',[CardController::class,'landingPage'])->name('cards.index');
Route::post('cards/apply',[CardController::class,'processApplication'])->name('card.apply');
/*================ LOAN ROUTE ====================*/
Route::get('loans',[LoanController::class,'landingPage'])->name('loans.index');
Route::post('loans/new',[LoanController::class,'processApplication'])->name('loans.new');
/*================ MEMBERSHIP ROUTE ====================*/
Route::get('membership',[MembershipController::class,'landingPage'])->name('membership.index');
Route::post('membership/new',[MembershipController::class,'processApplication'])->name('membership.new');
