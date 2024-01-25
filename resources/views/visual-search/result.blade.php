<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Search Results</title>
</head>
<body>

    <h1>Visual Search Results</h1>

    <div>
        <h2>Uploaded Image</h2>
        {{-- <img src="{{ URL::to('/') }}/storage/{{ $uploadedImage->store('uploads') }}" alt="Uploaded Image" width="200"> --}}
        <img src="data:image/jpg;base64,{!! base64_encode(file_get_contents($uploadedImage->path())) !!}" alt="Uploaded Image" width="200">
    </div>

    <div>
        <h2>Similar Item</h2>
        @if($similarItems)
            @foreach ($similarItems as $item)
            <img src="{{ $item->getImageRoute() }}" alt="Similar Item Image" width="200">
            @endforeach
        @else
            <p>No similar item found</p>
        @endif
    </div>

</body>
</html>
