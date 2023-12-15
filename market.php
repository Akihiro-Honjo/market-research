<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>市場調査アプリ</title>
</head>
<body>
    <h1>価格市場調査</h1>
    <form action="index_create.php" method="POST">
        <div>
        商品：<input type="text" name="product">
        </div>
        <div>
        場所：<input type="text" name="location">
        </div>
        <div>
        価格：<input type="price" name="price">
        </div>
        <div>
        日付：<input type="date" name="date">
        </div>
        <input type="submit" value="送信">
    </form>


<?php

// var_dump($_POST);
// exit();
$str = "";
$array = [];

$file = fopen('data/mr.csv', 'r');
flock($file, LOCK_EX);
// 価格の合計と行数の初期化
$totalPrice = 0;
$rowCount = 0;
// 最小値と最大値の初期化
$minPrice = PHP_INT_MAX; // PHPの最大整数値で初期化
$maxPrice = PHP_INT_MIN; // PHPの最小整数値で初期化

$minPriceLocation = "";
$maxPriceLocation = "";

if($file){
   // 取得したデータを`$str`に追加する
  while ($line = fgets($file)){
    $str .="<tr><td>{$line}</td></tr>";
     // 行をカンマで分割
     $parts = explode(',', $line);
     // 価格データ（3番目の要素）を合計に追加
         if (count($parts) > 2 && is_numeric(trim($parts[2]))) {
           $totalPrice += trim($parts[2]);
           $rowCount++;
           }
         if (count($parts) > 2 && is_numeric(trim($parts[2]))) {
            $price = trim($parts[2]);
            $totalPrice += $price;
            $rowCount++;

            if (count($parts) > 3) { // Assuming the location is the second element
              $location = trim($parts[1]);
              $price = trim($parts[2]);
    
            // 最小値と最大値の更新
            if ($price < $minPrice) {
                $minPrice = $price;
            }
            if ($price > $maxPrice) {
                $maxPrice = $price;

                if ($price == $minPrice) {
                  $minPriceLocation = $location;
              }
              if ($price == $maxPrice) {
                  $maxPriceLocation = $location;
              }
            }
}
}
}
}
// 平均価格の計算
$averagePrice = $rowCount > 0 ? $totalPrice / $rowCount : 0;
// 最小値と最大値が更新されていない場合の処理
if ($minPrice == PHP_INT_MAX) $minPrice = 0;
if ($maxPrice == PHP_INT_MIN) $maxPrice = 0;
flock($file, LOCK_UN);
fclose($file);

?>


<fieldset class="box">
<legend>価格情報</legend>
    <p>最安値: <?= number_format($minPrice, 2) ?> 円 (場所: <?= htmlspecialchars($minPriceLocation) ?>)</p>
    <p>最高値: <?= number_format($maxPrice, 2) ?> 円 (場所: <?= htmlspecialchars($maxPriceLocation) ?>)</p>
    <p>平均価格: <?= number_format($averagePrice, 2) ?> 円</p>
</fieldset>
<fieldset class="box">
    <table>
      <thead>
        <tr>
          <th>調査結果</th>
        </tr>
      </thead>
      <tbody>
      <?= $str ?>
      </tbody>
    </table>
  </fieldset>

  <style>
    .box{
        width: 500px;
    }
  </style>
    
</body>
</html>