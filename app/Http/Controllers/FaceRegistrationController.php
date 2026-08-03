<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FaceRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class FaceRegistrationController extends Controller
{
    // Base URL Python service
    private $pythonHost = 'http://localhost:5000';
    public function index()
    {
        // Ambil semua user dengan role siswa yang BELUM registrasi wajah untuk dropdown select
        $dropdownUsers = User::where('role', 'siswa')
            ->doesntHave('faceRegistration')
            ->orderBy('name')
            ->get();
        
        // Ambil data user dengan role siswa dengan paginasi 10 untuk tabel status registrasi
        $users = User::where('role', 'siswa')->with('faceRegistration')->latest()->paginate(10);

        return view('admin.face-registration', compact('dropdownUsers', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'dataset_path' => 'required|string',
            'total_images' => 'required|integer|min:1',
        ]);

        $registration = FaceRegistration::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'dataset_path' => $request->dataset_path,
                'total_images' => $request->total_images,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Registrasi wajah berhasil disimpan untuk user: ' . $registration->user->name,
            'data' => $registration
        ]);
    }

    public function destroy($id)
    {
        $registration = FaceRegistration::findOrFail($id);
        $userName = $registration->user->name;
        $userId = $registration->user_id;
        
        // 1. Hapus folder dataset di Python service (foto-foto wajah)
        $datasetPath = base_path('python_services/dataset/' . $userId);
        if (File::isDirectory($datasetPath)) {
            File::deleteDirectory($datasetPath);
        }

        // 2. Reset status capture di Python service
        try {
            Http::timeout(5)->post($this->pythonHost . '/reset/' . $userId);
        } catch (\Exception $e) {
            // Python service mungkin tidak jalan, lanjutkan saja
        }

        // 3. Hapus record dari database
        $registration->delete();

        // 4. Re-train model agar user yang dihapus tidak lagi dikenali saat scan
        try {
            Http::timeout(30)->post($this->pythonHost . '/train');
        } catch (\Exception $e) {
            // Jika retrain gagal, beri warning tapi tetap lanjut
            return redirect()->route('face-registration.index')
                ->with('success', 'Data registrasi wajah untuk ' . $userName . ' berhasil dihapus.')
                ->with('warning', 'Model wajah gagal di-retrain otomatis. Silakan retrain manual.');
        }

        return redirect()->route('face-registration.index')
            ->with('success', 'Data registrasi wajah untuk ' . $userName . ' berhasil dihapus dan model telah di-retrain.');
    }
}