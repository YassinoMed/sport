<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('subcategories')->orderBy('name')->paginate(15);
        return view('expenses.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('expenses.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:expense_categories,name',
            'description' => 'nullable|string|max:255',
        ]);
        ExpenseCategory::create($data);
        return redirect()->route('expense-categories.index')->with('success', __('messages.expense_category_created'));
    }

    public function edit(ExpenseCategory $category)
    {
        return view('expenses.categories.edit', compact('category'));
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:expense_categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
        ]);
        $category->update($data);
        return redirect()->route('expense-categories.index')->with('success', __('messages.expense_category_updated'));
    }

    public function destroy(ExpenseCategory $category)
    {
        $category->delete();
        return redirect()->route('expense-categories.index')->with('success', __('messages.expense_category_deleted'));
    }
}

