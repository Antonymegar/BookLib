<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\HelperService;
use Illuminate\Http\Request;
use PHPUnit\Exception;

class BooksController extends Controller
{
    protected $helperService;

    /**
     * @param $helperService
     */
    public function __construct(HelperService $helperService)
    {
        $this->helperService = $helperService;
    }

    /**
     * Display a listing of all books in the library
     */
    public function index()
    {
        try{
            $books = Book::all();
            if($books){
                return response()->json(['data' => $books], 200);
            }
            return response()->json(['message' => 'no record found'], 200);

        }catch(\Exception $exception){
            return response()->json(
                ['message' => 'error occurred while trying to fetch books',
                    'error' => $exception->getMessage()], 500);
        }
    }


    /**
     * Store new book to the database.
     */
    public function addBook(Request $request)
    {
        $userId = $this->helperService->getCurrentUserId($request);
        $book = new Book();
        try {
            $book->name = $request->name;
            $book->publisher = $request->publisher;
            $book->isbn = $request->isbn;
            $book->category = $request->category;
            $book->sub_category = $request->sub_category;
            $book->description = $request->description;
            $book->pages = $request->pages;
            $book->image = $request->image;
            $book->added_by = $userId;

            $book->save();
            return response()->json(['message' => 'Book added succesfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add book', 'error' => $e->getMessage()],400);
        }


    }

    /**
     * Display the details of a specified book by Id
     */
    public function showBookDetails(string $id)
    {
        try{
            $book = Book::findorfail($id);

            if($book != null){
                return response()->json(['message' =>'Book details','data' => $book],200);
            }else{
                return  response()->json(['message' => 'no record found'],200);
            }
        }catch (\Exception $e){
            return response()->json(['message' => 'error occurred  trying to fetch Book details', 'error' => $e->getMessage()],400);
        }
    }


    /**
     * Update the book that already exists.
     */
    public function updateBookDetails(Request $request, string $id)
    {
        $exisiting_book = Book::findorfail($id);

        if(!$exisiting_book){
            return  response()->json(["no record found"],200);
        }else{
            $exisiting_book->name = $request->name;
            $exisiting_book->publisher = $request->publisher;
            $exisiting_book->isbn = $request->isbn;
            $exisiting_book->category = $request->category;
            $exisiting_book->sub_category = $request->sub_category;
            $exisiting_book->description = $request->description;
            $exisiting_book->pages = $request->pages;
            $exisiting_book->image = $request->image;
            $exisiting_book->addedBy();

            $exisiting_book->update();

            return response()->json(['message' => 'Book record updatyed'], 200);

        }

    }

    /**
     * Remove the specified book from the library.
     */
    public function deleteBookRecord(string $id)
    {
        //
    }
}
