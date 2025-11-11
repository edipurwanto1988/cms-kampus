<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(50);
        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request, ImageService $imageService)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image'],
        ]);

        $partner = Partner::create([
            'name' => $data['name'],
            'is_active' => $data['is_active'] ?? true,
        ]);

        if ($request->hasFile('logo')) {
            $stored = $imageService->storeWithThumbnail($request->file('logo'));
            $partner->update(['logo_media_id' => $stored['media']->id]);
        }

        return redirect()->route('partners.index')->with('success', 'Partner created');
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner, ImageService $imageService)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image'],
        ]);

        $partner->update([
            'name' => $data['name'],
            'is_active' => $data['is_active'] ?? true,
        ]);

        if ($request->hasFile('logo')) {
            $stored = $imageService->storeWithThumbnail($request->file('logo'));
            $partner->update(['logo_media_id' => $stored['media']->id]);
        }

        return redirect()->route('partners.index')->with('success', 'Partner updated');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('partners.index')->with('success', 'Partner deleted');
    }
}


