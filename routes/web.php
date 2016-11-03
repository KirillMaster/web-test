<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/','HomeController@index');
Route::get('editor','DemoController@editor');
Route::get('docker','DemoController@docker');
Route::get('getProfiles', 'DemoController@getProfiles');
Route::get('test', 'DemoController@index');
Route::get('auth', 'DemoController@auth');


Auth::routes();


/*
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
*/



Route::get('/', 'HomeController@index');

Route::get('welcome',function (){
   return View('welcome');
});

Route::get('test', function(){return View('student.test');});


Route::group(['prefix' => 'admin'], function(){
    Route::get('/', function(){return View('admin.main');});
    Route::get('main', function(){return View('admin.main');});
    Route::get('students', function(){return View('admin.students');});
    Route::get('groups', function(){return View('admin.groups');});
    Route::get('disciplines', function(){return View('admin.disciplines');});
    Route::get('groups/{id}', function(){return View('admin.groups');});
    Route::get('theme/{id}', function(){return View('admin.themes');});
    Route::get('tests/{id}', function(){return View('admin.tests');});
    Route::get('tests', function(){return View('admin.tests');});
});


/*----------------------DEBUG ROUTES-----------------------------------*/

Route::get('/testSession', function (\Illuminate\Http\Request $request){
    $id = $request->session()->get('sessionId');
    $session = \TestEngine\TestSessionHandler::getSession($id);
    dd($session);
});

Route::get('/testRepo', function (\Illuminate\Http\Request $request){
    $repo =  app()->make(\Repositories\TestResultRepository::class);
    $result =  $repo->getLastForUser(5,5);
    dd($result);
});

Route::get('/deleteFile', function (\Illuminate\Http\Request $request){
   \Helpers\FileHelper::delete('images/questions/t81cnrp5nbebrk6zflv9kan9obeg1v.png');
});

Route::get('/getGroupSemester', function (\Illuminate\Http\Request $request){
    $manager =  app()->make(\Managers\GroupManager::class);
    $groupId = $request->query('groupId');
    $result =  $manager->getCurrentSemesterForGroup($groupId);
    dd($result);
});

/*---------------------------------------------------------------------*/




