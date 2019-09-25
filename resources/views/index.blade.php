<html>
<head>
    <title>Тестове завдання</title>
</head>
<body>
@if ($errors->any())
    <h3>Errors: </h3>
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <h3>Завантажте файл xlsx для імпорту у базу даних</h3>
    <form action='/' method='post' enctype='multipart/form-data'>
        <input type='file' name='file'>
        <input type='submit'>
    </form>
</body>
</html>
