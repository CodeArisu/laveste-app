<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DynamicOffCanvas extends Component
{
    public $offCanvasId;
    public function __construct($offCanvasId)
    {
        $this->offCanvasId = $offCanvasId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.dynamic-off-canvas');
    }
}
