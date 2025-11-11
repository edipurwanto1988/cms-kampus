<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Lecturer;
use App\Models\LecturerTranslation;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::with(['translations', 'photo'])
            ->withCount('translations')
            ->latest()
            ->paginate(20);
        return view('lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        $languages = Language::getAllOrdered();
        return view('lecturers.create', compact('languages'));
    }

    public function store(Request $request, ImageService $imageService)
    {
        $data = $request->validate([
            'NUPTK' => ['nullable', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'dept_id' => ['nullable', 'integer'],
            'position_title' => ['nullable', 'string', 'max:255'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'scholar_url' => ['nullable', 'url', 'max:255'],
            'researchgate_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.full_name' => ['required', 'string', 'max:255'],
            'translations.*.bio_html' => ['nullable', 'string'],
            'translations.*.research_interests' => ['nullable', 'string'],
            'translations.*.achievement' => ['nullable', 'string'],
            'photo' => ['nullable', 'image'],
        ]);

        DB::transaction(function () use ($request, $imageService, $data) {
            $lecturer = Lecturer::create([
                'NUPTK' => $data['NUPTK'] ?? null,
                'nip' => $data['nip'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'dept_id' => $data['dept_id'] ?? null,
                'position_title' => $data['position_title'] ?? null,
                'expertise' => $data['expertise'] ?? null,
                'scholar_url' => $data['scholar_url'] ?? null,
                'researchgate_url' => $data['researchgate_url'] ?? null,
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'featured' => $data['featured'] ?? false,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if ($request->hasFile('photo')) {
                $stored = $imageService->storeWithThumbnail($request->file('photo'));
                $lecturer->update(['photo_media_id' => $stored['media']->id]);
            }

            // Create all translations
            foreach ($data['translations'] as $locale => $translationData) {
                LecturerTranslation::create([
                    'lecturer_id' => $lecturer->id,
                    'locale' => $locale,
                    'full_name' => $translationData['full_name'],
                    'bio_html' => $translationData['bio_html'] ?? null,
                    'research_interests' => $translationData['research_interests'] ?? null,
                    'achievement' => $translationData['achievement'] ?? null,
                ]);
            }
        });

        return redirect()->route('lecturers.index')->with('success', 'Lecturer created with all translations');
    }

    public function show(Lecturer $lecturer)
    {
        $lecturer->load('translations', 'photo');
        return view('lecturers.show', compact('lecturer'));
    }

    public function edit(Lecturer $lecturer)
    {
        $lecturer->load(['translations', 'photo']);
        $languages = Language::getAllOrdered();
        return view('lecturers.edit', compact('lecturer', 'languages'));
    }

    public function update(Request $request, Lecturer $lecturer, ImageService $imageService)
    {
        $data = $request->validate([
            'NUPTK' => ['nullable', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'dept_id' => ['nullable', 'integer'],
            'position_title' => ['nullable', 'string', 'max:255'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'scholar_url' => ['nullable', 'url', 'max:255'],
            'researchgate_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.*.full_name' => ['required', 'string', 'max:255'],
            'translations.*.bio_html' => ['nullable', 'string'],
            'translations.*.research_interests' => ['nullable', 'string'],
            'translations.*.achievement' => ['nullable', 'string'],
            'photo' => ['nullable', 'image'],
        ]);

        DB::transaction(function () use ($request, $imageService, $lecturer, $data) {
            $lecturer->update([
                'NUPTK' => $data['NUPTK'] ?? null,
                'nip' => $data['nip'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'dept_id' => $data['dept_id'] ?? null,
                'position_title' => $data['position_title'] ?? null,
                'expertise' => $data['expertise'] ?? null,
                'scholar_url' => $data['scholar_url'] ?? null,
                'researchgate_url' => $data['researchgate_url'] ?? null,
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'featured' => $data['featured'] ?? false,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if ($request->hasFile('photo')) {
                $stored = $imageService->storeWithThumbnail($request->file('photo'));
                $lecturer->update(['photo_media_id' => $stored['media']->id]);
            }

            // Update all translations
            foreach ($data['translations'] as $locale => $translationData) {
                $translation = $lecturer->translations()->where('locale', $locale)->first();
                
                $updateData = [
                    'full_name' => $translationData['full_name'],
                    'bio_html' => $translationData['bio_html'] ?? null,
                    'research_interests' => $translationData['research_interests'] ?? null,
                    'achievement' => $translationData['achievement'] ?? null,
                ];
                
                if ($translation) {
                    $translation->update($updateData);
                } else {
                    LecturerTranslation::create(array_merge($updateData, [
                        'lecturer_id' => $lecturer->id,
                        'locale' => $locale,
                    ]));
                }
            }
        });

        return redirect()->route('lecturers.index')->with('success', 'Lecturer updated with all translations');
    }

    public function destroy(Lecturer $lecturer)
    {
        $lecturer->delete();
        return redirect()->route('lecturers.index')->with('success', 'Lecturer deleted');
    }

    public function updateTranslations(Request $request, Lecturer $lecturer)
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.full_name' => ['required', 'string'],
            'translations.*.bio_html' => ['nullable', 'string'],
            'translations.*.research_interests' => ['nullable', 'string'],
            'translations.*.achievement' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($lecturer, $data) {
            foreach ($data['translations'] as $locale => $translationData) {
                LecturerTranslation::updateOrCreate(
                    ['lecturer_id' => $lecturer->id, 'locale' => $locale],
                    [
                        'full_name' => $translationData['full_name'],
                        'bio_html' => $translationData['bio_html'] ?? null,
                        'research_interests' => $translationData['research_interests'] ?? null,
                        'achievement' => $translationData['achievement'] ?? null,
                    ]
                );
            }
        });

        return response()->json(['status' => 'ok']);
    }
}