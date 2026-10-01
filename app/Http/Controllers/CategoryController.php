<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list()
    {
        $categories = Category::orderBy('name')->paginate(15);
        return view('category.list', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ]);
        Category::create($data);
        return redirect()->route('category.list')->with('success', __('messages.category_created'));
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
        ]);
        $category->update($data);
        return redirect()->route('category.list')->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.list')->with('success', __('messages.category_deleted'));
    }

    public function seedDefaults(Request $request)
    {
        $defaults = [
            'Initiation (مبتدئ جدًا)',
            'Motricité / Fondamentaux (أساسيات الحركة)',
            'Pré-débutant (قبل المبتدئ)',
            'Intermédiaire 1 : maîtrise correcte des techniques',
            'Intermédiaire 2 : autonomie en entraînement',
            'Pré-avancé (ما قبل المتقدم)',
            'Avancé 1 : technique bonne, compétitions locales',
            'Avancé 2 : compétitions régionales',
            'Pré-élite (ما قبل النخبة)',
            'Élite national (نخبة وطنية)',
            'Élite international (نخبة دولية)',
            'Haut niveau (مستوى عالي جدًا)',
            'Semi-pro (شبه محترف)',
            'Pro (محترف كامل)',
            'Pro master (محترف ذو خبرة)',
            'Technique débutant / متوسط / متقدم',
            'Tactique débutant / متوسط / متقدم',
            'Physique : faible / moyen / عالي',
            'Santé 1 : remise en forme (استرجاع اللياقة)',
            'Santé 2 : maintien (حفاظ على اللياقة)',
            'Santé 3 : amélioration (تحسين الأداء)',
        ];

        $created = 0; $skipped = 0;
        foreach ($defaults as $name) {
            $exists = Category::where('name', $name)->exists();
            if ($exists) { $skipped++; continue; }
            Category::create(['name' => $name]);
            $created++;
        }

        return redirect()->route('category.list')->with('success', __('messages.categories_added_count', ['created' => $created, 'skipped' => $skipped]));
    }
}

