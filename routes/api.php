<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EnterLinkController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\LinkDirectoryController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\LinkHitController;
use App\Http\Controllers\Admin\LinkEmbededController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\EnterLinkHitController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PluginRepoController;
use App\Http\Controllers\Admin\TeleSaleController;
use App\Http\Controllers\Admin\TeleSaleLineController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\KeywordController;
use App\Http\Controllers\Admin\SatiliteDomainController;
use App\Http\Controllers\Admin\KeywordTrackingController;
use App\Http\Controllers\Admin\TeleSaleAgentController;
use App\Http\Controllers\Admin\FbLeagueController;
use App\Http\Controllers\Admin\TeleSaleVipController;
use App\Http\Controllers\Admin\TeleSaleCategoryController;
use App\Http\Controllers\Admin\PopupController;

use App\Http\Controllers\Admin\VoiceController;
use App\Http\Controllers\Admin\TeleSaleRecordController;

use App\Http\Controllers\Front\LinkController as FrontLinkController;
use App\Http\Controllers\Front\LinkEmbededController as FrontLinkEmbededController;
use App\Http\Controllers\Front\LinkDirectoryController as FrontLinkDirectoryController;
use App\Http\Controllers\Front\FolderController as FrontFolderController;
use App\Http\Controllers\Front\EnterLinkController as FrontEnterLinkController;
use App\Http\Controllers\Front\LiveFootballController as FrontLiveFootballController;

use App\Models\Permision;
use App\Models\PluginRepo;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user', 'middleware' => 'check.permision'], function () {

    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('check.permision');

    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('check.permision');

    Route::get('/me', [AuthController::class, 'me'])->withoutMiddleware('check.permision');

    Route::post('/logout', [AuthController::class, 'logout'])->withoutMiddleware('check.permision');

    Route::get('/refresh', [AuthController::class, 'refresh'])->withoutMiddleware('check.permision');

    Route::get('/', [UserController::class, 'index']);

    Route::post('/', [UserController::class, 'store']);

    Route::put('/{id}', [UserController::class, 'update']);

});

Route::group(['prefix' => 'link', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/hit/click-chart', [LinkHitController::class, 'getChartClick']);

    Route::get('/hit/filter', [LinkHitController::class, 'filter'])->withoutMiddleware(['check.permision']);

    Route::get('/hit', [LinkHitController::class, 'index']);

    Route::get('/hit/{id}', [LinkHitController::class, 'getLinkHitByLinkId']);

    Route::get('/', [LinkController::class, 'index']);

    Route::post('/', [LinkController::class, 'store']);

    Route::post('/create', [LinkController::class, 'createMany']);

    Route::get('/filter', [LinkController::class, 'filter'])->withoutMiddleware(['check.permision']);

    Route::post('/upload-image', [LinkController::class, 'upload']);

    Route::put('/{link}', [LinkController::class, 'update']);

    Route::get('/{link}', [LinkController::class, 'show']);

    Route::delete('/{id}', [LinkController::class, 'destroy'])->withoutMiddleware(['check.permision']);

});


Route::group(['prefix' => 'link-directory', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/', [LinkDirectoryController::class, 'index']);

    Route::post('/', [LinkDirectoryController::class, 'store']);

    Route::get('/filter', [LinkDirectoryController::class, 'filter'])->withoutMiddleware(['check.permision']);

    Route::put('/{linkDirectory}', [LinkDirectoryController::class, 'update']);

    Route::get('/{script_id}', [FrontLinkDirectoryController::class, 'show'])->withoutMiddleware(['auth:api', 'check.permision']);

    Route::delete('/{id}', [LinkDirectoryController::class, 'destroy'])->withoutMiddleware(['check.permision']);

});

Route::group(['prefix' => 'banner'], function () {

    Route::get('/{script_id}', [FrontLinkDirectoryController::class, 'show']);
});

