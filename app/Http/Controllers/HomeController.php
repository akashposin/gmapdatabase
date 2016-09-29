<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Input;
use Session;
use App\Http\Controllers\redirect;
use App\location;
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
            "lng",
            "image"
        );

        // local variables for POST variables for searching columns
        $id="";
        $name="";
        $description="";
        $address="";
        $lat="";
        $lng="";
        $image="";
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

        if($request->has('image') && $request->get('image')!=null)
            $image=trim($request->get('image'));

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

        if($image!=null)
            $query->where('image','=',$image);

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
            $records['data'][$i][]='<img style="width: 80px;" src=/shahbaz/laravel/googlemap/public/upload/'.$advertiser->image.'>';
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


    // fetch map data with latitude longitude
    public function googlemap(){
        $data=DB::table('location')->get();

        echo json_encode($data);
       }

    public function newlocation(){
        return view('newlocation');
    }

    public function edit($id)
    {
//      echo $id;
//      $edit_data=DB::table('location')->where('id',$id)->get();
        $edit_data = location::where('id',$id)->get();
        return view('edit',compact('edit_data',$edit_data));
    }

    public function update(Request $request, $id)
    {
        $location = new location();
        $input=$request->all();

        $location = location::find($id);
        $location->name = $input['name'];
        $location->description = $input['description'];
        $location->address = $input['address'];
        $location->lat = $input['latitude'];
        $location->lng = $input['longitude'];

        $path=base_path()."/public/upload";
        File::makeDirectory($path, $mode = 0777, true, true);
        if (Input::hasFile('file'))
        {
            $fileName = $request->file('file')->getClientOriginalName();
            $request->file('file')->move($path, $fileName);
            $location->image = $fileName;

        }
        if($location->update())
            {
                return redirect('/home')->with('flash_message', 'Data updated Succesfully !!');
            }else
            {
                return redirect('/home')->with('flash_err_message', 'Data not updated Succesfully !!');
            }


    }


    public function upload(Request $request)
    {
        $location = new location();
        /*making directory with base_path*/
        $input=$request->all();
        $path=base_path()."/public/upload";
        File::makeDirectory($path, $mode = 0777, true, true);
        if (Input::hasFile('file'))
        {
            $fileName = $request->file('file');
            $realname = $fileName->getClientOriginalName();
            $input = md5($realname).time().'.'.$fileName->getClientOriginalExtension();
            $destinationpath = public_path('/upload/thumbs/');
            $img = location::make($fileName->getRealPath());

            $img->resize(100,100,function($constraint){
                $constraint->aspectRatio();
            })->save($destinationpath.'/'.$input);
            $request->file('file')->move($path, $fileName);

            $location->name = $input['name'];
            $location->description = $input['description'];
            $location->address = $input['address'];
            $location->lat = $input['latitude'];
            $location->lng = $input['longitude'];
            $location->image = $fileName;

            if($location->save())
            {
//              \session::flash('flash_message','Inserted Succesfully');
                return redirect('home/newlocation')->with('flash_message', 'Data inserted Succesfully !!');
            }else
            {
                 return redirect('home/newlocation')->with('flash_err_message', 'Data not inserted Succesfully !!');
            }
        }
    }

//    public function upload(Request $request)
//    {
//        $location = new location();
//        /*making directory with base_path*/
//        $input=$request->all();
//        $path=base_path()."/public/upload";
//        File::makeDirectory($path, $mode = 0777, true, true);
//        if (Input::hasFile('file'))
//        {
//            $fileName = $request->file('file')->getClientOriginalName();
//            $request->file('file')->move($path, $fileName);
//
//            $location->name = $input['name'];
//            $location->description = $input['description'];
//            $location->address = $input['address'];
//            $location->lat = $input['latitude'];
//            $location->lng = $input['longitude'];
//            $location->image = $fileName;
//
//            if($location->save())
//            {
////              \session::flash('flash_message','Inserted Succesfully');
//                return redirect('home/newlocation')->with('flash_message', 'Data inserted Succesfully !!');
//            }else
//            {
//                return redirect('home/newlocation')->with('flash_err_message', 'Data not inserted Succesfully !!');
//            }
//        }
//    }
//How to upload edit delete display image with thumbnails in laravel 5
//https://www.youtube.com/watch?v=QsP0lSSXTc8
}
