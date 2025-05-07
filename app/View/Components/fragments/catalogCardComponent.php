<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class catalogCardComponent extends Component
{   
    public $image;
    public $title;

    public $url;

    public function __construct($image, $title, $url = null)
    {
        $this->url = $url;
        $this->image = $image;
        $this->title = $title;
    }

    public function render(): View|Closure|string
    {
        return view('components.fragments.catalog-card-component');
    }
}
