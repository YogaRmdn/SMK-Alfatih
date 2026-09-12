<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePPDBRegistrationRequest;
use App\Models\PPDBRegistration;
use App\Models\Program;
use Illuminate\Http\Request;

class PPDBController extends Controller
{
    public function index()
    {
        return view('public.ppdb.index')
            ->with('title', 'PPDB Online');
    }

    public function siswa()
    {
        $programs = Program::active()->orderBy('order')->get();

        return view('public.ppdb.siswa', compact('programs'))
            ->with('title', 'Daftar PPDB');
    }

    public function store(StorePPDBRegistrationRequest $request)
    {
        try {
            $registration = PPDBRegistration::create($request->validated());

            return redirect()
                ->route('ppdb.status', ['registration_number' => $registration->registration_number])
                ->with('success', 'Pendaftaran Anda berhasil dikirim. Simpan nomor pendaftaran berikut untuk memantau status.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data pendaftaran. Silakan coba lagi.');
        }
    }

    public function status(Request $request)
    {
        $registrationNumber = $request->string('registration_number')->trim()->toString();

        $registration = $registrationNumber !== ''
            ? PPDBRegistration::query()->with('program')->where('registration_number', $registrationNumber)->first()
            : null;

        return view('public.ppdb.status', [
            'registration' => $registration,
            'registrationNumber' => $registrationNumber,
        ])->with('title', 'Cek Status Pendaftaran');
    }
}
