<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('alamat', 'like', '%' . $request->search . '%')
                ->orWhere('kelas', 'like', '%' . $request->search . '%');

            });
        }

        // FILTER ROLE
        if ($request->role) {

            $query->where('role', $request->role);

        }

        // PAGINATION
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.master-data', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
            'kelas' => 'required',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto_users', 'public');
        } else {
            $foto = null;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'alamat' => $request->alamat,
            'kelas' => $request->kelas,
            'foto' => $foto,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function userPage(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('kelas', 'like', '%' . $request->search . '%')
                ->orWhere('alamat', 'like', '%' . $request->search . '%');

            });

        }

        $users = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.user', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.edituser', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama (optional tapi bagus)
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_users', 'public');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'alamat' => $request->alamat,
            'kelas' => $request->kelas,
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function showQr($id)
    {
        $user = User::findOrFail($id);

        $qr = base64_encode(
            QrCode::format('svg')
                ->size(300)
                ->generate($user->id . '-' . $user->name)
        );

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'kelas' => $user->kelas,
            'alamat' => $user->alamat,
            'foto' => $user->foto
                ? asset('storage/' . $user->foto)
                : null,

            'qr' => $qr
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}