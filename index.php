<?php

date_default_timezone_set("Asia/Tokyo");

$comment_array = array();
$pdo = null;
$stmt = null;
$error_messages = array();

//DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bbs', "double", "Double0906");
} catch (PDOException $e) {
    echo $e->getMessage();
}

//フォームの打ち込み
if (!empty($_POST["submitButton"])) {

    //名前のチェック
    if (empty($_POST["username"])) {
        echo "名前を入力してください";
        $error_messages["username"] = "名前を入力してください";
    }
    //コメントのチェック
    if (empty($_POST["comment"])) {
        echo "コメントを入力してください";
        $error_messages["comment"] = "コメントを入力してください";
    }

    $postDate = date("Y-m-d H:i:s");
    if (empty($error_messages)) {
        # code...

        try {
            $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");

            $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
            $stmt->bindParam(':postDate', $postDate, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

}

//DBからコメントデータ取得
$sql = "SELECT * FROM `bbs-table`;";
$comment_array = $pdo->query($sql);

//DB接続閉じる
$pdo = null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="title">PHPで掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach ($comment_array as $comment): ?>
                <article>
                    <div class="nameArea">
                        <span>名前：</span>
                        <p class="username">
                            <?php echo $comment["username"]; ?>
                        </p>
                        <time>:
                            <?php echo $comment["postDate"]; ?>
                        </time>
                    </div>
                    <p class="comment">
                        <?php echo $comment["comment"]; ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="">名前：</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>
</body>

</html>