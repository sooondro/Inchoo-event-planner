<h3 class="text-center my-3">Your reservations</h3>
<div class="container">
    <?php if (isset($data)) : ?>
        <?php if (!empty($data['events'])): ?>
            <div class="row mb-3">
                <?php foreach ($data['events'] as $event): ?>
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
                                <form action="/delete-reservation" method="post">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <input type="hidden" name="location" value="/reservations">
                                    <button type="submit" class="btn btn-outline-secondary d-flex mx-auto">Delete a reservation</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <h3 class="text-center mb-3 mt-5">No reservations made.</h3>
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

