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
        $data['last_update'] = date('F Y', strtotime('-1 month'));
        return view('home', $data);
        
    }

    /**
     * Bill of Lading and Booking
     */
    public function track_bl()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        $form = $form['track_data'];

        if(!$form['data_sale']['mbl_code'] || !$form['data_sale']['carrier']){

            return response()->json(['error' => 'Missing data'], 401);

        } else {

            $params    = [
                'type'         => 'BL',
                'number'       => $form['data_sale']['mbl_code'], 
                'sealine'      => $form['data_sale']['carrier'], 
                'api_key'      => env('SEARATES'),
                'force_update' => false,
            ];
            $response  = Http::withToken(env('SEARATES'))->get($this->sea_url.'reference', $params);
            if($response){
                //return response()->json($response->object()->status);
                if($response->object()->status === 'success'){
                    $reference = $response->object();
                    //return response()->json($response);
        
                    $form['type'] = 'mbl';
                    $form['data_tracking_sea'] = $reference->data;

                    if($reference->data->route){

                        foreach ($reference->data->locations as $key => $location) {
                            if($location->id == $reference->data->route->pol->location){
                                $form['data']['origin_code'] = $location->locode;
                                $form['data']['origin_name'] = strtoupper($location->name);
                                $form['data']['origin_country'] = strtoupper($location->country);
                            }
                            if($location->id == $reference->data->route->pod->location){
                                $form['data']['destination_code'] = $location->locode;
                                $form['data']['destination_name'] = strtoupper($location->name);
                                $form['data']['destination_country'] = strtoupper($location->country);
                            }
                        }

                        $form['data_sale']['pol'] = date('Y-m-d', strtotime($reference->data->route->pol->date));
                        $form['data_sale']['pod'] = date('Y-m-d', strtotime($reference->data->route->pod->date));
                        $form['data_sale']['postpod'] = date('Y-m-d', strtotime($reference->data->route->postpod->date));
                        
                        $form['data_sale']['etd'] = date('Y-m-d', strtotime($reference->data->route->pol->date));
                        $form['data_sale']['eta'] = date('Y-m-d', strtotime($reference->data->route->pod->date));
                    }

                    if($reference->data->vessels){
                        $form['data_sale']['vessel'] = $reference->data->vessels[0]->name;
                        $form['data_sale']['voyage'] = $reference->data->vessels[0]->call_sign;
                    }
                    
                    if($reference->data->containers){
                        for($i=0; $i<count($reference->data->containers); $i++){
                            $container = $reference->data->containers[$i];
                            $form['containers'][$i]['id'] = $container->number;
                            $form['containers'][$i]['description'] = $container->iso_code;
                            $form['containers'][$i]['status'] = "On Time";
                        }
                    }

                    
                    $form['data']['container_qty'] = count($form['containers']);

                    if(isset($form['id'])){
                        $temp = Tracking::find($form['id']);
                        $temp['data_tracking_sea'] = $reference->data;
                        $temp['containers'] = $form['containers'];
                        $temp['data'] = $form['data'];
                        $temp['data_sale'] = $form['data_sale'];
                        /* if($reference->data->route->pod->date < date('Y-m-d')){
                            $temp['status'] = 'complete';
                        } */
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

                        //return response()->json($data['tracking']->containers[0]);

                        if(isset($reference->data->containers[0])){
                            $data['events'] = $reference->data->containers[0]->events; 
                        } else {
                            $data['events'] = [];
                        }
                        $data['map_events'] = [];
                        if (!empty($data['events'])) {
                            foreach ($data['events'] as $key => $event) {
                                // get array from $data['order']['data_tracking_sea']['locations'] we have id of $event['location'] with filter
                                $data['events'][$key]->location = array_filter($reference->data->locations, function ($location) use ($event) {
                                    return $location->id == $event->location;
                                });
                                $data['events'][$key]->location = array_values($data['events'][$key]->location);
                                if (!empty($event->location)) {
                                    $data['events'][$key]->location = $data['events'][$key]->location[0];
                                }
                
                                // agrega a map_events las coordenadas de cada evento sin repetir
                                if (!in_array($data['events'][$key]->location, $data['map_events'])) {
                                    $data['map_events'][] = $data['events'][$key]->location;
                                }
                            }
                            // recorrer events para obtener el registro mas reciente utlizando date como referencia y que sea actual == true
                            $data['last_event'] = array_filter($data['events'], function ($event) {
                                return $event->actual == true;
                            });
                
                            // sort last_event by date
                            usort($data['last_event'], function ($a, $b) {
                                return strtotime($a->date) - strtotime($b->date);
                            });
                
                            $data['last_event'] = end($data['last_event']);
                            
                        }
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
            
        }
    }

    public function track_container()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        $form = $form['track_data'];

        if(!$form['data_sale']['container_id']){

            return response()->json(['error' => 'Missing data'], 401);

        } else {

            $params    = [
                'number'       => $form['data_sale']['container_id'],
                //'sealine'      => $form['carrier'],
                'sealine'      => 'AUTO',
                'api_key'      => env('SEARATES'),
                'force_update' => false,
            ];
            $response  = Http::withToken(env('SEARATES'))->get($this->sea_url.'container', $params);
            if($response){
                //return response()->json($response->object()->status);
                if($response->object()->status === 'success'){
                    $reference = $response->object();
                    //return response()->json($response);
        
                    $form['type'] = 'container';
                    $form['data_tracking_sea'] = $reference->data;


                    if($reference->data->route){

                        foreach ($reference->data->locations as $key => $location) {
                            if($location->id == $reference->data->route->pol->location){
                                $form['data']['origin_code'] = $location->locode;
                                $form['data']['origin_name'] = strtoupper($location->name);
                                $form['data']['origin_country'] = strtoupper($location->country);
                            }
                            if($location->id == $reference->data->route->pod->location){
                                $form['data']['destination_code'] = $location->locode;
                                $form['data']['destination_name'] = strtoupper($location->name);
                                $form['data']['destination_country'] = strtoupper($location->country);
                            }
                        }

                        $form['data_sale']['pol'] = date('Y-m-d', strtotime($reference->data->route->pol->date));
                        $form['data_sale']['pod'] = date('Y-m-d', strtotime($reference->data->route->pod->date));
                        $form['data_sale']['postpod'] = date('Y-m-d', strtotime($reference->data->route->postpod->date));
                        
                        $form['data_sale']['etd'] = date('Y-m-d', strtotime($reference->data->route->pol->date));
                        $form['data_sale']['eta'] = date('Y-m-d', strtotime($reference->data->route->pod->date));
                    }

                    if($reference->data->vessels){
                        $form['data_sale']['vessel'] = $reference->data->vessels[0]->name;
                        $form['data_sale']['voyage'] = $reference->data->vessels[0]->call_sign;
                    }
                    
                    if($reference->data->container){
                        $form['containers'] = [];

                        $container = $reference->data->container;
                        //$form['containers'][0] = $reference->data->container;
                        $form['containers'][0]['id'] = $container->number;
                        $form['containers'][0]['description'] = $container->iso_code;
                        $form['containers'][0]['status'] = "On Time";
                    }

                    
                    $form['data']['container_qty'] = count($form['containers']);

                    if(isset($form['id'])){
                        $temp = Tracking::find($form['id']);
                        $temp['data_tracking_sea'] = $reference->data;
                        $temp['containers'] = $form['containers'];
                        $temp['data'] = $form['data'];
                        $temp['data_sale'] = $form['data_sale'];
                        if($reference->data->route->pod->date < date('Y-m-d')){
                            $temp['status'] = 'complete';
                        }
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

                        //return response()->json($data['tracking']->containers[0]);

                        if(isset($reference->data->container)){
                            $data['events'] = $reference->data->container->events; 
                        } else {
                            $data['events'] = [];
                        }
                        $data['map_events'] = [];
                        if (!empty($data['events'])) {
                            foreach ($data['events'] as $key => $event) {
                                // get array from $data['order']['data_tracking_sea']['locations'] we have id of $event['location'] with filter
                                $data['events'][$key]->location = array_filter($reference->data->locations, function ($location) use ($event) {
                                    return $location->id == $event->location;
                                });
                                $data['events'][$key]->location = array_values($data['events'][$key]->location);
                                if (!empty($event->location)) {
                                    $data['events'][$key]->location = $data['events'][$key]->location[0];
                                }
                
                                // agrega a map_events las coordenadas de cada evento sin repetir
                                if (!in_array($data['events'][$key]->location, $data['map_events'])) {
                                    $data['map_events'][] = $data['events'][$key]->location;
                                }
                            }
                            // recorrer events para obtener el registro mas reciente utlizando date como referencia y que sea actual == true
                            $data['last_event'] = array_filter($data['events'], function ($event) {
                                return $event->actual == true;
                            });
                
                            // sort last_event by date
                            usort($data['last_event'], function ($a, $b) {
                                return strtotime($a->date) - strtotime($b->date);
                            });
                
                            $data['last_event'] = end($data['last_event']);
                            
                        }
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
            
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function track_awb()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        $form = $form['track_data'];

        $headers = array(
            'Content-Type: application/json',
            'Tracking-Api-Key: p27t7izs-57jj-zsb4-2ka2-slskfeoc1gt2'
        );
        $post = array(
            //'awb_number' => '172-56546685' //$form['data_sale']['awb_code'];
            'awb_number' => $form['data_sale']['awb_code']
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
            'awb_number' => $form['data_sale']['awb_code'],
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


                        $form['data_sale']['eta'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_depart_time));
                        $form['data_sale']['etd'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_arrival_time));

                        $form['data_sale']['pol'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_depart_time));
                        $form['data_sale']['pod'] = date('Y-m-d', strtotime($reference->flight_info[$key]->plan_arrival_time));
                        $form['data_sale']['postpod'] = null;
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
                    $temp['data_sale'] = $form['data_sale'];
                    
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
