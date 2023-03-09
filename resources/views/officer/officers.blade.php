@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Petugas</title>
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
<h2>Petugas</h2>
</div>
<div class="pull-right mb-2">
<a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Petugas</a>
</div>
</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
<p>{{ $message }}</p>
</div>
@endif
<div class="card-body">
    <table class="table table-bordered" id="officer">
    <thead>
    <tr>
    <th>Id</th>
    <th>NIP</th>
    <th>Username</th>
    <th>Nama</th>
    <th>Role</th>
    <th>Action</th>
    </tr>
    </thead>
    </table>
    </div>
    </div>
    
    <!-- boostrap siswa model -->
    <div class="modal fade" id="officer-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title" id="OfficerModal"></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <form action="javascript:void(0)" id="OfficerForm" name="OfficerForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" id="id">
    
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">NIP</label>
           <div class="col-sm-12">
               <input type="number" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" maxlength="50" required="">
           </div>
     </div>  
    <div class="form-group">
       <label for="name" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-12">
              <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" maxlength="50" required="">
          </div>
    </div>  
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nama</label>
           <div class="col-sm-12">
               <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" placeholder="Masukkan Nama" maxlength="50" required="">
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Password</label>
           <div class="col-sm-12">
               <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" maxlength="50" required="">
           </div>
     </div>
     <div class="form-group">
        <label for="name" class="col-sm-2 control-label">role</label>
           <div class="col-sm-12">
            <select name="role" required="required" class="form-control">
                <option value="">-- Pilih role --</option>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
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
    $('#officer').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ url('officer') }}",
    columns: [
    { data: 'id', name: 'id' },
    { data: 'nip', name: 'nip' },
    { data: 'username', name: 'username' },
    { data: 'nama_petugas', name: 'nama_petugas' },
    { data: 'role', name: 'role' },
    {data: 'action', name: 'action', orderable: false},
    ],
    order: [[0, 'desc']]
    });
    });
    function add(){
    $('#OfficerForm').trigger("reset");
    $('#OfficerModal').html("Add Petugas");
    $('#officer-modal').modal('show');
    $('#id').val('');
    }   
    function editFunc(id){
    $.ajax({
        type:"POST",
        url: "{{ url('edit-officer') }}",
        data: { id: id },
        dataType: 'json',
    success: function(res){
        $('#OfficerModal').html("Edit Petugas");
        $('#officer-modal').modal('show');
        $('#id').val(res.id);
        $('#nip').val(res.nip);
        $('#username').val(res.username);
        $('#nama_petugas').val(res.nama_petugas);
        $('#password').val(res.password);
        $('#role').val(res.role);
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
url: "/officer/"+ id + "/delete",
dataType: 'json',
success: function(res){
var oTable = $('#officer').dataTable();
oTable.fnDraw(false);
}
});
}
}
$('#OfficerForm').submit(function(e) {
e.preventDefault();
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ route('officer.store')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
$("#officer-modal").modal('hide');
var oTable = $('#officer').dataTable();
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