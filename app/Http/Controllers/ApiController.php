<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApiRequests;
use Illuminate\Support\Facades\Http;

class ApiController extends BaseController
{
    private $url;

    public function __construct()
    {
        $this->sea_url = 'https://tracking.searates.com/';
        $this->air_url = 'https://api.trackingmore.com/v4/';
    }

    public function home(){
        // get today minus 1 month
        //$last_update = date('d F, Y', strtotime('-1 month'));
        $data['version'] = '1.1.0';
        $data['last_check'] = date('F d, Y', strtotime('-13 days'));
        $data['last_update'] = 'February 2023';
        return view('home', $data);
        
    }

    /**
     * Bill of Lading and Booking
     */
    public function track_bl()
    {
        //$form = json_decode(file_get_contents('php://input'), true);

        //api_key: SMjHkR38fDcSxLbPQ
        //bl_code: 225013176
        //shipping_line: MAEU
        $form = request()->all();

        if(!$form['api_key']){

            return response()->json(['error' => 'Missing API KEY'], 401);

        } else if(!$form['bl_code']){

            return response()->json(['error' => 'Missing BL Code'], 401);

        }else if(!$form['shipping_line']){

            return response()->json(['error' => 'Missing Shipping Line'], 401);
        
        }else {
            
            $user = User::where('api_key', $form['api_key'])->first();

            // check if user_id is valid
            if(!$user){
                return response()->json(['error' => 'Invalid API KEY'], 401);
            }
            
            //return $user['id'];

            $params    = [
                'type'         => 'BL',
                'number'       => $form['bl_code'], 
                'sealine'      => $form['shipping_line'], 
                'api_key'      => env('SEARATES'),
                'force_update' => false,
            ];
            $response  = Http::withToken(env('SEARATES'))->get($this->sea_url.'reference', $params);
            if($response){
                //return response()->json($response->object()->status);
                if($response->object()){
                    $reference = $response->object();
                    $reference->data = (array)$reference->data;
                    //return $reference;
                    //return response()->json($reference);
                    if(!$reference->data){
                        $save['response_code'] = 'error';
                    } else {
                        $save['response_code'] = $response->object()->status;
                    }

                    //return $save;
        
                    $save['user_id'] = $user['id'];
                    $save['api_key'] = $form['api_key'];
                    $save['method'] = 'mbl';
                    $save['url'] = 'track/bl';
                    //
                    $save['ip'] = null;
                    $save['user_agent'] = null;
                    $save['response_code'] = $response->object()->status;
                    $save['response_time'] = null;
                    $save['response_size'] = null;
                    $save['response_type'] = null;
                    $save['response_content'] = $reference->data;
                    $save['response_headers'] = $response->object()->message;
                    $save['request_headers'] = null;
                    $save['request_body'] = null;
                    $save['request_params'] = null;
                    $save['request_cookies'] = null;
                    $save['request_files'] = null;
                    $save['request_server'] = null;
                    $save['request_query'] = null;
                    $save['request_route'] = null;
                    $save['request_session'] = null;
                    $save['request_input'] = $form['bl_code'];
                    //
                    
                    $apireq = ApiRequests::create($save);

                    if($apireq){
                        if(!$reference->data){
                            return response()->json(['error' => $response->object()->message], 401);
                        } else {
                            return response()->json($reference->data);
                        }

                    } else {
                        return response()->json(['error' => 'Error tracking BL: '.$form['bl_code']], 401);
                    }
        
                    
                } else {
                    return response()->json(['error' => $response->object()->message], 401);
                }

            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
        }
    }

    public function track_container()
    {

        $form = request()->all();

        if(!$form['api_key']){

            return response()->json(['error' => 'Missing API KEY'], 401);

        } else if(!$form['container_code']){

            return response()->json(['error' => 'Missing Container Code'], 401);

        }else {
            
            $user = User::where('api_key', $form['api_key'])->first();

            // check if user_id is valid
            if(!$user){
                return response()->json(['error' => 'Invalid API KEY'], 401);
            }
            
            //return $user['id'];

            $params    = [
                'number'       => $form['container_code'],
                //'sealine'      => $form['carrier'],
                'sealine'      => 'AUTO',
                'api_key'      => env('SEARATES'),
                'force_update' => false,
            ];
            $response  = Http::withToken(env('SEARATES'))->get($this->sea_url.'container', $params);
            if($response){
                //return response()->json($response->object()->status);
                if($response->object()){
                    $reference = $response->object();
                    $reference->data = (array)$reference->data;
                    //return $reference;
                    //return response()->json($reference);
                    if(!$reference->data){
                        $save['response_code'] = 'error';
                    } else {
                        $save['response_code'] = $response->object()->status;
                    }

                    //return $save;
        
                    $save['user_id'] = $user['id'];
                    $save['api_key'] = $form['api_key'];
                    $save['method'] = 'container';
                    $save['url'] = 'track/bl';
                    //
                    $save['ip'] = null;
                    $save['user_agent'] = null;
                    $save['response_code'] = $response->object()->status;
                    $save['response_time'] = null;
                    $save['response_size'] = null;
                    $save['response_type'] = null;
                    $save['response_content'] = $reference->data;
                    $save['response_headers'] = $response->object()->message;
                    $save['request_headers'] = null;
                    $save['request_body'] = null;
                    $save['request_params'] = null;
                    $save['request_cookies'] = null;
                    $save['request_files'] = null;
                    $save['request_server'] = null;
                    $save['request_query'] = null;
                    $save['request_route'] = null;
                    $save['request_session'] = null;
                    $save['request_input'] = $form['container_code'];
                    //
                    
                    $apireq = ApiRequests::create($save);

                    if($apireq){
                        if(!$reference->data){
                            return response()->json(['error' => $response->object()->message], 401);
                        } else {
                            return response()->json($reference->data);
                        }

                    } else {
                        return response()->json(['error' => 'Error tracking Container: '.$form['container_code']], 401);
                    }
        
                    
                } else {
                    return response()->json(['error' => $response->object()->message], 401);
                }

            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function track_awb()
    {

        $form = request()->all();

        if(!$form['api_key']){

            return response()->json(['error' => 'Missing API KEY'], 401);

        } else if(!$form['awb_code']){

            return response()->json(['error' => 'Missing AWB Code'], 401);

        }else {
            
            $user = User::where('api_key', $form['api_key'])->first();

            // check if user_id is valid
            if(!$user){
                return response()->json(['error' => 'Invalid API KEY'], 401);
            }
            
            //return $user['id'];

            
            $headers = array(
                'Content-Type: application/json',
                'Tracking-Api-Key: p27t7izs-57jj-zsb4-2ka2-slskfeoc1gt2'
            );
            $post = array(
                //'awb_number' => '172-56546685' //$form['awb_code'];
                'awb_number' => $form['awb_code']
            );
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.trackingmore.com/v4/awb");
            // SSL important
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            $output = curl_exec($ch);
            curl_close($ch);
            //$this -> response['response'] = json_decode($output);
    
            $response = json_decode($output);

            //return response()->json($response);
            //return response()->json($response->object()->status);

            if($response){
                //return response()->json($response->object()->status);
                if($response->data){
                    $reference = $response->data;
                    //return $reference;
                    //return response()->json($reference);
                    if($response->meta->code !== 200){
                        $save['response_code'] = 'error';
                    } else {
                        $save['response_code'] = $response->meta->code;
                    }

                    unset($reference->airline_info->trackpage_url);
                    //return $save;
        
                    $save['user_id'] = $user['id'];
                    $save['api_key'] = $form['api_key'];
                    $save['method'] = 'awb';
                    $save['url'] = 'track/awb';
                    //
                    $save['ip'] = null;
                    $save['user_agent'] = null;
                    $save['response_code'] = $response->meta->code;
                    $save['response_time'] = null;
                    $save['response_size'] = null;
                    $save['response_type'] = null;
                    $save['response_content'] = $reference;
                    $save['response_headers'] = $response->meta->message;
                    $save['request_headers'] = null;
                    $save['request_body'] = null;
                    $save['request_params'] = null;
                    $save['request_cookies'] = null;
                    $save['request_files'] = null;
                    $save['request_server'] = null;
                    $save['request_query'] = null;
                    $save['request_route'] = null;
                    $save['request_session'] = null;
                    $save['request_input'] = $form['awb_code'];
                    //
                    
                    $apireq = ApiRequests::create($save);

                    if($apireq){
                        if(!$reference){
                            return response()->json(['error' => $response->meta->message], 401);
                        } else {
                            return response()->json($reference);
                        }

                    } else {
                        return response()->json(['error' => 'Error tracking AWB: '.$form['awb_code']], 401);
                    }
        
                    
                } else {
                    return response()->json(['error' => $response->meta->message], 401);
                }

            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
        }

        return response()->json(['error' => 'Unauthorized'], 401);

        //////////////////////////////////////

        $form = json_decode(file_get_contents('php://input'), true);
        $form = $form['track_data'];

        $headers = array(
            'Content-Type: application/json',
            'Tracking-Api-Key: p27t7izs-57jj-zsb4-2ka2-slskfeoc1gt2'
        );
        $post = array(
            //'awb_number' => '172-56546685' //$form['awb_code'];
            'awb_number' => $form['awb_code']
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.trackingmore.com/v4/awb");
        // SSL important
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $output = curl_exec($ch);
        curl_close($ch);
        //$this -> response['response'] = json_decode($output);

        $response = json_decode($output);

        /* $response = Http::withHeaders([
            "Content-Type: application/json",
            "Tracking-Api-Key: p27t7izs-57jj-zsb4-2ka2-slskfeoc1gt2"
        ])->post("https://api.trackingmore.com/v4/awb/", [
            'awb_number' => $form['awb_code'],
        ]); */

        if($response){
            //return $response->data;
            if($response->data){
                $reference = $response->data;
                //return response()->json($response);

                if($reference->flight_info){
                    $reference->flight_info = (array) $reference->flight_info; 
                    
                    foreach ($reference->flight_info as $key => $value) {
                        //return $reference->flight_info[$i];
                        //$reference->flight_info[$key] = (array) $value;
                        $reference->flight_info[$key]->flight_number = $key;


                        $form['eta'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_depart_time));
                        $form['etd'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_arrival_time));

                        $form['pol'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_depart_time));
                        $form['pod'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_arrival_time));
                        $form['postpod'] = null;
                        $form['data']['origin_code'] = $reference->flight_way_station[0];
                        $form['data']['origin_name'] = $reference->flight_way_station[0];
                        $form['data']['origin_country'] = $reference->flight_way_station[0];
                        $form['data']['destination_code'] = end($reference->flight_way_station);
                        $form['data']['destination_name'] = end($reference->flight_way_station);
                        $form['data']['destination_country'] = end($reference->flight_way_station);

                        
                    }
                    $reference->flight_info = $this->transformFlightData($reference->flight_info);        
                }
                
                $form['data']['container_qty'] = 0;
                
                $form['type'] = 'awb';
                $form['data_tracking_air'] = $reference;

                if(isset($form['id'])){
                    $temp = Tracking::find($form['id']);
                    $temp['data_tracking_air'] = $reference;
                    $temp['data'] = $form['data'];
                    $temp = $form;
                    
                    $temp['count_tracking'] = $temp['count_tracking']+1;
                    $temp['last_tracking'] = date('Y-m-d H:i:s');
                    
                    $id_tracking = $temp->save();
                    //return response()->json($order);
                    
                }else{
                    $form['status'] = 'transit';
                    $form['status_transit'] = 'On Time';
                    $form['count_tracking'] = 1;
                    if(Auth::user()->hasRole('Sales')){
                        $form['sales_id'] = Auth::id();
                    }elseif(Auth::user()->hasRole('Client')){
                        $form['client_id'] = Auth::id();                            
                    }else{                           
                        $form['sales_id'] = Auth::id();
                    }
                    
                    $id_tracking = Tracking::create($form);
                }
    
                if($id_tracking){

                    $data = [];

                    $data['tracking'] = $form;
                    $data['tracking']['data_tracking_air'] = $form['data_tracking_air'];

                    //return response()->json($data['tracking']->containers[0]);

                    $data['events'] = [];

                    $data['map_events'] = [];

                    $data['last_event'] = [];

                    $data['air_events'] = $reference;
                    

                    /* return response()->json([
                        'status' => 'success',
                        'data' => $data,
                        'cod' => 200
                    ], 200); */
                    //return response()->json($data);
                    //return response()->json(['success' => $data], 200);
                    return $data;

                } else {
                    return response()->json(['error' => 'Tracking not updated'], 401);
                }
            } else {
                return response()->json(['error' => $response->object()->message], 401);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        
        //return view('publico.popular_routes', compact('response'));
        //$reference = $response->object();
        // response()->json($output);
        //return $reference;
        //dd($reference);
    }

    function transformFlightData($obj) {
        $arr = [];
      
        foreach ($obj as $key => $value) {
          $arr[] = $value;
          unset($obj[$key]);
        }
      
        return $arr;
      }
}
