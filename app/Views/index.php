<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Homepage</title>
</head>
<body>

<br>
<div class="container">
    <?php if (isset($data)) {
        foreach ($data as $event): ?>
            <div class="row mb-3">
                <div class="card mx-auto" style="width: 18rem">
                    <div class="card-body">
                        <h5 class="card-title"><?= $event->name ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Max: <?= $event->max_attendees ?> |
                            Date: <?= $event->date ?></h6>
                        <p class="card-text"><?= $event->description ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach;
    } ?>
</div>

</body>
</html>
