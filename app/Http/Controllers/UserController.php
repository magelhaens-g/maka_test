<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request){
        $data = User::select('*');

        if ($request->has('search')) {
            $data->where('name', 'like', '%' . $request->get('search') . '%')
                ->orWhere('address', 'like', '%' . $request->get('search') . '%');
        }

        return response()->json([
            "status" => "success",
            "data" => $data->get()
        ], 200);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50|unique:users',
            'address' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);


        $data = $request->only(['name', 'address']);
        
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('images', 'local');
                $data['image'] = $path;
            }
            User::create($data);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "Message" => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json([
            "status" => "success",
            "message" => "Successfully saved the data"
        ], 200);
    }

    public function detail($id) {
        try {
            $check = User::find($id);

            if (!$check) {
                throw new Exception("User not found", 404);
            }
        } catch (Exception $e) {
            return response()->json([
                "success" => "error",
                "message" => $e->getMessage()
            ], $e->getCode());
        }
        
        return response()->json([
            "status" => "success",
            "data" => $check
        ], 200);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:50|unique:users,name,' . $id,
            'address' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg'
        ]);


        $data = $request->only(['name']);
        
        try {
            if ($request->has('address')) {
                $data['address'] = $request->get('address');
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('images', 'local');
                $data['image'] = $path;
            }

            $check = User::find($id);

            if (!$check) {
                throw new Exception("User not found", 404);
            }

            $check->update($data);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "Message" => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json([
            "status" => "success",
            "message" => "Successfully updated the data"
        ], 200);
    }

    public function destroy($id) {
        try {
            $check = User::find($id);

            if (!$check) {
                throw new Exception("User not found", 404);
            }
            $check->delete();
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "Message" => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json([
            "status" => "success",
            "message" => "Successfully deleted the data"
        ], 200);
    }
}
