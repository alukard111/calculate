<?php
    require_once __DIR__.'/../../init.php';

    $result = null;

    function sortResult() {
        $field = urldecode($_GET['field']);
        $query = "SELECT * FROM PRODUCTS ORDER BY `$field` ASC";

        try {
            $pdo = new PDO('sqlite:'.__DIR__.'/../../db.sqlite');
        } catch (PDOException $Exception) {
            die($Exception->getMessage());
        }

        $resObj = $pdo->prepare($query);
        $resObj->execute();

        return $resObj->fetchAll();
    }

    if (isset($_GET['field'])) {
        $result = sortResult();
        header('Content-Type: application/json');
        echo json_encode($result);

        $template = $twig->load('table.html');
        echo $template->render([
            'sortProducts' => $result
        ]);
    }