<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\TrainingPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Stripe;

class StripeController extends Controller
{

    //
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe(Request $request)
    {
        // Rediriger vers la liste des utilisateurs pour sélectionner qui payer
        $query = User::role('user')
            ->withoutBanned()
            ->with(['gym', 'payments']);
        
        // Recherche par nom ou email
        if ($request->filled('q')) {
            $q = trim($request->get('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }
        
        $users = $query->latest('id')->paginate(20)->appends($request->query());
        
        return view('PaymentPackage.select_user', compact('users'));
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        $packageInfo = explode('|', $request->package_id);
        $price = $packageInfo[1];
        $training_package_id = $packageInfo[0];
        $city = explode('|', $request->gym_id);
        $gym_id = $city[0];
        $cityname = $city[1];
        $user = User::find($request->user_id);
        $package = TrainingPackage::find($training_package_id);
        $user->total_sessions += $package->sessions_number;
        $user_id = $request->user_id;
        $userEmail = $user->email;
        $userName = $user->name;
        $packageName = $package->name;
        // Simplification: pas de traitement Stripe réel, juste simulation
        // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // Stripe\Charge::create([
        //     "amount" => $price * 100,
        //     "currency" => "tnd",
        //     "source" => $request->stripeToken,
        //     "description" => "Successfully Bought",
        // ]);

        Session::flash('success', __('messages.payment_success_tnd'));
        Revenue::create([
            'price' => $price,
            'payment_id' => $training_package_id . '_' . $user_id . '_' . time(),
            'visa_number' => 'Paiement simplifié TND',
            'payment_method' => 'simple_payment',
            'user_id' => $user_id,
            'training_package_id' => $training_package_id,
            'cash_register_id' => $request->cash_register_id,
        ]);

        $user->update(['total_sessions' => $user->total_sessions]);

        return redirect()->route('PaymentPackage.purchase_history');
    }

    public function index(Request $request)
    {
        $query = \App\Models\UserPayment::with(['user', 'user.gym', 'user.city'])
            ->orderByDesc('created_at');

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                         $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
                    }
                    break;
            }
        }

        $payments = $query->paginate(20)->withQueryString();
        
        return view('PaymentPackage.purchase_history', [
            'payments' => $payments,
        ]);
    }
}
