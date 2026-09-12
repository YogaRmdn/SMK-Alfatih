<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRegistrationStatusRequest;
use App\Models\PPDBRegistration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = PPDBRegistration::query()
            ->with('program')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.registrations.index', compact('registrations'));
    }

    public function show(PPDBRegistration $registration)
    {
        $registration->load('program');

        return view('admin.registrations.show', compact('registration'));
    }

    public function update(UpdateRegistrationStatusRequest $request, PPDBRegistration $registration)
    {
        try {
            $registration->update($request->validated());

            return back()->with('success', "Status pendaftaran {$registration->registration_number} berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memperbarui status pendaftaran. Silakan coba lagi.');
        }
    }

    public function destroyAll()
    {
        try {
            $count = PPDBRegistration::count();

            if ($count === 0) {
                return back()->with('warning', 'Tidak ada data pendaftaran yang dapat dihapus.');
            }

            PPDBRegistration::query()->delete();

            return redirect()->route('admin.registrations.index')
                ->with('success', "{$count} data pendaftaran berhasil dihapus semua.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data pendaftaran. Silakan coba lagi.');
        }
    }

    public function destroy(PPDBRegistration $registration)
    {
        try {
            $registration->delete();

            return redirect()->route('admin.registrations.index')
                ->with('success', 'Data pendaftaran berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data pendaftaran. Silakan coba lagi.');
        }
    }
}
