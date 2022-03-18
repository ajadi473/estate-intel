<?php

namespace App\Http\Controllers;

use App\Models\TradesController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client as client;

use Illuminate\Support\Facades\Http;

class TradesControllerController extends Controller
{
    /**
     * @OA\Post(
     *      path="/v1/trades/all",
     *      operationId="getAllTrades",
     *      tags={"Trades"},
     * security={
     *  {"passport": {}},
     *   },
     *      summary="Get All trades",
     *      description="Returns list of all available trades",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    
    public $authorization;

    public function __construct(client $client)
    {
        $this->client = new client();

    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $trades_get_url = env('API_BASE_URL').'/trade/list';
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $trades_get_url, [ 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        // dd($responseBody['data']['offers']);
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Trades fetch failed';
            $status_code = Response::HTTP_NOT_FOUND;
        } else {
            $message = 'All trades fetched successfully';
            $status_code = Response::HTTP_OK;
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'count' => $responseBody['data']['count'],
            'data' => $responseBody['data']['trades'],
            'message' => $message

        ]);
    }

    public function fetch_a_trade(Request $request)
    {

        $get_url = env('API_BASE_URL').'/trade/get';
        
        $postInput = [
            "trade_hash" => $request->trade_hash
            
        ];
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Trade fetch failed';
            $status_code = Response::HTTP_NOT_FOUND;
            $data =  $responseBody['error']['message'];
        } else {
            $message = 'Trade fetched successfully';
            $status_code = Response::HTTP_OK;
            $data =  $responseBody['data']['trade'];
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'data' => $data,
            'message' => $message

        ]);
    }

    public function start_trade(Request $request)
    {

        $get_url = env('API_BASE_URL').'/trade/start';
        
        $postInput = $request->all();
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Trade start failed';
            $status_code = Response::HTTP_NOT_FOUND;
            $data = $responseBody['error']['message'];
        } else {
            $message = 'Trade started successfully';
            $status_code = Response::HTTP_OK;
            $data = $responseBody['data']['trade_hash'];
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,

        ]);
    }

    public function fetch_completed_trade(Request $request)
    {

        $get_url = env('API_BASE_URL').'/trade/completed';
        
        $postInput = [
            "page" => $request->page_number,
            "partner" => $request->partner
            
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Trade status';
            $status_code = Response::HTTP_NOT_FOUND;
            $data = $responseBody['error']['message'];
        } else {
            $message = 'Trade status';
            $status_code = Response::HTTP_OK;
            $data = $responseBody['data']['trades'];
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,

        ]);
    }


    public function fetch_offers(Request $request)
    {

        $get_url = env('API_BASE_URL').'/offer/all';
        
        $postInput = [
            "offer_type" => $request->offer_type,
            "type" => $request->type,
            "limit" => $request->limit,
            "offset" => $request->page_number,
            
        ];
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        // dd($responseBody['data']['offers']);
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Offer fetch failed';
            $status_code = Response::HTTP_NOT_FOUND;
        } else {
            $message = 'All offers fetched successfully';
            $status_code = Response::HTTP_OK;
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'count' => $responseBody['data']['count'],
            'data' => $responseBody['data']['offers'],
            'message' => $message

        ]);
    }

    public function fetch_an_offer(Request $request)
    {

        $get_url = env('API_BASE_URL').'/offer/get';
        
        $postInput = [
            "offer_hash" => $request->offer_hash
            
        ];
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        // dd($responseBody['data']['offers']);
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Offer fetch failed';
            $status_code = Response::HTTP_NOT_FOUND;
        } else {
            $message = 'Offer fetched successfully';
            $status_code = Response::HTTP_OK;
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'data' => $responseBody['data'],
            'message' => $message

        ]);
    }

    public function create_an_offer(Request $request)
    {

        $get_url = env('API_BASE_URL').'/offer/create';
        
        $postInput = $request->all();
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        // dd($responseBody['error']['message']);
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Offer create failed';
            $status_code = Response::HTTP_NOT_FOUND;
            $data = $responseBody['error']['message'];
        } else {
            $message = 'Offer created successfully';
            $status_code = Response::HTTP_OK;
            $data = '';
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data,

        ]);
    }

    public function list_created_offers(Request $request)
    {

        $get_url = env('API_BASE_URL').'/offer/list';
        
        $postInput = $request->all();
        
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' .env('token'),
            'Accept' => 'application/json',
        ];
  
        $client = $this->client;
        $response = $client->request('POST', $get_url, ['form_params' => $postInput, 'headers' => $headers]);

        $responseBody = json_decode($response->getBody(), true);
     
        $count = $responseBody['data']['count'];
        $status = $responseBody["status"];
        if ($status != "success") {
            $message = 'Offer list failed';
            $status_code = Response::HTTP_NOT_FOUND;
            $data = $responseBody['error']['message'];
        } else {
            $message = $count.' Offer listed';
            $status_code = Response::HTTP_OK;
            $data = $responseBody['data']['offers'];
        }
        
        
        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'count' => $count,
            'data' => $data,

        ]);
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TradesController  $tradesController
     * @return \Illuminate\Http\Response
     */
    public function show(TradesController $tradesController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TradesController  $tradesController
     * @return \Illuminate\Http\Response
     */
    public function edit(TradesController $tradesController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TradesController  $tradesController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TradesController $tradesController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TradesController  $tradesController
     * @return \Illuminate\Http\Response
     */
    public function destroy(TradesController $tradesController)
    {
        //
    }
}
