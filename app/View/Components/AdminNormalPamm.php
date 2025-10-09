<?php

namespace App\View\Components;

use App\Models\SoftwareSetting;
use Illuminate\View\Component;

class AdminNormalPamm extends Component
{
    public $activeNormalPamm = false;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $settings = SoftwareSetting::first();
        // if ($settings->pamm_type === 'normal') {
        //     $this->activeNormalPamm = true;
        // }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin-normal-pamm');
    }
}
