<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index()
    {
        return Car::orderBy('id')->get();
    }

    public function show($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return $car;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'price_per_day' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
        ]);

        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        return Car::create($data);
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1900|max:2100',
            'price_per_day' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
        ]);

        unset($data['image']);

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($car->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $car->update($data);

        return $car->fresh();
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        $this->deleteStoredImage($car->image_url);
        $car->delete();

        return response()->json(['message' => 'Car deleted']);
    }

    private function storeImage($file): string
    {
        $path = $file->store('cars', 'public');

        return 'storage/' . $path;
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (!$imageUrl) {
            return;
        }

        // Only delete files we uploaded to storage/app/public/cars
        if (!str_starts_with($imageUrl, 'storage/cars/')) {
            return;
        }

        $relative = str_replace('storage/', '', $imageUrl);
        Storage::disk('public')->delete($relative);
    }
}
