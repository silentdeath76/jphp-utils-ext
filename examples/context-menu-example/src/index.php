<?php

use php\gui\{event\UXMouseEvent, layout\UXStackPane, UXApplication, UXButton, UXContextMenu, UXForm};
use php\util\Configuration;
use utils\helpers\gui\ContextMenuHelper;


UXApplication::launch(function (UXForm $form) {
    $form->minWidth = 400;
    $form->minHeight = 400;
    $form->layout = new UXStackPane();
    $form->add($button = new UXButton("Click me"));
    $button->on("click", function (UXMouseEvent $event) use ($button) {
        $contextMenu = new UXContextMenu();

        $config = new Configuration();
        $config->set(ContextMenuHelper::GRAPHIC_WIDTH, 16);
        $config->set(ContextMenuHelper::GRAPHIC_HEIGHT, 16);
        $helper = ContextMenuHelper::of($contextMenu, $config);

        $helper->addItem("New file", function () {
            echo "New Fie\n";
        });

        $helper->addItem("rename", function () {
            echo "Rename\n";
        });

        $sitesCategory = $helper->addCategory("Send to");

        $sitesCategory->addItem("Google disk", function () {
            echo "Google\n";
        });

        $sitesCategory->addItem("Yandex disk", function () {
            echo "Yandex\n";
        });

        $sitesCategory->addItem("Drop Box", function () {
            echo "DropBox\n";
        });

        $contextMenu->showByNode($button, $event->x, $event->y);
    });
    $form->show();
});