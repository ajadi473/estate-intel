<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BooksController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch_all_books()
    {
        $books = BooksResource::collection(Books::all());

        if ($books) {
            $status_code = Response::HTTP_OK;
            $status = "success";
        } else {
            $status_code = Response::HTTP_NOT_FOUND;
            $status = "failed";
        }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_book(Request $request, $id)
    {
        // $book = Books::where('id', $id)->first();
        $book = new BooksResource(Books::findOrFail($id));
        $input = $request->all();
        

        $book->fill($input)->save();

        if ($book) {
            $status_code = Response::HTTP_OK;
            $status = "success";
        } else {
            $status_code = Response::HTTP_NOT_FOUND;
            $status = "failed";
        }

        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            "message" => "The book My First Book was updated successfully",
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
        $book = new BooksResource(Books::findOrFail($books));

        if ($book) {
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

        // return $book;

        if ($book) {
            $status_code = Response::HTTP_OK;
            $status = "success";
            $book_name = $book['name'];

            $book->delete();
        } else {
            $status_code = Response::HTTP_NOT_FOUND;
            $status = "failed";
        }

        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            "message" => "The book $book_name was deleted successfully",

        ]);
    }
}
