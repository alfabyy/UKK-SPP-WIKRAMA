@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rombel</title>
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
<h2>Rombel</h2>
</div>
<div class="pull-right mb-2">
<a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Rombel</a>
</div>
</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
<p>{{ $message }}</p>
</div>
@endif
<div class="card-body">
    <table class="table table-bordered" id="rombel">
    <thead>
    <tr>
    <th>Id</th>
    <th>Nama Rombel</th>
    <th>Kompetensi Keahlian</th>
    <th>Action</th>
    </tr>
    </thead>
    </table>
    </div>
    </div>
    <!-- boostrap rombel model -->
    <div class="modal fade" id="rombel-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title" id="RombelModal"></h4>
    </div>
    <div class="modal-body">
    <form action="javascript:void(0)" id="RombelForm" name="RombelForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" id="id">
    <div class="form-group">
    <label for="name" class="col-sm-2 control-label">Nama Rombel</label>
    <div class="col-sm-12">
    <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" placeholder="Masukkan Nama Rombel" maxlength="50" required="">
    </div>
    </div>  
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Kompetensi Keahlian</label>
        <div class="col-sm-12">
        <input type="text" class="form-control" id="kompetensi_keahlian" name="kompetensi_keahlian" placeholder="Masukkan Kompetensi Keahlian" maxlength="50" required="">
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
    $('#rombel').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('rombel.index') }}",
    columns: [
    { data: 'id', name: 'id' },
    { data: 'nama_kelas', name: 'nama_kelas' },
    { data: 'kompetensi_keahlian', name: 'kompetensi_keahlian' },
    {data: 'action', name: 'action', orderable: false},
    ],
    order: [[0, 'desc']]
    });
    });
    function add(){
    $('#RombelForm').trigger("reset");
    $('#RombelModal').html("Add Rombel");
    $('#rombel-modal').modal('show');
    $('#id').val('');
    }   
    function editFunc(id){
    $.ajax({
    type:"POST",
    url: "{{ url('edit-rombel') }}",
    data: { id: id },
    dataType: 'json',
    success: function(res){
        $('#RombelModal').html("Edit Rombel");
$('#rombel-modal').modal('show');
$('#id').val(res.id);
$('#nama_kelas').val(res.nama_kelas);
$('#kompetensi_keahlian').val(res.kompetensi_keahlian);
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
url: "/rombel/"+ id + "/delete",
dataType: 'json',
success: function(res){
var oTable = $('#rombel').dataTable();
oTable.fnDraw(false);
}
});
}
}
$('#RombelForm').submit(function(e) {
e.preventDefault();
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('rombel.store') }}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
$("#rombel-modal").modal('hide');
var oTable = $('#rombel').dataTable();
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