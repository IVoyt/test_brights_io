<?php
/**
 * @var string $title
 * @var string $message
 * @var string $originalMessage
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Contact Management App">
    <title>Contact Management App</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            padding-top: 4.5rem;
        }
    </style>
</head>
<body>

<main class="container">
    <h1><?= $title ?></h1>
    <div class="bg-light p-4 rounded">
        <div class="row">
            <div class="col">
                <h3><?= $message ?></h3>
                <p><?= $originalMessage ?></p>
            </div>
        </div>
    </div>
</main>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
