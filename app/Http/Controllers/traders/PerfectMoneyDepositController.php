<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\OnlinePaymentMethod;
use App\Services\AllFunctionService;

class PerfectMoneyDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('perfect_money_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    public function perfectMoneyDeposit()
    {
        $bank_accounts = BankAccount::where('user_id',auth()->user()->id)->select('bank_ac_number')->get();
        $perfectMoney = OnlinePaymentMethod::where('name', 'IT Corner')->select('info')->first();
        if ($perfectMoney) {
            $info = json_decode($perfectMoney->info);
        } else {
            $info = null;
        }
        return view('traders.deposit.perfect-money-deposit', [
            'perfectMoney' => $perfectMoney, 
            'info' => $info, 
            'pm_deposit_status' => 0,
            'bank_accounts' => $bank_accounts
        ]);
    }
}
