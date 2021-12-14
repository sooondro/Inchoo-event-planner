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
                    <img src="<?= $event->image ?? ''?>" class="card-img-top" alt="Event image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $event->name ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Max: <?= $event->max_attendees ?> |
                            Date: <?= $event->date ?></h6>
                        <p class="card-text"><?= $event->description ?></p>
                        <?php if ($data['isLoggedIn']) : ?>
                            <?php if(in_array($event->id, $data['adminEvents']))  : ?>
                                <form action="/delete-event" method="post">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <input type="hidden" name="location" value="/">
                                    <button type="submit" class="btn btn-outline-danger d-flex mx-auto">Delete event
                                    </button>
                                </form>
                                <form action="/edit-event" method="get">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <button type="submit" class="btn btn-outline-secondary d-flex mx-auto">Edit event
                                    </button>
                                </form>
                            <?php elseif (in_array($event->id, $data['reservedEvents'])): ?>
                                <form action="/delete-reservation" method="post">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <input type="hidden" name="location" value="/">
                                    <button type="submit" class="btn btn-outline-danger d-flex mx-auto">Delete a
                                        reservation
                                    </button>
                                </form>
                            <?php elseif ($event->count < $event->max_attendees): ?>
                                <form action="/reservations" method="post">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <button type="submit" class="btn btn-dark d-flex mx-auto">Make a reservation
                                    </button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-light d-flex mx-auto" disabled>Event not
                                    reservable
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="card-text">Log in to make a reservation</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach;
    } ?>
</div>
