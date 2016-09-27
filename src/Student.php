<?php
    Class Student
    {
        private $id;
        private $name;
        private $enrollment;

        function __construct($id=null, $name, $enrollment)
        {
        $this->id = $id;
        $this->name = $name;
        $this->enrollment = $enrollment;
        }
        function getId()
        {
        return $this->id;
        }
        function getName()
        {
        return $this->name;
        }
        function setName($new_name)
        {
        $this->name = $new_name;
        }
        function getEnrollment()
        {
        return $this->enrollment;
        }
        function setEnrollment($new_enrollment)
        {
        $this->enrollment = $new_enrollment;
        }
        function save(){
          $GLOBALS['DB']->exec("INSERT INTO students (name, enrollment) VALUES (
              '{$this->getName()}',
              '{$this->getEnrollment()}'
          );");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }
        function delete(){
          $GLOBALS['DB']->exec("DELETE FROM students WHERE id={$this->getid()};");
        }

        // function getClients($search_id){
        //     $all_clients = Client::getAll();
        //     $matched_clients = array();
        //     foreach($all_clients as $client){
        //         if($client->getStylistId() == $search_id){
        //             array_push($matched_clients, $client);
        //         }
        //     }
        //     return $matched_clients;
        // }

        static function find($search_id){
          $found_student = null;
          $returned_students = Student::getAll();
          foreach($returned_students as $student){
              $student_id = $student->getId();
              if($student_id == $search_id){
                  $found_student = $student;
              }
          }
          return $found_student;
        }

        static function getAll(){
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students");
            $students= array();
            foreach ($returned_students as $student) {
                $id = $student['id'];
                $name = $student['name'];
                $enrollment = $student['enrollment'];
                $new_student = new Student($id, $name, $enrollment);
                array_push($students, $new_student);
          }
          return $students;
        }
        static function deleteAll(){
          $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        function update($name, $enrollment){
            $GLOBALS['DB']->exec("UPDATE students SET
                name ='{$name}',
                enrollment = '{$enrollment}'
                WHERE id ='{$this->getId()}';");
            $this->setName($name);
            $this->setEnrollment($enrollment);
        }

        function addCourse($course_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$this->getId()}, {$course_id});");
        }

        function getCourses()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM students
                JOIN students_courses ON (students_courses.student_id = students.id)
                JOIN courses ON (courses.id = students_courses.course_id)
                WHERE students.id = {$this->getId()};");
            $courses = array();
            foreach($returned_courses as $course) {
                $id = $course['id'];
                $name = $course['name'];
                $course_number = $course['course_number'];
                $new_course = new Course($id, $name, $course_number);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        function removeCourse($course_id)
        {
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE student_id = {$this->getId()} AND course_id = {$course_id};");
        }
    }

 ?>
