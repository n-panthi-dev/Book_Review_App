<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // This method will show hooks listing page
    public function index(Request $request)
    {

        $books = Book::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $books->where('title', 'like', '%' . $request->keyword . '%');
        }
        $books = $books->withCount('reviews')->withSum('reviews', 'rating')->paginate(10);
        return view('books.list', ['books' => $books]);
    }
    public function create()
    {
        return view('books.create');
    }
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',
        ];
        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }


        $book = new Book();

        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();


        if (!empty($request->image)) {
            File::delete(public_path('uploads/books/') . $book->image);

            $image = $request->image;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/books'), $imageName);

            // Assign the uploaded file's name to the user
            $book->image = $imageName;
            $book->save();
        }
        return redirect()->route('books.index')->with('success', 'Book added successfully');
    }
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', [
            'book' => $book
        ]);


        return view('books.edit');
    }
    public function update($id, Request $request)
    {
        $book = Book::findOrFail($id);

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',
        ];
        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validator);
        }




        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();


        if (!empty($request->image)) {
            File::delete(public_path('uploads/books/') . $book->image);

            $image = $request->image;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/books'), $imageName);

            // Assign the uploaded file's name to the user
            $book->image = $imageName;
            $book->save();
        }
        return redirect()->route('books.index')->with('success', 'Book updated successfully');
    }
    public function destroy(Request $request)
    {
        $book = Book::find($request->id);
        if ($book == null) {
            session()->flash('error', 'Book not found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found'
            ]);
        } else {
            File::delete(public_path('uploads/books/' . $book->image));
            $book->delete();
            session()->flash('success', 'Book deleted successfully');

            return response()->json([
                'status' => true,
                'message' => 'Book deleted successfully'
            ]);
        }
    }
}
