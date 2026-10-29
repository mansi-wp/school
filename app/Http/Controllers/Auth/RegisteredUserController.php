<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    /**
     * Public self-registration is strictly disabled in this Coaching ERP.
     * All accounts are provisioned via Super Admin back-office.
     */
    public function create()
    {
        return redirect()->route('login')->with('error', 'Public self-registration is disabled.');
    }

    public function store(Request $request)
    {
        abort(403, 'Public self-registration is disabled.');
    }
}
