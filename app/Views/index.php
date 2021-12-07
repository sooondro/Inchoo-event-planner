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
