<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DbIntrospectController extends Controller
{
    public function index(Request $request)
    {
        $conn = 'mysql';
        $db = env('DB_DATABASE');

        try {
            $tables = DB::connection($conn)
                ->select("SELECT table_name FROM information_schema.tables WHERE table_schema = ? ORDER BY table_name", [$db]);
        } catch (\Throwable $e) {
            return response("<pre>DB error: " . htmlspecialchars($e->getMessage()) . "</pre>", 500);
        }

        $tableNames = array_map(fn($t) => $t->table_name ?? $t->TABLE_NAME ?? '', $tables);

        $candidate = ['logs', 'user', 'books', 'user_books', 'new_table'];
        $details = [];
        foreach ($candidate as $t) {
            $exists = in_array($t, $tableNames, true);
            $entry = ['exists' => $exists, 'columns' => [], 'rows' => []];
            if ($exists) {
                try {
                    $entry['columns'] = DB::connection($conn)->select("SHOW COLUMNS FROM `$t`");
                } catch (\Throwable $e) {
                    $entry['columns'] = [['error' => $e->getMessage()]];
                }
                try {
                    $entry['rows'] = DB::connection($conn)->select("SELECT * FROM `$t` LIMIT 3");
                } catch (\Throwable $e) {
                    $entry['rows'] = [['error' => $e->getMessage()]];
                }
            }
            $details[$t] = $entry;
        }

        if ($request->query('format') === 'json') {
            return response()->json([
                'conn' => $conn,
                'db' => $db,
                'tableNames' => $tableNames,
                'details' => $details,
            ]);
        }

        return view('db_introspect', [
            'conn' => $conn,
            'db' => $db,
            'tableNames' => $tableNames,
            'details' => $details,
        ]);
    }
}
