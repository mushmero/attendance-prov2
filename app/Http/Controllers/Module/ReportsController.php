<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    protected $title;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->title = 'Reports';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = array();
        $filter = $request->get('filter') ? $request->get('filter') : 'All';
        $dateFrom = $request->get('fromDate');
        $dateTo = $request->get('toDate');

        $array = $this->processData($filter, $dateFrom, $dateTo);

        $heads = [
            'Date',
            'Staff No',
            'Clock In',
            'Clock Out',
            'Status',
        ];

        foreach($array as $arr){
            $data[] = array(
                Carbon::parse($arr->created_at)->format('d/m/Y'),
                $arr->people_no,
                $arr->clock_in ? Carbon::parse($arr->clock_in)->format('d/m/Y h:i:s A') : '',
                $arr->clock_out ? Carbon::parse($arr->clock_out)->format('d/m/Y h:i:s A') : '',
                $arr->status,
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'desc']],
            'columns' => [null, null, null, null, null],
        ];

        $range = [
            'All',
            'Today',
            'Custom',
        ];
        
        $configDateFilter = [
            'format' => 'YYYY-MM-DD',
            'endDate' => 'js:moment().format("YYYY-MM-DD")',
            'maxDate' => 'js:moment().format("YYYY-MM-DD")',
        ];

        return view('modules.reports.list', [
            'heads' => $heads,
            'config' => $config,
            'range' => $range,
            'configDateFilter' => $configDateFilter,
            'title' => $this->title,
            'table_title' => $this->title.' List',
        ]);
    }

    function processData($filter, $dateFrom = '', $dateTo = '')
    {
        $array = [];

        if(!empty($dateFrom) && !empty($dateTo)){
            if($filter == 'Custom'){
                $array = Attendance::whereBetween(DB::raw('DATE(created_at)'), [Carbon::parse($dateFrom)->toDateString(), Carbon::parse($dateTo)->toDateString()])->get();
            }
        }else{
            if($filter == 'All'){
                $array = Attendance::all()->sortByDesc('created_at');
            }else if($filter == 'Today'){
                $array = Attendance::whereDate('created_at',Carbon::today()->toDateString())->orderBy('created_at')->get();
            }
        }

        return $array;
    }

    public function export(Request $request)
    {
        $filter = $request->get('filter') ? $request->get('filter') : 'All';
        $dateFrom = $request->get('fromDate');
        $dateTo = $request->get('toDate');

        $array = $this->processData($filter, $dateFrom, $dateTo);
        dd($array);
    }
}