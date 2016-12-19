<?php
$pageTitle = 'Каталог';
include dirname(__FILE__) . '/includes/header.php';
?>

<div><h2>Библиотека</h2></div>
<div><a href="newbook.php">Нова книга</a>&nbsp;&nbsp;&nbsp;<a href="newauthor.php">Нов автор</a></div><div><br/></div>
<form method="GET">
<?php
if (!isset($_GET['author_id'])) {

    echo 'Книги:&nbsp;&nbsp; <select name="sortBynameBook">
		<option value="-1">Без сортиране</option>';
    foreach ($sorting as $key => $value) {
        echo '<option value="' . $key . '"' . ($key == $_GET['sortBynameBook'] ? 'selected' : '') . '>' . $value . '</option>';
    }
    echo '</select>
		<input type="submit" name="sortBooks" value="Сортирай" /><br/><br/>
		Автори: <select name="sortBynameAuthor">
		<option value="-1">Без сортиране</option>';
    foreach ($sorting as $k => $v) {
        echo '<option value="' . $k . '"' . ($k == $_GET['sortBynameAuthor'] ? 'selected' : '') . '>' . $v . '</option>';
    }
    echo '</select>
		<input type="submit" name="sortAuthors" value="Сортирай" /><br/><br/>
		Kнига: <input type="text" name="book_title" />
		<input type="submit" name="search" value="Търси">';
}
?>
	<div><br/></div>
</form>
<?php
if (isset($_GET['author_id'])) {
    echo '<div><a href="index.php">Книги</a></div><div><br/></div>';
    $author_id = (int) $_GET['author_id'];
    $q         = mysqli_query($con, 'SELECT * FROM books_authors as ba
							 INNER JOIN books as b ON ba.book_id=b.book_id
							 INNER JOIN books_authors as bba ON bba.book_id=ba.book_id
							 INNER JOIN authors as a ON bba.author_id=a.author_id
							 WHERE ba.author_id = ' . $author_id);
    if (mysqli_error($con)) {
        echo "Грешка";
    }
} else {
    $sortingB = -1;
    if (isset($_GET['sortBynameBook']) && isset($sorting[$_GET['sortBynameBook']])) {
        $sortingB = $_GET['sortBynameBook'];
    }
    $sortingA = -1;
    if (isset($_GET['sortBynameAuthor']) && isset($sorting[$_GET['sortBynameAuthor']])) {
        $sortingA = $_GET['sortBynameAuthor'];
    }
    if (isset($_GET['search'])) {
        $book_title = trim($_GET['book_title']);
        $book_title = mysqli_real_escape_string($con, $book_title);
    }
    $q = mysqli_query($con, 'SELECT * FROM books
						 INNER JOIN books_authors ON books.book_id = books_authors.book_id
						 INNER JOIN authors ON authors.author_id = books_authors.author_id
						 ' . (!empty($book_title) ? 'WHERE  book_title LIKE "%' . $book_title . '%"' : '') . '
						 ORDER BY books.book_title ' . $sortingB . ', authors.author_name ' . $sortingA);
    if (mysqli_error($con)) {
        echo "Грешка" . mysqli_error($con);
    }
}
$result = [];
while ($row = mysqli_fetch_assoc($q)) {
    $result[$row['book_id']]['book_title']                 = $row['book_title'];
    $result[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
}
if (!$result) {
    echo '<p>В библиотеката няма намерена книга "'.htmlentities($book_title).'" </p>';
} else {

    echo '<table border = "1"><tr><td>Книги</td><td>Автори</td></tr>';
    foreach ($result as $row) {
        echo '<tr><td>' . $row['book_title'] . '</td><td>';
        $ar = [];
        foreach ($row['authors'] as $k => $va) {
            $ar[] = '<a href="index.php?author_id=' . $k . '">' . $va . '</a>';
        }
        echo implode(' , ', $ar) . '</td></tr>';
    }
    echo '</table>';
}

?>

<?php
include dirname(__FILE__) . '/includes/footer.php';
?>