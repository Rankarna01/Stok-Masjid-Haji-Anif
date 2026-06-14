<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KoordinatorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $koordinators = User::where('role', 'koordinator')->latest()->get();
            return response()->json($koordinators);
        }
        
        return view('admin.koordinator.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nama_mesjid' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $koordinator = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'koordinator',
            'nama_mesjid' => $request->nama_mesjid,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Koordinator berhasil ditambahkan',
            'data' => $koordinator
        ]);
    }

    public function update(Request $request, string $id)
    {
        $koordinator = User::where('role', 'koordinator')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'nama_mesjid' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nama_mesjid' => $request->nama_mesjid,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $koordinator->update($data);

        return response()->json([
            'message' => 'Data Koordinator berhasil diperbarui',
            'data' => $koordinator
        ]);
    }

  
    public function destroy(string $id)
    {
        $koordinator = User::where('role', 'koordinator')->findOrFail($id);
        $koordinator->delete();

        return response()->json(['message' => 'Koordinator berhasil dihapus']);
    }
}
