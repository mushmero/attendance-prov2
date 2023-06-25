<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Traits\AttendanceTraits;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use AttendanceTraits;
    private $title;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->title = 'Dashboard';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cardCounter = [
            'title' => 'Statistic',
            'theme' => 'default',
            'icon' => 'fas fa-lg fa-chart-line',
        ];

        $callouts = [
            'today' => [
                'theme' => 'info',
                'title' => 'Today',
                'title-class' => 'text-bold',
                'icon' => '',
                'class' => '',
                'value' => '<span class="text-success text-bold">Normal</span> : <span id="today-normal" class="h4">0</span><br> <span class="text-danger text-bold">Late</span> : <span id="today-late" class="h4">0</span>',
            ],
            'week' => [
                'theme' => 'info',
                'title' => 'Weekly',
                'title-class' => 'text-bold',
                'icon' => '',
                'class' => '',
                'value' => '<span class="text-success text-bold">Normal</span> : <span id="weekly-normal" class="h4">0</span><br> <span class="text-danger text-bold">Late</span> : <span id="weekly-late" class="h4">0</span>',
            ],
            'month' => [
                'theme' => 'info',
                'title' => 'Monthly',
                'title-class' => 'text-bold',
                'icon' => '',
                'class' => '',
                'value' => '<span class="text-success text-bold">Normal</span> : <span id="monthly-normal" class="h4">0</span><br> <span class="text-danger text-bold">Late</span> : <span id="monthly-late" class="h4">0</span>',
            ],
            'year' => [
                'theme' => 'info',
                'title' => 'Year To Date',
                'title-class' => 'text-bold',
                'icon' => '',
                'class' => '',
                'value' => '<span class="text-success text-bold">Normal</span> : <span id="yearly-normal" class="h4">0</span><br> <span class="text-danger text-bold">Late</span> : <span id="yearly-late" class="h4">0</span>',
            ],
        ];

        $range = [
            'Daily',
            'Monthly',
            'Yearly',
            'Custom',
        ];

        return view('home', [
            'title' => $this->title,
            'statCard' => $cardCounter,
            'callouts' => $callouts,
            'range' => $range,
        ]);
    }

    public function getData(Request $request)
    {
        $option = $request->get('option');
        $result = [];
        $normal = $this->getAttendance(['status' => 'Normal']);
        $late = $this->getAttendance(['status' => 'Late']);;
        if($option == 'Today'){
            $result = [
                'normal' => $normal->whereDate('created_at',Carbon::today()->toDateString())->count(),
                'late' => $late->whereDate('created_at',Carbon::today()->toDateString())->count(),
            ];
        }else if($option == 'Weekly'){
            $result = [
                'normal' => $normal->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->count(),
                'late' => $late->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->count(),
            ];
        }else if($option == 'Monthly'){
            $result = [
                'normal' => $normal->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])->count(),
                'late' => $late->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])->count(),
            ];
        }else if($option == 'Yearly'){
            $result = [
                'normal' => $normal->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->startOfYear()->toDateString(), Carbon::now()->endOfYear()->toDateString()])->count(),
                'late' => $late->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->startOfYear()->toDateString(), Carbon::now()->endOfYear()->toDateString()])->count(),
            ];
        }
        return response()->json(['result' => $result]);
    }

    public function getChartData(Request $request)
    {
        $filter = $request->get('filter') ? $request->get('filter') : 'Daily';
        $dateFrom = $request->get('fromDate');
        $dateTo = $request->get('toDate');
        $result = [];
        $status = false;
        $all = $this->getAttendance();
        if($filter == 'Daily'){
            $data = $all->groupBy(function($row){
                        return Carbon::parse($row->created_at)->format('l');
                    })
                    ->map(function($status){
                        return [
                            'Normal' => $status->where('status', 'Normal'),
                            'Late' => $status->where('status', 'Late'),
                        ];
                    });
            $result['data'] = Helper::countVal($data);
            $result['label'] = Helper::convertToLabel($data);
            $status = true;
        }else if($filter == 'Monthly'){
            $data = $all->groupBy(function($row){
                        return Carbon::parse($row->created_at)->format('F');
                    })
                    ->map(function($status){
                        return [
                            'Normal' => $status->where('status', 'Normal'),
                            'Late' => $status->where('status', 'Late'),
                        ];
                    });
            $result['data'] = Helper::countVal($data);
            $result['label'] = Helper::convertToLabel($data);
            $status = true;
        }else if($filter == 'Yearly'){
            $data = $all->groupBy(function($row){
                        return Carbon::parse($row->created_at)->format('Y');
                    })
                    ->map(function($status){
                        return [
                            'Normal' => $status->where('status', 'Normal'),
                            'Late' => $status->where('status', 'Late'),
                        ];
                    });
            $result['data'] = Helper::countVal($data);
            $result['label'] = Helper::convertToLabel($data);
            $status = true;            
        }else if($filter == 'Custom'){
            if($dateFrom != '' && $dateTo != ''){
                $data = $all
                        ->whereBetween('created_at', [Carbon::parse($dateFrom.' 00:00:00')->toDateTimeString(), Carbon::parse($dateTo.' 23:59:59')->toDateTimeString()])
                        ->groupBy(function($row){
                            return Carbon::parse($row->created_at)->format('Y-m-d');
                        })
                        ->map(function($status){
                            return [
                                'Normal' => $status->where('status', 'Normal'),
                                'Late' => $status->where('status', 'Late'),
                            ];
                        });
                $result['data'] = Helper::countVal($data);
                $label = count($data) > 0 ? $data : [$dateFrom => $dateFrom, $dateTo => $dateTo];
                $result['label'] = Helper::convertToLabel($label);
                $status = true;
            }
        }
        // dd($filter, $result);
        return response()->json(['status' => $status, 'result' => $result]);
    }
}
