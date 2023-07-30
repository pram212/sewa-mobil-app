<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use App\Models\Mobil;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $pengembalian = $user->mobil()
                ->where('status', false)
                ->with('merek')
                ->paginate(10)
                ->withQueryString();

        $requests = request()->all();

        return Inertia::render('Pengembalian/IndexPengembalian', compact('pengembalian', 'requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request('plat_nomor')) {
            $penyewaan = [];
        } else {
            $penyewaan = Penyewaan::whereHas('mobil', function(Builder $query) {
                        $query->where('plat_nomor', request('plat_nomor'));
                    })
                    ->where('status', 1)
                    ->with(['mobil', 'mobil.merek'])
                    ->get();
        }

        $mereks = Merek::select('id as value' , 'nama as label')->get();
        
        $requests = request()->all();

        return Inertia::render('Pengembalian/FormPengembalian', compact('mereks', 'penyewaan', 'requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $user->mobil()->syncWithoutDetaching([$request->mobil_id => ['tgl_mulai' => $request->tgl_mulai, 'tgl_selesai' => $request->tgl_mulai, 'status' => false ]]);

        Mobil::find($request->mobil_id)->update(['tersedia'=> true]);

        $notification = [
            'type' => 'success',
            'message' => 'Sewa anda berhasil dikembalikan!'
        ];

        return Redirect::route('pengembalian.index')->with($notification);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getMobil()
    {
        $mobils = Mobil::whereDoesntHave('user', function (Builder $query) {
            $query->whereDate('tgl_selesai', '<', request('tgl_mulai'));
        })
        ->where('merek_id', request('merek_id'))
        ->with('merek')
        ->get();

        return $mobils;

    }

}
