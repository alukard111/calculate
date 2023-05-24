<!-- код отвечающий за рендер таблицы  -->

<?php
    require_once __DIR__.'/init.php';
    //  ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ === add database
    try {
        $pdo = new PDO('sqlite:db.sqlite');
    }
    catch (PDOException $Exception) {
        echo $Exception->getMessage();
    }

    // СОЗДАНИЕ ЗАПРОСА К БД
    $query = "SELECT * FROM PRODUCTS";
    // Подготовка запроса
    $resObj = $pdo->query($query);
    // Запись запроса
    $resObj->execute();
    // Отправка и получение запроса
    $result = $resObj->fetchAll();

    // ===============================================
//        рендер пост запроса

    // отправление переменной в шаблон твига
    class Product {
        private $pdo;
        // ЗАПИСЬ В МАССИВ ВСЕГО ЧТО ИДЕТ В КЛАСС ПРОДУКТ
        public function __construct(
            PDO $pdo,
            $postName,
            $postType,
            $postWeight,
            $postPriceOfPacking,
            $postBoughtPacks,
            $postMargetName,
            $postTown
        )
        {
            $this->pdo = $pdo;
            $this->productData[':postName'] = $postName;
            $this->productData[':postType'] = $postType;
            $this->productData[':postWeight'] = $postWeight;
            $this->productData[':postPriceOfPacking'] = $postPriceOfPacking;

            $this->productData[':postBoughtPacks'] = $postBoughtPacks;
            $this->productData[':postMargetName'] = $postMargetName;
            $this->productData[':postTown'] = $postTown;
            $this->productData[':postDate'] = date('m.d.y');

            $this->productData[':postPriceForKg'] = $this->priceForKg($postWeight, $postPriceOfPacking);
//            написать функции для ввода этой информации
            $this->productData[':postAllSpent'] = $this->getAllSpent($postPriceOfPacking, $postBoughtPacks);

        }

        private function priceForKg($weight, $priceOfPacking) {
            if ($weight < 1000) {
                $countPackingInOneKg = round((1000 / $weight), 1);
                var_dump('\r\n','cointPacking:', $countPackingInOneKg);
                $result = $priceOfPacking * $countPackingInOneKg;
                return $result;
            }
        }
        private function getAllSpent($priceOfPacking, $boughtPacks) {
            return $priceOfPacking * $boughtPacks;
        }
        public $productData = array(
            ':postName' => '',
            ':postType' => '',
            ':postWeight' => '',
            ':postPriceOfPacking' => '',
            ':postPriceForKg' => '',
            ':postBoughtPacks' => '',
            ':postMargetName' => '',
            ':postTown' => '',
            ':postDate' => '',
            ':postAllSpent' => '',
        );
        // ЗАПИСЬ В БАЗУ ДАННЫХ И ОБНАВЛЕНИЕ СТРАНИЦЫ СРАЗУ
        public function setProduct() {
            try {
                $query = "INSERT INTO 'PRODUCTS' (
                   'Product name',
                   'Product type',
                   'Weight of packing',
                   'Price of packing',
                   'Price for 1 kg',
                   'Bought packs',
                   'Market Name',
                   'City/Country', 
                   'Data',
                   'All spent')
                     VALUES (
                     :postName,
                     :postType,
                     :postWeight,
                     :postPriceOfPacking,
                     :postPriceForKg,
                     :postBoughtPacks,
                     :postMargetName,
                     :postTown,
                     :postDate,
                     :postAllSpent
                       )";
                $resObject = $this->pdo->prepare($query);
                $resObject->execute($this->productData);
                header("Location: ".$_SERVER['REQUEST_URI']);
                exit;
            } catch (Exception $e) {
                var_dump('BIND PARAM +++++++++', $e->getMessage());
            };
        }
    }




    // ==========================    Добавление новой записи в бд
    if ($_POST['postName']
        && $_POST['postType']
        && $_POST['postWeight']
        && $_POST['postPriceOfPacking']
        && $_POST['postBoughtPacks']
        && $_POST['postMargetName']
        && $_POST['postTown']
    ) {

    // =============================== проверка на форматы ввода
        $typeValues = (is_string($_POST['postType']) && is_string($_POST['postName']) && (strlen($_POST['postName']) > 0) &&
            is_string($_POST['postMargetName']) && is_string($_POST['postTown']) &&
            is_numeric($_POST['postWeight']) && is_numeric($_POST['postPriceOfPacking']) &&
            is_numeric($_POST['postBoughtPacks']));
        // =============================== проверка на форматы ввода

        if ($typeValues) {
            $productTest = new Product(
                $pdo,
                $_POST['postName'],
                $_POST['postType'],
                $_POST['postWeight'],
                $_POST['postPriceOfPacking'],
                $_POST['postBoughtPacks'],
                $_POST['postMargetName'],
                $_POST['postTown']
            );
            $productTest->setProduct();

        } else {
            return ;
        }

    }


    // ===============================   Удаление продукта по id
    function deleteProduct($id) {
        $pdo = new PDO('sqlite:db.sqlite');
        $query = "DELETE FROM PRODUCTS WHERE `id` = :id";

        $resObj = $pdo->prepare($query);
        $resObj->bindValue(':id', $id);

        $resObj->execute();
        header("Location: ".$_SERVER['REQUEST_URI']);
        exit;
    }

//    ===========================================
    if ($_POST['delete'] && $pdo) {
        var_dump($_POST['delete']);
        deleteProduct($_POST['delete']);
    }
    //==============================================


    //    ============================================
//    function sortResult () {
//        $field = urldecode($_GET['field']);
////        $order = $_GET['order'];
//        $query = "SELECT * FROM PRODUCTS ORDER BY `$field` desc";
//
//        try {
//            $pdo = new PDO('sqlite:db.sqlite');
//        }
//        catch (PDOException $Exception) {
//            echo $Exception->getMessage();
//        }
//
//        // Подготовка запроса
//        $resObj = $pdo->query($query);
//        // Запись запроса
//        $resObj->execute();
//        // Отправка и получение запроса
//        var_dump('FETCH');
//        return $resObj->fetchAll();
//    }
//
//        var_dump($_POST['field'], 'post');
//    if ($_GET['field']) {
//        $field = urldecode($_GET['field']);
//        var_dump('field');
//        $result = sortResult();
////        exit;
//    }
    //    ============================================

    // =================== рендер продуктов
    echo $twig->render('table.html', [
        'products' => $result,
    ]);


