<?php

namespace App\Livewire\Forms;

use App\Models\Color;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateColorForm extends Form
{
    public Color $color;
    public string $name = '';
    public string $code = '';

    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('colors', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($this->color->id)
            ],
            'code' => [
                'required',
                Rule::unique('colors', 'code')
                    ->whereNull('deleted_at')
                    ->ignore($this->color->id)
            ],
        ];
    }

    public function setColor(Color $color)
    {
        $this->color = $color;
        $this->name = $color->name;
        $this->code = $color->code;
    }
    public function resetColor()
    {
        $this->name = $this->color->name;
        $this->code = $this->color->code;
    }
    public function isDirty()
    {
        return !($this->color->name == $this->name && $this->color->code == $this->code);
    }
}
