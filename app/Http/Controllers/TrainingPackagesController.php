<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingPackage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TrainingPackagesController extends Controller
{
    private function checkTrainingPackageAccess($packageId)
    {
        $user = auth()->user();
        $package = TrainingPackage::findOrFail($packageId);

        if ($user->hasRole('admin')) {
            return $package;
        }

        if ($user->hasRole('cityManager')) {
            $exists = DB::table('gyms_training_packages')
                ->join('gyms', 'gyms.id', '=', 'gyms_training_packages.gym_id')
                ->where('gyms_training_packages.training_package_id', $packageId)
                ->where('gyms.city_id', $user->city_id)
                ->exists();
            if (!$exists) abort(403);
            return $package;
        }

        if ($user->hasRole('gymManager') || $user->hasRole('coach')) {
            if ($user->gym_id) {
                $exists = DB::table('gyms_training_packages')
                    ->where('gyms_training_packages.training_package_id', $packageId)
                    ->where('gyms_training_packages.gym_id', $user->gym_id)
                    ->exists();
                if (!$exists) abort(403);
                return $package;
            }
        }

        abort(403);
    }

    #=======================================================================================#
    #			                             index                                         	#
    #=======================================================================================#
    public function index()
    {
        $user = auth()->user();
        $query = TrainingPackage::orderByDesc('id');

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->whereExists(function ($q) use ($user) {
                $q->select(DB::raw(1))
                  ->from('gyms_training_packages')
                  ->join('gyms', 'gyms.id', '=', 'gyms_training_packages.gym_id')
                  ->whereColumn('gyms_training_packages.training_package_id', 'training_packages.id')
                  ->where('gyms.city_id', $user->city_id);
            });
        } elseif ($user->hasRole('gymManager') || $user->hasRole('coach')) {
            if ($user->gym_id) {
                $query->whereExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))
                      ->from('gyms_training_packages')
                      ->whereColumn('gyms_training_packages.training_package_id', 'training_packages.id')
                      ->where('gyms_training_packages.gym_id', $user->gym_id);
                });
            }
        }

        $packages = $query->paginate(20);
        if ($packages->count() <= 0) {
            return view('empty');
        }
        return view('trainingPackeges.listPackeges', ['packages' => $packages]);
    }
    #=======================================================================================#
    #			                             create                                        	#
    #=======================================================================================#
    public function create()
    {
        $packages = TrainingPackage::all();


        return view('trainingPackeges.creatPackege', [
            'packages' => $packages,

        ]);
    }
    #=======================================================================================#
    #			                             store                                         	#
    #=======================================================================================#
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'price' => ['required', 'numeric', 'min:10', 'max:90'],
            'sessions_number' => ['required', 'numeric', 'min:1', 'max:40'],
        ]);

        $requestData = request()->all();
        $package = TrainingPackage::create($requestData);

        $id = $package->id;


        $data = array('gym_id' => $request->gym_id, "training_package_id" => $id);
        DB::table('gyms_training_packages')->insert($data);



        return redirect()->route('trainingPackeges.listPackeges')->with('success', __('messages.package_created'));
    }
    #=======================================================================================#
    #			                             show                                         	#
    #=======================================================================================#
    public function show($id)
    {
        $package = $this->checkTrainingPackageAccess($id);
        return view('trainingPackeges.show_training_package', ['package' => $package]);
    }
    #=======================================================================================#
    #			                             edit                                         	#
    #=======================================================================================#
    public function edit($id)
    {
        $package = $this->checkTrainingPackageAccess($id);
        $packages = TrainingPackage::all();

        return view('trainingPackeges.editPackege', ['package' => $package, 'packages' => $packages]);
    }
    #=======================================================================================#
    #			                             update                                         #
    #=======================================================================================#
    public function update(Request $request, $id)
    {
        $package = $this->checkTrainingPackageAccess($id);
        $request->validate([
            'name' => ['required'],
            'price' => ['required', 'numeric', 'min:10', 'max:90'],
            'sessions_number' => ['required', 'numeric', 'min:1', 'max:40'],
        ]);

        $package->update($request->all());
        return redirect()->route('trainingPackeges.listPackeges')->with('success', __('messages.package_updated'));
    }
    #=======================================================================================#
    #			                             delete                                        	#
    #=======================================================================================#
    public function delete($id)
    {
        $package = $this->checkTrainingPackageAccess($id);
        $package->delete();
        return response()->json(['success' => __('messages.package_deleted')]);
    }
}
