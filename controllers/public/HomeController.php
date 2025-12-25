<?php

class HomeController {
    public function index() {
        $homepageModel = new HomepageContent();
        $carousel = $homepageModel->getBySection('carousel');
        $about = $homepageModel->getBySection('about');
        $courses = $homepageModel->getBySection('courses');
        $eventsModel = new Event();
        $events = $eventsModel->getUpcoming();
        $galleryModel = new Gallery();
        $gallery = $galleryModel->getByCategory('featured');

        require 'views/public/homepage/index.php';
    }

    public function about() {
        $homepageModel = new HomepageContent();
        $about = $homepageModel->getBySection('about');
        require 'views/public/homepage/about.php';
    }

    public function courses() {
        $homepageModel = new HomepageContent();
        $courses = $homepageModel->getBySection('courses');
        require 'views/public/homepage/courses.php';
    }

    public function events() {
        $eventsModel = new Event();
        $events = $eventsModel->getAll();
        require 'views/public/homepage/events.php';
    }

    public function gallery() {
        $galleryModel = new Gallery();
        $gallery = $galleryModel->getAll();
        require 'views/public/homepage/gallery.php';
    }

    public function contact() {
        require 'views/public/homepage/contact.php';
    }

    public function admission() {
        require 'views/public/homepage/admission.php';
    }
}