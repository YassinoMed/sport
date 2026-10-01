<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\ExpenseSubcategory;
use Illuminate\Http\Request;

class ExpenseSubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = ExpenseSubcategory::with('category')->orderBy('name')->paginate(20);
        return view('expenses.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get(['id','name']);
        return view('expenses.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        ExpenseSubcategory::create($data);
        return redirect()->route('expense-subcategories.index')->with('success', __('messages.expense_subcategory_created'));
    }

    public function edit(ExpenseSubcategory $subcategory)
    {
        $categories = ExpenseCategory::orderBy('name')->get(['id','name']);
        return view('expenses.subcategories.edit', compact('subcategory','categories'));
    }

    public function update(Request $request, ExpenseSubcategory $subcategory)
    {
        $data = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        $subcategory->update($data);
        return redirect()->route('expense-subcategories.index')->with('success', __('messages.expense_subcategory_updated'));
    }

    public function destroy(ExpenseSubcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('expense-subcategories.index')->with('success', __('messages.expense_subcategory_deleted'));
    }
}

