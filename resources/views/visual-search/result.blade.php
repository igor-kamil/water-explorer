<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Visual Search Results</title>    
</head>
<body class="bg-gray-200 p-8">
    <div class="absolute left-1/2 -ml-0.5 -mt-8 w-0.5 h-screen bg-gray-dark"></div>

    <div class="flex justify-between">

        <div class="w-72">
            <h2 class="text-lg font-bold mb-2">Uploaded Image</h2>
            {{-- <img src="data:image/png;base64,{{ base64_encode(file_get_contents($uploadedImage->path())) }}" alt="Uploaded Image"> --}}
            <img class="w-full h-72 object-cover border border-gray-400 rounded-md" src="data:image/jpg;base64,{{ base64_encode(file_get_contents($uploadedImage->path())) }}" alt="Uploaded Image">
        </div>

        <div class="w-72">
            <h2 class="text-lg font-bold mb-2">Similar Item</h2>
            @if($similarItems)
                {{-- <img class="w-full h-72 object-cover border border-gray-400 rounded-md" src="{{ $similarItems->first()->getImageRoute() }}" alt="Similar Item Image"> --}}
                @foreach ($similarItems as $item)
                <img class="w-full h-72 object-cover border border-gray-400 rounded-md" src="{{ $item->getImageRoute() }}" alt="Similar Item Image"><br>
                @endforeach
            @else
                <p>No similar item found</p>
            @endif
        </div>
    </div>

</body>

</html>
