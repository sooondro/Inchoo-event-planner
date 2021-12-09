<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

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
                        <form action="/reservations" method="post">
                            <input type="hidden" name="eventId" value="<?= $event->id ?>">
                            <?php if ($data['isLoggedIn']) : ?>
                                <?php if ($event->count < $event->max_attendees) : ?>
                                    <button type="submit" class="btn btn-dark d-flex mx-auto">Make a reservation
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-light d-flex mx-auto" disabled>Event not
                                        reservable
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="card-text">Log in to make a reservation</p>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach;
    } ?>
</div>
