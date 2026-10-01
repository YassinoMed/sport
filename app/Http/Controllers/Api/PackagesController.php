<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingPackage;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function index()
    {
        $packages = TrainingPackage::all();
        return response()->json([
            'success' => true,
            'data' => $packages,
            'message' => __('messages.packages_retrieved')
        ]);
    }
    public function showPackage($packageId)
    {
        $package = TrainingPackage::find($packageId);
        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => __('messages.no_package_found')
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $package->name,
                'price' => $package->price
            ],
            'message' => __('messages.package_retrieved')
        ]);
    }
}
