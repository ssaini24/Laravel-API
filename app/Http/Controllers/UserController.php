<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Rule: select_star — Book::all() translates to SELECT * FROM users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Rule: missing_where_clause — delete without WHERE
    public function deleteAll()
    {
        DB::table('users')->delete();
        return response()->json(['message' => 'All users deleted']);
    }

    // Rule: n_plus_one — correlated subquery per user
    public function withOrderCount()
    {
        $users = User::select('id', 'name')
            ->selectSub(function ($query) {
                $query->from('orders')
                    ->whereColumn('orders.user_id', 'users.id')
                    ->selectRaw('COUNT(*)');
            }, 'order_count')
            ->get();
        return response()->json($users);
    }

    // Rule: function_on_indexed_column — YEAR() on created_at
    public function getByYear($year)
    {
        $users = User::whereYear('created_at', $year)->get();
        return response()->json($users);
    }

    // Rule: missing_where_clause — update without WHERE
    public function deactivateAll()
    {
        DB::table('users')->update(['active' => 0]);
        return response()->json(['message' => 'All users deactivated']);
    }

    // Rule: destructive_ddl — drop table via Schema
    public function dropSessions()
    {
        Schema::drop('sessions');
        return response()->json(['message' => 'Sessions table dropped']);
    }
}
