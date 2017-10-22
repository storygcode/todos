<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Models\Todo;

Route::middleware('cors')->group(function()
{
	Route::options('/todos', function () {
		return Todo::orderBy('created_at', 'asc')->paginate();
	});

	Route::options('/todos/{id}', function ($id) {

		$todo = Todo::find($id);

		if($todo){
			return $todo;	
		}

		return response()->json(['message' => 'Not found'],404);
		
	});

	Route::get('/todos', function () {
		return Todo::orderBy('created_at', 'asc')->paginate();
	});

	Route::get('/todos/{id}', function ($id) {

		$todo = Todo::find($id);

		if($todo){
			return $todo;	
		}

		return response()->json(['message' => 'Not found'],404);
		
	});

	Route::post('/todos', function (Request $request) {
		
		$only = $request->only(['name']);

		$validator = Validator::make($only, [
			'name' => 'required|max:255',
		]);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()],400);
		}

		return Todo::create($only);

	});

	Route::put('/todos/{todo}', function (Request $request, $id) {

		$only = $request->only(['name']);

		$validator = Validator::make($only, [
			'name' => 'required|max:255',
		]);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()],400);
		}

		$todo = Todo::find($id);
		
		if($todo){
			
			foreach ($only as $key => $value) {
				$todo->$key = $value;
			}

			$todo->save();
			
			return $todo;
		}

		return response()->json(['message' => 'Not found'],404);
		
	});

	/**
	 * Delete Todo
	 */
	Route::delete('/todos/{todo}', function ($id) {

		$todo = Todo::find($id);
		if($todo){
			$todo->delete();
			return $todo;
		}

		return response()->json(['message' => 'Not found'],404);
		
	});
});
