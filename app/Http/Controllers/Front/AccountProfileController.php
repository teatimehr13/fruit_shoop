<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountProfileController extends Controller
{
    public function update(Request $request)
{
    $data = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required', 'string','email'],
        'city' => ['nullable','string','max:50'],
        'district' => ['nullable','string','max:50'],
        'address_detail' => ['nullable','string','max:255'],
        'zip_code' => ['nullable', 'digits:3'],
        'phone' => ['nullable','regex:/^\d{8,15}$/'],
    ]);

    $user = $request->user();
    $user->update($data);

    return response()->json([
        'user' => $user->fresh(), // 最新資料回傳
    ]);
}
}
