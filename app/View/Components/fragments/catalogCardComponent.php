<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class catalogCardComponent extends Component
{   
    public $catalog;
    public $url;

    public $catalogId;

    public function __construct($catalog, $url = null)
    {
        $this->catalog = $catalog;
        $this->url = $url;
    }

    public function render(): View|Closure|string
    {
        return view('components.fragments.catalog-card-component');
    }
}
