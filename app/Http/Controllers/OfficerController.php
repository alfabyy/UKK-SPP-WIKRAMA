<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Officer;
use App\Models\User;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Officer::select('*'))
            ->addColumn('action', 'officer/officer-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('officer/officers');
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {  
  
        //  $officerId = $request->id;
  
        //  $officer = Officer::updateOrCreate([
        //     'id' => $officerId
        //    ],
            
        //     [
        //         'nip' => $request->nip,
        //         'nama_petugas' => $request->nama_petugas,
        //         'username' => $request->username,
        //         'password' => $request->password,
        //         'role' => $request->role,
        // ]);
                          
        //  return Response()->json($officer);

        $officer = Officer::where('nama_petugas', $request->nama_petugas)->where('username', $request->username)->first();

        if ($officer != null) {
            return redirect()->back()->withInput()->with('error', 'Data sudah ada');
        }else{
        $cekValidasi = $request->validate([
            'nip' => 'required',
            'nama_petugas' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required', 
        ]);

        switch ($cekValidasi) {
            case true:
                Officer::create([
                    'nip' => $request->nip,
                    'nama_petugas' => $request->nama_petugas,
                    'username' => $request->username,
                    'password' => $request->password,
                    'role' => $request->role,
                ]);
                 $cekUser = User::create([
                    'name' => $request->nama_petugas,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'level' => $request->role,
                 ]);

                 if ($cekUser) {
                    return Response()->json($officer);

                 }

                break;
            
            default:
            return redirect()->back()->withInput();
                break;
        }
    }
  
     }
       
       
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\company  $company
      * @return \Illuminate\Http\Response
      */

      public function edit($id)
      {   
        $officer = Officer::find($id);

        return view('officer/officers', compact('officer'));
      }

      public function update(Request $request, $id)
    {
        $officer = Officer::find($id);
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'level' => 'required',
            'nama_petugas' => 'required'
        ]);

        $officer->update($request->all());

        return Response()->json($officer);
    }
        
        
      /**
       * Remove the specified resource from storage.
       *
       * @param  \App\company  $company
       * @return \Illuminate\Http\Response
       */
      public function delete($id)
      {
        $officers = Officer::find($id);
    
        $hapus = $officers->delete();

        if ($hapus) {
            return Response()->json($officers);
        }
      }
}
