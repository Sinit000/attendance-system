@extends('admin.layouts.master')
@section('title', 'Create Payslip')


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
                    payroll List
                </div>
            </div>
        </div>
        
        @if (session('success'))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {!! session('success') !!}
            </div>
        </div>
        @endif
        <div class="section-body">
            <div class="card card-info">
                <div class="card-header">
                    <div class="ml-auto">
                        <a href="{{url('admin/salary')}}" class="btn btn-outline-primary ">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card">
                        <form method="POST" action="{{ url('admin/salary/store') }}">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <div class="card-header">
                                <h4>Create Pay slip</h4>
                            </div>
                            <div class="card-body">
                                <!-- row one -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Monthly <sup class="text-danger">*</sup></label>
                                            <select class="form-control  @error('monthly') is-invalid @enderror select2" id="monthly" name="monthly" style="width: 100%;">
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
                                            <div class="invalid-feedback" id="valid-monthly">{{ $errors->first('monthly') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Employee <sup class="text-danger">*</sup></label>
                                            <select class="form-control @error('user_id') is-invalid @enderror select2" id="user_id" name="user_id" style="width: 100%;">
                                                <option value="">Choose Employee</option>
                                                @foreach($user as $r)
                                                <option value="{{$r->id}}">{{$r->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="valid-user_id">{{ $errors->first('user_id') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Basic Salary <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control @error('base_salary') is-invalid @enderror" id="base_salary" name="base_salary" placeholder="Enter  base salary..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-base_salary">{{ $errors->first('base_salary') }}</div>
                                        </div>
                                    </div>


                                </div>
                                <!-- row two -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Salary Increment </label>
                                            <input type="text" class="form-control" id="salary_increment" name="salary_increment" placeholder="Enter  salary increment..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Salary Rate </label>
                                            <input type="text" class="form-control" id="salary_rate" name="salary_rate" placeholder="Enter  salary rate..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Gross Salary </label>
                                            <input type="text" class="form-control" id="gross_salary" disabled name="gross_salary" placeholder="" autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>

                                </div>
                                <!-- row three -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Allowance </label>
                                            <input type="text" class="form-control" id="allowance" name="allowance" placeholder="Enter  allowance..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">OT Rate </label>
                                            <input type="text" class="form-control" id="ot_rate" name="ot_rate" placeholder="Enter  ot rate..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">OT Method </label>
                                            <input type="text" class="form-control" id="ot_method" name="ot_method" placeholder="Enter  ot method..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>

                                </div>
                                <!-- row 4 -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">OT Hour </label>
                                            <input type="text" class="form-control" id="ot_hour" name="ot_hour" placeholder="Enter  ot hour..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Total OT </label>
                                            <input type="text" class="form-control" id="total_ot" disabled name="total_ot" placeholder="" autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Deduction </label>
                                            <input type="text" class="form-control" id="deduction" name="deduction" placeholder="Enter  deduction ..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Net Salary </label>
                                            <input type="text" class="form-control" id="net_salary" disabled name="net_salary" placeholder="" autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="gender">Notes </label>
                                            <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter notes..." autocomplete="off">
                                            <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- row 5 -->


                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" id="btn-save" class="btn btn-info">
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
<!-- <script src="{{ asset('backend/modules/datatables/datatables.min.js') }}"></script> -->
<!-- <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script> -->
<!-- <script src="https://demo.getstisla.com/assets/modules/jquery.min.js"></script> -->

<script>
   
    $(document).ready(function() {
        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('body').on('keyup', '#user_id, #monthly, #base_salary', function() {
            var test = $(this).val();
            if (test == '') {
                $(this).removeClass('is-valid is-invalid');
                
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
        $('form').submit(function() {
            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
        });
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