Route::group(['prefix' => 'link-enter'], function () {

    Route::get('/{script_id}', [FrontFolderController::class, 'show']);

});
Route::group(['prefix' => 'link-embed', 'middleware' => 'auth:api'], function () {

    Route::get('/trash', [LinkEmbededController::class, 'trash']);

    Route::put('/trash/{id}', [LinkEmbededController::class, 'trashRestore']);

    Route::delete('/trash/{id}', [LinkEmbededController::class, 'trashDestroy']);

    Route::get('/', [LinkEmbededController::class, 'index']);

    Route::post('/', [LinkEmbededController::class, 'store']);

    Route::get('/{slug}', [FrontLinkEmbededController::class, 'show'])->withoutMiddleware('auth:api');

    Route::put('/{id}', [LinkEmbededController::class, 'update']);

    Route::delete('/{id}', [LinkEmbededController::class, 'destroy']);
});


Route::group(['prefix' => 'redirector'], function () {

    Route::get('/link/{slug}', [FrontEnterLinkController::class, 'show']);

    Route::get('/', [FrontLinkController::class, 'index']);

    Route::get('/{slug}', [FrontLinkController::class, 'show']);

});

Route::group(['prefix' => 'media', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/', [MediaController::class, 'index']);

    Route::post('/', [MediaController::class, 'store']);

    Route::put('/{id}', [MediaController::class, 'update']);

    Route::delete('/{id}', [MediaController::class, 'destroy']);
});

Route::group(['prefix' => 'enter-link', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/filter', [EnterLinkController::class, 'filter'])->withoutMiddleware(['check.permision']);

    Route::post('/delete-many', [EnterLinkController::class, 'deleteMany']);

    Route::post('/edit-vote', [EnterLinkController::class, 'editVote']);

    Route::get('/hit/{id}', [EnterLinkHitController::class, 'getEnterLinkHitById'])->withoutMiddleware(['check.permision']);

    Route::post('/create', [EnterLinkController::class, 'createMany']);

    Route::put('/{enterLink}', [EnterLinkController::class, 'update']);

    Route::get('/', [EnterLinkController::class, 'index']);

    Route::get('/{id}', [EnterLinkController::class, 'show']);

    Route::post('/', [EnterLinkController::class, 'store']);

    Route::delete('/{id}', [EnterLinkController::class, 'destroy']);
});

Route::group(['prefix' => 'folder', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::put('/{folder}', [FolderController::class, 'update']);

    Route::get('/', [FolderController::class, 'index']);

    Route::post('/', [FolderController::class, 'store']);

    Route::get('/filter', [FolderController::class, 'filter'])->withoutMiddleware('check.permision');

    Route::get('/shortcode', [FrontFolderController::class, 'shortcode'])->withoutMiddleware(['auth:api', 'check.permision']);

    Route::post('/deletes', [FolderController::class, 'deletes']);
});

Route::group(['prefix' => 'vote'], function () {
    Route::post('/', [FrontEnterLinkController::class, 'vote']);
});

Route::group(['prefix' => 'role-permision', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/roles', [RoleController::class, 'index']);

    Route::get('/permisions', [PermisionController::class, 'index']);

    Route::post('/', [RoleController::class, 'create']);

    Route::put('/{id}', [RoleController::class, 'update']);
});

Route::group(['prefix' => 'plugin-repo', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/{id}', [PluginRepoController::class, 'show'])->withoutMiddleware(['auth:api', 'check.permision']);

    Route::get('/', [PluginRepoController::class, 'index']);

    Route::post('/', [PluginRepoController::class, 'store']);

    Route::put('/{id}', [PluginRepoController::class, 'update']);

    Route::post('/upload', [PluginRepoController::class, 'uploadPlugin']);
});

