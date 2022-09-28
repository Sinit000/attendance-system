<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Contract;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Overtime;
use App\Models\Payslip;
use App\Models\Position;
use App\Models\Structure;
use App\Models\User;
use App\Models\Workday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;
use Carbon\Carbon;

class PayslipController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];
    public function index()
    {
        $data = Payslip::all();
        // $data = Payslip::with('user', 'user.position')->orderBy('created_at', 'DESC')->get();
        foreach ($data as $key => $val) {
            $user = User::where('id', '=', $val->user_id)->first();
            $position = Position::where('id', '=', $user->position_id)->first();
            $ex1 = Contract::where('user_id', '=', $val->user_id)->first();
            $ex2 = Structure::where('id', '=', $ex1->structure_id)->first();
            $val->user_name = $user['name'];
            $val->position = $position['position_name'];
            $val->base_salary = $ex2['base_salary'];
            $val->allowance = $ex2['allowance'];
        }

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.payroll.payslip.index');
    }


    public function create()
    {
        $user = User::whereNotIn('id', [1])->get();
        // $contract = Contract::all();
        return view('admin.payroll.payslip.create', compact('user'));
    }
    public function addPayslip(Request $request, $id)
    {
        try {
            $from_date = $request->startDate;
            $to_date = $request->endDate;

            if ($from_date == '3') {
                $from_date = date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
                $to_date = date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
            }

            if ($from_date == '5') {
                $start = new Carbon('first day of last month');
                $end = new Carbon('last day of last month');
                $from_date = date('Y-m-d', strtotime($start->startOfMonth()));
                $to_date = date('Y-m-d', strtotime($end->endOfMonth()));
            }


            // $from_date = date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
            // $to_date = date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
            $user = User::find($id);
            $findContract = Contract::where('user_id', $user->id)->first();
            $totalCheckin = 0;

            if ($findContract) {
                $structure = Structure::find($findContract->structure_id);
                $standarHour = 0;
                $extra = 0;
                $basicSalry = $structure->base_salary;
                $work = Workday::where('id', '=', $user->workday_id)->first();

                if ($work->off_day) {
                    $workday = explode(',', $work->off_day);
                    if (count($workday) == 1) {
                        $standarHour = 26;
                    }
                    if (count($workday) == 2) {
                        $standarHour = 22;
                    }
                } else {
                    // this day , take salary and devided to find one day salary 
                    $standarHour = 26;
                    // 4 day , add more with 26 day 
                    // $extra = 4;
                    if ($request->checkin > $standarHour) {
                        $extra =  $request->checkin - $standarHour;
                    }

                    // cannot use standard 30 even though user cancel day off
                }
                $SalaryOneday =  $basicSalry / $standarHour;
                $extraDay = $SalaryOneday * $extra;



                $salaryAttendance = 0;
                $salaryCheckin = 0;
                $salaryCheckout = 0;
                $totalSalary = 0;
                $y = 0;
                $x = 0;
                $totalXY = 0;
                $taxTaxXY = 0;
                $totalXYUS = 0;
                $totalOt = 0;
                $taxXY = 0;
                $calbonus = 0;
                $bonus = 0;
                $senorityMoney = 0;

                $taxaloWance = 0;
                $taxSalary = 0;

                $countFamily = 0;
                $SalaryhaveTax = 0;
                $newSalary = 0;
                $case = "";

                $deDuction = 0;
                $grosSalary = 0;
                $ex_rate = 0;

                $calNetSalary = 0;
                $calbonus = 0;
                $calsenorityMoney = 0;
                $caladvanMoney = 0;
                $caldeDuction = 0;
                $calaloWance = 0;
                $calgrosSalary = 0;
                $caltaxSalry = 0;
                $calBasicSalry = 0;
                $roundGross = 0;
                $roundNet = 0;
                $taxAllowanceUS = 0;
                $roundAllowance = 0;
                $roundtaxSalry = 0;
                $taxSalryUs = 0;
                $n = "";
                $totalLeave = 0;
                $totalOTHour = 0;
                $currency = "usd";
                // standard 26 day , how if February
                // allwance
                if ($request->allowance) {
                    $x = $request->allowance;
                }
                if ($structure->allowance) {
                    $y = $structure->allowance;
                }
                $totalXY = $x + $y;
                if ($extra == 0) {
                    // in case user come before 27 , and not cancel dayoff
                    if ($request->checkin >= 26) {
                        $salaryAttendance = $basicSalry;
                    } else {
                        // if standanrd 22 , it means that user have two day off
                        if($standarHour==22){
                            if( $request->checkin >=22){
                                $salaryAttendance = $basicSalry;
                            }else{
                                $salaryAttendance = $SalaryOneday * $request->checkin;
                            }
                            
                        }else{
                            $salaryAttendance = $SalaryOneday * $request->checkin;
                        }
                        
                    }
                } else {
                    $salaryAttendance = $basicSalry + $extraDay;
                }

                $calBasicSalry =  $salaryAttendance;
                $totalSalary =  $salaryAttendance;

                // $totalSalary= $salaryAttendance+$salaryCheckin;
                // calculate from input
                if ($request->bonus) {
                    $bonus = $request->bonus;
                    $calbonus = $bonus;
                } else {
                    $bonus = 0;
                    $calbonus = 0;
                }

                if ($request->advance_salary) {
                    $advanMoney = $request->advance_salary;
                    $caladvanMoney = $advanMoney;
                } else {
                    $advanMoney = 0;
                    $caladvanMoney = 0;
                }
                if ($request->exchange_rate) {
                    $ex_rate = $request->exchange_rate;
                } else {
                    $ex_rate = 4000;
                }


                // only usd
                if ($user->nationality) {
                    if ($user->nationality == "Cambodian") {
                        if ($request->currency == "riel") {
                            // if($currencyStruct  == "riel" ){
                            //     $ex_money = $baseSalry;
                            // }
                            // elseif($currencyStruct  == "usd" ){
                            //     $ex_money= $baseSalry/$ex_rate;
                            // }
                            $newSalary =  $totalSalary + $advanMoney   + $bonus + $extraDay;
                            $calTotalOT = $totalOt;
                            if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                                // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                                if ($user->couple_job != "housewife") {
                                    if ($user->number_of_child >= 1) {
                                        $countFamily = $user->number_of_child;
                                        // if have advance money

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "001";

                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $case = "002";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $case = "003";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $case = "004";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "005";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        }
                                        // $grosSalary=  $taxSalary;
                                    } else {
                                        // don't have child and couple is not housewife

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "006";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $caltaxSalry = $taxSalary;
                                            $case = "007";
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $case = "008";
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                            $case = "009";
                                        } else {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "010";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        }
                                    }
                                } else {
                                    // if couple is house wife and have children add 1(wife)
                                    if ($user->number_of_child >= 1) {
                                        $countFamily = $user->number_of_child + 1;
                                        // if have advance money

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "1";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $case = "2";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $case = "3";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $case = "4";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "5";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        }
                                        // $grosSalary=  $taxSalary;
                                    } else {
                                        $countFamily = 1;
                                        // if have advance money

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "6";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $caltaxSalry = $taxSalary;
                                            $case = "7";
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $caltaxSalry = $taxSalary;
                                            $case = "8";
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $case = "9";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "10";
                                            $caltaxSalry = $taxSalary;
                                            $roundtaxSalry = round($caltaxSalry, 2);
                                        }
                                    }
                                }

                                // if married and divorce without chile
                                // let count married as single

                            } else {
                                // single
                                // $countFamily=$user->number_of_child;

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "single1";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "single2";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "single3";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "single4";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "single5";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                }
                            }

                            // tax allowance 
                            $taxTaxXY = $totalXY * 0.2;
                            $totalXYUS = $totalXY;
                            // $calaloWance = $aloWance;
                            // $caltaxaloWance = $taxaloWance;
                            $taxAllowanceUS =  $totalXYUS;
                            $roundAllowance = round($taxAllowanceUS, 2);
                            if ($request->deduction) {
                                $deDuction = $request->deduction;
                                $caldeDuction = $deDuction;
                            }
                            if ($taxSalary < 0) {
                                $taxSalary = 0;
                            }
                            // totalAllowance from allowance structure + allowance payslip
                            $grosSalary = ($newSalary +  $totalXYUS) -  ($taxSalary + $taxTaxXY);
                            $calgrosSalary = $grosSalary;
                            $netSalary = $grosSalary - $deDuction;
                            $calNetSalary = $netSalary;
                            $roundGross = $calgrosSalary;
                            $roundNet = $calNetSalary;
                        } else {
                            $newSalary = ($totalSalary + $advanMoney  + $bonus) * $ex_rate;
                            // $newSalary = ($totalSalary + $advanMoney   + $bonus) * $ex_rate;
                            // blum calculate exchange rate
                            if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                                // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                                if ($user->couple_job != "housewife") {
                                    if ($user->number_of_child >= 1) {
                                        $countFamily = $user->number_of_child;
                                        // if have advance money

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $caltaxSalry = $taxSalary;
                                            $case = "001";
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $case = "002";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $case = "003";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $case = "004";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "005";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        }
                                        // $grosSalary=  $taxSalary;
                                    } else {
                                        // don't have child and couple is not housewife

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "006";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $caltaxSalry = $taxSalary;
                                            $case = "007";
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $caltaxSalry = $taxSalary;
                                            $case = "008";
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $caltaxSalry = $taxSalary;
                                            $case = "009";
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary;
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "010";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        }
                                    }
                                } else {
                                    // if couple is house wife and have children add 1(wife)
                                    if ($user->number_of_child >= 1) {
                                        $countFamily = $user->number_of_child + 1;

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "1";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $case = "2";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $case = "3";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $case = "4";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "5";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        }
                                        // $grosSalary=  $taxSalary;
                                    } else {
                                        $countFamily = 1;
                                        // if have advance money

                                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                                            $taxSalary = 0;
                                            $case = "6";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                            $case = "7";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                            $case = "8";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                            $case = "9";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        } else {
                                            $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                            $case = "10";
                                            $caltaxSalry = $taxSalary;
                                            $taxSalryUs = $caltaxSalry / $ex_rate;
                                            $roundtaxSalry = round($taxSalryUs, 2);
                                        }
                                    }
                                }
                            } else {
                                // single
                                // $countFamily=$user->number_of_child;

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "single1";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "single2";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "single3";
                                    // Riel 
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "single4";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "single5";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                }
                            }
                            // allowance in riel
                            $taxTaxXY =  $totalXY * $ex_rate * 0.2;

                            $totalXYUS = $totalXY * $ex_rate;
                            // $calaloWance = $aloWance;  
                            // $caltaxaloWance = $taxaloWance;
                            $taxAllowanceUS =  $taxTaxXY / $ex_rate;
                            // this save into database
                            $roundAllowance = round($taxAllowanceUS, 2);
                            if ($request->deduction) {
                                $deDuction = ($request->deduction + $request->leave_deduction) * $ex_rate;
                                $caldeDuction = $request->deduction + $request->leave_deduction;
                            } else {
                                $deDuction =  $request->leave_deduction * $ex_rate;
                                $caldeDuction = $request->leave_deduction;
                            }
                            if ($taxSalary < 0) {
                                $taxSalary = 0;
                            }
                            // totalAllowance from allowance structure + allowance payslip
                            $grosSalary = ($newSalary + $totalXYUS) -  ($taxSalary +  $taxTaxXY);
                            // back to original currency
                            $calgrosSalary = $grosSalary / $ex_rate;
                            $netSalary = $grosSalary - $deDuction;
                            $calNetSalary = $netSalary / $ex_rate;
                            $roundGross = round($calgrosSalary, 2);
                            $roundNet = round($calNetSalary, 2);
                        }
                        $data = Payslip::create([
                            'from_date'           =>  $from_date,
                            'to_date'           =>  $to_date,
                            'user_id'           =>  $user->id,
                            // 'contract_id'          =>  $request->contract_id,
                            // 'base_salary'         => $calBasicSalry,
                            'advance_salary'    =>   $caladvanMoney,
                            // 'senority_salary'    =>  $calsenorityMoney,
                            'bonus'         => $calbonus,
                            // 'allowance'          =>  $totalXY,
                            'tax_allowance'          =>   $roundAllowance,
                            'total_attendance'         =>  $request->checkin,
                            'net_perday'         => round($SalaryOneday, 2),
                            'net_perhour'        => round($SalaryOneday / 9, 2),
                            'wage_hour'         =>  '9',
                            'tax_salary'         =>  $roundtaxSalry,
                            'gross_salary'       =>  $roundGross,
                            'deduction'    =>   $caldeDuction,
                            'net_salary'    =>   $roundNet,
                            'notes'    =>   $request->notes,
                            'currency'    =>    'usd',
                            'exchange_rate'    => $ex_rate,

                        ]);
                       
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                            // 'tax_allowance' => $totalXY,
                            // // 'from_date'           =>  $request->from_date,
                            // // 'to_date'           =>  $request->to_date,
                            // 'user_id'           =>  $user->id,
                            // // 'contract_id'          =>  $request->contract_id,
                            // 'base_salary'         => $calBasicSalry,
                            // 'advance_salary'    =>   $caladvanMoney,
                            // // 'senority_salary'    =>  $calsenorityMoney,
                            // 'bonus'         => $calbonus,
                            // 'allowance'          =>  $totalXY,
                            // 'tax_allowance'          =>   $roundAllowance,
                            // 'total_attendance'         =>  $request->checkin,
                            // 'total_deduction'         =>  $request->leave_deduction,
                            // 'net_perday'         =>  $SalaryOneday,
                            // 'net_perhour'        => $SalaryOneday / 9,
                            // 'wage_hour'         =>  '9',
                            // 'tax_salary'         =>  $roundtaxSalry,
                            // 'gross_salary'       =>  $roundGross,
                            // 'deduction'    =>   $caldeDuction,
                            // 'net_salary'    =>   $roundNet,
                            // 'notes'    =>   $request->notes,
                            // 'currency'    =>    $currency,
                            // 'exchange_rate'    => $ex_rate,
                            // 'case' => $case,
                            // 'newsalry' => $newSalary
                        ];
                        $checkin =Checkin::where('user_id','=',$user->id)->latest()->first();
                        $checkin->payslip_status ='true';
                        $checkin->update();

                    } else {
                        // foreigner 20% for salary, 20 % allowance 

                    }
                } else {
                    $respone = [
                        'message' => 'sorry, no employee nationality found!',
                        'code' => -1
                    ];
                }
            } else {
                $respone = [
                    'message' => 'please set up contract for employee',
                    'code' => -1
                ];
            }

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function updatePayslip(Request $request, $id)
    {
        request()->validate([

            'deduction' => "required",

        ], $this->customMessages);
        $data = Payslip::find($id);
        if ($data) {
            $user = User::find($request->user_id);
            $findContract = Contract::where('user_id', $user->id)->first();
            $structure = Structure::find($findContract->structure_id);
            $baseSalry = $structure->base_salary;

            // $advanMoney=0;
            // $caladvanMoney=0;
            // $bonus=0;
            // $advanMoney=0;
            // $calbonus=0;
            // $caladvanMoney=0;

            // $deDuction=0;
            // $caldeDuction=0;
            $totalDeduction = 0;
            $newNetSalary = 0;
            $newSalary = 0;
            $caltaxSalry = 0;
            $totalSalary = 0;
            $y = 0;
            $x = 0;
            $totalXY = 0;
            $taxTaxXY = 0;
            $totalXYUS = 0;
            $totalOt = 0;
            $taxXY = 0;
            $calbonus = 0;
            $bonus = 0;
            $senorityMoney = 0;

            $taxaloWance = 0;
            $taxSalary = 0;

            $countFamily = 0;
            $SalaryhaveTax = 0;
            $newSalary = 0;
            $case = "";

            $deDuction = 0;
            $grosSalary = 0;
            $ex_rate = 0;

            $calNetSalary = 0;
            $calbonus = 0;
            $calsenorityMoney = 0;
            $caladvanMoney = 0;
            $caldeDuction = 0;
            $calaloWance = 0;
            $calgrosSalary = 0;
            $caltaxSalry = 0;
            $calBasicSalry = 0;
            $roundGross = 0;
            $roundNet = 0;
            $taxAllowanceUS = 0;
            $roundAllowance = 0;
            $roundtaxSalry = 0;
            $taxSalryUs = 0;
            $n = "";
            $totalLeave = 0;
            $totalOTHour = 0;
            $currency = "usd";
            $y = 0;
            $x = 0;
            $taxTaxXY = 0;
            if ($structure->allowance) {
                $y = $structure->allowance;
            }
            $totalXY = $x + $y;
            if ($request->bonus) {
                $bonus = $request->bonus;
                $calbonus = $bonus;
            } else {
                $bonus = 0;
                $calbonus = 0;
            }
            if ($request->advance_salary) {
                $advanMoney = $request->advance_salary;
                $caladvanMoney = $advanMoney;
            } else {
                $advanMoney = 0;
                $caladvanMoney = 0;
            }
            if ($request->deduction) {
                $deDuction = $request->deduction;
                $caldeDuction = $request->deduction;
            } else {
                $deDuction = 0;
                $caldeDuction = 0;
            }
            // $totalDeduction = $deDuction;

            // calculate from input



            if ($request->exchange_rate) {
                $ex_rate = $request->exchange_rate;
            } else {
                $ex_rate = 4000;
            }

            if ($advanMoney == 0 && $bonus == 0) {
                $newNetSalary = $data->gross_salary  - $deDuction;
                $roundAllowance = $data->tax_allowance;
                $roundtaxSalry = $data->tax_salary;
                $roundNet = round($newNetSalary, 2);
                $roundGross = $data->gross_salary;
            } 
            if($advanMoney != 0 || $bonus != 0){
                if ($user->nationality == "Cambodian") {
                    $newSalary = ($data->gross_salary + $data->tax_salary  + $data->tax_allowance + $bonus + $advanMoney);
                    if ($request->currency == "riel") {
                        // if($currencyStruct  == "riel" ){
                        //     $ex_money = $baseSalry;
                        // }
                        // elseif($currencyStruct  == "usd" ){
                        //     $ex_money= $baseSalry/$ex_rate;
                        // }

                        if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                            // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                            if ($user->couple_job != "housewife") {
                                if ($user->number_of_child >= 1) {
                                    $countFamily = $user->number_of_child;
                                    // if have advance money

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "001";

                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $case = "002";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $case = "003";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $case = "004";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "005";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    }
                                    // $grosSalary=  $taxSalary;
                                } else {
                                    // don't have child and couple is not housewife

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "006";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $caltaxSalry = $taxSalary;
                                        $case = "007";
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $case = "008";
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                        $case = "009";
                                    } else {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "010";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    }
                                }
                            } else {
                                // if couple is house wife and have children add 1(wife)
                                if ($user->number_of_child >= 1) {
                                    $countFamily = $user->number_of_child + 1;
                                    // if have advance money

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "1";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $case = "2";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $case = "3";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $case = "4";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "5";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    }
                                    // $grosSalary=  $taxSalary;
                                } else {
                                    $countFamily = 1;
                                    // if have advance money

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "6";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $caltaxSalry = $taxSalary;
                                        $case = "7";
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $caltaxSalry = $taxSalary;
                                        $case = "8";
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $case = "9";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "10";
                                        $caltaxSalry = $taxSalary;
                                        $roundtaxSalry = round($caltaxSalry, 2);
                                    }
                                }
                            }

                            // if married and divorce without chile
                            // let count married as single

                        } else {
                            // single
                            // $countFamily=$user->number_of_child;

                            if ($newSalary >= 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "single1";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "single2";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "single3";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "single4";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } else {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "single5";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            }
                        }

                        // tax allowance 
                        $taxTaxXY = $totalXY * 0.2;
                        $totalXYUS = $totalXY;
                        // $calaloWance = $aloWance;
                        // $caltaxaloWance = $taxaloWance;
                        $taxAllowanceUS =  $totalXYUS;
                        $roundAllowance = round($taxAllowanceUS, 2);
                        if ($request->deduction) {
                            $deDuction = $request->deduction;
                            $caldeDuction = $deDuction;
                        }
                        if ($taxSalary < 0) {
                            $taxSalary = 0;
                        }
                        // totalAllowance from allowance structure + allowance payslip
                        $grosSalary = ($newSalary +  $totalXYUS) -  ($taxSalary + $taxTaxXY);
                        $calgrosSalary = $grosSalary;
                        $netSalary = $grosSalary - $deDuction;
                        $calNetSalary = $netSalary;
                        $roundGross = $calgrosSalary;
                        $roundNet = $calNetSalary;
                    } else {
                        $newSalary = $newSalary * $ex_rate;
                        // $newSalary = ($totalSalary + $advanMoney   + $bonus) * $ex_rate;
                        // blum calculate exchange rate
                        if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                            // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                            if ($user->couple_job != "housewife") {
                                if ($user->number_of_child >= 1) {
                                    $countFamily = $user->number_of_child;
                                    // if have advance money

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $caltaxSalry = $taxSalary;
                                        $case = "001";
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $case = "002";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $case = "003";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $case = "004";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "005";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    }
                                    // $grosSalary=  $taxSalary;
                                } else {
                                    // don't have child and couple is not housewife

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "006";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $caltaxSalry = $taxSalary;
                                        $case = "007";
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $caltaxSalry = $taxSalary;
                                        $case = "008";
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $caltaxSalry = $taxSalary;
                                        $case = "009";
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary;
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "010";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    }
                                }
                            } else {
                                // if couple is house wife and have children add 1(wife)
                                if ($user->number_of_child >= 1) {
                                    $countFamily = $user->number_of_child + 1;

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "1";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $case = "2";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $case = "3";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $case = "4";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "5";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    }
                                    // $grosSalary=  $taxSalary;
                                } else {
                                    $countFamily = 1;
                                    // if have advance money

                                    if ($newSalary >= 0 && $newSalary <= 1300000) {
                                        $taxSalary = 0;
                                        $case = "6";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                        $case = "7";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                        $case = "8";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                        $case = "9";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    } else {
                                        $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                        $case = "10";
                                        $caltaxSalry = $taxSalary;
                                        $taxSalryUs = $caltaxSalry / $ex_rate;
                                        $roundtaxSalry = round($taxSalryUs, 2);
                                    }
                                }
                            }
                        } else {
                            // single
                            // $countFamily=$user->number_of_child;

                            if ($newSalary >= 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "single1";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "single2";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "single3";
                                // Riel 
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "single4";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } else {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "single5";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            }
                        }
                        // allowance in riel
                        $taxTaxXY =  $totalXY * $ex_rate * 0.2;

                        $totalXYUS = $totalXY * $ex_rate;
                        // $calaloWance = $aloWance;  
                        // $caltaxaloWance = $taxaloWance;
                        $taxAllowanceUS =  $taxTaxXY / $ex_rate;
                        // this save into database
                        $roundAllowance = round($taxAllowanceUS, 2);
                        if ($request->deduction) {
                            $deDuction = $request->deduction  * $ex_rate;
                            $caldeDuction = $request->deduction;
                        } else {
                            $deDuction = 0;
                            $caldeDuction = 0;
                        }
                        if ($taxSalary < 0) {
                            $taxSalary = 0;
                        }
                        // totalAllowance from allowance structure + allowance payslip
                        $grosSalary = ($newSalary + $totalXYUS) -  ($taxSalary +  $taxTaxXY);
                        // back to original currency
                        $calgrosSalary = $grosSalary / $ex_rate;
                        $netSalary = $grosSalary - $deDuction;
                        $calNetSalary = $netSalary / $ex_rate;
                        $roundGross = round($calgrosSalary, 2);
                        $roundNet = round($calNetSalary, 2);
                    }
                } else {
                    // foreigner 20% for salary, 20 % allowance 

                }
            }
            



            $data->user_id = $user->id;
            
            $data->advance_salary = $caladvanMoney;
            // $data->senority_salary= $calsenorityMoney;
            $data->bonus = $calbonus;

            $data->tax_allowance =  $roundAllowance;

            $data->tax_salary =  $roundtaxSalry;
            $data->gross_salary = $roundGross;
            $data->deduction =  $caldeDuction;

            $data->net_salary =  $roundNet;
            $data->notes =  $request->notes;


            $query = $data->update();

          
            if ($query) {
                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                ];
            } else {
                $respone = [
                    'message' => 'Something went wrong!',
                    'code' => -1,
                ];
            }
            return response()->json($respone, 200);
        }
    }

    public function store(Request $request)
    {

        request()->validate([
            // 'user_id' => "required",
            // 'deduction' => "required",
            // 'from_date' => "required",
            // 'to_date' => "required",
            // 'currency' => "required"
        ], $this->customMessages);
        $from_date = date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
        $to_date = date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
        $user = User::find($request->user_id);
        $findContract = Contract::where('user_id', $request->user_id)->first();
        $structure = Structure::find($findContract->structure_id);
        $basicSalry = $structure->base_salary;
        $standarHour =        $findContract->working_schedule;
        $SalaryOneday =  $basicSalry / $standarHour;
        // $currencyStruct = $structure->currency;
        // $ex_money =0;
        $salaryAttendance = 0;
        $salaryCheckin = 0;
        $salaryCheckout = 0;
        $totalSalary = 0;
        $y = 0;
        $x = 0;
        $totalXY = 0;
        $taxTaxXY = 0;
        $totalXYUS = 0;
        $totalOt = 0;
        $taxXY = 0;
        $calbonus = 0;
        $bonus = 0;
        $senorityMoney = 0;

        $taxaloWance = 0;
        $taxSalary = 0;

        $countFamily = 0;
        $SalaryhaveTax = 0;
        $newSalary = 0;
        $case = "";

        $deDuction = 0;
        $grosSalary = 0;
        $ex_rate = 0;

        $calNetSalary = 0;
        $calbonus = 0;
        $calsenorityMoney = 0;
        $caladvanMoney = 0;
        $caldeDuction = 0;
        $calaloWance = 0;
        $calgrosSalary = 0;
        $caltaxSalry = 0;
        $calBasicSalry = 0;
        $roundGross = 0;
        $roundNet = 0;
        $taxAllowanceUS = 0;
        $roundAllowance = 0;
        $roundtaxSalry = 0;
        $taxSalryUs = 0;
        $n = "";
        $totalLeave = 0;
        $totalOTHour = 0;


        // allwance
        if ($request->allowance) {
            $x = $request->allowance;
        }
        if ($structure->allowance) {
            $y = $structure->allowance;
        }
        $totalXY = $x + $y;

        // cal ot
        // $findOt = Overtime::where('user_id', $request->user_id)->whereDate('overtimes.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))
        //     ->whereDate('overtimes.created_at', '<=', date('Y-m-d', strtotime($request->to_date)))->get();
        // if ($findOt) {

        //     for ($i = 0; $i < count($findOt); $i++) {
        //         if ($findOt[$i]["pay_status"] == "completed") {
        //             $totalOt +=  $findOt[$i]["total_ot"];
        //             $totalOTHour= $findOt[$i]["ot_hour"];
        //             $totalOt = round($totalOt, 2);
        //         } else {
        //             $totalOt = 0;
        //         }
        //     }
        // } else {
        //     $totalOt = 0;
        // }
        // leave 
        $findLeave = Leave::where('user_id', $request->user_id)->whereDate('leaves.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))
            ->whereDate('leaves.created_at', '<=', date('Y-m-d', strtotime($request->to_date)))
            ->get();
        if ($findLeave) {

            for ($i = 0; $i < count($findLeave); $i++) {
                $totalLeave +=  $findLeave[$i]["leave_deduction"];
                $totalLeave = round($totalLeave, 2);
            }
        } else {
            $totalLeave = 0;
        }
        // caculate salary from input first 
        // count attendance day which complete checkin and checkout
        $countAD = Checkin::where('user_id', $request->user_id)->whereDate('checkins.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))
            ->whereDate('checkins.created_at', '<=', date('Y-m-d', strtotime($request->to_date)))
            // ->where('checkin_status','!=','permission halfday morning')
            ->where('status', 'present')
            ->count();
        // onday 10 *28
        $salaryAttendance = $SalaryOneday * $countAD;
        $calBasicSalry =  $salaryAttendance;


        // count checkin only (if only checkin  , we take salary oneday/2)
        // $countCheckin = Checkin::where('user_id', $request->user_id)->whereDate('checkins.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))
        // ->whereDate('checkins.created_at', '<=', date('Y-m-d', strtotime($request->to_date)))
        // ->where('status','scanned')
        // ->count();
        // $salaryCheckin = ($SalaryOneday*$countCheckin)/2;
        // // permission halfday morning
        // $countCheckout = Checkin::where('user_id', $request->user_id)->whereDate('checkins.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))
        // ->whereDate('checkins.created_at', '<=', date('Y-m-d', strtotime($request->to_date)))
        // ->where('checkin_status','permission halfday morning')
        // ->where('status','present')
        // ->count();
        // $salaryCheckout =  ($SalaryOneday*$countCheckout)/2;
        $totalSalary =  $salaryAttendance + $totalOt - $totalLeave;

        // $totalSalary= $salaryAttendance+$salaryCheckin;
        // calculate from input
        if ($request->bonus) {
            $bonus = $request->bonus;
            $calbonus = $bonus;
        } else {
            $bonus = 0;
            $calbonus = 0;
        }

        if ($request->advance_salary) {
            $advanMoney = $request->advance_salary;
            $caladvanMoney = $advanMoney;
        } else {
            $advanMoney = 0;
            $caladvanMoney = 0;
        }
        if ($request->exchange_rate) {
            $ex_rate = $request->exchange_rate;
        } else {
            $ex_rate = 4000;
        }


        // only usd
        if ($user->nationality) {
            if ($user->nationality == "Cambodian") {
                if ($request->currency == "riel") {
                    // if($currencyStruct  == "riel" ){
                    //     $ex_money = $baseSalry;
                    // }
                    // elseif($currencyStruct  == "usd" ){
                    //     $ex_money= $baseSalry/$ex_rate;
                    // }
                    $newSalary =  $totalSalary + $advanMoney   + $bonus;
                    $calTotalOT = $totalOt;
                    if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                        // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                        if ($user->couple_job != "housewife") {
                            if ($user->number_of_child >= 1) {
                                $countFamily = $user->number_of_child;
                                // if have advance money

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "001";

                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "002";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "003";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "004";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "005";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                }
                                // $grosSalary=  $taxSalary;
                            } else {
                                // don't have child and couple is not housewife

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "006";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $caltaxSalry = $taxSalary;
                                    $case = "007";
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "008";
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                    $case = "009";
                                } else {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "010";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                }
                            }
                        } else {
                            // if couple is house wife and have children add 1(wife)
                            if ($user->number_of_child >= 1) {
                                $countFamily = $user->number_of_child + 1;
                                // if have advance money

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "1";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "2";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "3";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "4";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "5";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                }
                                // $grosSalary=  $taxSalary;
                            } else {
                                $countFamily = 1;
                                // if have advance money

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "6";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $caltaxSalry = $taxSalary;
                                    $case = "7";
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $caltaxSalry = $taxSalary;
                                    $case = "8";
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "9";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "10";
                                    $caltaxSalry = $taxSalary;
                                    $roundtaxSalry = round($caltaxSalry, 2);
                                }
                            }
                        }

                        // if married and divorce without chile
                        // let count married as single

                    } else {
                        // single
                        // $countFamily=$user->number_of_child;

                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                            $taxSalary = 0;
                            $case = "single1";
                            $caltaxSalry = $taxSalary;
                            $roundtaxSalry = round($caltaxSalry, 2);
                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                            $case = "single2";
                            $caltaxSalry = $taxSalary;
                            $roundtaxSalry = round($caltaxSalry, 2);
                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                            $case = "single3";
                            $caltaxSalry = $taxSalary;
                            $roundtaxSalry = round($caltaxSalry, 2);
                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                            $case = "single4";
                            $caltaxSalry = $taxSalary;
                            $roundtaxSalry = round($caltaxSalry, 2);
                        } else {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                            $case = "single5";
                            $caltaxSalry = $taxSalary;
                            $roundtaxSalry = round($caltaxSalry, 2);
                        }
                    }

                    // tax allowance 
                    $taxTaxXY = $totalXY * 0.2;
                    $totalXYUS = $totalXY;
                    // $calaloWance = $aloWance;
                    // $caltaxaloWance = $taxaloWance;
                    $taxAllowanceUS =  $totalXYUS;
                    $roundAllowance = round($taxAllowanceUS, 2);
                    if ($request->deduction) {
                        $deDuction = $request->deduction;
                        $caldeDuction = $deDuction;
                    }
                    if ($taxSalary < 0) {
                        $taxSalary = 0;
                    }
                    // totalAllowance from allowance structure + allowance payslip
                    $grosSalary = ($newSalary +  $totalXYUS) -  ($taxSalary + $taxTaxXY);
                    $calgrosSalary = $grosSalary;
                    $netSalary = $grosSalary - $deDuction;
                    $calNetSalary = $netSalary;
                    $roundGross = $calgrosSalary;
                    $roundNet = $calNetSalary;
                } else {
                    $calTotalOT = $totalOt;
                    $newSalary = ($totalSalary + $advanMoney   + $bonus) * $ex_rate;
                    // blum calculate exchange rate
                    if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                        // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                        if ($user->couple_job != "housewife") {
                            if ($user->number_of_child >= 1) {
                                $countFamily = $user->number_of_child;
                                // if have advance money

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $caltaxSalry = $taxSalary;
                                    $case = "001";
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "002";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "003";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "004";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "005";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                }
                                // $grosSalary=  $taxSalary;
                            } else {
                                // don't have child and couple is not housewife

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "006";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $caltaxSalry = $taxSalary;
                                    $case = "007";
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $caltaxSalry = $taxSalary;
                                    $case = "008";
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $caltaxSalry = $taxSalary;
                                    $case = "009";
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary;
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "010";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                }
                            }
                        } else {
                            // if couple is house wife and have children add 1(wife)
                            if ($user->number_of_child >= 1) {
                                $countFamily = $user->number_of_child + 1;

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "1";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "2";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "3";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "4";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "5";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                }
                                // $grosSalary=  $taxSalary;
                            } else {
                                $countFamily = 1;
                                // if have advance money

                                if ($newSalary >= 0 && $newSalary <= 1300000) {
                                    $taxSalary = 0;
                                    $case = "6";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                    $case = "7";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                    $case = "8";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                    $case = "9";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                } else {
                                    $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                    $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                    $case = "10";
                                    $caltaxSalry = $taxSalary;
                                    $taxSalryUs = $caltaxSalry / $ex_rate;
                                    $roundtaxSalry = round($taxSalryUs, 2);
                                }
                            }
                        }
                    } else {
                        // single
                        // $countFamily=$user->number_of_child;

                        if ($newSalary >= 0 && $newSalary <= 1300000) {
                            $taxSalary = 0;
                            $case = "single1";
                            $caltaxSalry = $taxSalary;
                            $taxSalryUs = $caltaxSalry / $ex_rate;
                            $roundtaxSalry = round($taxSalryUs, 2);
                        } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                            $case = "single2";
                            $caltaxSalry = $taxSalary;
                            $taxSalryUs = $caltaxSalry / $ex_rate;
                            $roundtaxSalry = round($taxSalryUs, 2);
                        } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                            $case = "single3";
                            // Riel 
                            $caltaxSalry = $taxSalary;
                            $taxSalryUs = $caltaxSalry / $ex_rate;
                            $roundtaxSalry = round($taxSalryUs, 2);
                        } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                            $case = "single4";
                            $caltaxSalry = $taxSalary;
                            $taxSalryUs = $caltaxSalry / $ex_rate;
                            $roundtaxSalry = round($taxSalryUs, 2);
                        } else {
                            $SalaryhaveTax = $newSalary;
                            $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                            $case = "single5";
                            $caltaxSalry = $taxSalary;
                            $taxSalryUs = $caltaxSalry / $ex_rate;
                            $roundtaxSalry = round($taxSalryUs, 2);
                        }
                    }
                    // allowance in riel
                    $taxTaxXY =  $totalXY * $ex_rate * 0.2;

                    $totalXYUS = $totalXY * $ex_rate;
                    // $calaloWance = $aloWance;  
                    // $caltaxaloWance = $taxaloWance;
                    $taxAllowanceUS =  $taxTaxXY / $ex_rate;
                    // this save into database
                    $roundAllowance = round($taxAllowanceUS, 2);
                    if ($request->deduction) {
                        $deDuction = $request->deduction * $ex_rate;
                        $caldeDuction = $request->deduction;
                    } else {
                        $caldeDuction = 0;
                    }
                    if ($taxSalary < 0) {
                        $taxSalary = 0;
                    }
                    // totalAllowance from allowance structure + allowance payslip
                    $grosSalary = ($newSalary + $totalXYUS) -  ($taxSalary +  $taxTaxXY);
                    // back to original currency
                    $calgrosSalary = $grosSalary / $ex_rate;
                    $netSalary = $grosSalary - $deDuction;
                    $calNetSalary = $netSalary / $ex_rate;
                    $roundGross = round($calgrosSalary, 2);
                    $roundNet = round($calNetSalary, 2);
                }
            } else {
                // foreigner 20% for salary, 20 % allowance 

            }
        }

        // if currency ="riel"
        $data = Payslip::create([
            'from_date'           =>  $request->from_date,
            'to_date'           =>  $request->to_date,
            'user_id'           =>  $request->user_id,
            // 'contract_id'          =>  $request->contract_id,
            'base_salary'         => $calBasicSalry,
            'advance_salary'    =>   $caladvanMoney,
            // 'senority_salary'    =>  $calsenorityMoney,
            'bonus'         => $calbonus,
            'allowance'          =>  $totalXY,
            'tax_allowance'          =>   $roundAllowance,
            'total_attendance'         =>  $countAD,
            'total_leave'         =>  $totalLeave,
            // 'ot_hour'         =>  $totalOTHour,
            // 'total_ot'         =>  $totalOt,
            'tax_salary'         =>  $roundtaxSalry,
            'gross_salary'       =>  $roundGross,
            'deduction'    =>   $caldeDuction,
            'net_salary'    =>   $roundNet,
            'notes'    =>   $request->notes,
            'currency'    =>    $request->currency,
            'exchange_rate'    => $ex_rate,
        ]);

        return response()->json($data);

        // return response()->json([
        //     "case"=>$case,
        //     'new_salary'         =>$newSalary,
        //     // 'currency_stru'=>$currencyStruct,
        //     'from_date'           =>  $request->from_date,
        //     'to_date'           =>  $request->to_date,
        //     'user_id'           =>  $request->user_id,
        //     'contract_id'          =>  $request->contract_id,
        //     'base_salary'         =>$calBasicSalry,
        //     // 'advance_salary'    =>   $caladvanMoney,
        //     // 'senority_salary'    =>  $calsenorityMoney,
        //     // 'bonus'         => $calbonus,
        //     'allowance'          =>  $totalXY,
        //     'tax_allowance'          =>   $roundAllowance,
        //     'ot_hour'          =>  $totalOTHour,
        //     'total_leave'          =>  $totalLeave,

        //     'total_ot'         =>  $totalOt,
        //     'tax_salary'         =>  $roundtaxSalry,
        //     'gross_salary'       =>  $roundGross,
        //     'deduction'    =>   $caldeDuction,
        //     'net_salary'    =>   $roundNet,
        //     'notes'    =>   $request->notes,
        //     'currency'    =>    $request->currency,
        //     'exchange_rate'    =>$ex_rate,

        // ]);

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = Payslip::find($id);
        return response()->json($data);
        // $user = User::whereNotIn('id', [1])->get();
        // $contract = Contract::all();
        // return view('admin.payroll.payslip.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        request()->validate([
            'user_id' => "required",
            'exchange_rate' => "required",
            'from_date' => "required",
            'to_date' => "required",
            // 'currency' => "required"
        ], $this->customMessages);
        $data = Payslip::find($id);
        if ($data) {
            $user = User::find($request->user_id);
            $findContract = Contract::where('user_id', $request->user_id)->first();

            $structure = Structure::find($findContract->structure_id);
            $baseSalry = $structure->base_salary;

            $ex_money = 0;

            $y = 0;
            $x = 0;
            $totalXY = 0;
            $taxTaxXY = 0;
            $totalXYUS = 0;
            $totalOt = 0;
            $taxXY = 0;
            $calbonus = 0;
            $bonus = 0;
            $senorityMoney = 0;

            $taxaloWance = 0;
            $taxSalary = 0;

            $countFamily = 0;
            $SalaryhaveTax = 0;
            $newSalary = 0;
            $case = "";

            $deDuction = 0;
            $grosSalary = 0;
            $ex_rate = 0;

            $calNetSalary = 0;
            $calbonus = 0;
            $calsenorityMoney = 0;
            $caladvanMoney = 0;
            $caldeDuction = 0;
            $calaloWance = 0;
            $calgrosSalary = 0;
            $caltaxSalry = 0;
            $calBasicSalry = 0;
            $roundGross = 0;
            $roundNet = 0;
            $taxAllowanceUS = 0;
            $roundAllowance = 0;
            $roundtaxSalry = 0;
            $taxSalryUs = 0;
            $n = "";
            // allwance
            if ($request->allowance) {
                $x = $request->allowance;
            }
            if ($structure->allowance) {
                $y = $structure->allowance;
            }
            $totalXY = $x + $y;
            $calBasicSalry = $baseSalry;
            // cal ot
            $findOt = Overtime::where('user_id', $request->user_id)->whereDate('overtimes.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))
                ->whereDate('overtimes.created_at', '<=', date('Y-m-d', strtotime($request->to_date)))->get();
            if ($findOt) {

                for ($i = 0; $i < count($findOt); $i++) {
                    if ($findOt[$i]["pay_status"] == "pending") {
                        $totalOt +=  $findOt[$i]["total_ot"];
                        $totalOt = round($totalOt, 2);
                    } else {
                        $totalOt = 0;
                    }
                }
            } else {
                $totalOt = 0;
            }
            // calculate from input
            if ($request->bonus) {
                $bonus = $request->bonus;
                $calbonus = $bonus;
            } else {
                $bonus = 0;
                $calbonus = 0;
            }

            if ($request->advance_salary) {
                $advanMoney = $request->advance_salary;
                $caladvanMoney = $advanMoney;
            } else {
                $advanMoney = 0;
                $caladvanMoney = 0;
            }
            if ($request->exchange_rate) {
                $ex_rate = $request->exchange_rate;
            } else {
                $ex_rate = 4000;
            }
            // only us

            // if currency ="riel"
            if ($request->currency == "riel") {

                $newSalary = $baseSalry + $advanMoney + $totalOt + $senorityMoney + $bonus;
                $calTotalOT = $totalOt;
                if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                    // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                    if ($user->couple_job != "housewife") {
                        if ($user->number_of_child >= 1) {
                            $countFamily = $user->number_of_child;
                            // if have advance money

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "001";

                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "002";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "003";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "004";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } else {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "005";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            }
                            // $grosSalary=  $taxSalary;
                        } else {
                            // don't have child and couple is not housewife

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "006";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $caltaxSalry = $taxSalary;
                                $case = "007";
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "008";
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                                $case = "009";
                            } else {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "010";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            }
                        }
                    } else {
                        // if couple is house wife and have children add 1(wife)
                        if ($user->number_of_child >= 1) {
                            $countFamily = $user->number_of_child + 1;
                            // if have advance money

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "1";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "2";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "3";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "4";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } else {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "5";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            }
                            // $grosSalary=  $taxSalary;
                        } else {
                            $countFamily = 1;
                            // if have advance money

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "6";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $caltaxSalry = $taxSalary;
                                $case = "7";
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $caltaxSalry = $taxSalary;
                                $case = "8";
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "9";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            } else {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "10";
                                $caltaxSalry = $taxSalary;
                                $roundtaxSalry = round($caltaxSalry, 2);
                            }
                        }
                    }

                    // if married and divorce without chile
                    // let count married as single

                } else {
                    // single
                    // $countFamily=$user->number_of_child;

                    if ($newSalary > 0 && $newSalary <= 1300000) {
                        $taxSalary = 0;
                        $case = "single1";
                        $caltaxSalry = $taxSalary;
                        $roundtaxSalry = round($caltaxSalry, 2);
                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                        $case = "single2";

                        $caltaxSalry = $taxSalary;
                        $roundtaxSalry = round($caltaxSalry, 2);
                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                        $case = "single3";
                        $caltaxSalry = $taxSalary;
                        $roundtaxSalry = round($caltaxSalry, 2);
                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                        $case = "single4";
                        $caltaxSalry = $taxSalary;
                        $roundtaxSalry = round($caltaxSalry, 2);
                    } else {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                        $case = "single5";
                        $caltaxSalry = $taxSalary;
                        $roundtaxSalry = round($caltaxSalry, 2);
                    }
                }

                // tax allowance 
                $taxTaxXY = $totalXY * 0.2;
                $totalXYUS = $totalXY;
                // $calaloWance = $aloWance;
                // $caltaxaloWance = $taxaloWance;
                $taxAllowanceUS =  $totalXYUS;
                $roundAllowance = round($taxAllowanceUS, 2);
                if ($request->deduction) {
                    $deDuction = $request->deduction;
                    $caldeDuction = $deDuction;
                }
                if ($taxSalary < 0) {
                    $taxSalary = 0;
                }
                // totalAllowance from allowance structure + allowance payslip
                $grosSalary = ($newSalary +  $totalXYUS) -  ($taxSalary + $taxTaxXY);
                $calgrosSalary = $grosSalary;
                $netSalary = $grosSalary - $deDuction;
                $calNetSalary = $netSalary;
                $roundGross = $calgrosSalary;
                $roundNet = $calNetSalary;
            } else {
                $calTotalOT = $totalOt;
                $newSalary = ($baseSalry + $advanMoney +  $totalOt +  $bonus) * $ex_rate;
                // blum calculate exchange rate
                if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                    // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                    if ($user->couple_job != "housewife") {
                        if ($user->number_of_child >= 1) {
                            $countFamily = $user->number_of_child;
                            // if have advance money

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $caltaxSalry = $taxSalary;
                                $case = "001";
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "002";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "003";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "004";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } else {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "005";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            }
                            // $grosSalary=  $taxSalary;
                        } else {
                            // don't have child and couple is not housewife

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "006";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $caltaxSalry = $taxSalary;
                                $case = "007";
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $caltaxSalry = $taxSalary;
                                $case = "008";
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $caltaxSalry = $taxSalary;
                                $case = "009";
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } else {
                                $SalaryhaveTax = $newSalary;
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "010";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            }
                        }
                    } else {
                        // if couple is house wife and have children add 1(wife)
                        if ($user->number_of_child >= 1) {
                            $countFamily = $user->number_of_child + 1;

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "1";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "2";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "3";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "4";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } else {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "5";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            }
                            // $grosSalary=  $taxSalary;
                        } else {
                            $countFamily = 1;
                            // if have advance money

                            if ($newSalary > 0 && $newSalary <= 1300000) {
                                $taxSalary = 0;
                                $case = "6";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                                $case = "7";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                                $case = "8";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                                $case = "9";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            } else {
                                $SalaryhaveTax = $newSalary - (150000 * $countFamily);
                                $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                                $case = "10";
                                $caltaxSalry = $taxSalary;
                                $taxSalryUs = $caltaxSalry / $ex_rate;
                                $roundtaxSalry = round($taxSalryUs, 2);
                            }
                        }
                    }
                } else {
                    // single
                    // $countFamily=$user->number_of_child;

                    if ($newSalary > 0 && $newSalary <= 1300000) {
                        $taxSalary = 0;
                        $case = "single1";
                        $caltaxSalry = $taxSalary;
                        $taxSalryUs = $caltaxSalry / $ex_rate;
                        $roundtaxSalry = round($taxSalryUs, 2);
                    } elseif ($newSalary > 1300000 && $newSalary <= 2000000) {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.05) - 65000;
                        $case = "single2";
                        $caltaxSalry = $taxSalary;
                        $taxSalryUs = $caltaxSalry / $ex_rate;
                        $roundtaxSalry = round($taxSalryUs, 2);
                    } elseif ($newSalary > 2000000 && $newSalary < 8500000) {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.1) - 165000;
                        $case = "single3";
                        // Riel 
                        $caltaxSalry = $taxSalary;
                        $taxSalryUs = $caltaxSalry / $ex_rate;
                        $roundtaxSalry = round($taxSalryUs, 2);
                    } elseif ($newSalary > 8500000 && $newSalary < 12500000) {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.15) - 590000;
                        $case = "single4";
                        $caltaxSalry = $taxSalary;
                        $taxSalryUs = $caltaxSalry / $ex_rate;
                        $roundtaxSalry = round($taxSalryUs, 2);
                    } else {
                        $SalaryhaveTax = $newSalary;
                        $taxSalary = ($SalaryhaveTax * 0.2) - 1215000;
                        $case = "single5";
                        $caltaxSalry = $taxSalary;
                        $taxSalryUs = $caltaxSalry / $ex_rate;
                        $roundtaxSalry = round($taxSalryUs, 2);
                    }
                }
                // allowance in riel
                $taxTaxXY =  $totalXY * $ex_rate * 0.2;

                $totalXYUS = $totalXY * $ex_rate;
                // $calaloWance = $aloWance;  
                // $caltaxaloWance = $taxaloWance;
                $taxAllowanceUS =  $taxTaxXY / $ex_rate;
                // this save into database
                $roundAllowance = round($taxAllowanceUS, 2);
                if ($request->deduction) {
                    $deDuction = $request->deduction * $ex_rate;
                    $caldeDuction = $request->deduction;
                } else {
                    $caldeDuction = 0;
                }
                if ($taxSalary < 0) {
                    $taxSalary = 0;
                }
                // totalAllowance from allowance structure + allowance payslip
                $grosSalary = ($newSalary + $totalXYUS) -  ($taxSalary +  $taxTaxXY);
                // back to original currency
                $calgrosSalary = $grosSalary / $ex_rate;
                $netSalary = $grosSalary - $deDuction;
                $calNetSalary = $netSalary / $ex_rate;
                $roundGross = round($calgrosSalary, 2);
                $roundNet = round($calNetSalary, 2);
            }
            $data->from_date = $request->from_date;
            $data->to_date = $request->to_date;
            $data->user_id = $request->user_id;
            // $data->contract_id=$request->contract_id;
            $data->base_salary = $calBasicSalry;
            $data->advance_salary = $caladvanMoney;
            // $data->senority_salary= $calsenorityMoney;
            $data->bonus = $calbonus;
            $data->allowance =  $totalXY;
            $data->tax_allowance =  $roundAllowance;
            $data->total_ot =  $totalOt;
            $data->tax_salary =  $roundtaxSalry;
            $data->gross_salary = $roundGross;
            $data->deduction =  $caldeDuction;

            $data->net_salary =  $roundNet;
            $data->notes =  $request->notes;
            $data->currency = $request->currency;
            $data->exchange_rate = $ex_rate;
            $query = $data->update();
            return response()->json($query);
            // $query = $data->update();
            //     return response()->json([
            //     // 'currency_stru'=>$currencyStruct,
            //     'from_date'           =>  $request->from_date,
            //     'to_date'           =>  $request->to_date,
            //     'user_id'           =>  $request->user_id,
            //     // 'contract_id'          =>  $request->contract_id,
            //     'base_salary'         =>$calBasicSalry,
            //     'advance_salary'    =>   $caladvanMoney,
            //     // 'senority_salary'    =>  $calsenorityMoney,
            //     'bonus'         => $calbonus,
            //     'allowance'          =>  $totalXY,
            //     'tax_allowance'          =>   $roundAllowance,

            //     'total_ot'         =>  $totalOt,
            //     'tax_salary'         =>  $roundtaxSalry,
            //     'gross_salary'       =>  $roundGross,
            //     'deduction'    =>   $caldeDuction,
            //     'net_salary'    =>   $roundNet,
            //     'notes'    =>   $request->notes,
            //     'currency'    =>    $request->currency,
            //     'exchange_rate'    =>$ex_rate,
            //     "case"=>$case
            // ]);

            // return response()->json( $query );


        }
    }


    public function destroy($id)
    {
        $data = Payslip::find($id);
        if ($data) {
            $data->delete();
            $respone = [
                'message' => 'Success',
                'code' => 0,
            ];
        } else {
            $respone = [
                'message' => 'No payslip id found',
                'code' => -1,

            ];
        }
        return response()->json(
            $respone,
            200
        );
    }
    public function overtimeView()
    {
        return view('admin.payroll.report.overtime_report');
    }
    // overtime report for account
    public function overtimeReport(Request $request, Overtime $customer)
    {
        $date = date('Y-m-d');
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        $start_date = $request->start_date ?? date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
        $end_date = $request->end_date ?? date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
        $status = $request->get('filter_status', '');
        // $limit = $request->get('limit', 10);
        $today = '1';
        $thisWeek = '2';
        $thisMonth = '3';
        $thisYear = '4';
        $lastMonth = '5';
        // return response()->json([
        //     'dateRang'=>$request->id
        // ]);

        if ($request->startDate  == $today && $request->endDate ==  $today) {
            $start_date = $date;
            $end_date = $date;
        } elseif ($request->startDate  == 'yesterday' && $request->endDate ==  'yesterday') {
            $start_date = date('Y-m-d', strtotime($date . '-1 day'));
            $end_date = date('Y-m-d', strtotime($date . '-1 day'));
        } elseif ($request->startDate  == $thisWeek && $request->endDate == $thisWeek) {
            $start_date = date('Y-m-d', strtotime(Carbon::now()->startOfWeek()));
            $end_date = date('Y-m-d', strtotime(Carbon::now()->endOfWeek()));
        }
        // elseif ($request->dateRange == 'last_week') {
        //     $start_date = Carbon::now()->startOfWeek();
        //     $end_date = Carbon::now()->endOfWeek();
        //     $start_date = date('Y-m-d', strtotime($start_date . ' -7 day'));
        //     $end_date = date('Y-m-d', strtotime($end_date . ' -7 day'));
        // } 
        elseif ($request->startDate == $thisMonth && $request->endDate == $thisMonth) {
            $start_date = date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
            $end_date = date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
        } elseif ($request->startDate  == $lastMonth && $request->endDate ==  $lastMonth) {
            $start = new Carbon('first day of last month');
            $end = new Carbon('last day of last month');
            $start_date = date('Y-m-d', strtotime($start->startOfMonth()));
            $end_date = date('Y-m-d', strtotime($end->endOfMonth()));
        } elseif ($request->startDate  == $thisYear && $request->endDate == $thisYear) {
            $start = new Carbon('first day of January ' . date('Y'));
            $end = new Carbon('last day of December ' . date('Y'));
            $start_date = date('Y-m-d', strtotime($start));
            $end_date = date('Y-m-d', strtotime($end));
        } else {
            $start_date = $request->startDate;
            $end_date = $request->endDate;
        }
        $customers = Overtime::select(

            DB::raw('
            
        overtimes.user_id AS user_id,
       
        SUM(overtimes.ot_hour) AS hour,
        SUM(overtimes.total_ot) AS total,
        CONCAT(users.name) AS user_name,
        CONCAT(positions.position_name) AS position_name
        ')
        )
            ->leftJoin('users', 'users.id', '=', 'overtimes.user_id')
            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
            ->where('overtimes.send_status', '=', 'true')->groupBy('user_id');


        if ($request->search && !empty($request->search)) {
            $search = $request->search;
            $customers = $customers
                ->where(function ($query) use ($search) {
                    $query->where('users.id',      'like',     '%' . $search . '%');
                    $query->orWhere('users.name',      'like',     '%' . $search . '%');
                    // $query->orWhere('users.email',      'like',     '%' . $search . '%');
                    // $query->orWhere('customers.age',      'like',     '%'.$search.'%');
                    // $query->orWhere('customers.phone1',      'like',     '%'.$search.'%');
                    // $query->orWhere('customers.phone2',      'like',     '%'.$search.'%');
                    // $query->orWhere('customers.email',      'like',     '%'.$search.'%');
                    // $query->orWhere('customers.fax',      'like',     '%'.$search.'%');
                    // $query->orWhere('customers.address',      'like',     '%'.$search.'%');
                    // $query->orWhere('customers.pob',      'like',     '%'.$search.'%');
                });
            // ->where("customers.deleted", 0);
        }

        $customers =  $customers->whereDate('overtimes.created_at', '>=', date('Y-m-d', strtotime($start_date)))
            ->whereDate('overtimes.created_at', '<=', date('Y-m-d', strtotime($end_date)))->get();;
        $items = $customers;

        if (request()->ajax()) {
            return datatables()->of($items)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function attendanceView()
    {
        return view('admin.payroll.report.attendace_account');
    }
    public function attendaceReport(Request $request, Overtime $customer)
    {
        $date = date('Y-m-d');
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        $start_date = $request->start_date ?? date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
        $end_date = $request->end_date ?? date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
        $status = $request->get('filter_status', '');
        // $limit = $request->get('limit', 10);
        $today = '1';
        $thisWeek = '2';
        $thisMonth = '3';
        $thisYear = '4';
        $lastMonth = '5';


        if ($request->startDate  == $today && $request->endDate ==  $today) {
            $start_date = $date;
            $end_date = $date;
        } elseif ($request->startDate  == 'yesterday' && $request->endDate ==  'yesterday') {
            $start_date = date('Y-m-d', strtotime($date . '-1 day'));
            $end_date = date('Y-m-d', strtotime($date . '-1 day'));
        } elseif ($request->startDate  == $thisWeek && $request->endDate == $thisWeek) {
            $start_date = date('Y-m-d', strtotime(Carbon::now()->startOfWeek()));
            $end_date = date('Y-m-d', strtotime(Carbon::now()->endOfWeek()));
        }
        // elseif ($request->dateRange == 'last_week') {
        //     $start_date = Carbon::now()->startOfWeek();
        //     $end_date = Carbon::now()->endOfWeek();
        //     $start_date = date('Y-m-d', strtotime($start_date . ' -7 day'));
        //     $end_date = date('Y-m-d', strtotime($end_date . ' -7 day'));
        // } 
        elseif ($request->startDate == $thisMonth && $request->endDate == $thisMonth) {
            $start_date = date('Y-m-d', strtotime(Carbon::now()->startOfMonth()));
            $end_date = date('Y-m-d', strtotime(Carbon::now()->endOfMonth()));
        } elseif ($request->startDate  == $lastMonth && $request->endDate ==  $lastMonth) {
            $start = new Carbon('first day of last month');
            $end = new Carbon('last day of last month');
            $start_date = date('Y-m-d', strtotime($start->startOfMonth()));
            $end_date = date('Y-m-d', strtotime($end->endOfMonth()));
        } elseif ($request->startDate  == $thisYear && $request->endDate == $thisYear) {
            $start = new Carbon('first day of January ' . date('Y'));
            $end = new Carbon('last day of December ' . date('Y'));
            $start_date = date('Y-m-d', strtotime($start));
            $end_date = date('Y-m-d', strtotime($end));
        } else {
            $start_date = $request->startDate;
            $end_date = $request->endDate;
        }
        // $checkins = Checkin::select('id','user_id')
        // ->groupBy('user_id')
        // ->get();
        // select one time count all record 
        $customers = Checkin::select(

            DB::raw('
        checkins.user_id AS user_id,
        
        count(checkins.id) AS total_checkin,
        CONCAT(users.name) AS user_name,
        CONCAT(positions.position_name) AS position_name,
        CONCAT(workdays.id) AS workday_id
        
         
       
        ')
        )
            ->leftJoin(
                'users',
                'users.id',
                '=',
                'checkins.user_id'

            )
            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
            // CONCAT(workdays.off_day) AS off_day
            ->leftJoin('workdays', 'workdays.id', '=', 'users.workday_id')

            // ->orWhere('leaves.status', '=', 'pending')
            // ->where('leaves.status', '=', 'approved')
            ->where('checkins.send_status', '=', 'true')->groupBy('user_id');
        // $start_date1 = date('Y/m/d', strtotime($start_date));
        // $end_date1 = date('Y/m/d',  strtotime($end_date));

        // $customers = $customers                ->whereBetween('date', [$start_date1, $end_date1]);
        $customers = $customers->whereDate('checkins.created_at', '>=', date('Y-m-d', strtotime($start_date)))
            ->whereDate('checkins.created_at', '<=', date('Y-m-d', strtotime($end_date)))->get();
        $start_date1 = date('Y/m/d', strtotime($start_date));
        $end_date1 = date('Y/m/d',  strtotime($end_date));

        foreach ($customers as $key => $val) {

            $checkin = ['0'];
            $deduction = 0;
            $ex1 = Leave::where('user_id', $val->user_id)
                ->whereBetween('from_date', [$start_date1, $end_date1])
                ->whereBetween('to_date', [$start_date1, $end_date1])
                // ->orWhere('to_date','=>',"2022/09/30")
                ->get();

            for ($i = 0; $i < count($ex1); $i++) {
                if ($ex1[$i]['leave_deduction']) {
                    $deduction += $ex1[$i]['leave_deduction'];
                } else {
                    $deduction = 0;
                }
            }

            $val->leave_deduction = $deduction;
        }
        $items = $customers;
        // return response()->json(
        //     [
        //         'code' => 0,
        //         'message' => 'Success',
        //         'attendance' => $items['id'],
        //         // 'duration' => $totalPh
        //     ],
        //     200
        // );

        if ($request->search && !empty($request->search)) {
            $search = $request->search;
            $customers = $customers
                ->where(function ($query) use ($search) {
                    $query->where('users.id',      'like',     '%' . $search . '%');
                    $query->orWhere('users.name',      'like',     '%' . $search . '%');
                });
            // ->where("customers.deleted", 0);
        }
        $totalPh = 0;
        $ph = Holiday::whereDate('from_date', '>=', date('Y-m-d', strtotime($start_date)))
            ->whereDate('to_date', '<=', date('Y-m-d', strtotime($end_date)))->get();

        foreach ($items as $key => $v) {
            for ($i = 0; $i < count($ph); $i++) {
                // $totalPh += $ph[$i]['duration'];
                $from_date = date('m/d/Y', strtotime($ph[$i]['from_date']));
                $to_date = date('m/d/Y', strtotime($ph[$i]['to_date']));
                $user = Checkin::where('user_id', '=', $v->user_id)
                    ->where('date', '>=', $from_date)
                    ->where('date', '<=', $to_date)
                    ->count();
                if ($user == 0) {
                    $totalPh = $v['total_checkin'] + $ph[$i]['duration'];
                } else {
                    $totalPh =  $v['total_checkin'];
                }
                $w = Workday::where('id', '=', $v->workday_id)->first();
                $check = "";
                $notCheck1 = $this->getWeekday($from_date);
                $notCheck2 = $this->getWeekday($to_date);

                // if($w->off_day){
                //     $totalPh = $totalPh -1;
                // }
                if ($w->off_day == $notCheck1 || $w->off_day == $notCheck2) {
                    $check = "true";
                    // minus one off day , if user come to work on holiday
                    $totalPh = $totalPh - 1;
                } else {

                    $totalPh = $totalPh;
                    $check = "false";
                }
            }
            $v->new_attendance = $totalPh;
            // $v->not =$notCheck1;
            // $v->not1 =$notCheck2;
            // $v->check = $check;



        }

        if (request()->ajax()) {
            return datatables()->of($items)

                ->addIndexColumn()
                ->make(true);
        }




        
    }
    public function employeeView(){
        $data = User::with('role', 'department', 'position')->whereNotIn('id', [1])->orderBy('created_at', 'ASC')
        ->where('status','=','true')
        ->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->addColumn('image', 'admin.users.image')
                // ->addColumn('image', function ($row) {
                //     $url= 'uploads/employee/rXbGc2oXcxMmzXss8zasrQF59FjSSEs7ENjg4Yjy.jpg';
                //      return '<div>  <img src="uploads/employee/rXbGc2oXcxMmzXss8zasrQF59FjSSEs7ENjg4Yjy.jpg" alt="logo" width="50" ></div>';
                //      })
                ->rawColumns(['action', 'image'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.payroll.report.report_employee');
    }
    function getWeekday($date)
    {
        return date('w', strtotime($date));
    }
}
