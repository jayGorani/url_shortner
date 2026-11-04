<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\{DashboardController,ShortUrlController,RedirectController,InvitationController,TeamController,SuperAdminController};

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class,'create'])->name('login')->middleware('guest');

Route::post('/login',[AuthenticatedSessionController::class,'store'])->name('login.store')->middleware('guest');

Route::get('/invitations/{token}',[InvitationController::class,'show'])->name('invitations.show')->middleware('guest');

Route::post('/invitations/accept',[InvitationController::class,'accept'])->name('invitations.accept')->middleware('guest');

Route::middleware('auth')->group(function(){

    Route::post('/logout',[AuthenticatedSessionController::class,'destroy'])->name('logout');

    Route::get('/dashboard',DashboardController::class)->name('dashboard');

    Route::get('/short-urls',[ShortUrlController::class,'index'])->name('short-urls.index');

    Route::middleware('role:admin,member')->group(function(){
        Route::get('/short-urls/create',[ShortUrlController::class,'create'])->name('short-urls.create');
        Route::post('/short-urls',[ShortUrlController::class,'store'])->name('short-urls.store');
    });

    Route::middleware('role:admin,super_admin')->group(function(){
        Route::get('/invitations', [InvitationController::class,'index'])->name('invitations.index');
        Route::post('/invitations',[InvitationController::class,'store'])->name('invitations.store');
        Route::post('/invitations/{invitation}/resend',[InvitationController::class,'resend'])->name('invitations.resend');
        Route::delete('/invitations/{invitation}',[InvitationController::class,'revoke'])->name('invitations.revoke');
    });

    Route::middleware('role:super_admin')->group(function(){
        Route::get('/companies',[SuperAdminController::class,'companies'])->name('super-admin.companies');
        Route::get('/users',[SuperAdminController::class,'users'])->name('super-admin.users');
    });

    Route::middleware('role:admin')->get('/team',[TeamController::class,'index'])->name('team.index');

});

Route::get('/{short_code}',RedirectController::class)->where('short_code','[A-Za-z0-9]{8}')->name('short.redirect');