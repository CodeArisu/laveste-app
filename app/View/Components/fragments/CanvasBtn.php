<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CanvasBtn extends Component
{
    public $offCanvasId;
    public $buttonName;

    public function __construct($offCanvasId, $buttonName)
    {
        $this->offCanvasId = $offCanvasId;
        $this->buttonName = $buttonName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.canvas-btn');
    }
}
