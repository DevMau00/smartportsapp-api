@extends('layouts.publico')

@section('title', 'Docs')

@push('styles')

    <style >
    
    
    </style>

@endpush

@section('content')


<div class="content-page">
    <div class="content-code"></div>
    <div class="content">


        <div class="overflow-hidden content-section" id="get-api-key">
            <h1>Get your  API KEY</h1>
            <pre>
    API ROOT

        https://api.smartports.app/
                </pre>
            <p>
                You can get your API KEY from the your profile page. Log into your account and click on the top right corner on your profile to retrieve your secret API KEY.
            </p>
            <p>
                To use this API, you need an <strong>API key</strong>. Please contact us at <a href="mailto:info@smartports.app">info@smartports.app</a> if you run into any trouble with your KEY.
            </p>
        </div>


        <div class="overflow-hidden content-section" id="track-bl">
            <h2>Track Bill of Lading</h2>
            <pre><code class="bash">
# Here is a curl example
curl \
-X POST https://api.smartports.app/track/bl \
-F 'api_key=your_api_key' \
-F 'bl_code=222709941' \
-F 'shipping_line=MAEU' \
                </code></pre>
            <p>
                To track the Bill of Lading you need to make a POST call to the following url :<br>
                <code class="higlighted break-word">https://api.smartports.app/track/bl</code>
            </p>
            <br>
            <pre><code class="json" style="overflow-x:hidden;">
Result example :

{
  query:{
    bl_code: "222709941",
    shipping_line: "MAEU"
  }
  result: [
    {
        "route": {
          "pod": {
            "date": "2022-12-03 04:14:00",
            "actual": true,
            "location": 1
          },
          "pol": {
            "date": "2022-11-10 13:44:00",
            "actual": true,
            "location": 2
          },
          "prepol": {
            "date": null,
            "actual": null,
            "location": 2
          },
          "postpod": {
            "date": "2022-12-03 04:14:00",
            "actual": true,
            "location": 1
          }
        },
        "vessels": [
          {
            "id": 1,
            "imo": 9437050,
            "flag": "LR",
            "mmsi": 636020984,
            "name": "GSL TINOS",
            "call_sign": "5LBR6"
          }
        ],
        "locations": [
          {
            "id": 1,
            "lat": 19.11695,
            "lng": -104.34214,
            "name": "Manzanillo",
            "state": "Estado de Colima",
            "locode": "MXZLO",
            "country": "Mexico",
            "country_code": "MX"
          },
          {
            "id": 2,
            "lat": 31.366365,
            "lng": 121.61475,
            "name": "Shanghai",
            "state": "Shanghai Shi",
            "locode": "CNSHG",
            "country": "China",
            "country_code": "CN"
          },
          {
            "id": 3,
            "lat": 30.8703,
            "lng": 120.0933,
            "name": "Huzhou",
            "state": "Zhejiang Sheng",
            "locode": "CNHZH",
            "country": "China",
            "country_code": "CN"
          }
        ],
        "containers": [
          {
            "events": [
              {
                "date": "2022-11-07 16:29:00",
                "type": "land",
                "actual": true,
                "status": "CEP",
                "vessel": null,
                "voyage": null,
                "location": 3,
                "description": "Gate out Empty"
              },
              {
                "date": "2022-11-09 00:30:00",
                "type": "land",
                "actual": true,
                "status": "LTS",
                "vessel": null,
                "voyage": null,
                "location": 2,
                "description": "Gate in"
              },
              {
                "date": "2022-11-10 13:44:00",
                "type": "land",
                "actual": true,
                "status": "CGI",
                "vessel": null,
                "voyage": null,
                "location": 2,
                "description": "Gate in"
              },
              {
                "date": "2022-11-12 13:58:00",
                "type": "sea",
                "actual": true,
                "status": "CLL",
                "vessel": 1,
                "voyage": "246S",
                "location": 2,
                "description": "Load"
              },
              {
                "date": "2022-12-03 04:14:00",
                "type": "sea",
                "actual": true,
                "status": "CDD",
                "vessel": 1,
                "voyage": "246S",
                "location": 1,
                "description": "Discharge"
              },
              {
                "date": "2022-12-09 04:25:00",
                "type": "land",
                "actual": true,
                "status": "CGO",
                "vessel": null,
                "voyage": null,
                "location": 1,
                "description": "Gate out"
              }
            ],
            "number": "GCXU5781866",
            "iso_code": "42G0"
          }
        ]
      }
  ]
}
                </code></pre>
            <h4>QUERY PARAMETERS</h4>
            <table class="central-overflow-x">
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>api_key</td>
                    <td>String</td>
                    <td>Your API key.</td>
                </tr>
                
                
                <tr>
                    <td>bl_code</td>
                    <td>String</td>
                    <td>
                        The Bill of Lading number/code.
                    </td>
                </tr>
                <tr>
                    <td>shipping_line</td>
                    <td>String</td>
                    <td>
                        The shipping line code from the json file.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>



        <div class="overflow-hidden content-section" id="track-container">
            <h2>Track Container</h2>
            <pre><code class="bash">
