<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Data Pasar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 p-4">
                <h5 class="text-center mb-4">Pilih File Excel Data Pasar</h5>
                <form action="{{ route('pasar.proses') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control mb-3" required>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('beranda') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success px-4">Upload Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>