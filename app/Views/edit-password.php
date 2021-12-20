<h3 class="text-center">Edit user password</h3>
<div class="container d-flex justify-content-center">
    <?php if (isset($data['confirmation'])) : ?>
        <span style="color:red;">
            <?= $data['message'] ?>
        </span>
    <?php endif; ?>
</div>
<div class="container d-flex justify-content-center mt-3">
    <form action="/edit-password" method="post" style="width: 30rem;">
        <div class="mb-3">
            <label for="password" class="form-label">New password</label>
            <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="repeated-password" class="form-label">Repeated new password</label>
            <input
                    type="password"
                    class="form-control"
                    id="repeated-password"
                    name="repeated-password"
                    required
            >
        </div>
        <div class="mb-3">
            <label for="current-password" class="form-label">Current password</label>
            <input
                    type="password"
                    class="form-control"
                    id="current-password"
                    name="current-password"
                    required
            >
        </div>
        <button type="submit" class="btn btn-outline-secondary">Submit changes</button>
    </form>
</div>