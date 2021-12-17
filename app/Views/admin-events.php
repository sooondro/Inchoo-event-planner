<div class="container">
    <?php if (isset($data)) {
        foreach ($data['events'] as $event): ?>
            <div class="row mb-3">
                <div class="card mx-auto" style="width: 18rem">
                    <div class="card-body">
                        <h5 class="card-title"><?= $event->name ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Max: <?= $event->max_attendees ?> |
                            Date: <?= $event->date ?></h6>
                        <p class="card-text"><?= $event->description ?></p>
                        <form action="/delete-event" method="post" >
                            <input type="hidden" name="eventId" value="<?= $event->id ?>">
                            <input type="hidden" name="location" value="/admin-events">
                            <button type="submit" class="btn btn-dark d-flex mx-auto mb-2">Delete event</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach;
    } ?>
</div>