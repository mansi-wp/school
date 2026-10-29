<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('approver')->latest('expense_date')->paginate(25);
        return view('admin.expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:Salary,Printing & Stationery,Utilities,Rent & Maintenance,Hardware & Software,Marketing & Events,Refreshments,Misc',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'paid_to' => 'required|string|max:150',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card,cheque',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $count = Expense::count() + 1;
        $validated['expense_number'] = 'EXP/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);
        $validated['approved_by'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense voucher recorded.');
    }
}
