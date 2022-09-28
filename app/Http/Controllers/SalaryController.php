<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has alpready been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];
    public function index()
    {
        $data = Salary::with('user')->orderBy('created_at', 'DESC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        // return response()->json(
        //     [
        //         'data'=>$data
        //     ]
        // );
        return view('admin.salary.index');
    }


    public function create()
    {

        $user = User::whereNotIn('id', [1])->get();
        return view('admin.salary.create', compact('user'));
    }


    public function store(Request $request)
    {
        request()->validate([
            'user_id' => "required",
            'monthly' => "required",
            'currency'=>"required",
            'base_salary' => "required"
        ], $this->customMessages);
        $user = User::find($request->user_id);


        $totalOt = 0;
        $aloWance = 0;
        $taxaloWance = 0;
        $deDuction = 0;
        $salaryInc = 0;
        $otHour = 0;
        $otMethod = 0;
        $otRate = 0;
        $grosSalary = 0;
        $bonus = 0;
        $senorityMoney = 0;
        $advanMoney = 0;
        $taxSalary = 0;
        $countFamily = 0;
        $SalaryhaveTax = 0;
        $newSalary = 0;
        $case = "";

        $ex_rate = 0;
        $calNetSalary = 0;
        $calbonus = 0;
        $calOTRate = 0;
        $calTotalOT = 0;
        $calsenorityMoney = 0;
        $caladvanMoney = 0;
        $caltaxaloWance = 0;
        $caldeDuction = 0;
        $calaloWance = 0;
        $calgrosSalary = 0;
        $caltaxSalry = 0;
        $baseSalry = 0;
        $calBasicSalry = 0;
        $roundGross = 0;
        $roundNet = 0;
        $taxAllowanceUS = 0;
        $roundAllowance = 0;
        $roundtaxSalry = 0;
        $taxSalryUs = 0;

        if ($request->currency == "riel") {
            if ($request->bonus) {
                $bonus = $request->bonus;
                $calbonus = $bonus;
            } else {
                $bonus = 0;
                $calbonus = 0;
            }
            if ($request->senority_salary) {
                $senorityMoney = $request->senority_salary;
                $calsenorityMoney = $senorityMoney;
            } else {
                $senorityMoney = 0;
                $calsenorityMoney = 0;
            }
            if ($request->advance_salary) {
                $advanMoney = $request->advance_salary;
                $caladvanMoney = $advanMoney;
            } else {
                $advanMoney = 0;
                $caladvanMoney = 0;
            }
            if ($request->ot_hour && $request->ot_method && $request->ot_rate) {
                $otHour = $request->ot_hour;
                $otMethod = $request->ot_method;
                $otRate = $request->ot_rate;

                $totalOt = $request->ot_hour * $request->ot_method * $request->ot_rate;
                $calOTRate = $otRate;
                $calTotalOT = $totalOt;
            } else {

                $totalOt = 0;

                $otHour = 0;
                $otMethod = 0;
                $otRate = 0;
                $calTotalOT = 0;
                $calOTRate = 0;
            }

            if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                if ($user->couple_job != "housewife") {
                    if ($user->number_of_child >= 1) {
                        $countFamily = $user->number_of_child;
                        // if have advance money
                        if ($advanMoney > 0) {
                            $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                        } else {
                            $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                        }
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
                        if ($advanMoney > 0) {
                            $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                        } else {
                            $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                        }
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
                        if ($advanMoney > 0) {
                            $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                        } else {
                            $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                        }
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
                        if ($advanMoney > 0) {
                            $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                        } else {
                            $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                        }
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
                if ($advanMoney > 0) {
                    $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                } else {
                    $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                }
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

            if ($request->allowance && $request->deduction) {
                // bonus have 20% tax
                $taxaloWance = $request->allowance * 0.2;
                $aloWance = $request->allowance;
                $deDuction = $request->deduction;

                $calaloWance = $aloWance;
                $caldeDuction = $deDuction;
                $taxAllowanceUS = $taxaloWance;
                $roundAllowance = round($taxAllowanceUS, 2);
            } else {
                if ($request->allowance) {
                    $taxaloWance = $request->allowance * 0.2;
                    $aloWance = $request->allowance;
                    $calaloWance = $aloWance;
                    $caltaxaloWance = $taxaloWance;
                    $taxAllowanceUS = $taxaloWance;
                    $roundAllowance = round($taxAllowanceUS, 2);
                }
                if ($request->deduction) {
                    $deDuction = $request->deduction;
                    $caldeDuction = $deDuction;
                }
            }
            $grosSalary = ($newSalary + $aloWance) -  ($taxSalary + $taxaloWance);
            $calgrosSalary = $grosSalary;
            $netSalary = $grosSalary - $deDuction;
            $calNetSalary = $netSalary;
            $roundGross = $calgrosSalary;
            $roundNet = $calNetSalary;
        } else {
            // if exchange rate is usd
            if ($request->exchange_rate) {
                $ex_rate = $request->exchange_rate;
            } else {
                $ex_rate = 4000;
            }

            // dollar 

            if ($request->bonus) {
                $bonus = $request->bonus;
                $calbonus = $bonus;
            } else {
                $bonus = 0;
                $calbonus = 0;
            }
            if ($request->senority_salary) {
                $senorityMoney = $request->senority_salary;
                $calsenorityMoney = $senorityMoney;
            } else {
                $senorityMoney = 0;
                $calsenorityMoney = 0;
            }
            if ($request->advance_salary) {
                $advanMoney = $request->advance_salary;
                $caladvanMoney = $advanMoney;
            } else {
                $advanMoney = 0;
                $caladvanMoney = 0;
            }
            if ($request->ot_hour && $request->ot_method && $request->ot_rate) {
                $otHour = $request->ot_hour;
                $otMethod = $request->ot_method;
                $otRate = $request->ot_rate;

                $totalOt = $request->ot_hour * $request->ot_method * $request->ot_rate;
                $calOTRate = $otRate;
                $calTotalOT = $totalOt;
            } else {

                $totalOt = 0;
                $otHour = 0;
                $otMethod = 0;
                $otRate = 0;
                $calOTRate = 0;
                $calTotalOT = 0;
            }

            if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                if ($user->couple_job != "housewife") {
                    if ($user->number_of_child >= 1) {
                        $countFamily = $user->number_of_child;
                        // if have advance money
                        if ($advanMoney > 0) {
                            $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        } else {
                            $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        }
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
                        if ($advanMoney > 0) {
                            $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        } else {
                            $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        }
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
                        // if have advance money
                        if ($advanMoney > 0) {
                            $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        } else {
                            $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        }
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
                        if ($advanMoney > 0) {
                            $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        } else {
                            $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        }
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

                // if married and divorce without chile
                // let count married as single

            } else {
                // single
                // $countFamily=$user->number_of_child;
                if ($advanMoney > 0) {
                    $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                } else {
                    $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                }
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

            if ($request->allowance && $request->deduction) {
                // bonus have 20% tax
                $taxaloWance = ($request->allowance) * $ex_rate * 0.2;
                $taxAllowanceUS = $taxaloWance / $ex_rate;
                $roundAllowance = round($taxAllowanceUS, 2);
                $aloWance = ($request->allowance) * $ex_rate;

                //    for calculate with groos salry because groos seperate to riel
                $deDuction = ($request->deduction) * $ex_rate;
                $calaloWance = $request->allowance;
                $caldeDuction = $request->deduction;
            } else {
                if ($request->allowance) {
                    $taxaloWance = ($request->allowance) * $ex_rate * 0.2;

                    $aloWance = ($request->allowance) * $ex_rate;
                    $calaloWance = $request->allowance;
                    $caltaxaloWance = $taxaloWance;
                    $taxAllowanceUS = $taxaloWance / $ex_rate;
                    $roundAllowance = round($taxAllowanceUS, 2);
                }
                if ($request->deduction) {
                    $deDuction = ($request->deduction) * $ex_rate;
                    $caldeDuction = $request->deduction;
                }
            }
            $grosSalary = ($newSalary + $aloWance) -  ($taxSalary + $taxaloWance);
            $calgrosSalary = $grosSalary / $ex_rate;

            $netSalary = $grosSalary - $deDuction;
            $calNetSalary = $netSalary / $ex_rate;
            $roundGross = round($calgrosSalary, 2);
            $roundNet = round($calNetSalary, 2);
        }


        if ($request->currency == "riel") {
            $baseSalry = $request->base_salary;
            $calBasicSalry = $baseSalry;
        } else {
            if ($request->exchange_rate) {
                $baseSalry = ($request->base_salary) * $request->exchange_rate;
                $calBasicSalry = $baseSalry / $request->exchange_rate;
            } else {
                $baseSalry = ($request->base_salary) * 4000;
                $calBasicSalry = $baseSalry / 4000;
            }
        }

        // return response()->json(
        //     [
        //         'case'=>$case,
        //         'new_salary'=>$newSalary,
        //         'tax_salar'=>$roundtaxSalry
        //     ]
        // );

        $data = Salary::create([
            'user_id'           =>  $request->user_id,
            'monthly'          =>  $request->monthly,
            'base_salary'         => $calBasicSalry,
            'advance_salary'    =>   $caladvanMoney,
            'senority_salary'    =>  $calsenorityMoney,
            'bonus'         => $calbonus,
            'allowance'          =>  $calaloWance,
            'tax_allowance'          =>   $roundAllowance,
            'ot_rate'         =>  $calOTRate,
            'ot_hour'    =>   $otHour,
            'ot_method'         =>  $otMethod,
            'total_ot'         =>   $calTotalOT,
            'tax_salary'         =>  $roundtaxSalry,
            'gross_salary'       =>  $roundGross,
            'deduction'    =>   $caldeDuction,
            'net_salary'    =>   $roundNet,
            'notes'    =>   $request->notes,
            'currency'    =>    $request->currency,
            'exchange_rate'    =>$ex_rate,
        ]);
        return response()->json($data);
        // return redirect()->back()->with('message', "One record has been created successfully!");
        // session()->flash('message', "One record has been created successfully!");
        // if ($data) {
        //     return redirect()->back()->with('message', "One record has been created successfully!");
        // } else {
        //     return redirect()->back()->with('error', "Something went wrong!");
        // }
    }
    function calCulatePayrool($latitude1, $longitude1, $latitude2, $longitude2, $unit)
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        switch ($unit) {
            case 'miles':
                break;
            case 'kilometers':
                $distance = $distance * 1.609344;
        }
        return (round($distance, 2));
    }


    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $data=Salary::find($id);
        $user = User::whereNotIn('id', [1])->get();


        return view('admin.salary.edit', compact('data','user'));
    }

   
    public function update(Request $request, $id)
    {
        $data = Salary::find($id);
        request()->validate([
            'user_id' => "required",
            'monthly' => "required",
            'currency'=>"required",
            'base_salary' => "required"
        ], $this->customMessages);
        if($data){
            $totalOt = 0;
            $aloWance = 0;
            $taxaloWance = 0;
            $deDuction = 0;
            $salaryInc = 0;
            $otHour = 0;
            $otMethod = 0;
            $otRate = 0;
            $grosSalary = 0;
            $bonus = 0;
            $senorityMoney = 0;
            $advanMoney = 0;
            $taxSalary = 0;
            $countFamily = 0;
            $SalaryhaveTax = 0;
            $newSalary = 0;
            $case = "";
    
            $ex_rate = 0;
            $calNetSalary = 0;
            $calbonus = 0;
            $calOTRate = 0;
            $calTotalOT = 0;
            $calsenorityMoney = 0;
            $caladvanMoney = 0;
            $caltaxaloWance = 0;
            $caldeDuction = 0;
            $calaloWance = 0;
            $calgrosSalary = 0;
            $caltaxSalry = 0;
            $baseSalry = 0;
            $calBasicSalry = 0;
            $roundGross = 0;
            $roundNet = 0;
            $taxAllowanceUS = 0;
            $roundAllowance = 0;
            $roundtaxSalry = 0;
            $taxSalryUs = 0;
            request()->validate([
                'user_id' => "required",
                'monthly' => "required",
                'currency'=>"required",
                'base_salary' => "required"
            ], $this->customMessages);
            $user = User::find($request->user_id);
            $data = Salary::find($id);
            if($data){
                $totalOt = 0;
                $aloWance = 0;
                $taxaloWance = 0;
                $deDuction = 0;
                $salaryInc = 0;
                $otHour = 0;
                $otMethod = 0;
                $otRate = 0;
                $grosSalary = 0;
                $bonus = 0;
                $senorityMoney = 0;
                $advanMoney = 0;
                $taxSalary = 0;
                $countFamily = 0;
                $SalaryhaveTax = 0;
                $newSalary = 0;
                $case = "";
        
                $ex_rate = 0;
                $calNetSalary = 0;
                $calbonus = 0;
                $calOTRate = 0;
                $calTotalOT = 0;
                $calsenorityMoney = 0;
                $caladvanMoney = 0;
                $caltaxaloWance = 0;
                $caldeDuction = 0;
                $calaloWance = 0;
                $calgrosSalary = 0;
                $caltaxSalry = 0;
                $baseSalry = 0;
                $calBasicSalry = 0;
                $roundGross = 0;
                $roundNet = 0;
                $taxAllowanceUS = 0;
                $roundAllowance = 0;
                $roundtaxSalry = 0;
                $taxSalryUs = 0;
                if ($request->currency == "riel") {
                    if ($request->bonus) {
                        $bonus = $request->bonus;
                        $calbonus = $bonus;
                    } else {
                        $bonus = 0;
                        $calbonus = 0;
                    }
                    if ($request->senority_salary) {
                        $senorityMoney = $request->senority_salary;
                        $calsenorityMoney = $senorityMoney;
                    } else {
                        $senorityMoney = 0;
                        $calsenorityMoney = 0;
                    }
                    if ($request->advance_salary) {
                        $advanMoney = $request->advance_salary;
                        $caladvanMoney = $advanMoney;
                    } else {
                        $advanMoney = 0;
                        $caladvanMoney = 0;
                    }
                    if ($request->ot_hour && $request->ot_method && $request->ot_rate) {
                        $otHour = $request->ot_hour;
                        $otMethod = $request->ot_method;
                        $otRate = $request->ot_rate;
        
                        $totalOt = $request->ot_hour * $request->ot_method * $request->ot_rate;
                        $calOTRate = $otRate;
                        $calTotalOT = $totalOt;
                    } else {
        
                        $totalOt = 0;
        
                        $otHour = 0;
                        $otMethod = 0;
                        $otRate = 0;
                        $calTotalOT = 0;
                        $calOTRate = 0;
                    }
        
                    if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                        // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                        if ($user->couple_job != "housewife") {
                            if ($user->number_of_child >= 1) {
                                $countFamily = $user->number_of_child;
                                // if have advance money
                                if ($advanMoney > 0) {
                                    $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                                } else {
                                    $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                                }
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
                                if ($advanMoney > 0) {
                                    $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                                } else {
                                    $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                                }
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
                                if ($advanMoney > 0) {
                                    $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                                } else {
                                    $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                                }
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
                                if ($advanMoney > 0) {
                                    $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                                } else {
                                    $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                                }
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
                        if ($advanMoney > 0) {
                            $newSalary = $request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus;
                        } else {
                            $newSalary = $request->base_salary + $totalOt + $senorityMoney + $bonus;
                        }
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
        
                    if ($request->allowance && $request->deduction) {
                        // bonus have 20% tax
                        $taxaloWance = $request->allowance * 0.2;
                        $aloWance = $request->allowance;
                        $deDuction = $request->deduction;
        
                        $calaloWance = $aloWance;
                        $caldeDuction = $deDuction;
                        $taxAllowanceUS = $taxaloWance;
                        $roundAllowance = round($taxAllowanceUS, 2);
                    } else {
                        if ($request->allowance) {
                            $taxaloWance = $request->allowance * 0.2;
                            $aloWance = $request->allowance;
                            $calaloWance = $aloWance;
                            $caltaxaloWance = $taxaloWance;
                            $taxAllowanceUS = $taxaloWance;
                            $roundAllowance = round($taxAllowanceUS, 2);
                        }
                        if ($request->deduction) {
                            $deDuction = $request->deduction;
                            $caldeDuction = $deDuction;
                        }
                    }
                    $grosSalary = ($newSalary + $aloWance) -  ($taxSalary + $taxaloWance);
                    $calgrosSalary = $grosSalary;
                    $netSalary = $grosSalary - $deDuction;
                    $calNetSalary = $netSalary;
                    $roundGross = $calgrosSalary;
                    $roundNet = $calNetSalary;
                } else {
                    // if exchange rate is usd
                    if ($request->exchange_rate) {
                        $ex_rate = $request->exchange_rate;
                    } else {
                        $ex_rate = 4000;
                    }
        
                    // dollar 
        
                    if ($request->bonus) {
                        $bonus = $request->bonus;
                        $calbonus = $bonus;
                    } else {
                        $bonus = 0;
                        $calbonus = 0;
                    }
                    if ($request->senority_salary) {
                        $senorityMoney = $request->senority_salary;
                        $calsenorityMoney = $senorityMoney;
                    } else {
                        $senorityMoney = 0;
                        $calsenorityMoney = 0;
                    }
                    if ($request->advance_salary) {
                        $advanMoney = $request->advance_salary;
                        $caladvanMoney = $advanMoney;
                    } else {
                        $advanMoney = 0;
                        $caladvanMoney = 0;
                    }
                    if ($request->ot_hour && $request->ot_method && $request->ot_rate) {
                        $otHour = $request->ot_hour;
                        $otMethod = $request->ot_method;
                        $otRate = $request->ot_rate;
        
                        $totalOt = $request->ot_hour * $request->ot_method * $request->ot_rate;
                        $calOTRate = $otRate;
                        $calTotalOT = $totalOt;
                    } else {
        
                        $totalOt = 0;
                        $otHour = 0;
                        $otMethod = 0;
                        $otRate = 0;
                        $calOTRate = 0;
                        $calTotalOT = 0;
                    }
        
                    if ($user->merital_status == "married" && ($user->couple_job == "housewife" || $user->couple_job != "housewife")) {
                        // child under 14 or still study until 25 , get reduce tax on father or mother's salary
                        if ($user->couple_job != "housewife") {
                            if ($user->number_of_child >= 1) {
                                $countFamily = $user->number_of_child;
                                // if have advance money
                                if ($advanMoney > 0) {
                                    $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                } else {
                                    $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                }
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
                                if ($advanMoney > 0) {
                                    $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                } else {
                                    $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                }
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
                                // if have advance money
                                if ($advanMoney > 0) {
                                    $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                } else {
                                    $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                }
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
                                if ($advanMoney > 0) {
                                    $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                } else {
                                    $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                                }
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
        
                        // if married and divorce without chile
                        // let count married as single
        
                    } else {
                        // single
                        // $countFamily=$user->number_of_child;
                        if ($advanMoney > 0) {
                            $newSalary = ($request->base_salary + $advanMoney + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        } else {
                            $newSalary = ($request->base_salary + $totalOt + $senorityMoney + $bonus) * $ex_rate;
                        }
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
        
                    if ($request->allowance && $request->deduction) {
                        // bonus have 20% tax
                        $taxaloWance = ($request->allowance) * $ex_rate * 0.2;
                        $taxAllowanceUS = $taxaloWance / $ex_rate;
                        $roundAllowance = round($taxAllowanceUS, 2);
                        $aloWance = ($request->allowance) * $ex_rate;
        
                        //    for calculate with groos salry because groos seperate to riel
                        $deDuction = ($request->deduction) * $ex_rate;
                        $calaloWance = $request->allowance;
                        $caldeDuction = $request->deduction;
                    } else {
                        if ($request->allowance) {
                            $taxaloWance = ($request->allowance) * $ex_rate * 0.2;
        
                            $aloWance = ($request->allowance) * $ex_rate;
                            $calaloWance = $request->allowance;
                            $caltaxaloWance = $taxaloWance;
                            $taxAllowanceUS = $taxaloWance / $ex_rate;
                            $roundAllowance = round($taxAllowanceUS, 2);
                        }
                        if ($request->deduction) {
                            $deDuction = ($request->deduction) * $ex_rate;
                            $caldeDuction = $request->deduction;
                        }
                    }
                    $grosSalary = ($newSalary + $aloWance) -  ($taxSalary + $taxaloWance);
                    $calgrosSalary = $grosSalary / $ex_rate;
        
                    $netSalary = $grosSalary - $deDuction;
                    $calNetSalary = $netSalary / $ex_rate;
                    $roundGross = round($calgrosSalary, 2);
                    $roundNet = round($calNetSalary, 2);
                }
        
        
                if ($request->currency == "riel") {
                    $baseSalry = $request->base_salary;
                    $calBasicSalry = $baseSalry;
                } else {
                    if ($request->exchange_rate) {
                        $baseSalry = ($request->base_salary) * $request->exchange_rate;
                        $calBasicSalry = $baseSalry / $request->exchange_rate;
                    } else {
                        $baseSalry = ($request->base_salary) * 4000;
                        $calBasicSalry = $baseSalry / 4000;
                    }
                }
                $data->user_id=$request->user_id;
                $data->monthly=$request->monthly;
                $data->base_salary=$calBasicSalry;
                $data->advance_salary=$caladvanMoney;
                $data->senority_salary=$calsenorityMoney;
                $data->bonus=$calbonus;
                $data->allowance=$calaloWance;
                $data->tax_allowance=$roundAllowance;
                $data->ot_rate= $calOTRate;
                $data->ot_hour=$otHour;
                $data->ot_method=$otMethod;
                $data->total_ot=$calTotalOT;
                $data->tax_salary=$roundtaxSalry;
    
                $data->gross_salary= $roundGross;
                $data->deduction=$caldeDuction;
                $data->net_salary= $roundNet;
                $data->notes=$request->notes;  
                $data->currency=$request->currency;
                $data->exchange_rate=$ex_rate;
    
    
                $query = $data->update();
                return response()->json($query);
                // return redirect()->back()->with('success', "Something went wrong!");
        
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Salary::find($id);
        if ($data) {
            $data->delete();
                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                ];
        }
        return response(
            $respone,
            200
        );
    }
}
