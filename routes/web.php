<?php
use App\Http\Controllers\GroupUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LedgerController;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard/data', function (\Illuminate\Http\Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = \App\Models\Ledger::query();

        if ($user->isSuperUser() && $request->has('user_id') && $request->user_id != 'all') {
            $query->where('user_id', $request->user_id);
        } elseif (!$user->isSuperUser()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereRaw('GREATEST(COALESCE(created_at, updated_at), COALESCE(updated_at, created_at)) BETWEEN ? AND ?', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $ledgers = $query->get();

        $dailyData = [];
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($ledgers as $ledger) {
            // Get the newer date between created_at and updated_at
            $dateObj = max($ledger->created_at, $ledger->updated_at) ?: $ledger->created_at;
            $date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');

            if(!isset($dailyData[$date])) {
                $dailyData[$date] = ['income' => 0, 'expense' => 0];
            }
            if ($ledger->type === 'income') {
                $dailyData[$date]['income'] += $ledger->amount;
                $totalIncome += $ledger->amount;
            } else {
                $dailyData[$date]['expense'] += $ledger->amount;
                $totalExpense += $ledger->amount;
            }
        }
        ksort($dailyData);

        $usersList = [];
        if ($user->isSuperUser()) {
            $usersList = \App\Models\User::select('id', 'name')->get();
        }

        return response()->json([
            'labels' => array_keys($dailyData),
            'incomes' => array_column($dailyData, 'income'),
            'expenses' => array_column($dailyData, 'expense'),
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'users' => $usersList,
            'is_super_user' => $user->isSuperUser(),
            'current_user_id' => $user->id
        ]);
    })->name('dashboard.data');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔐 เฉพาะ Super User เท่านั้น
    Route::middleware(['super'])->group(function () {
        Route::resource('group_users', GroupUserController::class);
        Route::resource('users', UserController::class);
    });

    Route::get('/ledgers/import', [LedgerController::class, 'import'])
    ->name('ledgers.import');
    Route::get('/ledgers/{ledger}', [LedgerController::class, 'show'])->name('ledgers.view');

    Route::post('/ledgers/import', [LedgerController::class, 'importStore'])
    ->name('ledgers.import.store');

    Route::get('/ledgers/import/template', [LedgerController::class, 'csv_template'])
    ->name('import.csv_template');

    Route::resource('ledgers',LedgerController::class);

});

require __DIR__.'/auth.php';
