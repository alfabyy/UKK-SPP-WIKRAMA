<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Rombel;
use App\Models\Spp;
use App\Models\User;

class StudentController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rombels = Rombel::all();
        $spps = Spp::all();
        if(request()->ajax()) {
            $data = Student::select('*');
            return datatables()->of($data)
            ->addColumn('action', 'student/student-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->addColumn('id_rombel', function ($row)
            {
                $rombels = Rombel::where('id', $row->id_rombel)->first();
                return $rombels->nama_kelas;
            })
            ->addColumn('id_spp', function ($row)
            {
                $spp = Spp::where('id', $row->id_spp)->first();
                return 'Rp. ' .number_format($spp->nominal) .' ' .'-' .'Tahun Masuk ' .$spp->tahun;
            })
            ->make(true);
        }
        return view('student/students', compact('rombels', 'spps'));
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {  
  
        //  $studentId = $request->id;
        //  $rombels = Rombel::all();
  
        //  $student   =   Student::updateOrCreate(
        //              [
        //               'id' => $studentId
        //              ],
        //              [
        //              'nisn' => $request->nisn, 
        //              'nis' => $request->nis,
        //              'nama' => $request->nama,
        //              'id_rombel' => $request->id_rombel,
        //              'alamat' => $request->alamat,
        //              'no_telp' => $request->no_telp,
        //              'id_spp' => $request->id_spp,
        //              ]);    

        //              $cekUser = User::updateOrcreate([
        //                 'name' => $request->nama,
        //                 'username' => substr($request->nama, 0,4) . $request->nis,
        //                 'password' => Hash::make($request->nis),
        //                 'level' => 'siswa',
        //              ]);

        //              if ($cekUser) {
        //                 return Response()->json($student);
    
        //              }

        $student = Student::where('nisn', $request->nisn)->where('nis', $request->nis)->first();

        if ($student != null) {
            return redirect()->back()->withInput()->with('error', 'Data sudah ada');
        }else{
        $cekValidasi = $request->validate([
            'nisn' => 'required',
            'nis' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'id_rombel' => 'required', 
            'no_telp' => 'required',
            'id_spp' => 'required'
        ]);

        switch ($cekValidasi) {
            case true:
                Student::create([
                    'nisn' => $request->nisn,
                    'nis' => $request->nis,
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'id_rombel' => $request->id_rombel,
                    'no_telp' => $request->no_telp,
                    'id_spp' => $request->id_spp,
                ]);
                 $cekUser = User::create([
                    'name' => $request->nama,
                    'username' => $request->nis,
                    'password' => Hash::make($request->nis),
                    'nisn' => $request->nisn,
                    'level' => 'siswa',
                 ]);

                 if ($cekUser) {
                    return Response()->json($student);

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

      public function edit(Request $request)
      {   
          $where = array('id' => $request->id);
          $student  = Student::where($where)->first();
        
          return Response()->json($student);
      }
        
        
      /**
       * Remove the specified resource from storage.
       *
       * @param  \App\company  $company
       * @return \Illuminate\Http\Response
       */
      public function delete($id)
      {
          $student = Student::find($id);
    
        $hapus = $student->delete();

        if ($hapus) {
            return Response()->json($student);
        }
      }
}
