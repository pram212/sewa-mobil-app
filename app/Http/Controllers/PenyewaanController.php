<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $penyewaan = $user->mobil()
                ->with('merek')
                ->paginate(10)
                ->withQueryString();

        $requests = request()->all();

        return Inertia::render('Penyewaan/IndexPenyewaan', compact('penyewaan', 'requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request('tgl_mulai') || !request('merek_id')) {
            $mobils = [];
        } else {
            $mobils = Mobil::whereDoesntHave('user', function (Builder $query) {
                $query->whereDate('tgl_selesai', '<', request('tgl_mulai'));
            })
            ->where('merek_id', request('merek_id'))
            ->with('merek')
            ->get();
        }

        $mereks = Merek::select('id as value' , 'nama as label')->get();
        
        $requests = request()->all();

        return Inertia::render('Penyewaan/FormPenyewaan', compact('mereks', 'mobils', 'requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $user->mobil()->attach($request->mobil_id, ['tgl_mulai' => $request->tgl_mulai, 'tgl_selesai' => $request->tgl_mulai, 'status' => true ]);

        Mobil::find($request->mobil_id)->update(['tersedia' => false]);

        $notification = [
            'type' => 'success',
            'message' => 'Selamat pesanan anda berhasil ditambahkan!'
        ];

        return Redirect::route('penyewaan.index')->with($notification);

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
