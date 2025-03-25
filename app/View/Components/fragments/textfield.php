<?php

namespace App\View\Components\fragments;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class textfield extends Component
{
    public $type, $placeholder;
    public string $name, $field;

    public function __construct($type, $name, $field, $placeholder)
    {
        $this->type = $type;
        $this->name = $name;
        $this->field = $field;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fragments.textfield');
    }
}
