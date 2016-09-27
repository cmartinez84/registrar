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
        static function deleteAll(){
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        function update($name, $course_number){
            $GLOBALS['DB']->exec("UPDATE courses SET
                name ='{$name}',
                course_number = '{$course_number}'
                WHERE id ='{$this->getId()}';");
                $this->setName($name);
                $this->setCourseNumber($course_number);
        }

    }
?>
