<div class="container">
    <?php if (isset($data)) : ?>
        <h3>Events that have passed:</h3>
        <?php if (!empty($data['pastEvents'])): ?>
            <div class="row mb-3 mt-5">
                <?php foreach ($data['pastEvents'] as $event): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card mx-auto" style="width: 18rem">
                            <img src="<?= $event->image ?? '' ?>" class="card-img-top" alt="Event image">
                            <div class="card-body">
                                <h5 class="card-title"><?= $event->name ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Max: <?= $event->max_attendees ?> |
                                    Date: <?= $event->date ?></h6>
                                <p class="card-text"><?= $event->description ?></p>
                                <form action="/delete-event" method="post">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <input type="hidden" name="location" value="/admin-events">
                                    <button type="submit" class="btn btn-outline-danger d-flex mx-auto">Delete event
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <h3 class="text-center">No admin events have passed</h3>
        <?php endif; ?>
        <h3 class="mt-5">Future events:</h3>
        <?php if (!empty($data['futureEvents'])): ?>
            <div class="row mb-3 mt-5">
                <?php foreach ($data['futureEvents'] as $event): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card mx-auto" style="width: 18rem">
                            <img src="<?= $event->image ?? '' ?>" class="card-img-top" alt="Event image">
                            <div class="card-body">
                                <h5 class="card-title"><?= $event->name ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Max: <?= $event->max_attendees ?> |
                                    Date: <?= $event->date ?></h6>
                                <p class="card-text"><?= $event->description ?></p>
                                <form action="/delete-event" method="post">
                                    <input type="hidden" name="eventId" value="<?= $event->id ?>">
                                    <input type="hidden" name="location" value="/admin-events">
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-outline-danger">Delete event</button>
                                        <a href="/edit-event?eventId=<?= $event->id ?>">
                                            <button type="button" class="btn btn-outline-secondary">Edit event
                                            </button>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <h3>No future admin events</h3>
            <div class="text-center">
                <a href="/create-event">
                    <button class="btn btn-outline-secondary">
                        Create an event
                    </button>
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>