<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class LedgerController extends Controller
{
    /**
     * 📊 List + Filter + Sort + Pagination
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔐 Validate
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'type'       => 'nullable|in:income,expense',
            'user_id'    => 'nullable|exists:users,id',
            'sort_by'    => 'nullable|in:id,amount,created_at',
            'sort_dir'   => 'nullable|in:asc,desc',
            'per_page'   => 'nullable|in:10,25,50',
        ]);

        $query = Ledger::with('user');

        // 📅 Filter
        $query->when(
            $request->start_date,
            fn($q) => $q->whereDate('created_at', '>=', $request->start_date)
        );

        $query->when(
            $request->end_date,
            fn($q) => $q->whereDate('created_at', '<=', $request->end_date)
        );

        // 💰 Type
        $query->when(
            $request->type,
            fn($q) => $q->where('type', $request->type)
        );

        // 🔐 Role
        if ($user->isSuperUser()) {

            $query->when(
                $request->user_id,
                fn($q) => $q->where('user_id', $request->user_id)
            );

            $users = User::orderBy('name')->get();
        } else {

            $query->where('user_id', $user->id);
            $users = collect();
        }

        // 🔽 Sort
        $sortBy  = $validated['sort_by']  ?? 'id';
        $sortDir = $validated['sort_dir'] ?? 'asc';

        $query->orderBy($sortBy, $sortDir);

        // 📄 Pagination
        $perPage = $validated['per_page'] ?? 10;

        $ledgers = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('ledgers.index', compact(
            'ledgers',
            'users',
            'sortBy',
            'sortDir'
        ));
    }

    /**
     * ➕ Create form
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $users = $user->isSuperUser()
            ? User::orderBy('name')->get()
            : collect();

        return view('ledgers.create', compact('users'));
    }

    /**
     * 💾 Store
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'type'   => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'note'   => 'nullable|string',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'user_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($request, $user) {

            // 📸 upload
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('ledgers', 'public');
            }

            // 🔐 user assign
            $userId = $user->isSuperUser()
                ? ($request->user_id ?? $user->id)
                : $user->id;

            Ledger::create([
                'user_id' => $userId,
                'type'    => $request->type,
                'amount'  => $request->amount,
                'note'    => $request->note,
                'image'   => $imagePath,
            ]);
        });

        return redirect()->route('ledgers.index')
            ->with('success', 'Created successfully');
    }

    /**
     * 👁 Show (optional)
     */
    public function show(Ledger $ledger)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔐 ป้องกันแก้ของคนอื่น
        if (!$user->isSuperUser() && $ledger->user_id != $user->id) {
            abort(403);
        }

        $users = $user->isSuperUser()
            ? User::orderBy('name')->get()
            : collect();

        return view('ledgers.view', compact('ledger', 'users'));

    }

    /**
     * ✏️ Edit form
     */
    public function edit(Ledger $ledger)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔐 ป้องกันแก้ของคนอื่น
        if (!$user->isSuperUser() && $ledger->user_id != $user->id) {
            abort(403);
        }

        $users = $user->isSuperUser()
            ? User::orderBy('name')->get()
            : collect();

        return view('ledgers.edit', compact('ledger', 'users'));
    }

    /**
     * 🔄 Update
     */
    public function update(Request $request, Ledger $ledger)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔐 ป้องกันแก้ของคนอื่น
        if (!$user->isSuperUser() && $ledger->user_id != $user->id) {
            abort(403);
        }

        $request->validate([
            'type'   => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'note'   => 'nullable|string',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($request, $ledger, $user) {

            $imagePath = $ledger->image;

            // 📸 replace image
            if ($request->hasFile('image')) {

                // ลบรูปเก่า
                if ($ledger->image) {
                    Storage::disk('public')->delete($ledger->image);
                }

                $imagePath = $request->file('image')->store('ledgers', 'public');
            }

            $userId = $user->isSuperUser()
                ? ($request->user_id ?? $ledger->user_id)
                : $user->id;

            $ledger->update([
                'user_id' => $userId,
                'type'    => $request->type,
                'amount'  => $request->amount,
                'note'    => $request->note,
                'image'   => $imagePath,
            ]);
        });

        return redirect()->route('ledgers.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * 🗑 Delete
     */
    public function destroy(Ledger $ledger)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔐 ป้องกันลบของคนอื่น
        if (!$user->isSuperUser() && $ledger->user_id != $user->id) {
            abort(403);
        }

        // ลบรูป
        if ($ledger->image) {
            Storage::disk('public')->delete($ledger->image);
        }

        $ledger->delete();

        return redirect()->route('ledgers.index')
            ->with('success', 'Deleted successfully');
    }

    public function import()
    {
        return view('ledgers.import');
    }
    public function importStore(Request $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // ✅ validate file
    $request->validate([
        'fileCSV' => 'required|file|mimes:csv,txt|max:5120',
    ]);

    $file = $request->file('fileCSV');
    $path = $file->getRealPath();

    // ✅ อ่านไฟล์
    $lines = file($path);

    // ✅ parse + fix encoding
    $rows = array_map(function ($line) {

        // 🔥 ลบ BOM (Excel UTF-8)
        $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);

        // 🔥 detect encoding
        $encoding = mb_detect_encoding($line, mb_list_encodings(), true);

        if ($encoding) {
            $line = mb_convert_encoding($line, 'UTF-8', $encoding);
        } else {
            // fallback กันพัง
            $line = iconv('UTF-8', 'UTF-8//IGNORE', $line);
        }

        return str_getcsv($line);

    }, $lines);

    $errors = [];
    $successCount = 0;

    foreach ($rows as $index => $row) {

        // 👉 skip header
        if ($index === 0) continue;

        try {

            // 🔍 validate column
            if (count($row) < 2) {
                throw new \Exception("format ไม่ถูกต้อง");
            }

            $type   = strtolower(trim($row[0]));
            $amount = $row[1];
            $note   = $row[2] ?? null;

            // 🔍 validate type
            if (!in_array($type, ['income', 'expense'])) {
                throw new \Exception("type ต้องเป็น income หรือ expense");
            }

            // 🔍 validate amount
            if (!is_numeric($amount)) {
                throw new \Exception("amount ต้องเป็นตัวเลข");
            }

            // 💾 save
            Ledger::create([
                'user_id' => $user->id,
                'type'    => $type,
                'amount'  => $amount,
                'note'    => $note,
            ]);

            $successCount++;

        } catch (\Exception $e) {

            // ❌ เก็บ error แต่ไม่หยุด
            $errors[] = "Row " . ($index + 1) . " : " . $e->getMessage();

            Log::warning('CSV Import Error', [
                'row' => $index + 1,
                'error' => $e->getMessage()
            ]);
        }
    }

    // 🎯 ส่งผลลัพธ์กลับ
    return redirect()->route('ledgers.import')
        ->with('success', "Import สำเร็จ {$successCount} รายการ")
        ->with('import_errors', $errors);
}

    public function csv_template()
    {
        $filename = 'ledger_template.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ];

        $callback = function () {
            $output = fopen('php://output', 'w');

            // ✅ BOM กัน Excel ภาษาไทยพัง
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ✅ header
            fputcsv($output, ['type', 'amount', 'note']);

            // ✅ sample data
            fputcsv($output, ['income', '1000', 'Salary']);
            fputcsv($output, ['expense', '200', 'Food']);

            fclose($output);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
