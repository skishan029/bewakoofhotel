<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class FrequentlyAskedQuestionController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\FrequentlyAskedQuestion::withTrashed()->orderByRaw('-deleted_at asc')->latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group">';

                if ($row->trashed()) {
                    $btn .= \App\Helper\Helper::commonDisableEditButton();
                    $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '1', '2');
                } else {
                    $url = route('admin.frontend.frequentlyaskedquestion.edit', ['id'=> $row->id]);
                    $btn .= \App\Helper\Helper::commonEditButton($url);
                    $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '2', '1');
                }
                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('question', function($row){
                return $row->question;
            })
            ->editColumn('answer', function($row){
                return $row->answer;
            })
            ->rawColumns(['action','question','answer'])
            ->make(true);
        }

    }
    function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'question'  => 'required|string',
                'answer'    => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $frequentlyAskedQuestion = new \App\Models\FrequentlyAskedQuestion();
            $frequentlyAskedQuestion->question = (empty($request->question)) ? NULL : strip_tags(trim($request->question));
            $frequentlyAskedQuestion->answer = (empty($request->answer)) ? NULL : strip_tags(trim($request->answer));
            $frequentlyAskedQuestion->save();
            return response()->json(['type'=>'success', 'message'=> "Successfully created FAQ"]);
            
        }
        $data['table'] = collect([
            'question'  =>'Question',
            'answer'    =>'Answer',
            'action'    =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.frequentlyaskedquestion.create');
        $data['dataTableURL'] = route('admin.frontend.frequentlyaskedquestion.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.frequentlyaskedquestion');

        $data['title'] = 'Create & All FAQ';
        return view('admin.frontend.frequentlyaskedquestion.index', $data);
    }
    function edit(Request $request, $id)
    {
        $frequentlyAskedQuestion = \App\Models\FrequentlyAskedQuestion::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'question'  => 'required|string',
                'answer'    => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (blank($frequentlyAskedQuestion)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }

            $frequentlyAskedQuestion->question = (empty($request->question)) ? NULL : strip_tags(trim($request->question));
            $frequentlyAskedQuestion->answer = (empty($request->answer)) ? NULL : strip_tags(trim($request->answer));
            $frequentlyAskedQuestion->save();
            return response()->json(['type'=>'success', 'message'=> "Successfully updated FAQ"]);
            
        }

        if (blank($frequentlyAskedQuestion)) {
            return redirect()->route('admin.frontend.frequentlyaskedquestion.create');
        }

        $data['table'] = collect([
            'question'  =>'Question',
            'answer'    =>'Answer',
            'action'    =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.frequentlyaskedquestion.edit', ['id'=> $id]);
        $data['dataTableURL'] = route('admin.frontend.frequentlyaskedquestion.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.frequentlyaskedquestion');
        $data['title'] = 'Edit & All FAQ';
        $data['frequentlyAskedQuestion'] = $frequentlyAskedQuestion;
        return view('admin.frontend.frequentlyaskedquestion.index', $data);
    }
}
