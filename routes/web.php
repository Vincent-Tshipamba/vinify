<?php

use App\Models\User;
use App\Models\TextAnalysis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TextAnalysisController;
use App\Http\Controllers\RolePermissionController;

Route::get('/', function () {
    $nbrAnalyses = TextAnalysis::count();
    $nbrUsers = User::count();

    return view('welcome', compact('nbrAnalyses', 'nbrUsers'));
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/subscription', [UniversityController::class, 'index'])->name('subscription.index');
    
    Route::post('/universities', [UniversityController::class, 'store']);
    
    Route::post('/plagiarism-check', [TextAnalysisController::class, 'analyzeFile']);

    Route::get('/text-analyses/', [TextAnalysisController::class, 'index'])->name('analyses.index');
    Route::get('/analyses', [TextAnalysisController::class, 'detectAIText'])->name('ai-detection');
    Route::get('/analyses/{textAnalyseId}', [TextAnalysisController::class, 'show'])->name('analyses.show');
    Route::post('/analyses/{textAnalyseId}/delete', [TextAnalysisController::class, 'delete'])->name('analyses.delete');

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    Route::post('/upload-text', [TextAnalysisController::class, 'extractText'])->name('analyze.file');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::get('/admin/roles-permissions', [RolePermissionController::class, 'index'])->name('admin.roles-permissions');
    Route::get('/users-roles', [RolePermissionController::class, 'getUsersRoles'])->name(name: 'roles.users.index');
    Route::get('/roles-permissions', [RolePermissionController::class, 'getRolesPermissions'])->name('roles.permissions.index');
    Route::post('/users/roles/update', [RolePermissionController::class, 'updateUserRole'])->name('users.roles.update');
    Route::post('/roles-permissions/update', [RolePermissionController::class, 'updateRolePermissions'])->name(name: 'roles.permissions.update');

    Route::get('/contact', function () {
        return view('vinify.contact');
    })->name('contact');

    // Roles only
    Route::post('/roles/create', [RolePermissionController::class, 'createRole'])->name('roles.store');
    Route::put('/roles/{role}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');

    // Permissions only
    Route::post('/permissions/create', [RolePermissionController::class, 'createPermission'])->name('permissions.store');
    Route::put('/permissions/{permission}', [RolePermissionController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('permissions.destroy');
});

require __DIR__ . '/auth.php';