Route::group(['prefix' => 'api'], function() {

    /*--------------------------------------------------------------------------------
     *      Организационная структура ВУЗа (Институты, профили)
     * -------------------------------------------------------------------------------
     */
    Route::get('institute/{id}/profiles', 'OrgStructureController@getInstituteProfiles');
    Route::get('institutes', 'OrgStructureController@getAllInstitutes');
    Route::get('profiles', 'OrgStructureController@getAllProfiles');


    Route::group(['prefix' => 'profile'], function () {
        Route::get('{id}/groups', 'OrgStructureController@getProfileGroups');
        Route::get('{id}/plans', 'OrgStructureController@getProfilePlans');

        Route::post('create', 'OrgStructureController@createProfile');
        Route::post('update', 'OrgStructureController@updateProfile');
        Route::post('delete/{id}', 'OrgStructureController@deleteProfile');
    });


    /*------------------------------------------------------------------------------
     *                       УЧЕБНЫЕ ПЛАНЫ
     * -----------------------------------------------------------------------------
     */
    Route::group(['prefix' => 'plan'], function () {
        Route::get('{id}', 'StudyPlanController@getPlan');
        Route::get('{id}/disciplines', 'StudyPlanController@getPlanDisciplines');
        Route::post('create', 'StudyPlanController@create');
        Route::post('update', 'StudyPlanController@update');
        Route::post('delete/{id}', 'StudyPlanController@delete');

        /*------------------------------------------------------------------------
        *                      Работа с дисциплинами планов                      */

        Route::group(['prefix' => 'discipline'], function () {
            Route::get('{id}/marks', 'StudyPlanController@getDisciplinePlanMarkTypes');
            Route::post('create', 'StudyPlanController@addDisciplinePlan');
            Route::post('update', 'StudyPlanController@updateDisciplinePlan');
            Route::post('delete/{id}', 'StudyPlanController@deleteDisciplinePlan');
        });

        /*------------------------------------------------------------------------
        *                      Работа с оценками по дисциплинам планов           */

        Route::group(['prefix' => 'mark'], function () {
            Route::post('create', 'StudyPlanController@addDisciplinePlan');
            Route::post('update', 'StudyPlanController@updateDisciplinePlan');
            Route::post('delete/{id}', 'StudyPlanController@deleteDisciplinePlan');
            Route::post('linkTest', 'StudyPlanController@linkMarkToTest');
        });
    });

    /*-----------------------------------------------------------------------------
     *                           ГРУППЫ
     *-----------------------------------------------------------------------------
     */
    Route::group(['prefix' => 'groups'], function () {
        Route::get('show', 'GroupController@getProfileGroupsByNamePaginated');
        Route::get('{id}', 'GroupController@getGroup');
        Route::post('create', 'GroupController@create');
        Route::post('update', 'GroupController@update');
        Route::post('delete/{id}', 'GroupController@delete');
        Route::get('/', 'GroupController@getAll');
        Route::get('{id}/students', 'GroupController@getGroupStudents');

        /*------------------------------------------------------------------------
        *                      Работа со студентами группы                       */

        Route::group(['prefix' => 'student'], function () {

            Route::post('create', 'GroupController@createStudent');
            Route::post('update', 'GroupController@updateStudent');
            Route::post('delete/{id}', 'GroupController@deleteStudent');
            Route::post('setGroup', 'GroupController@setStudentGroup');
        });
    });

    /*-----------------------------------------------------------------------------
     *                           ДИСЦИПЛИНЫ
     *-----------------------------------------------------------------------------
     */
    Route::group(['prefix' => 'disciplines'], function () {
        Route::get('/', 'DisciplineController@getAll');
        Route::post('create', 'DisciplineController@create');
        Route::post('update', 'DisciplineController@update');
        Route::post('delete/{id}', 'DisciplineController@delete');
        Route::get('show', 'DisciplineController@getByNameAndProfilePaginated');
        Route::get('{id}/profiles', 'DisciplineController@getDisciplineProfilesIds');
        Route::get('{id}/tests', 'DisciplineController@getTestsByDiscipline');
        Route::get('{id}', 'DisciplineController@getDiscipline');

        /*------------------------------------------------------------------------
        *                   Работа со темами дисциплин                          */

        Route::get('{id}/themes', 'DisciplineController@getThemes');

        Route::group(['prefix' => 'themes'], function () {
            Route::post('create', 'DisciplineController@createTheme');
            Route::post('update', 'DisciplineController@updateTheme');
            Route::post('delete/{id}', 'DisciplineController@deleteTheme');
            Route::get('{id}', 'DisciplineController@getTheme');

        });
    });

    /*-----------------------------------------------------------------------------
     *                           ПРЕПОДАВАТЕЛИ
     *-----------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'lecturers'], function () {
        Route::post('create', 'LecturerController@create');
        Route::post('update', 'LecturerController@update');
        Route::post('delete/{id}', 'LecturerController@delete');
        Route::get('show', 'LecturerController@getByNamePaginated');
    });

    /*-----------------------------------------------------------------------------
     *                              ВОПРОСЫ
     *-----------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'questions'], function () {
        Route::post('create', 'QuestionController@create');
        Route::post('update', 'QuestionController@update');
        Route::post('delete/{id}', 'QuestionController@delete');
        Route::get('show', 'QuestionController@getByThemeAndTextPaginated');
        Route::get('{id}', 'QuestionController@get');
    });

    /*-----------------------------------------------------------------------------
    *                              ТЕСТЫ
    *------------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'tests'], function () {
        Route::post('create', 'TestController@create');
        Route::post('update', 'TestController@update');
        Route::post('delete/{id}', 'TestController@delete');
        Route::get('show', 'TestController@getByNameAndDisciplinePaginated');

        Route::get('showForStudent', 'TestController@getStudentTestsByDiscipline');
        Route::post('start', 'TestProcessController@startTest');
        Route::post('answer', 'TestProcessController@answer');
        Route::get('nextQuestion', 'TestProcessController@getNextQuestion');
    });

    /*-----------------------------------------------------------------------------
    *                        ДОПОЛНИТЕЛЬНЫЕ ПОПЫТКИ
    *------------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'attempts'], function () {

        Route::get('get', 'TestResultController@getExtraAttemptsCount');
        Route::post('set', 'TestResultController@setExtraAttempts');
    });
    /*-----------------------------------------------------------------------------
    *                           РЕЗУЛЬТАТЫ ТЕСТОВ
    *------------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'results'], function () {
        Route::get('show', 'TestResultController@getByGroupAndTest');
        Route::get('/{id}', 'TestResultController@getById');
    });




});