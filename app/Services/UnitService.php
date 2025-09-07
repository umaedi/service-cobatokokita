<?php

namespace App\Services;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UnitService 
{
    public function getUnits()
    {
        $units = Unit::all();
        return $units;
    }
}