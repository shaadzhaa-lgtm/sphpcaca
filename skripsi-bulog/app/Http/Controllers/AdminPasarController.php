<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPasarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pasar::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasar', 'like', "%{$search}%")
                  ->orWhere('kabupaten', 'like', "%{$search}%")
                  ->orWhere('kantor_cabang', 'like', "%{$search}%");
            });
        }

        if ($kancab = $request->input('kantor_cabang')) {
            $query->where('kantor_cabang', $kancab);
        }

        $pasars      = $query->orderBy('nama_pasar')->paginate(10)->withQueryString();
        $kancabList  = Pasar::kancabOptions();
        $totalTarget = Pasar::sum('target');
        $totalPasar  = Pasar::count();
        $totalKancab = Pasar::distinct('kantor_cabang')->count('kantor_cabang');

        return view('pasars.index', compact(
            'pasars', 'kancabList', 'totalTarget', 'totalPasar', 'totalKancab'
        ));
    }

    public function create()
    {
        $kancabList = Pasar::kancabOptions();
        return view('pasars.create', compact('kancabList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kantor_cabang' => ['required', 'string', Rule::in(Pasar::kancabOptions())],
            'kabupaten'     => ['required', 'string', 'max:255'],
            'nama_pasar'    => ['required', 'string', 'max:255'],
            'latitude'      => ['required', 'numeric', 'between:-90,90'],
            'longitude'     => ['required', 'numeric', 'between:-180,180'],
            'target'        => ['required', 'integer', 'min:0'],
        ]);

        Pasar::create($validated);

        return redirect()->route('pasars.index')
            ->with('success', 'Pasar berhasil ditambahkan.');
    }

    public function show(Pasar $pasar)
    {
        return view('pasars.show', compact('pasar'));
    }

    public function edit(Pasar $pasar)
    {
        $kancabList = Pasar::kancabOptions();
        return view('pasars.edit', compact('pasar', 'kancabList'));
    }

    public function update(Request $request, Pasar $pasar)
    {
        $validated = $request->validate([
            'kantor_cabang' => ['required', 'string', Rule::in(Pasar::kancabOptions())],
            'kabupaten'     => ['required', 'string', 'max:255'],
            'nama_pasar'    => ['required', 'string', 'max:255'],
            'latitude'      => ['required', 'numeric', 'between:-90,90'],
            'longitude'     => ['required', 'numeric', 'between:-180,180'],
            'target'        => ['required', 'integer', 'min:0'],
        ]);

        $pasar->update($validated);

        return redirect()->route('pasars.index')
            ->with('success', 'Data pasar berhasil diperbarui.');
    }

    public function destroy(Pasar $pasar)
    {
        $pasar->delete();

        return redirect()->route('pasars.index')
            ->with('success', 'Pasar berhasil dihapus.');
    }
}