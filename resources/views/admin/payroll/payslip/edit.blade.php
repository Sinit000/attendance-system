@extends('admin.layouts.master')
@section('title', 'Edit Payslip')


@section('content')

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Payroll Data</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    payslip List
                </div>
            </div>
        </div>

        @if(session()->has('message'))
        <div class="alert alert-info alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {{ session()->get('message') }}
            </div>
        </div>
        @endif
        <div class="section-body">
            <div class="card card-info">
                <div class="card-header">
                    <div class="ml-auto">
                        <a href="{{url('admin/payslip')}}" class="btn btn-info ">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card">
                        <form id="company-form">
                            @csrf
                            <input type="hidden" name="id" value="{{$data->id}}" id="id">
                            <div class="card-header">
                                <h4>Edit Pay slip</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Currency <sup class="text-danger">*</sup></label>
                                            <select class="form-control " id="currency" name="currency" style="width: 100%;">
                                                
                                                 <!-- <option value="">Choose Currency</option>
                                                 <option value="riel"{{$data->currency == 'riel'?'selected':''}}  >Riel</option> -->
                                                <option value="usd"{{$data->currency == 'usd'?'selected':''}}  >USD</option>

                                            </select>
                                            <div class="invalid-feedback" id="valid-currency"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Exchange Rate </label>
                                            <input type="text" class="form-control " id="exchange_rate" name="exchange_rate" value="{{$data->exchange_rate}}" placeholder="Enter  base salary..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-exchange_rate"></div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Exchange Rate <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control " id="exchange_rate" name="exchange_rate" placeholder="Enter  base salary..." autocomplete="off">

                                        </div>
                                    </div> -->


                                </div>
                                <!-- row one -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">From date <sup class="text-danger">*</sup></label>
                                            <div class="bootstrap-timepicker ">
                                                <input type="date" class="form-control datepicker" id="from_date" name="from_date" value="{{$data->from_date}}">
                                            </div>
                                            <div class="invalid-feedback" id="valid-from_date"></div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="gender">Monthly <sup class="text-danger">*</sup></label>
                                            <select class="form-control  select2" id="monthly" name="monthly" style="width: 100%;">
                                                <option value="">Choose month</option>
                                                <option value="January">Jan</option>
                                                <option value="February">February</option>
                                                <option value="March">March</option>
                                                <option value="April">April</option>
                                                <option value="May">May</option>
                                                <option value="June">June</option>
                                                <option value="July">July</option>
                                                <option value="August">August</option>
                                                <option value="September">September</option>
                                                <option value="October">October</option>
                                                <option value="November">November</option>
                                                <option value="December">December</option>
                                            </select>
                                            <div class="invalid-feedback" id="valid-monthly"></div>
                                        </div> -->
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">To date <sup class="text-danger">*</sup></label>
                                            <div class="bootstrap-timepicker ">
                                                <input type="date" class="form-control datepicker" id="to_date" name="to_date" value="{{$data->to_date}}">
                                            </div>
                                            <div class="invalid-feedback" id="valid-to_date"></div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Employee <sup class="text-danger">*</sup></label>
                                            <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                                                <option value="">Choose Employee</option>
                                                @foreach($user as $r)
                                                <option value="{{$r->id}}" {{$r->id == $data->user_id?'selected':''}}>{{$r->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="valid-user_id"></div>
                                        </div>

                                    </div>



                                </div>
                               
                                <!-- row three -->
                                <div class="row">
                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Senority Salary </label>
                                            <input type="text" class="form-control" id="senority_salary" name="senority_salary" placeholder="Enter  senority..." autocomplete="off" value="{{$data->senority_salary}}">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Advance money </label>
                                            <input type="text" class="form-control" id="advance_salary" name="advance_salary" placeholder="Enter  advance money..." autocomplete="off" value="{{$data->advance_salary}}">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Bonus </label>
                                            <input type="text" class="form-control" id="bonus" name="bonus" placeholder="Enter  bonus..." autocomplete="off" value="{{$data->bonus}}">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Allowance </label>
                                            <input type="text" class="form-control" id="allowance" name="allowance" placeholder="Enter  allowance..." autocomplete="off" value="{{$data->allowance}}">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">OT Rate </label>
                                            <input type="text" class="form-control" id="ot_rate" name="ot_rate" placeholder="Enter  ot rate..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">OT Method </label>
                                            <input type="text" class="form-control" id="ot_method" name="ot_method" placeholder="Enter  ot method..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div> -->

                                </div>
                                <!-- row 4 -->
                                <!-- <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">OT Hour </label>
                                            <input type="text" class="form-control" id="ot_hour" name="ot_hour" placeholder="Enter  ot hour..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Total OT </label>
                                            <input type="text" class="form-control" id="total_ot" disabled name="total_ot" placeholder="" autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Gross Salary </label>
                                            <input type="text" class="form-control" id="gross_salary" disabled name="gross_salary" placeholder="" autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Total OT </label>
                                            <input type="text" class="form-control" id="total_ot" disabled name="total_ot" placeholder="" autocomplete="off" value="{{$data->total_ot}}">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Deduction </label>
                                            <input type="text" class="form-control" id="deduction" name="deduction" placeholder="Enter  deduction ..." autocomplete="off" value="{{$data->deduction}}">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Net Salary </label>
                                            <input type="text" class="form-control" id="net_salary" disabled name="net_salary" placeholder="" autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary"></div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="gender">Notes </label>
                                            <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter notes..." autocomplete="off" value="{{$data->notes}}">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- row 5 -->


                            </div>
                            <div class="card-footer text-right">
                                <button type="button" id="btn-save" class="btn btn-info">
                                    <i class="fas fa-check"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="{{ asset('backend/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>


<script>
    $(document).ready(function() {
        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#btn-save').click(function() {
            var formData = {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                user_id: $('#user_id').val(),
                // contract_id: $('#contract_id').val(),
                // base_salary: $('#base_salary').val(),
                currency: $('#currency').val(),
                exchange_rate: $('#exchange_rate').val(),


                advance_salary: $('#advance_salary').val(),
                bonus: $('#bonus').val(),
                // senority_salary: $('#senority_salary').val(),
                allowance: $('#allowance').val(),

                // ot_rate: $('#ot_rate').val(),
                // ot_method: $('#ot_method').val(),
                // ot_hour: $('#ot_hour').val(),
                // total_ot: $('#total_ot').val(),
                deduction: $('#deduction').val(),
                notes: $('#notes').val(),
            };
            // fd.append

            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
            var id = $('#id').val();
            type = "PUT";
            ajaxurl = "{{ url('admin/payslip/update') }}" + '/' + id;
            console.log(ajaxurl);


            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    console.log("Sinit")
                    console.log(data);
                    swal({
                        title: "Success!",
                        text: "Data has been added successfully!",
                        icon: "success",
                        timer: 3000
                    });
                    $('#btn-save').html('<i class="fas fa-check"></i> Update Data');
                    $('#btn-save').removeAttr('disabled');
                    $("#company-form")[0].reset();

                    // $('#formModal').modal('hide');
                },
                error: function(data) {
                    try {
                        if (data.responseJSON.errors.exchange_rate) {
                            $('#exchange_rate').removeClass('is-valid').addClass('is-invalid');
                            $('#valid-exchange_rate').removeClass('valid-feedback').addClass('invalid-feedback');
                            $('#valid-exchange_rate').html(data.responseJSON.errors.exchange_rate);
                        }
                        // if (data.responseJSON.errors.contract_id) {
                        //     $('#contract_id').removeClass('is-valid').addClass('is-invalid');
                        //     $('#valid-contract_id').removeClass('valid-feedback').addClass('invalid-feedback');
                        //     $('#valid-contract_id').html(data.responseJSON.errors.contract_id);
                        // }
                        if (data.responseJSON.errors.user_id) {
                            $('#user_id').removeClass('is-valid').addClass('is-invalid');
                            $('#valid-user_id').removeClass('valid-feedback').addClass('invalid-feedback');
                            $('#valid-user_id').html(data.responseJSON.errors.user_id);
                        }
                        if (data.responseJSON.errors.from_date) {
                            $('#from_date').removeClass('is-valid').addClass('is-invalid');
                            $('#valid-from_date').removeClass('valid-feedback').addClass('invalid-feedback');
                            $('#valid-from_date').html(data.responseJSON.errors.from_date);
                        }
                        if (data.responseJSON.errors.to_date) {
                            $('#to_date').removeClass('is-valid').addClass('is-invalid');
                            $('#valid-to_date').removeClass('valid-feedback').addClass('invalid-feedback');
                            $('#valid-to_date').html(data.responseJSON.errors.to_date);
                        }

                        $('#btn-save').html('<i class="fas fa-check"></i> Update Data');
                        $('#btn-save').removeAttr('disabled');
                        // $("#company-form")[0].reset();
                    } catch {
                        swal({
                            title: "Sorry!",
                            text: "An error occurred, please try again",
                            icon: "error",
                            timer: 3000
                        });
                        $('#btn-save').html('<i class="fas fa-check"></i> Update Data');
                        $('#btn-save').removeAttr('disabled');
                        // $("#company-form")[0].reset();

                        // $('#formModal').modal('hide');
                    }
                }
            });
        });
        // $('body').on('keyup', '#user_id, #monthly, #base_salary', function() {
        //     var test = $(this).val();
        //     if (test == '') {
        //         $(this).removeClass('is-valid is-invalid');

        //     } else {
        //         $(this).removeClass('is-invalid').addClass('is-valid');
        //     }
        // });
        // $('form').submit(function() {
        //     $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
        // });
        // $("#base_salary", "").keyup(function() {
        // var gross = 0;
        // var baseSalary = Number($("#base_salary").val());
        // var salaryIncre = Number($("#salary_increment").val());
        // var gross = baseSalary + salaryIncre;
        // $("#gross_salary").val(gross);

        //     alert(gross);
        //     console.log(gross);

        // })
    });
</script>

@endsection