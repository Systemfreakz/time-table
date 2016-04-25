<?php

class Course {

    private $data;
    private $id;
    private $link;
    private $description;
    private $image;
    private $name;
    private $category;

    public final static function getAllCourses() {
        global $wpdb;
        $courses = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table_course');
        $resultCourses = array();
        foreach ($courses as $course) {
            $resultCourses[] = new Course($course);
        }
        return $resultCourses;
    }

    public function __construct($data) {
        if (is_numeric($data)) {
            global $wpdb;
            $data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table_course WHERE id = ' . $data);
            $data = $data[0];
        }else if (is_null($data)) {
            $data = new stdClass;
            $data->name = "";
            $data->id = "";
            $data->link = "";
            $data->description = "";
            $data->image = "";
            $data->category = new Category(null);
        }
        $this->data = $data;
        $this->name = $data->name;
        $this->id = $data->id;
        $this->link = $data->link;
        $this->description = $data->description;
        $this->image = $data->image;
        $this->category = new Category($data->category);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLink() {
        return $this->link;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCategory() {
        return $this->category;
    }

}