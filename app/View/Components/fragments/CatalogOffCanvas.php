<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CatalogOffCanvas extends Component
{   
    public function __construct()
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.catalog-off-canvas');
    }
}
