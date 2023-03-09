@include('layouts.app')


<div class="m-2">
    <h2>Pembayaran</h2>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

{{-- @if(Auth::user()->level == 'admin') --}}
    <a href="{{ route('payment.create') }}" class="btn btn-success">Add</a>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
          </div>
        @endif
        {{-- @endif --}}

        {{-- @if(Auth::user()->level == 'petugas') --}}
    {{-- <a href="{{ route('payment.create') }}" class="btn btn-success">Add</a>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
          </div>
        @endif --}}
        {{-- @endif --}}

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>ID Petugas</th>
                <th>NISN</th>
                <th>Tanggal dibayar</th>
                <th>Bulan dibayar</th>
                <th>Tahun dibayar</th>
                <th>ID spp</th>
                <th>Jumlah bayar</th>
                @if(Auth::user()->level == 'admin')
                <th colspan="2">Option</th>
                @elseif(Auth::user()->level == 'petugas')
                <th colspan="2">Option</th>
                @endif
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

{{-- @if(Auth::user()->level == 'admin') --}}
    <script>
        $(function () {
            console.log('cek');
        var table = $('.data-table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],           
            processing: true,
            serverSide: false,
            ajax: "{{ route('payment.index') }}",
            columns: [
                {data: 'id_petugas', name: 'id_petugas'},
                {data: 'nisn', name: 'nisn'},
                {data: 'tanggal_bayar', name: 'tanggal_bayar'},
                {data: 'bulan_dibayar', name: 'dibulan_bayar'},
                {data: 'tahun_dibayar', name: 'tahun_dibayar'},
                {data: 'id_spp', name: 'id_spp'},
                {data: 'jumlah_bayar', name: 'jumlah_bayar'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[1, 'desc']],
            columnDefs: [
                {
                    target : 1,
                    orderable: false,
                    render: function (data) {
                        // $.ajax({
                        //     url: "{{ url('Pembayaran.getDataSiswa') }}" + "/" + data.nisn,
                        //     dataType: 'json',
                        // });
                    }

                }
            ]
            
        });

        $('#data-table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
        
    });
    </script>


   