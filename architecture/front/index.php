<?php
$language = 'en';
?>
<html>
<head>
    <title>My online shop</title>
</head>
<body>
    <header>
        <h1>
            <?php require_once 'E:\script_php\architecture\front\header.php'; ?>
        </h1>
    </header>
    <div>
        <h2>
            <?php 
            if($language === 'en')
            {
                echo 'our articles';
            }else{
                echo 'Our products';
            }
            ?> :
        </h2>
        <?php
        function loadArticles(): array {
            return [
                [
                    'name'=>'mother board',
                    'price'=> 120,
                    'description'=>'a super mother!'
                ],[
                    'name'=>'graphic card',
                    'price'=>500,
                    'description'=>'4k has never been so close'
                ],[
                    'name'=>'RAM',
                    'price'=>150,
                    'description'=>'16Go DDR4'
                ],
                ];
        }
        foreach (loadArticles() as $article){
            echo '<div>
            <h3>'.$artcile['name']. ' - ' .$article['price'].'€</h3>
            <p>'.$article['description'].'</p>
            </div>';
        }
        ?>
    </div>
</body>
</html>