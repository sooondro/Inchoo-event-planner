<h3 class="text-center">Create a new event!</h3>
<div class="container d-flex justify-content-center">
    <?php if (isset($data['confirmation'])) : ?>
        <span style="color:red;">
            <?= $data['message'] ?>
        </span>
    <?php endif; ?>
</div>
<div class="container d-flex justify-content-center mt-3">
    <form action="<?= $data['location'] ?>" method="post" enctype="multipart/form-data" style="width: 30rem;">
        <div class="mb-3">
            <label for="name" class="form-label">Event name</label>
            <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= $data['formValues']['name'] ?? '' ?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date and time</label>
            <input
                    type="datetime-local"
                    class="form-control"
                    id="date"
                    name="date"
                    value="<?= $data['formValues']['date'] ?? '' ?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input
                    type="text"
                    class="form-control"
                    id="location"
                    name="location"
                    value="<?= $data['formValues']['location'] ?? '' ?>"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="max" class="form-label">Max attendees</label>
            <input
                    type="number"
                    class="form-control"
                    id="max"
                    name="max"
                    value="<?= $data['formValues']['max'] ?? '1' ?>"
                    required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea
                    class="form-control"
                    id="description"
                    name="description"
                    rows="3"
                    required
            ><?= $data['formValues']['description'] ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Event image</label>
            <input class="form-control" type="file" id="image" name="image" required>
        </div>
        <input type="hidden" name="eventId" value="<?= $data['formValues']['eventId'] ?? ''?>">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
