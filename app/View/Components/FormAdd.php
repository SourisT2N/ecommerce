<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormAdd extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title;
    public $name;

    public function __construct($title,$name)
    {
        //
        $this->title = $title;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-add');
    }
}
