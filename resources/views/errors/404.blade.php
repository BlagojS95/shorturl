<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md bg-white border border-gray-300 shadow-md rounded p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">404 - Not Found</h1>
        <p class="text-lg text-gray-700 mb-4">Oops! The page you are looking for doesn't exist.</p>
        <a href="{{route('urls.index')}}" class="text-blue-600 hover:underline">Go back to home</a>
    </div>
</body>
</html>