# Here is a curl example
curl \
-X POST https://api.smartports.app/track/container \
-F 'api_key=your_api_key' \
-F 'container_code=MRKU6333050' \
                </code></pre>
            <p>
                To track the container you need to make a POST call to the following url, the container call will return an OBJECT instead of an ARRAY as from the track BL call :<br>
                <code class="higlighted break-word">https://api.smartports.app/track/container</code>
            </p>
            <br>
            <pre><code class="json" style="overflow-x:hidden;">
Result example :

{
  query:{
    container_code: "MRKU6333050",
  }
  result: [
    {
        "route": {
          "pod": {
            "date": "2023-01-23 02:31:00",
            "actual": true,
            "location": 1
          },
          "pol": {
            "date": "2022-12-11 05:01:00",
            "actual": true,
            "location": 5
          },
          "prepol": {
            "date": null,
            "actual": null,
            "location": 5
          },
          "postpod": {
            "date": "2023-02-15 15:25:00",
            "actual": true,
            "location": 2
          }
        },
        "vessels": [
          {
            "id": 1,
            "imo": 9456977,
            "flag": "LR",
            "mmsi": 636091883,
            "name": "MERKUR ARCHIPELAGO",
            "call_sign": "A8UC5"
          },
          {
            "id": 2,
            "imo": 9928188,
            "flag": "PA",
            "mmsi": 352002065,
            "name": "MAERSK ACADIA",
            "call_sign": "3E3754"
          }
        ],
        "container": {
          "events": [
            {
              "date": "2022-12-08 14:03:00",
              "type": "land",
              "actual": true,
              "status": "CEP",
              "vessel": null,
              "voyage": null,
              "location": 4,
              "description": "Gate out Empty"
            },
            {
              "date": "2022-12-11 05:01:00",
              "type": "land",
              "actual": true,
              "status": "CGI",
              "vessel": null,
              "voyage": null,
              "location": 5,
              "description": "Gate in"
            },
            {
              "date": "2022-12-16 05:41:00",
              "type": "sea",
              "actual": true,
              "status": "CLL",
              "vessel": 1,
              "voyage": "249E",
              "location": 5,
              "description": "Load"
            },
            {
              "date": "2022-12-18 03:06:00",
              "type": "sea",
              "actual": true,
              "status": "CDT",
              "vessel": 1,
              "voyage": "249E",
              "location": 3,
              "description": "Discharge"
            },
            {
              "date": "2022-12-30 21:47:00",
              "type": "sea",
              "actual": true,
              "status": "CLT",
              "vessel": 2,
              "voyage": "252S",
              "location": 3,
              "description": "Load"
            },
            {
              "date": "2023-01-23 02:31:00",
              "type": "sea",
              "actual": true,
              "status": "CDD",
              "vessel": 2,
              "voyage": "252S",
              "location": 1,
              "description": "Discharge"
            },
            {
              "date": "2023-02-03 11:20:00",
              "type": "land",
              "actual": true,
              "status": "CGO",
              "vessel": null,
              "voyage": null,
              "location": 1,
              "description": "On rail"
            },
            {
              "date": "2023-02-15 15:25:00",
              "type": "land",
              "actual": true,
              "status": "CDC",
              "vessel": null,
              "voyage": null,
              "location": 2,
              "description": "Off rail"
            }
          ],
          "number": "MRKU6333050",
          "iso_code": "42G0"
        },
        "locations": [
          {
            "id": 1,
            "lat": 17.97066,
            "lng": -102.22124,
            "name": "Lazaro Cardenas",
            "state": "Estado de Michoacan de Ocampo",
            "locode": "MXLZC",
            "country": "Mexico",
            "country_code": "MX"
          },
          {
            "id": 2,
            "lat": 19.28786,
            "lng": -99.65324,
            "name": "Toluca",
            "state": "Estado de Mexico",
            "locode": "MXTLC",
            "country": "Mexico",
            "country_code": "MX"
          },
          {
            "id": 3,
            "lat": 35.10168,
            "lng": 129.03004,
            "name": "Busan",
            "state": "Busan",
            "locode": "KRPUS",
            "country": "South Korea",
            "country_code": "KR"
          },
          {
            "id": 4,
            "lat": 30.29365,
            "lng": 120.16142,
            "name": "Hangzhou",
            "state": "Zhejiang Sheng",
            "locode": "CNHAZ",
            "country": "China",
            "country_code": "CN"
          },
          {
            "id": 5,
            "lat": 29.87819,
            "lng": 121.54945,
            "name": "Ningbo",
            "state": "Zhejiang Sheng",
            "locode": "CNNGB",
            "country": "China",
            "country_code": "CN"
          }
        ]
    }
    ]
}
                </code></pre>
            <h4>QUERY PARAMETERS</h4>
            <table class="central-overflow-x">
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>api_key</td>
                    <td>String</td>
                    <td>Your API key.</td>
                </tr>
                
                
                <tr>
                    <td>container_code</td>
                    <td>String</td>
                    <td>
                        The container number/code.
                    </td>
                </tr>
                
                </tbody>
            </table>
        </div>

        <div class="overflow-hidden content-section" id="track-awb">
            <h2>Track Air Way Bill</h2>
            <pre><code class="bash">
