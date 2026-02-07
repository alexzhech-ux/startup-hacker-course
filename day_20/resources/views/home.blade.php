<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Книги</title>
</head>
<body>

<h1>Список книг</h1>

@foreach ($books as $book)
    <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px">
        <h3>{{ $book->title }}</h3>
        <p>{{ $book->description }}</p>

        @if($book->cover)
            <img src="{{ $book->cover }}" width="120">
        @endif

        <p>
            Жанры:
            @foreach ($book->genres as $genre)
                <span>{{ $genre->name }}</span>
            @endforeach
        </p>
    </div>
@endforeach

<hr>

<h2>Добавить книгу</h2>

<form method="POST" action="/books">
    @csrf

    <input name="title" placeholder="Название"><br><br>
    <textarea name="description" placeholder="Описание"></textarea><br><br>

    <label>
        <input type="checkbox" name="is_for_adult" value="1">
        18+
    </label><br><br>

    <button type="submit">Сохранить</button>
</form>

</body>
</html>