<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Payment, Student, Officer, Spp, Rombel, User};
use carbon\Carbon;
use DataTables;

class PaymentController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::all();
            $student = Student::all();
            $spp = Spp::all();
            $users = User::all();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row)
                    {   
                        $btn = '<form action="/payment/'.$row->id.'" onsubmit="return confirm(\'Apakah anda yakin ingin menghapus '.$row->nisn.' ?\');" method="POST">
                        <a href="payment/'.$row->id.'/edit" class="btn btn-warning btn-sm">Edit</a>

                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                        <form action="/payment/'.$row->id.'" method="POST">
                        <a href="payment/'.$row->id.'/struk" class="btn btn-primary btn-sm">Print</a>

                        ';
                                
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->addColumn('nisn', function ($row)
            {
                $student = Student::where('nisn', $row->nisn)->first();
                return $student->nisn .' ' .'- ' .' ' .$student->nama;
            })
            ->addColumn('id_spp', function ($row)
            {
                $spp = Spp::where('id', $row->id_spp)->first();
                return 'Rp. ' .number_format($spp->nominal) .' ' .'-' .'Tahun Masuk ' .$spp->tahun;
            })
            ->addColumn('jumlah_bayar', function ($row)
            {
                $spp = Spp::where('id', $row->id_spp)->first();
                return 'Rp. ' .number_format($spp->nominal);
            })
            ->addColumn('id_petugas', function ($row)
            {
                $users = User::where('id', $row->id_petugas)->first();
                return $users->level;
            })
            ->make(true);
                }

                return view('payment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all();
        $payment = Payment::all();
        return view('payment.create', compact('students','payment'));
    }

    public function getData($nisn, $berapa)
    {
        $student = Student::where('nisn', '=', $nisn)->first();
        $spp = Spp::where('id', $student->id_spp)->first();
        $payment = Payment::where('nisn', '=', $nisn)
            ->orderBy('id', 'Desc')->latest()
            ->first();


        if ($payment == null) {
            $data = [
                'nominal' => $spp->nominal * $berapa,
                'bulan' => 'Belum Pernah Bayar',
                'tahun' => '',
            ];
        } else {

            if ($payment->tahun_dibayar == substr($spp->tahun, -3, 3)+3 && $payment->bulan_dibayar == 'Juni') {
                $data = [
                    'nominal' => $spp->nominal * $berapa,
                    'bulan' => 'Sudah Lunas',
                    'tahun' => '',
                ];
            } else {
                $data = [
                    'nominal' => $spp->nominal * $berapa,
                    'bulan' => $payment->bulan_dibayar,
                    'tahun' => $payment->tahun_dibayar,
                ];
            }
        }

        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nisn' => 'required|numeric',
            'jumlah_bayar' => 'required|numeric',
        ]);

        // dd($request->bayar_berapa);
        for ($i = 0; $i < $request->bayar_berapa; $i++) {
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            $student = Student::where('nisn', '=', $request->nisn)->first();
            $spp = Spp::where('id', $student->id_spp)->first();
            $payment = Payment::where('nisn', '=', $student->nisn)->get();

            if ($payment->isEmpty()) {
                $bln = 6;
                $tahun = substr($spp->tahun, 0, 4);
            } else {
                $payment = Payment::where('nisn', '=', $student->nisn)
                    ->orderBy('id', 'Desc')->latest()
                    ->first();

                $bln = array_search($payment->bulan_dibayar, $bulan);

                if ($bln == 11) {
                    $bln = 0;
                    $tahun = $payment->tahun_dibayar + 1;
                } else {
                    $bln = $bln + 1;
                    $tahun = $payment->tahun_dibayar;
                }
                // dd($payment->bulan_dibayar);

                if ($payment->tahun_dibayar == $spp->tahun+3 && $payment->bulan_dibayar == 'Juni') {
                    return redirect()->back()->with('error', 'Sudah Lunas');
                }
                
            }

            if ($request->jumlah_bayar < $spp->nominal) {
                return back()->with('tjumlah_bayar', 'Uang yang dimasukan tidak sesuai');
            }

            

            $paymentSimpan = Payment::create([
                'id_petugas' => auth()->user()->id,
                'nisn' => $request->nisn,
                'tanggal_bayar' => Carbon::now()->timezone('asia/jakarta'),
                'bulan_dibayar' => $bulan[$bln],
                'tahun_dibayar' => $tahun,
                'id_spp' => $spp->id,
                'jumlah_bayar' => $request->jumlah_bayar
            ]);
        }

        if ($paymentSimpan) {
            return redirect()->route('payment.index')->with('success', 'Data Created Successfully!');
        } else {
            return redirect()->back()->with('error', 'Data gagal masuk');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::find($id);
        $students = Student::all();
        return view('payment.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pembayaran  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        $cek = $request->validate([
             'nisn' => 'required',
             'tanggal_bayar' => 'required',
             'bulan_dibayar' => 'required',
             'tahun_dibayar' => 'required',
             'id_spp' => 'required',
             'jumlah_bayar' => 'required'
         ]);
 
         switch ($cek) {
             case true:
                 $payment->update([
                    'id_petugas' => auth()->user()->id,
                    'nisn' => $request->nisn,
                    'tanggal_bayar' => Carbon::now()->timezone('asia/jakarta'),
                    'bulan_dibayar' => $bulan[$bln],
                    'tahun_dibayar' => $tahun,
                    'id_spp' => $spp->id,
                    'jumlah_bayar' => $request->jumlah_bayar
                 ]);
                     return redirect()->route('payment.index')->with('success', 'Data berhasil diubah');
                 
                 break;
             
             default:
                 return redirect()->back()->withInput()->with('error', 'Data gagal diubah');
                 break;
         }
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pembayaran  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        $hapus = $payment->delete();

        if ($hapus) {
            return redirect()->route('payment.index')->with('success', 'Data berhasil dihapus');
        }
    }
}