# Here is a curl example
curl \
-X POST https://api.smartports.app/track/awb \
-F 'api_key=your_api_key' \
-F 'awb_code=172-56546685' \
                </code></pre>
            <p>
                To track the air way bill you need to make a POST call to the following url,:<br>
                <code class="higlighted break-word">https://api.smartports.app/track/awb</code>
            </p>
            <br>
            <pre><code class="json" style="overflow-x:hidden;">
Result example :

{
  query:{
    awb_code: "172-56546685",
  }
  result: [
        {
            {
                "piece": 2,
                "origin": "LIN",
                "weight": "260K",
                "awb_number": "172-56546685",
                "last_event": "2022-12-20 16:00:00, 2 pieces delivered, MEX, CV6684",
                "track_info": [
                  {
                    "event": "2 pieces delivered",
                    "piece": "2",
                    "status": "DLV",
                    "weight": "",
                    "station": "MEX",
                    "plan_date": "2022-12-19 13:46:00",
                    "actual_date": "2022-12-20 16:00:00",
                    "flight_number": "CV6684"
                  },
                  {
                    "event": "2 pieces documents delivered",
                    "piece": "2",
                    "status": "AWD",
                    "weight": "",
                    "station": "MEX",
                    "plan_date": "2022-12-15 22:46:00",
                    "actual_date": "2022-12-15 15:00:00",
                    "flight_number": "CV6684"
                  },
                  {
                    "event": "2 pieces ready to be picked up",
                    "piece": "2",
                    "status": "NFD",
                    "weight": "",
                    "station": "MEX",
                    "plan_date": "2022-12-15 22:46:00",
                    "actual_date": "2022-12-15 14:20:00",
                    "flight_number": "CV6684"
                  },
                  {
                    "event": "2 pieces received on CV6684",
                    "piece": "2",
                    "status": "RCF",
                    "weight": "",
                    "station": "MEX",
                    "plan_date": "2022-12-15 21:46:00",
                    "actual_date": "2022-12-15 14:10:00",
                    "flight_number": "CV6684"
                  },
                  {
                    "event": "2 pieces arrived on CV6684",
                    "piece": "2",
                    "status": "ARR",
                    "weight": "",
                    "station": "MEX",
                    "plan_date": "2022-12-15 13:26:00",
                    "actual_date": "2022-12-15 13:46:00",
                    "flight_number": "CV6684"
                  },
                  {
                    "event": "2 pieces departed on CV6684",
                    "piece": "2",
                    "status": "DEP",
                    "weight": "",
                    "station": "LUX",
                    "plan_date": "2022-12-15 08:20:00",
                    "actual_date": "2022-12-15 08:48:00",
                    "flight_number": "CV6684"
                  },
                  {
                    "event": "2 pieces received on CV9951B",
                    "piece": "2",
                    "status": "RCF",
                    "weight": "",
                    "station": "LUX",
                    "plan_date": "2022-12-14 15:21:00",
                    "actual_date": "2022-12-14 19:37:00",
                    "flight_number": "CV9951B"
                  },
                  {
                    "event": "2 pieces arrived on CV9951B",
                    "piece": "2",
                    "status": "ARR",
                    "weight": "",
                    "station": "LUX",
                    "plan_date": "2022-12-14 13:21:00",
                    "actual_date": "2022-12-14 17:00:00",
                    "flight_number": "CV9951B"
                  },
                  {
                    "event": "2 pieces departed on CV9951B",
                    "piece": "2",
                    "status": "DEP",
                    "weight": "",
                    "station": "LIN",
                    "plan_date": "2022-12-13 22:00:00",
                    "actual_date": "2022-12-13 17:51:00",
                    "flight_number": "CV9951B"
                  },
                  {
                    "event": "2 pieces accepted",
                    "piece": "2",
                    "status": "RCS",
                    "weight": "",
                    "station": "LIN",
                    "plan_date": "2022-12-13 19:30:00",
                    "actual_date": "2022-12-13 17:20:00",
                    "flight_number": "CV9951"
                  }
                ],
                "destination": "MEX",
                "flight_info": [
                  {
                    "depart_time": "2022-12-13 22:00:00",
                    "arrival_time": "2022-12-14 13:21:00",
                    "flight_number": "CV9951B",
                    "depart_station": "LIN",
                    "arrival_station": "LUX",
                    "plan_depart_time": "2022-12-13 22:00:00",
                    "plan_arrival_time": "2022-12-14 13:21:00"
                  },
                  {
                    "depart_time": "2022-12-15 08:20:00",
                    "arrival_time": "2022-12-15 13:26:00",
                    "flight_number": "CV6684",
                    "depart_station": "LUX",
                    "arrival_station": "MEX",
                    "plan_depart_time": "2022-12-15 08:20:00",
                    "plan_arrival_time": "2022-12-15 13:26:00"
                  }
                ],
                "airline_info": {
                  "url": "https://www.cargolux.com/",
                  "name": "Cargolux Airlines",
                  "track_url": "https://cvtnt.champ.aero/trackntrace?awbnumber=172-56546685"
                },
                "status_number": 4,
                "flight_way_station": [
                  "LIN",
                  "LUX",
                  "MEX"
                ]
            }
        }
    ]
}
                </code></pre>
            <h4>QUERY PARAMETERS</h4>
            <table class="central-overflow-x">
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>api_key</td>
                    <td>String</td>
                    <td>Your API key.</td>
                </tr>
                
                
                <tr>
                    <td>awb_code</td>
                    <td>String</td>
                    <td>
                        The air way bill number/code.
                    </td>
                </tr>
                
                </tbody>
            </table>
        </div>



        <div class="overflow-hidden content-section" id="shipping-lines">
            <h2>Shipping Lines</h2>
            
            <p>
                Download the full json with over 140 shipping lines at:<br>
                <code class="higlighted break-word">https://api.smartports.app/ShippingLines.json</code>
            </p>
            <br>
            <pre><code class="json" style="overflow-x:hidden;">
