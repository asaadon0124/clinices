<?php

namespace App\Http\Controllers\Documents;

use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Models\UserDocsImage;
use App\Models\UserDocumentation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Documents\UserDocumnetationRequest;

class UserDocumentationController extends Controller
{
    use ApiTrait;
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin') 
        {
            $docs = UserDocumentation::all();

        } elseif($user->role == 'user') 
        {
           $docs = User::find($user->id)->user_docs;
        }else
        {
            // doctor 
        }


        if ($docs->isEmpty()) 
        {
            return $this->errorsMessage(['error' => 'No Data Here']);
        }

        return $this->data(compact('docs'));
    }


    public function store(UserDocumnetationRequest $request)
    {
       $user = Auth::user();
       DB::beginTransaction();
        UserDocumentation::create(
        [
            'desc'      => $request->desc,
            'type'      => $request->type,
            'user_id'   => $user->id,
        ]);

         // CHECK PHOTO
         if ($request->hasFile('image'))
         {
             $photo                 =  $request->image->store('docs_image','public');
             $docs_image            = new UserDocsImage();
             $docs_image->image     = $photo;
             $docs_image->user_id   = $user->id;
             $docs_image->save();
         }
 
        
        DB::commit();
            return $this->successMessage('Created Successfully');
        DB::rollback();

        
    }


    public function update(UserDocumnetationRequest $request,$id)
    {
       $user = Auth::user();
       $doc = UserDocumentation::find($id);
       if ($doc) 
       {
            $doc->update(
            [
                'desc'      => $request->desc,
                'type'      => $request->type,
                'user_id'   => $user->id,
            ]);
    
            return $this->successMessage('Updated Successfully');
       }
       
    }


    public function delete($id)
    {
       $doc = UserDocumentation::find($id);
       if ($doc) 
       {
           $doc->delete();
            return $this->successMessage('Deleted Successfully');
       }
       
    }
}
