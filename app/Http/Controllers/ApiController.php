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
            if($user['email_verified_at'] == null){
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
                    $save['url'] = 'track/bl'; //hay que subir que cambie a container
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

                    unset($reference->air_info->trackpage_url);
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