Route::group(['prefix' => 'telesales', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/lines', [TeleSaleLineController::class, 'index']);
    Route::post('/lines', [TeleSaleLineController::class, 'store']);
    Route::post('/lines/delete', [TeleSaleLineController::class, 'deleteMany']);
    Route::put('/lines/{id}', [TeleSaleLineController::class, 'update']);

    Route::post('/bulk-edit', [TeleSaleController::class, 'bulkEdit'])->withoutMiddleware('check.permision');

    Route::post('/bulk-action', [TeleSaleController::class, 'bulkAction'])->withoutMiddleware('check.permision');

    Route::post('/agents', [TeleSaleController::class, 'getAgent'])->withoutMiddleware('check.permision');

    Route::get('/tele-line', [TeleSaleController::class, 'teleLine'])->withoutMiddleware('check.permision');

    Route::post('/update-assign', [TeleSaleController::class, 'updateAssign']);
    Route::post('/update-line', [TeleSaleController::class, 'updateLine'])->withoutMiddleware('check.permision');
    Route::post('/update-agent', [TeleSaleController::class, 'updateAgent'])->withoutMiddleware('check.permision');

    Route::post('/delete', [TeleSaleController::class, 'deleteMany']);

    Route::get('/users', [TeleSaleController::class, 'users'])->withoutMiddleware('check.permision');

    Route::get('/duplicates', [TeleSaleController::class, 'checkDuplicates']);

    Route::post('/import', [TeleSaleController::class, 'import']);


    Route::get('/sources', [TeleSaleController::class, 'listSource'])->withoutMiddleware('check.permision');


    Route::get('/', [TeleSaleController::class, 'index'])->withoutMiddleware('check.permision');

    Route::post('/', [TeleSaleController::class, 'store']);

    Route::put('/{id}', [TeleSaleController::class, 'update']);

});

Route::group(['prefix' => 'post', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::post('/update-all-post-content', [PostController::class, 'updateAllPostContent'])->withoutMiddleware(['check.permision']);
    Route::post('/deletes', [PostController::class, 'deleteMany']);
    Route::post('/create', [PostController::class, 'createMany'])->withoutMiddleware(['check.permision']);
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);
    Route::put('/{id}', [PostController::class, 'update']);

    Route::get('/content', [PostController::class, 'getContent'])->withoutMiddleware(['auth:api', 'check.permision']);

});

Route::group(['prefix' => 'keyword', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::get('/keycode', [KeywordController::class, 'getKeyCode'])->withoutMiddleware(['check.permision']);
    Route::put('/keycode/{id}', [KeywordController::class, 'updateKeyCode'])->withoutMiddleware(['check.permision']);

    Route::post('/deletes', [KeywordController::class, 'deleteMany']);
    Route::post('/create', [KeywordController::class, 'createMany'])->withoutMiddleware(['check.permision']);
    Route::get('/', [KeywordController::class, 'index']);
    Route::post('/', [KeywordController::class, 'store']);
    Route::put('/{id}', [KeywordController::class, 'update']);

    Route::get('/content', [KeywordController::class, 'getContent'])->withoutMiddleware(['auth:api', 'check.permision']);
    Route::post('/password', [KeywordController::class, 'getPassword'])->withoutMiddleware(['auth:api', 'check.permision']);
    Route::post('/check-password', [KeywordController::class, 'checkPassword'])->withoutMiddleware(['auth:api', 'check.permision']);


});
Route::group(['prefix' => 'satilite-domain', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::post('/deletes', [SatiliteDomainController::class, 'deleteMany']);
    Route::post('/create', [SatiliteDomainController::class, 'createMany'])->withoutMiddleware(['check.permision']);
    Route::get('/', [SatiliteDomainController::class, 'index']);
    Route::post('/', [SatiliteDomainController::class, 'store']);
    Route::put('/{id}', [SatiliteDomainController::class, 'update']);

});

Route::group(['prefix' => 'keyword-tracking', 'middleware' => ['auth:api', 'check.permision']], function () {
    Route::get('/', [KeywordTrackingController::class, 'index']);
    Route::get('/filter', [KeywordTrackingController::class, 'filter'])->withoutMiddleware(['check.permision']);
    Route::get('/click-chart', [KeywordTrackingController::class, 'getChartClick'])->withoutMiddleware(['check.permision']);
});

