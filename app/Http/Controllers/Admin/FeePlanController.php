<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePlan;
use Illuminate\Http\Request;

class FeePlanController extends Controller
{
    public function index()
    {
        $feePlans = FeePlan::with('student')->latest()->paginate(25);
        return view('admin.fees.structure', compact('feePlans'));
    }
}
