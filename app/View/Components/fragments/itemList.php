<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class itemList extends Component
{
    public string $ulClass, $liClass;
    public array $list;

    public function __construct($ulClass, $liClass, array $list = [])
    {
        $this->ulClass = $ulClass;
        $this->liClass = $liClass;
        $this->list = $list;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.item-list');
    }
}
