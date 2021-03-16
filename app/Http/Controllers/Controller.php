<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct(){
        // config(['app.timezone' => setting('timezone')]);
    }

    /**
     * @param $result
     * @param $message
     * @return mixed
     */
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    /**
     * @param $error
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    public function showRequests(){
        return view('layouts.submenu.requests')->with("applicants" , Applicant::all());
    }

    public function acceptRequest($id){
        $applicant = Applicant::find($id);
        $user = new User();
        $user->name =  $applicant->name;
        $user->email =  $applicant->email;
        $user->password = $applicant->password;
        $user->api_token = str_random(60);
        $user->save();
        $user->assignRole('cashier');
        $applicant->delete();
    }
    public function showUsers(){
        //$users = User::where('rule_id','!=',1)->get();  // get all none admins users
        $users = User::all();
        return view('layouts.submenu.users')->with(["users"=>$users]);
    }
    public function records(){
        return view('layouts.submenu.records');
    }
   /* public function dailyReports(){
        $reports = DayReport::all();  // all daily reports
        return view('layouts.submenu.dailyreports')->with(['reports'=>$reports]);
    }*/
    
    /*public function printPDF($id){
        set_time_limit(1000);
        $report = DayReport::find($id);
       // $pdf = PDF::loadView('layouts.dailyreportpdf',['report'=>$report]);
       // return $pdf->download('invoice.pdf');
        //$path = public_path().'/pdf';
        $path = public_path();
        $pdf = PDF::loadView('layouts.dailyreportpdf',['report'=>$report]);
        $pdf->save($path.'/my_pdf.pdf', 'UTF-8');
        return response()->download($path.'/my_pdf.pdf');
    }*/

    /*public function showMap(){
        return view('layouts.submenu.map');
    } */

    public function makeAdmin($id){
        $user = User::find($id);
        $role = array('admin');
        $user->assignRole($role);
        return back()->with('success','تم منح سماحية المشرف بنجاح');
    }
    public function makeCashier($id){
        $user = User::find($id);
        $role = array('cashier');
        $user->assignRole($role);
        return back()->with('success','تم منح سماحية الكاشير بنجاح');
    }
    public function makeDriver($id){
        $user = User::find($id);
        $role = array('driver');
        $user->assignRole($role);
        return back()->with('success','تم منح سماحية السائق بنجاح');
    }
    public function makeClient($id){
        $user = User::find($id);
        $role = array('client');
        $user->assignRole($role);
        return back()->with('success','تم منح سماحية الزبون بنجاح');
    }

}
