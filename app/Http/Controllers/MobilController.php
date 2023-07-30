<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Http\Requests\StoreMobilRequest;
use App\Http\Requests\UpdateMobilRequest;
use App\Models\Merek;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mobils = Mobil::when(request('merek_id'), fn ($kategori) => $kategori->where('merek_id',  request('merek_id')))
            ->when(request('model'), fn ($kategori) => $kategori->where('model', 'like', '%' . request('model') . '%'))
            ->when(request('tersedia'), fn ($kategori) => $kategori->where('tersedia', '==',  request('tersedia')))
            ->with(['merek'])
            ->paginate(10)
            ->withQueryString();

        $mobils->transform(function ($penyewaan, $key) {
            $ketersediaan = true;

            if ($penyewaan->user->count()  > 0) {
                if ($penyewaan->user->first()->pivot->status) {
                    $ketersediaan = false;
                }
            }

            $penyewaan->ketersediaan = $ketersediaan;
            
            return $penyewaan;

        });
        

        $mereks = Merek::select('id as value' , 'nama as label')->get();

        $requests = request()->all();

        return Inertia::render('Mobil/IndexMobil', compact('mobils', 'requests', 'mereks'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mereks = Merek::select('id as value' , 'nama as label')->get();

        return Inertia::render('Mobil/FormMobil', compact('mereks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMobilRequest $request)
    {
        Mobil::create($request->all());
        
        $notification = [
            'type' => 'success',
            'message' => 'Data baru berhasil ditambahkan!'
        ];

        return Redirect::route('mobil.index')->with($notification);

    }

    /**
     * Display the specified resource.
     */
    public function show(Mobil $mobil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mobil $mobil)
    {

        $mereks = Merek::select('id as value' , 'nama as label')->get();

        return Inertia::render('Mobil/FormMobil', compact('mereks', 'mobil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMobilRequest $request, Mobil $mobil)
    {
        $mobil->update($request->all());
        
        $notification = [
            'type' => 'success',
            'message' => 'Data berhasil diupdate!'
        ];

        return Redirect::route('mobil.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mobil $mobil)
    {
        $jumlahSewa = $mobil->penyewaan()->where('status', true)->count();

        if ($jumlahSewa > 0) {
            # code...
            $notification = [
                'type' => 'error',
                'message' => 'Gagal! Mobil ' . $mobil->plat_nomor . ' sedang dalam penyewaan'
            ];
    
            return Redirect::back()->with($notification);
            
        }

        $mobil->delete();

        $notification = [
            'type' => 'success',
            'message' => 'Data berhasil dihapus!'
        ];

        return Redirect::route('mobil.index')->with($notification);

    }
}
