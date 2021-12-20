<div class="container">
    <?php if (isset($data)) : ?>
        <?php if (!empty($data['events'])): ?>
            <?php foreach ($data['events'] as $event): ?>
                <div class="row mb-3">
                    <div class="card mx-auto" style="width: 18rem">
                        <div class="card-body">
                            <h5 class="card-title"><?= $event->name ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Max: <?= $event->max_attendees ?> |
                                Date: <?= $event->date ?></h6>
                            <p class="card-text"><?= $event->description ?></p>
                            <form action="/delete-reservation" method="post">
                                <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                <input type="hidden" name="location" value="/reservations">
                                <button type="submit" class="btn btn-dark">Delete a reservation</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3 class="text-center mb-5 mt-5">No reservations made.</h3>
            <div class="text-center">
                <a href="/">
                    <button class="btn btn-outline-secondary">
                        Please make a reservation
                    </button>
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

