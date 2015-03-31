<?php
class Layout{
    private $title;
    public function __construct($title) {
        $this->title = $title;
        ob_start();
    }
    
    public function print_layout(){
        $html = ob_get_contents();
        ob_end_clean();
        
        $content = file_get_contents(__DIR__."/layout.html");
        $content = str_replace("[body]", $html, $content);
        $content = str_replace("[title]", $this->title, $content);
        echo $content;
    }
}

$layout = new Layout('Creating user');

