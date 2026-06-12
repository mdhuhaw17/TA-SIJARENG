<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('users')->latest()->paginate(10);

        return view('admin.kelas', compact('groups'));
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);

        $siswas = User::where('role', 'siswa')->get();

        return view('admin.editgroup', compact('group', 'siswas'));
    }

    public function updateSiswa(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        // sink = update siswa di kelas
        $group->users()->sync($request->siswa ?? []);

        return redirect()->route('group.create')
            ->with('success', 'Siswa berhasil ditambahkan ke kelas');
    }

    public function create()
    {
        $groups = Group::withCount('users')->latest()->paginate(10);

        return view('admin.tambahgroup', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_group' => 'required',
        ]);

        $group = Group::create([
            'nama_group' => $request->nama_group,
        ]);

        // simpan siswa ke group
        if ($request->siswa) {
            $group->users()->attach($request->siswa);
        }

        return redirect()->back()
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function absenManual()
    {
        return view('admin.absenmanual');
    }

    public function detailAbsen($kategori)
    {
        if ($kategori == 'besar') {

            $users = User::whereIn('kelas', ['4', '5', '6'])
                ->where('role', 'siswa')
                ->get();

            $title = 'Kelas Besar';

        } else {

            $users = User::whereIn('kelas', ['1', '2', '3'])
                ->where('role', 'siswa')
                ->get();

            $title = 'Kelas Kecil';
        }

        return view('admin.detailabsen', compact('users', 'title'));
    }
}