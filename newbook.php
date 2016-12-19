<?php
$pageTitle = 'Добави книга';
include dirname(__FILE__) . '/includes/header.php';
?>

<div><h3>Добави нова книра</h3></div>
<div><a href="index.php">Книги</a></div><div><br/></div>
<form method="POST" action="newbook.php">
<div>Име на книгата: <input type="text" name="book_title" />
<input type="submit" name="addbook" value="Добави" /></div>
	<?php
$authors = getAuthors($con);
if ($authors === false) {
    echo "Грешка";
}
?>
	<div><h4>Автори: <h4></div><div><select name="authors[]" multiple size="23">
		<?php
foreach ($authors as $row) {
    echo '<option value="' . $row['author_id'] . '">' . $row['author_name'] . '</option>';
}
?>
	</select></div>
</form>

<?php

if (isset($_POST['addbook'])) {
    $book_title = trim($_POST['book_title']);
    if (!isset($_POST['authors'])) {
        $_POST['authors'] = '';
    }
    $authors = $_POST['authors'];
    $er      = [];
    if (mb_strlen($book_title) < 2) {
        $er[] = "<p>Името на книгата трябва да не е по-късо от 2 символа</p>";
    }
    if (!is_array($authors) || count($authors) == 0) {
        $er[] = "<p>Трябва да изберете автор/и на книгата</p>";
    }

    if (!isAuthorIdExist($con, $authors)) {
        $er[] = "<p>Невалиден автор</p>";
    }

    if (count($er) > 0) {
        foreach ($er as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    else{
        mysqli_query($con, 'INSERT INTO books (book_title) VALUES ("'.mysqli_real_escape_string($con, $book_title).'")');
        if (mysqli_error($con)) {
            echo "Грешка";
            exit;
        }
        $id = mysqli_insert_id($con);
        foreach ($authors as $authorId) {
            mysqli_query($con, 'INSERT INTO books_authors (book_id, author_id) VALUES ('.$id.','.$authorId.')');
            if (mysqli_error($con)) {
                echo "Грешка";
                exit;
            }
        }
        echo "Книгата е добавена успешно";
        
    }
}

?>

<?php
include dirname(__FILE__) . '/includes/footer.php';
?>