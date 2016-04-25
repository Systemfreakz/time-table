<?php

class Category {

    private $data;
    private $id;
    private $name;
    private $color;

    public final static function getAllCategories() {
        global $wpdb;
        $categories = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table_category');
        $resultCategories = array();
        foreach ($categories as $category) {
            $resultCategories[] = new Category($category);
        }
        return $resultCategories;
    }

    public function __construct($data) {
        if (is_numeric($data)) {
            global $wpdb;
            $data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'time_table_category WHERE id = ' . $data);
            if (array_key_exists(0, $data)) {
                $data = $data[0];
            }else {
                $data = new stdClass;
                $data->name = "";
                $data->id = "";
                $data->color = "";
                echo '<div id="message" class="error notice is-dismissible">
                <p>Die Kategorie eines Kurses existiert nicht</p>
            </div>';
            }
        }else if (is_null($data)) {
            $data = new stdClass;
            $data->name = "";
            $data->id = "";
            $data->color = "";
        }
        $this->data = $data;
        $this->name = $data->name;
        $this->id = $data->id;
        $this->color = $data->color;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getColor() {
        return $this->color;
    }

}