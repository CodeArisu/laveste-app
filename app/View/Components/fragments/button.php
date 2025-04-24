<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class button extends Component
{
    public $uri;
    public $label, $btnType;

    public function __construct($uri, $label, $btnType)
    {
        $this->uri = $uri;
        $this->label = $label;
        $this->btnType = $btnType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.button');
    }
}
