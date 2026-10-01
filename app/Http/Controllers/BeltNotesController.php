<?php

namespace App\Http\Controllers;

use App\Models\BeltNoteCategory;
use App\Models\BeltNoteItem;
use App\Models\BeltNoteSubcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BeltNotesController extends Controller
{
    private function beltOptions(): array
    {
        $opts = [];
        $opts['Blanche'] = __('messages.belt_format_white', ['color' => __('messages.belt_white')]);
        foreach ([
            ['Jaune', 'yellow', 3],
            ['Verte', 'green', 3],
            ['Bleue', 'blue', 3],
            ['Violette', 'purple', 3],
            ['Rouge', 'red', 3],
        ] as [$key, $color, $n]) {
            for ($i = 1; $i <= $n; $i++) {
                $value = "$key $i";
                $label = __('messages.belt_format', ['color' => __("messages.belt_$color"), 'level' => $i]);
                $opts[$value] = $label;
            }
        }
        foreach (range(1, 6) as $i) {
            $value = "Noire $i";
            $label = __('messages.belt_format_black', ['color' => __('messages.belt_black'), 'level' => $i]);
            $opts[$value] = $label;
        }
        return $opts;
    }

    public function index(Request $request)
    {
        $beltOptions = $this->beltOptions();
        $beltName = $request->get('belt_name');

        if ($beltName !== null && $beltName !== '') {
            $request->validate([
                'belt_name' => ['required', 'string', Rule::in(array_keys($beltOptions))],
            ]);
        }

        $categories = collect();
        $eligibleAthletes = collect();
        if ($beltName) {
            $categories = BeltNoteCategory::query()
                ->where('belt_name', $beltName)
                ->with(['notes', 'subcategories.notes', 'subcategories.childrenRecursive'])
                ->orderBy('position')
                ->orderBy('id')
                ->get();

            $users = User::role('user')
                ->withoutBanned()
                ->with(['belts' => function ($q) {
                    $q->orderByDesc('achieved_at')->orderByDesc('id');
                }])
                ->orderBy('name')
                ->get(['id', 'name']);

            $eligibleAthletes = $users
                ->filter(function (User $u) use ($beltName) {
                    return !$u->belts->contains(function ($b) use ($beltName) {
                        return $b->belt_name === $beltName;
                    });
                })
                ->map(function (User $u) {
                    return [
                        'id' => $u->id,
                        'name' => $u->name,
                        'last_belt' => $u->belts->first()?->belt_name,
                    ];
                })
                ->values();
        }

        return view('belt.notes', [
            'beltOptions' => $beltOptions,
            'beltName' => $beltName,
            'categories' => $categories,
            'eligibleAthletes' => $eligibleAthletes,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $beltOptions = $this->beltOptions();

        $data = $request->validate([
            'belt_name' => ['required', 'string', Rule::in(array_keys($beltOptions))],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'numeric', 'min:0'],
        ]);

        BeltNoteCategory::create([
            'belt_name' => $data['belt_name'],
            'name' => $data['name'],
            'position' => $data['position'] ?? 0,
        ]);

        return redirect()->route('belt.notes.index', ['belt_name' => $data['belt_name']])->with('success', __('messages.category_added'));
    }

    public function destroyCategory(BeltNoteCategory $category)
    {
        $beltName = $category->belt_name;
        $category->delete();
        return redirect()->route('belt.notes.index', ['belt_name' => $beltName])->with('success', __('messages.category_deleted'));
    }

    public function storeSubcategory(Request $request, BeltNoteCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'numeric', 'min:0'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('belt_note_subcategories', 'id')->where(function ($q) use ($category) {
                    $q->where('belt_note_category_id', $category->id);
                }),
            ],
        ]);

        BeltNoteSubcategory::create([
            'belt_note_category_id' => $category->id,
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'position' => $data['position'] ?? 0,
        ]);

        return redirect()->route('belt.notes.index', ['belt_name' => $category->belt_name])->with('success', __('messages.subcategory_added'));
    }

    public function destroySubcategory(BeltNoteSubcategory $subcategory)
    {
        $beltName = $subcategory->category?->belt_name;
        $subcategory->delete();
        return redirect()->route('belt.notes.index', ['belt_name' => $beltName])->with('success', __('messages.subcategory_deleted'));
    }

    public function storeCategoryNote(Request $request, BeltNoteCategory $category)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'position' => ['nullable', 'numeric', 'min:0'],
        ]);

        $category->notes()->create([
            'content' => $data['content'],
            'position' => $data['position'] ?? 0,
        ]);

        return redirect()->route('belt.notes.index', ['belt_name' => $category->belt_name])->with('success', __('messages.note_added'));
    }

    public function storeSubcategoryNote(Request $request, BeltNoteSubcategory $subcategory)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'position' => ['nullable', 'numeric', 'min:0'],
        ]);

        $subcategory->notes()->create([
            'content' => $data['content'],
            'position' => $data['position'] ?? 0,
        ]);

        return redirect()->route('belt.notes.index', ['belt_name' => $subcategory->category?->belt_name])->with('success', __('messages.note_added'));
    }

    public function destroyNote(BeltNoteItem $note)
    {
        $noteable = $note->noteable;
        $beltName = null;
        if ($noteable instanceof BeltNoteCategory) {
            $beltName = $noteable->belt_name;
        } elseif ($noteable instanceof BeltNoteSubcategory) {
            $beltName = $noteable->category?->belt_name;
        }

        $note->delete();

        return redirect()->route('belt.notes.index', ['belt_name' => $beltName])->with('success', __('messages.note_deleted'));
    }
}
