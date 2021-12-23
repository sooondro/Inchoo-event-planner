<?php if (empty($data['events'])): ?>
    <h3 class="text-center my-3">Sorry, there is no events planned...</h3>
    <?php if ($data['isAdmin']): ?>
        <a href="/create-event" style="text-decoration: none">
            <button type="button" class="btn btn-outline-secondary d-flex mx-auto">
                Create a new event
            </button>
        </a>
    <?php endif; ?>
<?php else: ?>
    <h3 class="text-center my-3">All Future Events</h3>
<?php endif; ?>
<hr>
<div class="container">
    <div class="row mb-3">
        <?php if (isset($data)) {
            foreach ($data['events'] as $event): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card mx-auto" style="width: 18rem">
                        <img src="<?= $event->image ?? '' ?>" class="card-img-top" alt="Event image">
                        <div class="card-body">
                            <h5 class="card-title"><?= $event->name ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Location: <?= $event->location ?>
                            </h6>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Max attendees: <?= $event->max_attendees ?>
                            </h6>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Reservations made: <?= $event->count ?>
                            </h6>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Date: <?= $event->date ?>
                            </h6>
                            <p class="card-text"><?= $event->description ?></p>
                            <?php if ($data['isLoggedIn']) : ?>
                                <?php if (in_array($event->id, $data['adminEvents']))  : ?>
                                    <form action="/delete-event" method="post">
                                        <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                        <input type="hidden" name="imagePath" value="<?= $event->image ?>">
                                        <input type="hidden" name="location" value="/">
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-outline-danger">Delete
                                                event
                                            </button>
                                            <a href="/edit-event?eventId=<?= $event->id ?>">
                                                <button type="button" class="btn btn-outline-secondary">Edit event
                                                </button>
                                            </a>

                                        </div>
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
                                <a href="/login" class="btn btn-light d-flex mx-auto">Login to make a reservation</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        } ?>
    </div>
</div>
