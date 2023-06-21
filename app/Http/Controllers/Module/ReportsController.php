<?php

namespace App\Http\Controllers\Module;

use App\Exports\GeneralExportArray;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    protected $title, $headers, $exportFormat, $writerType;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->title = 'Reports';
        $this->headers = [
            'Date',
            'Staff No',
            'Clock In',
            'Clock Out',
            'Status',
        ];
        $this->exportFormat = 'xlsx';
        $this->writerType = \Maatwebsite\Excel\Excel::XLSX;
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

        $heads = $this->headers;

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

        $result = $this->processData($filter, $dateFrom, $dateTo);

        if($result->count() > 0){
            $header = $this->headers;
    
            $data = [];
    
            foreach($result as $arr){
                $data[] = array(
                    Carbon::parse($arr->created_at)->format('d/m/Y'),
                    $arr->people_no,
                    $arr->clock_in ? Carbon::parse($arr->clock_in)->format('d/m/Y h:i:s A') : '',
                    $arr->clock_out ? Carbon::parse($arr->clock_out)->format('d/m/Y h:i:s A') : '',
                    $arr->status,
                );
            }
    
            if($filter == 'Custom'){
                $appendFilename = $filter.'_'.$dateFrom.'-'.$dateTo;
            }else{
                $appendFilename = $filter;
            }
    
            $filename = 'Attendance_'.$this->title.'_'.$appendFilename.'_'.Carbon::now()->format('Ymd').'_'.Carbon::now()->format('his').'.'.$this->exportFormat;
    
            return new GeneralExportArray($header, $data, $filename, $this->writerType);
        }else{
            return response()->json(['status' => false, 'message' => 'No content found. Unable to export']);
        }
    }
}
