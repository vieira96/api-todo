<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Todo;

class ApiController extends Controller
{
    public function store(Request $request) 
    {
        $array = [ 'error' => ''];
        
        $rules = [
            'title' => ['required', 'min:3']
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = filter_var($request->input('title'), FILTER_SANITIZE_STRIPPED);
        
        $todo = new Todo();
        $todo->title = $title;
        $todo->save();

        return $array;
    }

    public function readAllTodos()
    {
        $array = ['error' => ''];
        
        // $results = Todo::paginate(2);
        $results = Todo::all();
        // $array['results'] = $results->items();
        $array['results'] = $results;
        // $array['current_page'] = $results->currentPage();
        // $array['total_items'] = $results->total();
        return $array;
    }

    public function readTodo($id)
    {
        $array = ['error' => ''];
        
        $result = Todo::find($id);
        if(!$result) {
            $array['error'] = 'Tarefa não encontrada';
            return $array;
        }
        $array['result'] = $result;
        return $array;
    }

    public function updateTodo($id, Request $request)
    {
        $array = [ 'error' => ''];
        
        $todo = Todo::find($id);
        
        if(!$todo) {
            $array['error'] = "Tarefa não encontrada";
            return $array;
        }

        $rules = [
            'title' => ['min:3'],
            'done' => ['boolean']
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = $request->input('title');
        $done = $request->input('done');
        if($title){
            $todo->title = $title;  
        }
        
        if($done !== NULL) {
            $todo->done = $done;
        }

        $todo->save();

        return $array;
    }

    public function deleteTodo($id)
    {
        $array = [ 'error' => ''];
        
        $todo = Todo::find($id);
        if(!$todo) {
            $array['error'] = "Tarefa não encontrada";
            return $array;
        }
        $todo->delete();

        $array['result']  = 'Tarefa deletada com sucesso';
        return $array;
    }
}
