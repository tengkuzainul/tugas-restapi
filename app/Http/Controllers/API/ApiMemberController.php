<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::all();

        return response()->json([
            'success' => true,
            'message' => 'Members Data',
            'data' => MemberResource::collection($members)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validate([
                'nama_member' => 'required|string|min:3',
                'alamat' => 'required|string|min:5',
                'gender' => 'required|in:L,P',
                'no_hp' => 'required|string|min:10',
                'email' => 'required|email|unique:members,email',
            ]);

            // Membuat data member baru
            $member = Member::create($validatedData);

            // Mengembalikan respons JSON
            return response()->json([
                'success' => true,
                'message' => 'Data Member Berhasil Ditambahkan!',
                'data' => new MemberResource($member)
            ], 201);
        } catch (ValidationException $e) {
            // Mengembalikan respons jika validasi gagal
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Mengembalikan respons jika terjadi error lain
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Member retrieved successfully',
            'data' => new MemberResource($member)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $member = Member::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama_member' => 'required|string|min:3',
                'alamat' => 'required|string|min:5',
                'gender' => 'required|in:L,P',
                'no_hp' => 'required|string|min:10',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah data',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Mengecek apakah ada perubahan data sebelum menyimpan
            if ($member->isDirty()) {
                $member->save();
            }

            return response()->json([
                'success' => true,
                'message' => $member->isDirty() ? 'Data Member Berhasil Diubah' : 'Tidak ada perubahan data',
                'data' => new MemberResource($member)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }
}
