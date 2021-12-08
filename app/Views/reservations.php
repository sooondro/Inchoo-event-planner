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
                            <input type="hidden" name="eventId" value="<?= $event->event_id ?>">
                            <button type="submit">Make a reservation</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach;
    } ?>
</div>

