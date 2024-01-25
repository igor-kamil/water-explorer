<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Visual Search</title>
</head>
<body class="bg-gray-200 p-8">

    <form action="/visual-search" method="post" enctype="multipart/form-data" class="max-w-md mx-auto bg-white p-6">
        @csrf

        <label for="image" class="block text-sm font-medium text-gray-700">Upload Image:</label>
        <input type="file" name="image" accept="image/*" required class="mt-1 p-2 w-full border rounded-md">

        <button type="submit" class="mt-4 p-2 bg-black text-white rounded-md hover:bg-gray-dark">Search</button>
    </form>

</body>
</html>
