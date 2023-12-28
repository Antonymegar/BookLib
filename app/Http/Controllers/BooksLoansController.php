<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\User;
use Illuminate\Http\Request;

class BooksLoansController extends Controller
{
    /**
     * Display a listing of the bookloans.
     */
    public function index()
    {
       try{
           $book_loans = BookLoan::all();
           if ($book_loans == null){
               return response()->json(['message' => 'no record found']);
           }else{
               return response()->json($book_loans);

           }
       }catch (\Exception $exception){
           return response()->json(['message' => 'error, while retrieving book loans']);
       }
    }

    /**
     * Request book loan from the library.
     */
    public function requestBookLoan(Request $request)
    {
     try{
         $book_loan = new BookLoan();
         //check  whether the user is valid
         $user = User::find($request->user_id);
         //check whether book exists.
         $book = Book::find($request->book_id);

         if(($user && $book) == null){
             return response()->json(['message' => 'user or book not found'], 400);
         }else{
             $book_loan->user_id = $user->id;
             $book_loan->book_id = $book->id;
             $book_loan->loan_date = $request->loan_date;
             $book_loan->due_date = $request->due_date;
             $book_loan->return_date = $request->return_date;
             $book_loan->extended = BookLoan::EXTENDED_NOT;
             $book_loan->extension_date  = null;
             $book_loan->penalty_amount = null;
             $book_loan->penalty_status = BookLoan::PENALTY_STATUS_INACTIVE ;
             $book_loan->status = BookLoan::STATUS_PENDING ; //enum, pending or approved

             $book_loan->save();
             return  response()->json(['message' => 'loan request posted']);

         }
     }catch(\Exception $ex){
         return  response()->json([
             'message' => 'error occurred while posting book loan',
             'error' => $ex->getMessage()]);
     }

    }

    /**
     * Approve book loans request
     */
    public function approveBookLoan(string $id)
    {
        //check the book loan exists
        $book_loan = BookLoan::find($id);

        if(!$book_loan){
            return response()->json(['message' => 'loan record not found'],200);
        }else{
            $book_loan->status = BookLoan::STATUS_APPROVED; //change status to approved or cancelled
            $book_loan->update();
            return response()->json(['message' => 'loan request approved'],200);
        }
    }

    /**
     * Extending book loan request
     */
    public function extendBookLoan(Request $request, string $id)
    {
        try{
            //check whether the loan id exists
            $loan_record = BookLoan::find($id);
            if(!$loan_record){
                return response()->json(['message' => 'unable to retrieve loan record']);
            }
            $existing_due_date = strtotime($loan_record->due_date);
            $extension_date =  $request->extension_date;
            if($existing_due_date >= $extension_date){
                return  response()->json(['message'=>'can not extend on past or same date']);
            }else{
                //new return date is one day after extension date
                $return_date = (new \DateTime($extension_date))->modify('+1 day');
                $loan_record->extension_date = $extension_date;
                $loan_record->return_date = $return_date->format('Y-m-d');
                $loan_record->extended = BookLoan::EXTENDED_YES;

                $loan_record->update();
                return  response()->json(['message'=>'Loan request extended']);
            }

        }catch (\Exception $exception){
            return  response()->json([
                'message'=>'error occurred while extending loan requests',
                'error' =>$exception->getMessage()]);
        }
    }

    /**
     * List of all books borrowed by the user
     * */
    public function bookLoansPerUser(string $user_id)
    {

        try{
            $book_loans = BookLoan::where('user_id', $user_id)->get();
            return $book_loans;
        }catch (\Exception $exception){
            return  response()->json([
                'message'=>'error occurred while fetching user loan requests',
                'error' =>$exception->getMessage()]);
        }
    }

    /**
     * Returned  borrowed book
     */
    public function returnBorrowedBook(string $id)
    {
        try{
            $book_loan_record = BookLoan::find($id);
            if(!$book_loan_record){
                return response()->json([
                    'message' => 'no record not found']);
            }else{
                $book_loan_record->delete($id);
                return response()->json([
                    'message' => 'book returned, and record removed from loan list']);
            }

        }catch (\Exception $exception){
            return response()->json([
                'message' => 'error occurred while returning borrowed book',
                'error' => $exception->getMessage()]);
        }
    }

}
