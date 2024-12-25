<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\MemberStoreRequest;
use App\Http\Requests\Member\MemberUpdateRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return MemberResource::collection( Member::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberStoreRequest $request)
    {
       return new MemberResource( Member::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return  new MemberResource( $member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberUpdateRequest $request, Member $member)
    {
        $member->update($request->validated()) ;
       return new MemberResource($member);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Member::destroy($id);
        return response()->json([
            "message"=>"deleted"
        ]);
    }
}
