<?php

namespace App\View\Components;

use App\Models\PammInvestor;
use App\Models\PammUser;
use App\Services\PermissionService;
use Illuminate\View\Component;

class NormalPamm extends Component
{
    public $hasPammAccount;
    public $hasInvestment;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->hasPammAccount = PammUser::where('user_id', auth()->id())->exists();
        $this->hasInvestment = PammInvestor::where('user_id', auth()->id())->exists();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.normal-pamm');
    }
}
