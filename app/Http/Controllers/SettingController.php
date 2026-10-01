<?php

namespace App\Http\Controllers;

use App\Models\CustomFolderFile;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $fontFamily = Setting::get('font_family', 'Source Sans Pro');
        $fontSize = Setting::get('font_size', '16');

        return view('settings.index', compact('fontFamily', 'fontSize'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'font_family' => 'required|string',
            'font_size' => 'required|integer|min:10|max:30',
        ]);

        Setting::set('font_family', $request->font_family);
        Setting::set('font_size', $request->font_size);

        return redirect()->back()->with('success', __('messages.settings_updated'));
    }

    public function customFolders(Request $request)
    {
        $folder = $request->string('folder')->toString();

        $folders = CustomFolderFile::query()
            ->select(['folder_name', 'folder_slug'])
            ->selectRaw('COUNT(*) as files_count')
            ->groupBy(['folder_name', 'folder_slug'])
            ->orderBy('folder_name')
            ->get();

        $filesQuery = CustomFolderFile::query()->orderByDesc('created_at');
        if ($folder !== '') {
            $filesQuery->where('folder_slug', $folder);
        }
        $files = $filesQuery->paginate(20)->withQueryString();

        return view('settings.custom-folders', [
            'folders' => $folders,
            'files' => $files,
            'folder' => $folder,
        ]);
    }

    public function storeCustomFolders(Request $request)
    {
        $validated = $request->validate([
            'folder_name' => ['required', 'string', 'max:120'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'max:10240', 'mimes:pdf,png,jpg,jpeg'],
        ]);

        $folderName = trim($validated['folder_name']);
        $folderSlug = (string) Str::slug($folderName);
        if ($folderSlug === '') {
            $folderSlug = 'dossier-' . Str::random(8);
        }

        foreach ($request->file('files', []) as $file) {
            $ext = strtolower((string) $file->getClientOriginalExtension());
            $name = time() . '_' . Str::random(12) . ($ext !== '' ? ('.' . $ext) : '');
            $path = $file->storeAs("private/custom-folders/{$folderSlug}", $name);

            CustomFolderFile::create([
                'folder_name' => $folderName,
                'folder_slug' => $folderSlug,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        return redirect()->route('settings.custom-folders.index', ['folder' => $folderSlug])
            ->with('success', __('messages.custom_folders_saved'));
    }

    public function downloadCustomFolderFile(CustomFolderFile $file)
    {
        if (!Storage::exists($file->file_path)) {
            abort(404);
        }

        return response()->download(Storage::path($file->file_path), $file->original_name);
    }
}
