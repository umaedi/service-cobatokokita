<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnitResource;
use App\Services\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    public function index(Request $request)
    {
        $units =$this->unitService->getUnits();
        return UnitResource::collection($units);
    }
}
