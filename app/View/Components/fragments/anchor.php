<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class anchor extends Component
{
    public $uri;
    public $label;
    public $aClass;

    public function __construct($uri, $label, $aClass)
    {
        $this->uri = $uri;
        $this->label = $label;
        $this->aClass = $aClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.anchor');
    }
}
