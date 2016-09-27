<?php

    class Course
    {
        private $id;
        private $name;
        private $course_number;

        function __construct($id=null, $name, $course_number){
            $this->id = $id;
            $this->name = $name;
            $this->course_number = $course_number;
        }
        function getId(){
            return $this->id;
        }
        function getName(){
            return $this->name;
        }
        function setName($new_name){
            $this->name = $new_name;
        }
        function getCourseNumber(){
            return $this->course_number;
        }
        function setCourseNumber($new_course_number){
            $this->course_number = $new_course_number;
        }

        function save(){
            $GLOBALS['DB']->exec("INSERT INTO courses (name, course_number) VALUES (
                '{$this->getName()}',
                '{$this->getCourseNumber()}'
            );");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }
        function delete(){
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id={$this->getid()};");
        }


        static function find($search_id){
            $returned_courses = Course::getAll();
            foreach($returned_courses as $course){
                $course_id = $course->getId();
                if($course_id == $search_id){
                    $found_course = $course;
                }
            }
            return $found_course;
        }

        static function getAll(){
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses");
            $courses= array();
            foreach ($returned_courses as $course) {
                $id = $course['id'];
                $name = $course['name'];
                $course_number = $course['course_number'];
                $new_course = new Course($id, $name, $course_number);
                array_push($courses, $new_course);
            }
            return $courses;
        }
        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        function update($name, $course_number)
        {
            $GLOBALS['DB']->exec("UPDATE courses SET
                name ='{$name}',
                course_number = '{$course_number}'
                WHERE id ='{$this->getId()}';");
                $this->setName($name);
                $this->setCourseNumber($course_number);
        }
        function addStudent($student_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$student_id}, {$this->getId()});");
        }
        function getStudents()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT students.* FROM courses
                JOIN students_courses ON(students_courses.course_id = courses.id)
                JOIN students ON(students.id = students_courses.student_id)
                WHERE courses.id = {$this->getId()};"
            );
            $students = array();
            foreach ($returned_students as $student){
                $id = $student['id'];
                $name = $student['name'];
                $enrollment = $student['enrollment'];
                $found_student = new Student($id, $name, $enrollment);
                array_push($students, $found_student);

            }
            return $students;
        }

    }
?>
