<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();

        return response()->json($members);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:suami,istri,anak',
        ]);
        $data['user_id'] = $request->user()->id;

        $member = Member::create($data);

        return response()->json($member, 201);
    }

    public function show(Request $request, Member $member)
    {
        if ($member->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($member);
    }

    public function update(Request $request, Member $member)
    {
        if ($member->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:suami,istri,anak',
        ]);

        $member->update($data);

        return response()->json($member);
    }

    public function destroy(Request $request, Member $member)
    {
        if ($member->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $member->delete();

        return response()->json(null, 204);
    }
}
