<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CateringPackageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $query = CateringPackage::query()->latest();
        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $packages = $query->paginate(10);

        return view('admin.catering-packages.index', compact('packages', 'search'));
    }

    public function create()
    {
        return view('admin.catering-packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price_per_pax' => 'required|integer|min:0',
            'min_pax'       => 'nullable|integer|min:1',
            'is_active'     => 'nullable|boolean',
            'image'         => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('catering', 'public');

        CateringPackage::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'price_per_pax' => $request->price_per_pax,
            'min_pax'       => $request->min_pax,
            'is_active'     => $request->boolean('is_active'),
            'image'         => $imagePath,
        ]);

        return redirect()->route('admin.catering-packages.index')->with('success', 'Paket catering berhasil ditambahkan.');
    }

    public function edit(CateringPackage $catering_package)
    {
        return view('admin.catering-packages.edit', ['package' => $catering_package]);
    }

    public function update(Request $request, CateringPackage $catering_package)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price_per_pax' => 'required|integer|min:0',
            'min_pax'       => 'nullable|integer|min:1',
            'is_active'     => 'nullable|boolean',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($catering_package->image) Storage::disk('public')->delete($catering_package->image);
            $catering_package->image = $request->file('image')->store('catering', 'public');
        }

        $catering_package->update([
            'name'          => $request->name,
            'description'   => $request->description,
            'price_per_pax' => $request->price_per_pax,
            'min_pax'       => $request->min_pax,
            'is_active'     => $request->boolean('is_active'),
            'image'         => $catering_package->image,
        ]);

        return redirect()->route('admin.catering-packages.index')->with('success', 'Paket catering berhasil diperbarui.');
    }

    public function destroy(CateringPackage $catering_package)
    {
        if ($catering_package->image) Storage::disk('public')->delete($catering_package->image);
        $catering_package->delete();
        return back()->with('success', 'Paket catering berhasil dihapus.');
    }
}
