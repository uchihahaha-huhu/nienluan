<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddressBook;
use Illuminate\Support\Facades\Validator;

class AddressBookController extends Controller
{
    /**
     * Lấy danh sách địa chỉ của user
     */
    public function getAddressBookForUser($userId)
    {
        $addresses = AddressBook::where('user_id', $userId)->get();

        return response()->json([
            'success' => true,
            'data' => $addresses,
        ]);
    }

    /**
     * Tạo địa chỉ mới cho user
     */
    public function createAddressBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|exists:users,id',
            'name'      => 'required|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
            'address'   => 'required|string',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Nếu is_default = true, set các địa chỉ khác của user is_default = false
        if ($request->has('is_default') && $request->is_default) {
            AddressBook::where('user_id', $request->user_id)->update(['is_default' => false]);
        }

        $address = AddressBook::create($request->only([
            'user_id',
            'name',
            'phone',
            'email',
            'address',
            'is_default'
        ]));

        return response()->json([
            'success' => true,
            'data' => $address,
        ]);
    }

    /**
     * Cập nhật địa chỉ
     */
    public function updateAddressBook(Request $request, $id)
    {
        $address = AddressBook::find($id);
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'sometimes|required|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
            'address'   => 'sometimes|required|string',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->has('is_default') && $request->is_default) {
            // Đặt các địa chỉ khác của user is_default = false
            AddressBook::where('user_id', $address->user_id)->update(['is_default' => false]);
        }

        $address->update($request->only([
            'name',
            'phone',
            'email',
            'address',
            'is_default'
        ]));

        return response()->json([
            'success' => true,
            'data' => $address,
        ]);
    }

    /**
     * Xóa địa chỉ
     */
    public function deleteAddressBook($id)
    {
        $address = AddressBook::find($id);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }


    public function getDefaultAddressBookForUser($userId)
    {
        $address = AddressBook::where('user_id', $userId)->where('is_default', true)->first();

        return response()->json([
            'success' => true,
            'data' => $address,
        ]);
    }

    public function setDefaultAddress(Request $request)
{
    $validator = Validator::make($request->all(), [
        'address_id' => 'required|exists:address_book,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Lấy địa chỉ theo id
    $address = AddressBook::find($request->address_id);
    if (!$address) {
        return response()->json([
            'success' => false,
            'message' => 'Address not found',
        ], 404);
    }

    // Reset tất cả địa chỉ user đó về false
    AddressBook::where('user_id', $address->user_id)->update(['is_default' => false]);

    // Set đúng địa chỉ này thành mặc định
    $address->is_default = true;
    $address->save();

    return response()->json([
        'success' => true,
        'message' => 'Set địa chỉ mặc định thành công',
        'data' => $address,
    ]);
}
}
