<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $setting = Setting::getSettings();

        return view('settings.index', compact('setting'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $setting = Setting::getSettings();

        $data = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
            'top_link' => 'nullable|string|max:255',
            'site_title' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'whatsapp' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'google_verification_code' => 'nullable|string|max:255',
            'analytics_code' => 'nullable|string',
            'google_map' => 'nullable|string',
            'bank_account_info' => 'nullable|string',
            'bank_transfer_enabled' => 'nullable|boolean',
            'cash_on_delivery_card_enabled' => 'nullable|boolean',
            'cash_on_delivery_cash_enabled' => 'nullable|boolean',
            'online_payment_enabled' => 'nullable|boolean',
            'free_shipping_limit' => 'nullable|string|max:255',
            'shipping_cost' => 'nullable|string|max:255',
            'discount_threshold' => 'nullable|string|max:255',
            'discount_type' => 'nullable|boolean',
            'discount_amount' => 'nullable|string|max:255',
            'top_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order_email' => 'nullable|email|max:255',
            'credit_card_selection' => 'nullable|boolean',
            'cash_on_delivery_shipping_cost' => 'nullable|string|max:255',
            'top_text' => 'nullable|string|max:255',
            'top_text_color' => 'nullable|string|max:20',
            'top_background_color' => 'nullable|string|max:20',
        ]);

        // Logo yükleme
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Favicon yükleme
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        // Top image yükleme
        if ($request->hasFile('top_image')) {
            if ($setting->top_image) {
                Storage::disk('public')->delete($setting->top_image);
            }
            $data['top_image'] = $request->file('top_image')->store('settings', 'public');
        }

        // Boolean değerler
        $data['bank_transfer_enabled'] = $request->has('bank_transfer_enabled');
        $data['cash_on_delivery_card_enabled'] = $request->has('cash_on_delivery_card_enabled');
        $data['cash_on_delivery_cash_enabled'] = $request->has('cash_on_delivery_cash_enabled');
        $data['online_payment_enabled'] = $request->has('online_payment_enabled');
        $data['credit_card_selection'] = $request->has('credit_card_selection');
        $data['discount_type'] = $request->has('discount_type');

        $setting->update($data);

        return redirect()->route('settings.index')
            ->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