Download {
    https://api.smartports.app/ShippingLines.json
{
  
  result: [
    {
        "carrier_code": "ALRB",
        "carrier_name": "AC Container Line"
    },
    {
        "carrier_code": "ADMU",
        "carrier_name": "Admiral Container Lines"
    },
    {
        "carrier_code": "ANRM",
        "carrier_name": "Alianca"
    },
    {
        "carrier_code": "APLU",
        "carrier_name": "American President Lines (APL)"
    },
    {
        "carrier_code": "ARKU",
        "carrier_name": "Arkas"
    },
    {
        "carrier_code": "ACLU",
        "carrier_name": "Atlantic Container Line (ACL)"
    },
    {
        "carrier_code": "ANNU",
        "carrier_name": "Australia National Line (ANL)"
    },
    {
        "carrier_code": "BLJU",
        "carrier_name": "Avana Global FZCO (BALAJI)"
    },
    {
        "carrier_code": "BURU",
        "carrier_name": "BAL Container Line"
    },
    ......
                </code></pre>
            
        </div>


        <div class="overflow-hidden content-section" id="content-errors">
            <h2>Errors</h2>
            <p>
                The Smartports API uses the following error codes:
            </p>
            <table>
                <thead>
                <tr>
                    <th>Error Code</th>
                    <th>Meaning</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>X000</td>
                    <td>
                        Some parameters are missing. This error appears when you don't pass every mandatory parameters.
                    </td>
                </tr>
                <tr>
                    <td>X001</td>
                    <td>
                        Unknown or unvalid <code class="higlighted">api_key</code>. This error appears if you use an unknow API key or if your API key expired.
                    </td>
                </tr>
                <tr>
                    <td>X002</td>
                    <td>
                        Unvalid <code class="higlighted">api_key</code> for this domain. This error appears if you use an  API key non specified for your domain. Developper or Universal API keys doesn't have domain checker.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="content-code"></div>
</div>

@endsection



@push('scripts')

   

@endpush
