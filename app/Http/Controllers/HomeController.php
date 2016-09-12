<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function show(Request $request)
    {
        // DB columns array
        $columns=array(
            "",
            "id",
            "name",
            "description",
            "address",
            "lat",
            "lng"
        );

        // local variables for POST variables for searching columns
        $id="";
        $name="";
        $description="";
        $address="";
        $lat="";
        $lng="";

        // Assigning POST values to local variables

        if($request->has('id') && $request->get('id')!=null)
            $id=trim($request->get('id'));

        if($request->has('name') && $request->get('name')!=null)
            $name=trim($request->get('name'));

        if($request->has('description') && $request->get('description')!=null)
            $description=trim($request->get('description'));

        if($request->has('address') && $request->get('address')!=null)
            $address=trim($request->get('address'));

        if($request->has('lat') && $request->get('lat')!=null)
            $lat=trim($request->get('lat'));

        if($request->has('lng') && $request->get('lng')!=null)
            $lng=trim($request->get('lng'));


        $iDisplayLength = intval($request->get('length'));  // getting rows per page value for paging
        $iDisplayStart = intval($request->get('start'));    // getting offset value for paging
        $sEcho = intval($request->get('draw'));

        $query_order_array=$request->get('order', array(array('column'=>1,'dir'=>'asc')));
        $query_order_column=$query_order_array[0]['column'];
        $query_order_direction=$query_order_array[0]['dir'];

        // Building query for search
        $query = DB::table('location');
        //$query->leftjoin('advertiser_types','advertiser_types.id','=','advertisers.type_id');
       // $query->leftjoin('advertiser_widgets_advertisers','advertiser_widgets_advertisers.advertiser_id','=','advertisers.id');
        //$query->leftjoin('advertiser_widgets','advertiser_widgets.id','=','advertiser_widgets_advertisers.advertiser_widget_id');
        //$query->select(DB::raw("GROUP_CONCAT(DISTINCT advertiser_widgets.name ORDER BY advertiser_widgets.name SEPARATOR ', ') as advertiserwidgets"),'advertisers.*', 'advertiser_types.type as advertiser_type');
        //$query->select('advertisers.*', 'advertiser_types.type as advertiser_type');
        //$query->where('advertisers.is_delete','=',0);

        /*$tags[]=1;
        $tags[]=2;
        $query->whereHas('advertiser_widgets', function($query) use($tags) {
            $query->whereIn('name', $tags);
        });
        */

        if($id!=null)
            $query->where('id','=',$id);

        if($name!=null)
            $query->where('name','LIKE','%'.$name.'%');

        if($description!=null)
            $query->where('description','=',$description);

        if($address!=null)
            $query->where('address','=',$address);

        if($lat!=null)
            $query->where('lat','=',$lat);

        if($lng!=null)
            $query->where('lng','=',$lng);


        //$query->groupBy('users.id');
        //if($advertiser_address!=null)
           // $query->havingRaw("GROUP_CONCAT(DISTINCT advertiser_widgets.name ORDER BY advertiser_widgets.name SEPARATOR ', ') LIKE '%".$advertiser_address."%'");

        // copying query for total records
        //$copy_query = $query;
        //$iTotalRecords=$copy_query->count();

        $sql=$query->toSql();

        $count = DB::table( DB::raw("($sql) as sub") )
            ->mergeBindings($query) // you need to get underlying Query Builder
            ->count();

        $iTotalRecords=$count;

        //$iTotalRecords= DB::table(DB::raw("($sql) AS a"))->count();

        $query->orderBy($columns[$query_order_column], $query_order_direction);

        if($iDisplayLength>0)
            $query->limit($iDisplayLength)->offset($iDisplayStart);

        //getting searched records
        $advertisers=$query->get();

        $i=0;
        $records = array();
        $records["data"] = array();
        foreach($advertisers as $advertiser)
        {
            //$advertiserwidgets=$advertiser->advertiserwidgets;
            //if(trim($advertiserwidgets)=="")
              //  $advertiserwidgets="N/A";

            //$advertisertype=$advertiser->advertiser_type;
            //if(trim($advertisertype)=="")
              //  $advertisertype="N/A";


            $advertiser->created_at =  Carbon::parse($advertiser->created_at);
            $records['data'][$i][]='<input type="checkbox" name="id[]" value="'.$advertiser->id.'">';
            $records['data'][$i][]=$advertiser->id;
            $records['data'][$i][]=$advertiser->name;
            $records['data'][$i][]=$advertiser->description;
            $records['data'][$i][]=$advertiser->address;
            $records['data'][$i][]=$advertiser->lat;
            $records['data'][$i][]=$advertiser->lng;
            $records['data'][$i][]='
                <div class="btn-group" role="group">
                    <a href="'.url('/edit', [$advertiser->id]).'" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                    <a href="'.url('/delete', [$advertiser->id]).'" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                </div>';
            $i++;
        }
        if ($request->get("customActionType")!==null && $request->get("customActionType") == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }
}