Route::group(['prefix' => 'telesaleagent', 'middleware' => ['auth:api',  'check.permision']], function () {
    Route::post('/delete', [TeleSaleAgentController::class, 'deleteMany']);
    Route::get('/', [TeleSaleAgentController::class, 'index']);
    Route::post('/', [TeleSaleAgentController::class, 'store']);
    Route::put('/{id}', [TeleSaleAgentController::class, 'update']);
});

Route::group(['prefix' => 'fbstat', 'middleware' => ['auth:api', 'check.permision']], function () {
    Route::post('/leagues-delete', [FbLeagueController::class, 'destroy']);
    Route::put('/leagues/{id}', [FbLeagueController::class, 'update']);
    Route::get('/leagues', [FbLeagueController::class, 'index']);
    Route::post('/leagues', [FbLeagueController::class, 'store']);
});

Route::group(['prefix' => 'telesalevip', 'middleware' => ['auth:api', 'check.permision']], function () {
    Route::post('/delete', [TeleSaleVipController::class, 'deleteMany']);
    Route::get('/', [TeleSaleVipController::class, 'index']);
    Route::post('/', [TeleSaleVipController::class, 'store']);
    Route::put('/{id}', [TeleSaleVipController::class, 'update']);
});

Route::group(['prefix' => 'telesalecategory', 'middleware' => ['auth:api', 'check.permision']], function () {
    Route::post('/delete', [TeleSaleCategoryController::class, 'deleteMany']);
    Route::get('/', [TeleSaleCategoryController::class, 'index']);
    Route::post('/', [TeleSaleCategoryController::class, 'store']);
    Route::put('/{id}', [TeleSaleCategoryController::class, 'update']);
});


Route::group(['prefix' => 'telesalerecord', 'middleware' => ['auth:api', 'check.permision']], function () {
    Route::post('/delete', [TeleSaleRecordController::class, 'deleteMany']);
    Route::get('/', [TeleSaleRecordController::class, 'index']);
    Route::get('/statistic', [TeleSaleRecordController::class, 'statisticCall']);
    Route::get('/filter', [TeleSaleRecordController::class, 'getFilter'])->withoutMiddleware(['check.permision']);

    Route::post('/', [TeleSaleRecordController::class, 'store']);
    Route::put('/{id}', [TeleSaleRecordController::class, 'update']);
});


Route::group(['prefix' => 'livefb'], function () {
    Route::get('/schedule', [FrontLiveFootballController::class, 'schedule']);
    Route::get('/result', [FrontLiveFootballController::class, 'result']);
    Route::get('/ranking', [FrontLiveFootballController::class, 'ranking']);
    Route::get('/tylekeo', [FrontLiveFootballController::class, 'tylekeo']);
});


Route::group(['prefix' => 'popup', 'middleware' => ['auth:api', 'check.permision']], function () {

    Route::post('/update-all', [PopupController::class, 'updateAll'])->withoutMiddleware(['check.permision']);
    Route::post('/deletes', [PopupController::class, 'deleteMany']);
    Route::post('/create', [PopupController::class, 'createMany'])->withoutMiddleware(['check.permision']);
    Route::get('/', [PopupController::class, 'index']);
    Route::post('/', [PopupController::class, 'store']);
    Route::put('/{id}', [PopupController::class, 'update']);

    Route::get('/content', [PopupController::class, 'getContent'])->withoutMiddleware(['auth:api', 'check.permision']);
    Route::post('/content', [PopupController::class, 'updateDataTele'])->withoutMiddleware(['auth:api', 'check.permision']);
    Route::post('/content-data', [PopupController::class, 'updateDataTele'])->withoutMiddleware(['auth:api', 'check.permision']);

});


Route::group(['prefix' => 'calling', 'middleware' => ['auth:api']], function () {

    Route::post('/voice', [VoiceController::class, 'voice'])->withoutMiddleware(['auth:api']);
    Route::get('/token', [VoiceController::class, 'token']);
    // Route::post('/events', [VoiceController::class, 'events'])->withoutMiddleware(['auth:api']);
    Route::post('/actions', [VoiceController::class, 'actions'])->withoutMiddleware(['auth:api']);

    // Route::post('/save', [VoiceController::class, 'save']);


});


