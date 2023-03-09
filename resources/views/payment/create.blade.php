@include('layouts.app')

 <!-- css untuk select2 -->
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <!-- jika menggunakan bootstrap4 gunakan css ini  -->
 <link rel="stylesheet"
     href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
 <!-- cdn bootstrap4 -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
     integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">


    <div class="m-2">
        <a href="{{ route('payment.index') }}" class="btn btn-danger btn-sm mb-2">Back</a>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
          </div>
        @endif
        <form action="{{ route('payment.store') }}" method="POST">
            @csrf
            <div class="input-group mt-2 mb-2">
                <div class="input-group-prepend">
                    <span for="nisn" class="input-group-text">NISN</span>
                </div>
                <select name="nisn" class="input-group-text" data-live-search="true" id="nisn">
                    <option >--PILIH--</option>
                    @foreach ($students as $i)
                        <option value="{{ $i->nisn }}">{{ $i->nisn }} - {{ $i->nama }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="input-group mb-2" id="namaaa">
                <div class="input-group-prepend">
                    <span for="terakhir_bayar" class="input-group-text">Nama</span>
                </div>
                <input type="text" id="namaaa" class="form-control" readonly name="namaaa" >
                
            </div> --}}
            <div class="input-group mt-2 mb-2">
                <div class="input-group-prepend">
                    <span for="spp" class="input-group-text">Spp</span>
                </div>
                <input type="text" class="form-control" id="spp" disabled required>
            </div>
            
            <div class="input-group mb-2" id="terakhir_bayar">
                <div class="input-group-prepend">
                    <span for="terakhir_bayar" class="input-group-text">Terakhir Bayar</span>
                </div>
                <input type="text" id="lastm" class="form-control" readonly name="terakhir_bayar" >
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span for="berapa" class="input-group-text">Bayar Berapa Bulan</span>
                </div>
                <select name="bayar_berapa" id="berapa" class="form-control">
                    <option value="1">1 Bulan</option>
                    <option value="2">2 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="4">4 Bulan</option>
                    <option value="5">5 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="7">7 Bulan</option>
                    <option value="8">8 Bulan</option>
                    <option value="9">9 Bulan</option>
                    <option value="10">10 Bulan</option>
                    <option value="11">11 Bulan</option>
                    <option value="12">12 Bulan</option>
                </select>
            </div>
            <div class="input-group mb-2" id="total_bayar">
                <div class="input-group-prepend">
                    <span for="total_bayar" class="input-group-text">Total pembayaran</span>
                </div>
                <input type="text" id="byr" class="form-control" disabled name="total_bayar" required>
            </div>
            <div class="input-group mb-2" id="total_bayar">
                <div class="input-group-prepend">
                    <span for="total_bayar" class="input-group-text">Bayar</span>
                </div>
                <input type="text" id="bayar-spp" class="form-control" name="jumlah_bayar" required>
            </div>
            <div class="text-center">
                <button class="btn btn-success btn-lg disabled" id="button" type="submit">Tambah data</button>
            </div>
        </div>
        </form>
    </div>

    <!-- wajib jquery  -->
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script> --}}
<!-- js untuk bootstrap4  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
    crossorigin="anonymous"></script>
<!-- js untuk select2  -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


{{-- @section('script') --}}
    <script>
        $(function () {
            $('#nisn').on('change', function () {
                
                var nisn = $(this).val();
                var berapa = $('#berapa').val();
                console.log(nisn);
                $.ajax({
                    url : "{{ url('payment/getData') }}" + "/" + nisn + "/" + berapa,
                    type : "GET", 
                    dataType: "json",
                    success: function (data) {
                        console.log(data.bulan);
                        $('#spp').val(data.nominal);
                        $('#byr').val(data.nominal);
                        $('#lastm').val(data.bulan + " " + data.tahun);

                    }
                });
            });

            $('#berapa').on('change', function () {
                var brp = $(this).val();
                var spp = $('#spp').val();
                var total = spp * brp;
                $('#byr').val(total);
            });

            $("#nisn").select2({
                    theme: 'bootstrap4',
                    placeholder: "Please Select"
                });

            $('#bayar-spp').keyup(function () {
                var total = $('#byr').val();
                var bayar = $(this).val();

                var sanitized = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(sanitized);

                if (parseInt(bayar) >= parseInt(total)) {
                    $('#button').removeClass('disabled');
                }else {
                    $('#button').addClass('disabled');
                }
            }); 


            });
            
    </script>
    {{-- <script>
         $(document).ready(function () {
                $("#nisn").select2({
                    theme: 'bootstrap4',
                    placeholder: "Please Select"
                });

            });
    </script> --}}
{{-- @endsection --}}