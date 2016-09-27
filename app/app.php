<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";
    date_default_timezone_set('America/Los_Angeles');

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
      return $app['twig']->render('home.html.twig');
    });

    $app->get('/students', function() use ($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student'=>null, 'allCourses'=>Course::getAll()));
    });

    $app->post('/students', function() use ($app) {
        $new_student = new Student(null, $_POST['name'], $_POST['enrollment']);
        $new_student-> save();
        $new_student->addCourse($_POST['course_id']);
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student'=>$new_student, 'allCourses'=>Course::getAll(), 'studentCourses'=>$new_student->getCourses()));
    });

    $app->get('/students/{id}', function($id) use ($app) {
        $found_student = Student::find($id);
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student' => $found_student, 'allCourses'=>Course::getAll(), 'studentCourses'=>$found_student->getCourses()));
    });

    $app->delete('/students/delete/{id}', function($id) use ($app) {
        $new_student = Student::find($id);
        $new_student-> delete();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student'=>null, 'allCourses'=>Course::getAll()));
    });

    $app->patch('/students/edit/{id}', function($id) use ($app) {
        $found_student = Student::find($id);
        $found_student-> update($_POST['name'], $_POST['enrollment']);
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student'=>$found_student, 'allCourses'=>Course::getAll(), 'studentCourses'=>$found_student->getCourses()));
    });

    $app->post('/students/add/{id}', function($id) use ($app) {
        $found_student = Student::find($id);
        $found_student-> addCourse($_POST['course_id']);
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student'=>$found_student, 'allCourses'=>Course::getAll(), 'studentCourses'=>$found_student->getCourses()));
    });

    $app->delete('/students/remove/{id}', function($id) use ($app) {
        $found_student = Student::find($id);
        $found_student-> removeCourse($_POST['course_id']);
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll(), 'student'=>$found_student, 'allCourses'=>Course::getAll(), 'studentCourses'=>$found_student->getCourses()));
    });

    $app->get('/courses', function() use ($app) {
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>null));
    });

    $app->post('/courses', function() use ($app) {
        $new_course = new Course(null, $_POST['name'], $_POST['course_number']);
        $new_course->save();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>$new_course));
    });

    $app->get('/courses/{id}', function($id) use ($app) {
        $found_course =  Course::find($id);
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>$found_course, 'courseStudents' => $found_course->getStudents(), 'allStudents' => Student::getAll()));
    });

    $app->post('/courses/{id}', function($id) use ($app) {
        $found_course =  Course::find($id);
        if($_POST['student_ids'] != null){
            $found_course->addStudent($_POST['student_ids']);
        }
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>$found_course, 'courseStudents' => $found_course->getStudents(), 'allStudents' => Student::getAll()));
    });
    $app->delete('/courses/remove/{id}', function($id) use ($app) {
        $found_course =  Course::find($id);
        $found_course->removeStudent($_POST['student_id']);
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>$found_course, 'courseStudents' => $found_course->getStudents(), 'allStudents' => Student::getAll()));
    });


    $app->patch('/courses/edit/{id}', function($id) use ($app) {
        $found_course =  Course::find($id);
        $found_course->update($_POST['name'], $_POST['course_number']);
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>$found_course, 'courseStudents' => $found_course->getStudents(), 'allStudents' => Student::getAll()));
    });
    $app->delete('/courses/delete/{id}', function($id) use ($app) {
        $found_course =  Course::find($id);
        $found_course->delete();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll(), 'course'=>null));
    });


    // $app->post("/", function() use ($app) {
    //     $new_Stylist = new Stylist(null, $_POST['name'], $_POST['date_began'], $_POST['specialty']);
    //     $new_Stylist->save();
    //   return $app['twig']->render('home.html.twig', array('stylists' => Stylist::getAll()));
    // });
    //
    // $app->patch("/{id}/edit", function($id) use ($app) {
    //     $found_stylist= Stylist::find($id);
    //     $found_stylist->update($_POST['name'], $_POST['date_began'], $_POST['specialty']);
    //   return $app['twig']->render('home.html.twig', array('stylists' => Stylist::getAll()));
    // });
    //
    // $app->delete("/delete/{id}", function($id) use ($app) {
    //     $found_stylist = Stylist::find($id);
    //     $found_stylist->delete();
    //   return $app['twig']->render('home.html.twig', array('stylists' => Stylist::getAll()));
    // });
    //
    // $app->get("/stylist/{id}", function($id) use ($app) {
    //     $found_stylist = Stylist::find($id);
    //     $found_clients  = $found_stylist->getClients($id);
    //     return $app['twig']->render('stylist.html.twig', array('stylist' => $found_stylist, 'clients' => $found_clients, 'stylists' => Stylist::getAll()));
    // });
    //
    // $app->post("/stylist/{id}", function($id) use ($app) {
    //     $found_stylist = Stylist::find($id);
    //     $new_client = new Client (null, $_POST['name'], $_POST['last_appointment'], $_POST['next_appointment'], $_POST['stylist_id']);
    //     $new_client->save();
    //     $found_clients  = $found_stylist->getClients($id);
    //     return $app['twig']->render('stylist.html.twig', array('stylist' => $found_stylist, 'clients' => $found_clients, 'stylists' => Stylist::getAll()));
    // });
    //
    // $app->delete("/stylist/{id}/delete/{client_id}", function($id, $client_id) use ($app) {
    //     $found_stylist = Stylist::find($id);
    //     $new_client = Client::find($client_id);
    //     $new_client->delete();
    //     $found_clients =$found_stylist->getClients($id);
    //     return $app['twig']->render('stylist.html.twig', array('stylist' => $found_stylist, 'clients' => $found_clients, 'stylists' => Stylist::getAll()));
    // });
    //
    // $app->patch("/stylist/{id}/edit/", function($id) use ($app) {
    //     $found_stylist = Stylist::find($id);
    //     $stuff = $_POST['person'];
    //     $found_client = Client::find($stuff);
    //     $found_client->update($_POST['name'],$_POST['last_appointment'], $_POST['next_appointment']);
    //     $found_clients =$found_stylist->getClients($id);
    //     return $app['twig']->render('stylist.html.twig', array('stylist' => $found_stylist, 'clients' => $found_clients, 'stylists' => Stylist::getAll()));
    // });

    return $app;
?>
