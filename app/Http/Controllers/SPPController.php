<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spp;

class SPPController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Spp::select('*'))
            ->addColumn('action', 'spp/spp-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('spp/spps');
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {  
  
         $sppId = $request->id;
  
         $spp   =   Spp::updateOrCreate(
                     [
                      'id' => $sppId
                     ],
                     [
                     'nama_spp' => $request->nama_spp, 
                     'tahun' => $request->tahun,
                     'nominal' => $request->nominal,
                     ]);    
                          
         return Response()->json($spp);
  
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
          $spp  = Spp::where($where)->first();
        
          return Response()->json($spp);
      }
        
        
      /**
       * Remove the specified resource from storage.
       *
       * @param  \App\company  $company
       * @return \Illuminate\Http\Response
       */
      public function delete($id)
      {
        $spps = Spp::find($id);
    
        $hapus = $spps->delete();

        if ($hapus) {
            return Response()->json($spps);
        }
      }
}
