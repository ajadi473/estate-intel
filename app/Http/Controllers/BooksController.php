<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client as client;

class BooksController extends Controller
{
    /**
     * @OA\Post(
     *      path="/v1/books/create",
     *      operationId="storeNewBook",
     *      tags={"Books"},
     * security={
     *  {"passport": {}},
     *   },
     *      summary="Stores a new book",
     *      description="Stores a new book",
     *      @OA\Response(
     *          response=201,
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch_all_books(Request $request)
    {
        $search_params = $request['search'];
        if($search_params) {
            $books = BooksResource::collection((Books::where('name', 'like' ,'%'.$search_params.'%')
                                                ->orWhere('country', 'like','%'.$search_params.'%')
                                                ->orWhere('publisher', 'like','%'.$search_params.'%')
                                                ->orWhere('release_date', 'like','%'.$search_params.'%')
                                                ->get()));
        } else {
            $books = BooksResource::collection(Books::all());
        }
    
        $status_code = Response::HTTP_OK;
        $status = "success";
        
        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            'data' => $books,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_book(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'isbn' => 'required',
            'authors' => 'required',
            'country' => 'required',
            'number_of_pages' => 'required',
            'publisher' => 'required',
            'release_date' => 'required|date',
        ]);

        $book_data = $request->all();

        $book = new BooksResource(Books::create($book_data));

        if ($book) {
            $status_code = Response::HTTP_CREATED;
            $status = "success";
        } else {
            $status_code = Response::HTTP_UNAUTHORIZED;
            $status = "failed";
        }

        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            'data' => $book

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_book(Request $request, $id)
    {
        $book = new BooksResource(Books::findOrFail($id));
        $input = $request->all();
        $book_name = $book['name']; 
        

        $book->fill($input)->save();


        $status_code = Response::HTTP_OK;
        $status = "success";


        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            "message" => "The book $book_name was updated successfully",
            'data' => $book

        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function show_book($books)
    {
        $fetch_book = Books::findOrFail($books);
        $book = new BooksResource($fetch_book);

        if ($fetch_book) {
            $status_code = Response::HTTP_OK;
            $status = "success";
        } else {
            $status_code = Response::HTTP_NOT_FOUND;
            $status = "failed";
        }

        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            'data' => $book

        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function destroy_book($books)
    {
        
        $book = new BooksResource(Books::findOrFail($books));


        if ($book) {
            $status_code = Response::HTTP_NO_CONTENT;
            $status = "success";
            $book_name = $book['name'];

            $book->delete();
        } else {
            $status_code = Response::HTTP_NOT_FOUND;
            $status = "Book not found";
        }

        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            "message" => "The book $book_name was deleted successfully",

        ]);
    }

    public function fetch_external_api(Request $request)
    {
        $books_filter_url = env('API_BASE_URL').'?name='.$request['name'];

        $client = new client();
        $response = $client->request('GET', $books_filter_url);
        ;
        $responseBody = json_decode($response->getBody(), true);

        $data = new BooksCollection($responseBody);
        if ($responseBody != null) {
            $status_code = Response::HTTP_OK;
            $status = "success";

        } else {
            $status_code = Response::HTTP_NOT_FOUND;
            $status = "not found";
        }

        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            "data" => $data,

        ]);

    }
}
