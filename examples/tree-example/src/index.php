<?php

use php\gui\UXApplication;
use php\gui\UXForm;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use utils\helpers\gui\TreeHelper;

UXApplication::launch(function (UXForm $form) {
    $form->add($tree = new UXTreeView());
    $tree->root = new UXTreeItem();

    $files = [
        "path/to/file.txt",
        "path/to/another/file.txt",
        "path/to/secondFile.txt",
        "just/another/file.txt"
    ];

    $helper = new TreeHelper();

    foreach ($files as $file) {
        $helper->makeTree($tree->root, explode('/', $file), function (UXTreeItem $element, $isSub) {
            // Калллбек событие нужно для того, чтобы определять есть вложенные в него элементы или нет,
            // но если в нем нет вложенных элементво, то все равносначалал сработает что элементы есть а потом что нет,
            // т.е. будет два вызова с одним и темже элментом
            // результат выполнения этого кода будте такой
            // path is: dir
            // to is: dir
            // file.txt is: dir
            // file.txt is: file
            // ...
            // file.txt is: dir
            // file.txt is: file
            // для того чтобы поставить нужную иконку у элемента этого более чем достаточно
            echo sprintf("%s is: %s\n", $element->value, $isSub ? "dir" : "file");
        });
    }

    // отсортирует так, что сначала идут элементы с вложениями и потом конечные элеменыт
    $helper->sort($tree->root);

    $form->show();
});