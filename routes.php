<?php
/**
 * All routes here
 */

$router->get('/', 'app/views/employee/EMsign');
$router->get('/EMsign', 'app/views/employee/EMsign');
$router->post('/EMsign', 'app/views/employee/EMsign');

$router->get('/EMlogin', 'app/views/employee/EMlogin');
$router->post('/EMlogin', 'app/views/employee/EMlogin');

$router->get('/EMdash', 'app/views/employee/EMdash');


$router->any('/EMadd', 'app/views/employee/EMadd');
$router->any('/EMupdate/{id}', 'app/views/employee/EMupdate');
$router->get('/delete/{id}', function($id) {
    // 1. Perform the deletion
    db()->table('employees')->where('id', $id)->delete();
    
    // 2. FIX: Redirect to the correct route name
    header('Location: ' . url('/EMdash')); 
    exit;;
});


    $router->get('/logout', function() {
 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header('Location:' . url('/EMlogin'));
    exit;
});
//$router->get('/test-db', function(){
//var_dump(db());
//});

//php -S localhost:3000