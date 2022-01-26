<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use ApiResponser;

    public $rules = [
        'name' => ['required', 'max:250'],
        'gender' => ['required', 'max:250', 'in:male,female'],
        'country' => ['required', 'max:250'],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $authors = Book::all();

        return $this->successResponse($authors, 200);
    }

    public function store(Request $request)
    {
        $campos = $this->validate($request, $this->rules);
        $author = Book::create($campos);
        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    public function show($book)
    {
        $author = Book::findOrFail($book);

        return $this->successResponse($book, Response::HTTP_OK);
    }

    public function update(Request $request, $book)
    {
        $author = Book::findOrFail($book);
        $campos = $this->validate($request, $this->rules);
        $author->fill($campos);
        if ($author->isClean()) {
            return $this->errorResponse(
                "No hay cambios en los datos",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $author->save();

        return $this->successResponse($author, Response::HTTP_OK);
    }

    public function destroy($book)
    {
        $book = Book::findOrFail($book);
        $book->delete();
        return $this->successResponse($book);
    }
}
