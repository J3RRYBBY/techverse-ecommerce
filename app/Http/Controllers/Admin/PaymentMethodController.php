<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function payment()
    {
        $paymentMethods = PaymentMethod::latest()->get();

        return view('admin.payment.payment', compact('paymentMethods'));
    }

    public function createPayment()
    {
        return view('admin.payment.create');
    }

    public function storePayment(Request $request, CloudinaryService $cloudinary)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $imageUrl = null;
        $imagePublicId = null;

        if ($request->hasFile('logo')) {
            $uploaded = $cloudinary->upload($request->file('logo'), 'techverse/payment');

            $imageUrl = $uploaded['url'];
            $imagePublicId = $uploaded['public_id'];
        }

        PaymentMethod::create([
            'name' => $request->name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'logo' => $imageUrl,
            'logo_public_id' => $imagePublicId,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin#payment')->with('createSuccess', 'Payment method created successfully.');
    }

    public function editPayment(int $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('admin.payment.edit', compact('paymentMethod'));
    }

    public function updatePayment(Request $request, int $id, CloudinaryService $cloudinary)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('logo')) {
            /*
            |--------------------------------------------------------------------------
            | Upload new image first
            |--------------------------------------------------------------------------
            */

            $uploaded = $cloudinary->upload($request->file('logo'), 'techverse/payment');

            /*
            |--------------------------------------------------------------------------
            | Delete old Cloudinary image
            |--------------------------------------------------------------------------
            */

            if ($paymentMethod->logo_public_id) {
                $cloudinary->delete($paymentMethod->logo_public_id);
            }

            $data['logo'] = $uploaded['url'];
            $data['logo_public_id'] = $uploaded['public_id'];
        }

        $paymentMethod->update($data);

        return to_route('admin#payment')->with('updateSuccess', 'Payment method updated successfully.');
    }

    public function deletePayment(int $id, CloudinaryService $cloudinary)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete image from Cloudinary
        |--------------------------------------------------------------------------
        */

        if ($paymentMethod->logo_public_id) {
            $cloudinary->delete($paymentMethod->logo_public_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete payment method from database
        |--------------------------------------------------------------------------
        */

        $paymentMethod->delete();

        return to_route('admin#payment')->with('deleteSuccess', 'Payment method deleted successfully.');
    }

    public function toggleStatus(int $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $paymentMethod->is_active = !$paymentMethod->is_active;
        $paymentMethod->save();

        return back()->with('statusSuccess', 'Payment method status updated.');
    }
}
