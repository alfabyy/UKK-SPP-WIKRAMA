@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Siswa</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="container mt-2">
<div class="row">
<div class="col-lg-12 margin-tb">
<div class="pull-left">
<h2>Siswa</h2>
</div>
<div class="pull-right mb-2">
<a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Siswa</a>
</div>
</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
<p>{{ $message }}</p>
</div>
@endif
<div class="card-body">
    <table class="table table-bordered" id="student">
    <thead>
    <tr>
    <th>Id</th>
    <th>NISN</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Rombel</th>
    <th>Alamat</th>
    <th>No Telp</th>
    <th>SPP</th>
    <th>Action</th>
    </tr>
    </thead>
    </table>
    </div>
    </div>
    
    <!-- boostrap siswa model -->
    <div class="modal fade" id="student-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title" id="StudentModal"></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <form action="javascript:void(0)" id="StudentForm" name="StudentForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" id="id">
    
    <div class="form-group">
       <label for="name" class="col-sm-2 control-label">NISN</label>
          <div class="col-sm-12">
              <input type="number" class="form-control" id="nisn" name="nisn" placeholder="Masukkan NISN" maxlength="50" required="">
          </div>
    </div>  
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">NIS</label>
           <div class="col-sm-12">
               <input type="number" class="form-control" id="nis" name="nis" placeholder="Masukkan NIS" maxlength="50" required="">
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nama</label>
           <div class="col-sm-12">
               <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" maxlength="50" required="">
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Rombel</label>
           <div class="col-sm-12">
            <select name="id_rombel" required="required" class="form-control">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_kelas }}
                    </option>
                @endforeach
            </select>
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Alamat</label>
           <div class="col-sm-12">
               <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" maxlength="50" required="">
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">No Telp</label>
           <div class="col-sm-12">
               <input type="number" class="form-control" id="no_telp" name="no_telp" placeholder="Masukkan No Telp" maxlength="50" required="">
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">SPP</label>
           <div class="col-sm-12">
            <select name="id_spp" required="required" class="form-control">
                <option value="">-- Pilih Spp --</option>
                @foreach ($spps as $spp)
                    <option value="{{ $spp->id }}">{{ $spp->nominal }} - Tahun Masuk {{ $spp->tahun }}
                    </option>
                @endforeach
            </select>
           </div>
     </div>            
        <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success" id="btn-save">Submit
        </button>
        </div>
        </form>
        </div>
        <div class="modal-footer">
        </div>
        </div>
        </div>
        </div>
        <!-- end bootstrap model -->
    </body>
    <script type="text/javascript">
    $(document).ready( function () {
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $('#student').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('student.index') }}",
    columns: [
    { data: 'id', name: 'id' },
    { data: 'nisn', name: 'nisn' },
    { data: 'nis', name: 'nis' },
    { data: 'nama', name: 'nama' },
    { data: 'id_rombel', name: 'id_rombel' },
    { data: 'alamat', name: 'alamat' },
    { data: 'no_telp', name: 'no_telp' },
    { data: 'id_spp', name: 'id_spp' },
    {data: 'action', name: 'action', orderable: false},
    ],
    order: [[0, 'desc']]
    });
    });
    function add(){
    $('#StudentForm').trigger("reset");
    $('#StudentModal').html("Add Siswa");
    $('#student-modal').modal('show');
    $('#id').val('');
    }   
    function editFunc(id){
    $.ajax({
    type:"POST",
    url: "{{ url('edit-student') }}",
    data: { id: id },
    dataType: 'json',
    success: function(res){
$('#StudentModal').html("Edit Siswa");
$('#student-modal').modal('show');
$('#id').val(res.id);
$('#nisn').val(res.nisn);
$('#nis').val(res.nis);
$('#nama').val(res.nama);
$('#id_rombel').val(res.id_rombel);
$('#alamat').val(res.alamat);
$('#no_telp').val(res.no_telp);
$('#id_spp').val(res.id_spp);
}
});
}  
function deleteFunc(id){
if (confirm("Delete Record?") == true) {
var id = id;
console.log(id);
// ajax
$.ajax({
type:"GET",
url: "/student/"+ id + "/delete",
dataType: 'json',
success: function(res){
var oTable = $('#student').dataTable();
oTable.fnDraw(false);
}
});
}
}
$('#StudentForm').submit(function(e) {
e.preventDefault();
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('student.store') }}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
$("#student-modal").modal('hide');
var oTable = $('#student').dataTable();
oTable.fnDraw(false);
$("#btn-save").html('Submit');
$("#btn-save"). attr("disabled", false);
},
error: function(data){
console.log(data);
}
});
});
</script>
</html>

