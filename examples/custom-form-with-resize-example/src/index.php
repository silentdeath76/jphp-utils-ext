<?php 

\php\gui\UXApplication::launch(function (\php\gui\UXForm $form) {
    $form->add($container = new \php\gui\layout\UXScrollPane(new \php\gui\layout\UXAnchorPane()));
    $container->width = 640;
    $container->height = 400;

    \utils\helpers\FormResizer::$debug = true;
    $resize = \utils\helpers\FormResizer::init($form);
    $resize->minSize(400, 250);


    // $container->content->add();

    $form->style = 'UNDECORATED';
    $form->show();
});