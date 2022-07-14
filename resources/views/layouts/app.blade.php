<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Анализатор страниц</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
<header>
    <h1>HEADER</h1>
</header>

<main class="flex-grow-1">
    @include('flash::message')

    @yield('content')
</main>

<footer>
    <div>
        <a href="https://hexlet.io/pages/about">Hexlet</a>
    </div>
</footer>

</body>
</html>
