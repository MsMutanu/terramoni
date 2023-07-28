<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class AgentsDashboardController extends Controller
{
    //



    public function dashboard()
    {

        $currentHour = Carbon::now()->hour;
        $salutation = '';

        if ($currentHour >= 0 && $currentHour < 12) {
            $salutation = 'Good Morning';
        } elseif ($currentHour >= 12 && $currentHour < 18) {
            $salutation = 'Good Afternoon';
        } else {
            $salutation = 'Good Evening';
        }

        //$transactions = DB::table('tbl_transactions')->get();
        $POSCount = DB::table('tbl_pos_terminals')->count();
        $transactions = DB::table('tbl_transactions')
                ->orderBy('Id', 'desc')
                ->get();

        $POSCount= $agentCount="0";
        $mobile=Auth::user()->mobile_no;
        $agent_id = DB::table('tbl_agents')->where('phone', $mobile)->value('id');
        $POSCount = DB::table('tbl_pos_terminals')->where('agent_id', $agent_id)->count();
       // return $agent_id;


        $data = [
            'salutation' => $salutation,// salutations
            'agentCount' => $agentCount,// counts number of agents
            'POSCount' => $POSCount,// counts number of POS
            'transactions' => $transactions,// Transactions lists
            // Add more data to the array as needed
        ];

        return view ('agents_portal.home')->with($data);




    }

    public function blank()
    {
        return view ('agents_portal.underconstraction');
    }

    public function allocatedterminals()
    {

        $mobile=Auth::user()->mobile_no;
        $agent_id = DB::table('tbl_agents')->where('phone', $mobile)->value('id');

      //  return $agent_id;

         // Select all records from tbl_pos_terminals where agent_id matches $agent_id
         $pos_terminals = DB::table('tbl_pos_terminals')->where('agent_id', $agent_id)->get();

         // Now you can use the $posTerminals variable as needed
         // For example, you can loop through the records or return them as a JSON response
        // return response()->json($pos_terminals);

         return view ('agents_portal.posstable', compact('pos_terminals'));

    }

}