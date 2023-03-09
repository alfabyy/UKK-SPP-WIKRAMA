<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;

class RombelController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Rombel::select('*'))
            ->addColumn('action', 'rombel/rombel-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('rombel/rombels');
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {  
  
         $rombelId = $request->id;
  
         $rombel   =   Rombel::updateOrCreate(
                     [
                      'id' => $rombelId
                     ],
                     [
                     'nama_kelas' => $request->nama_kelas, 
                     'kompetensi_keahlian' => $request->kompetensi_keahlian,
                     ]);    
                          
                    
         return Response()->json($rombel);
         
  
     }
       
       
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\company  $company
      * @return \Illuminate\Http\Response
      */

      public function edit(Request $request)
      {   
          $where = array('id' => $request->id);
          $rombel  = Rombel::where($where)->first();
        
          return Response()->json($rombel);
      }

      public function update(Request $request, Rombel $rombel)
    {
        //
    }
        
        
      /**
       * Remove the specified resource from storage.
       *
       * @param  \App\company  $company
       * @return \Illuminate\Http\Response
       */
      public function delete($id)
      {
        $rombels = Rombel::find($id);
    
        $hapus = $rombels->delete();

        if ($hapus) {
            return Response()->json($rombels);
        }
      }
}
