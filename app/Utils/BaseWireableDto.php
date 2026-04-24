<?php

namespace App\Utils;

use App\Traits\WireableDto;
use Livewire\Wireable;

readonly class BaseWireableDto implements Wireable
{
    use WireableDto;
}